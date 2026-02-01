<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AccessRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AiLabAuthController extends Controller
{
    /**
     * Validate the access token and redirect to the AI MVP.
     */
    public function validateAndRedirect($token, Request $request)
    {
        // 1. Find the request
        $accessRequest = AccessRequest::where('token', $token)->first();

        // 2. Validate
        if (!$accessRequest) {
            abort(403, 'Invalid access token.');
        }

        if ($accessRequest->status !== 'approved') {
            abort(403, 'Access request not approved or already expired.');
        }

        if ($accessRequest->expires_at && Carbon::now()->greaterThan($accessRequest->expires_at)) {
            $accessRequest->update(['status' => 'expired']);
            abort(403, 'Access token expired.');
        }

        // 3. Mark as used (optional - or just keep it 'approved' until expiry)
        // For Option B (auto-approve temporary), we let it be used multiple times until expiry?
        // Or strictly one-time? Let's keep it valid until expiry for better UX (refreshing page).
        
        // 4. Construct Redirect URL
        $portfolio = $accessRequest->portfolio;
        
        if (!$portfolio || !$portfolio->url) {
            abort(404, 'AI Product URL not configured.');
        }

        $mvpUrl = rtrim($portfolio->url, '/');
        // If the URL already has params, use & otherwise ?
        $separator = str_contains($mvpUrl, '?') ? '&' : '?';
        
        $redirectUrl = "{$mvpUrl}/auth/callback{$separator}token={$token}&email=" . urlencode($accessRequest->email);

        return redirect()->away($redirectUrl);
    }
}
