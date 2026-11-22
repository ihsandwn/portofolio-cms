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
    public $token = null;

    protected $rules = [
        'email' => 'required|email',
    ];

    protected function messages(): array
    {
        return [
            'email.required' => __('A valid email address is required.'),
            'email.email' => __('Enter an email address in a valid format.'),
        ];
    }

    public function mount($slug)
    {
        $this->project = Portfolio::where('slug', $slug)
            ->where('type', 'ai_agent')
            ->firstOrFail();
    }

    public function requestAccess()
    {
        $this->validate();

        // Monthly session budget: max 2 demo sessions per visitor email,
        // per portfolio, per calendar month. Each grant expires 10 minutes
        // after it is issued. Route-level throttling on the handoff endpoint
        // remains the anti-abuse backstop (see routes/web.php).
        $monthStart = Carbon::now()->startOfMonth();
        $sessionsThisMonth = AccessRequest::where('portfolio_id', $this->project->id)
            ->where('email', $this->email)
            ->where('created_at', '>=', $monthStart)
            ->count();

        if ($sessionsThisMonth >= 2) {
            $this->addError('email', __('Monthly access limit reached (2 sessions per month). Please try again next month.'));
            return;
        }

        // 2. Generate Token & Expiry
        $token = Str::random(64);
        $expiresAt = Carbon::now()->addMinutes(10);

        // 3. Create Record (IP kept for audit; the monthly budget key is the email)
        AccessRequest::create([
            'portfolio_id' => $this->project->id,
            'email' => $this->email,
            'token' => hash('sha256', $token),
            'status' => 'approved', // Option B: Auto-approve
            'expires_at' => $expiresAt,
            'ip_address' => request()->ip(),
        ]);

        $this->token = $token;
        $this->generatedUrl = route('ai-lab.auth');
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
