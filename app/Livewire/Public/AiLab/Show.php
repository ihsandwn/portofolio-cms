<?php

namespace App\Livewire\Public\AiLab;

use Livewire\Component;
use App\Models\Portfolio;

use App\Models\AccessRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class Show extends Component
{
    public $project;
    public $email;
    public $accessRequested = false;
    public $generatedUrl = null;

    protected $rules = [
        'email' => 'required|email',
    ];

    public function mount($slug)
    {
        $this->project = Portfolio::where('slug', $slug)
            ->where('type', 'ai_agent')
            ->firstOrFail();
    }

    public function requestAccess()
    {
        $this->validate();

        // 1. Rate Limiting Check (Simple IP based)
        $ip = request()->ip();
        $recentRequests = AccessRequest::where('ip_address', $ip)
            ->where('portfolio_id', $this->project->id)
            ->whereDate('created_at', Carbon::today())
            ->count();

        if ($recentRequests >= 2) {
            $this->addError('email', 'Daily limit reached. Please try again tomorrow.');
            return;
        }

        // 2. Generate Token & Expiry
        $token = Str::random(64);
        $expiresAt = Carbon::now()->addMinutes(10);

        // 3. Create Record
        AccessRequest::create([
            'portfolio_id' => $this->project->id,
            'email' => $this->email,
            'token' => $token,
            'status' => 'approved', // Option B: Auto-approve
            'expires_at' => $expiresAt,
            'ip_address' => $ip,
        ]);

        // 4. Generate Signed URL (Direct link to MVP with token)
        // Note: In real prod, this link should point to a Laravel route that validates then redirects.
        // For MVP Option B, we send them directly to the AI App with the token.
        // Format: https://mvp-url.com/auth/callback?token=XYZ&email=User
        
        $mvpUrl = rtrim($this->project->url, '/');
        // If the URL already has params, use & otherwise ?
        $separator = str_contains($mvpUrl, '?') ? '&' : '?';
        
        // Use the secure internal route for redirection
        $this->generatedUrl = route('ai-lab.auth', ['token' => $token]);
        $this->accessRequested = true;
    }

    public function render()
    {
        return view('livewire.public.ai-lab.show')
            ->layout('components.layouts.app', [
                'title' => $this->project->title,
                'description' => $this->project->description
            ]);
    }
}
