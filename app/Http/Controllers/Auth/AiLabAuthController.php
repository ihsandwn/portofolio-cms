<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AccessRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class AiLabAuthController extends Controller
{
    private function app(Request $request): array
    {
        $request->validate(['audience' => 'required|string|max:80']);
        $app = config('services.ai_lab.apps.'.$request->string('audience'));
        abort_unless(is_array($app) && strlen($app['secret'] ?? '') >= 32 && !empty($app['url']) && config('services.ai_lab.cache_store'), 503);
        abort_unless(hash_equals($app['secret'], $request->bearerToken() ?? ''), 401);
        return $app;
    }

    public function exchange(Request $request)
    {
        $app = $this->app($request);
        $request->validate(['token' => ['nullable', 'regex:/^[A-Za-z0-9]{64}$/'], 'code' => ['nullable', 'regex:/^[A-Za-z0-9]{48}$/']]);
        $code = $request->input('code');
        $grantToken = $request->input('token');
        abort_unless((bool) $code xor (bool) $grantToken, 400);

        if ($code) {
            $key = 'ai-lab:code:'.hash('sha256', $code);
            $session = Cache::store(config('services.ai_lab.cache_store'))->pull($key);
            abort_unless(is_array($session) && $session['audience'] === $request->input('audience') && $session['expires_at'] > now()->timestamp, 401);
            $expiresAt = AccessRequest::whereKey($session['grant_id'])->where('status', 'approved')->where('expires_at', '>', now())->value('expires_at');
            abort_unless($expiresAt, 401);
            $session['expires_at'] = $expiresAt->timestamp;
        } else {
            $hash = hash('sha256', $grantToken);
            $grant = AccessRequest::where('token', $hash)->where('status', 'approved')->where('expires_at', '>', now())->first();
            abort_unless($grant && $grant->portfolio && rtrim($grant->portfolio->url, '/') === rtrim($app['url'], '/'), 401);
            abort_unless(AccessRequest::whereKey($grant->id)->where('token', $hash)->update(['token' => null]) === 1, 401);
            $session = ['audience' => $request->input('audience'), 'grant_id' => $grant->id, 'expires_at' => $grant->expires_at->timestamp];
        }

        $token = Str::random(64);
        abort_unless(Cache::store(config('services.ai_lab.cache_store'))->put('ai-lab:session:'.hash('sha256', $token), $session, $session['expires_at'] - now()->timestamp), 503);
        return response()->json(['token' => $token, 'audience' => $session['audience'], 'expires_at' => $session['expires_at']])->header('Cache-Control', 'no-store');
    }

    public function verify(Request $request)
    {
        $this->app($request);
        $request->validate(['token' => ['required', 'regex:/^[A-Za-z0-9]{64}$/']]);
        $session = Cache::store(config('services.ai_lab.cache_store'))->get('ai-lab:session:'.hash('sha256', $request->input('token')));
        abort_unless(is_array($session) && $session['audience'] === $request->input('audience') && $session['expires_at'] > now()->timestamp, 401);
        abort_unless(AccessRequest::whereKey($session['grant_id'])->where('status', 'approved')->where('expires_at', '>', now())->exists(), 401);
        return response()->json(['valid' => true, 'audience' => $session['audience'], 'expires_at' => $session['expires_at']])->header('Cache-Control', 'no-store');
    }

    public function handoff(Request $request)
    {
        $request->validate(['token' => ['required', 'regex:/^[A-Za-z0-9]{64}$/']]);
        $hash = hash('sha256', $request->input('token'));
        $grant = AccessRequest::where('token', $hash)->where('status', 'approved')->where('expires_at', '>', now())->first();
        abort_unless($grant && $grant->portfolio, 401);
        // Preferred: portfolio slug is exactly the audience key
        // (see docs/ai-lab-auth.md). Fallback: match the portfolio URL against
        // the configured app URLs — the same rule exchange() already uses —
        // so portfolios seeded before the audience-slug convention still work.
        // The fallback only applies when exactly one app URL matches.
        $audience = $grant->portfolio->slug;
        $app = config('services.ai_lab.apps.'.$audience);
        if (! is_array($app)) {
            $candidates = [];
            foreach ((array) config('services.ai_lab.apps', []) as $key => $candidate) {
                if (is_array($candidate) && ! empty($candidate['url'])
                    && rtrim($grant->portfolio->url ?? '', '/') === rtrim($candidate['url'], '/')) {
                    $candidates[$key] = $candidate;
                }
            }
            if (count($candidates) === 1) {
                $audience = (string) array_key_first($candidates);
                $app = reset($candidates);
            }
        }
        abort_unless(is_array($app) && strlen($app['secret'] ?? '') >= 32 && !empty($app['url']) && config('services.ai_lab.cache_store'), 503);
        abort_unless(AccessRequest::whereKey($grant->id)->where('token', $hash)->update(['token' => null]) === 1, 401);

        $code = Str::random(48);
        Cache::store(config('services.ai_lab.cache_store'))->put('ai-lab:code:'.hash('sha256', $code), [
            'audience' => $audience, 'grant_id' => $grant->id, 'expires_at' => now()->addSeconds(60)->timestamp,
        ], 60);
        return redirect()->away(rtrim($app['url'], '/').'/auth/callback?code='.$code);
    }
}
