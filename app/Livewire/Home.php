<?php

namespace App\Livewire;

use App\Models\Page;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Support\Str;
use Livewire\Component;

class Home extends Component
{
    public $filter = 'all';

    public function setFilter($filter)
    {
        $this->filter = $filter;
    }

    public function render()
    {
        $portfolios = Portfolio::query()
            ->where('is_featured', true)
            ->when($this->filter !== 'all', function ($q) {
                if ($this->filter === 'ai') {
                    $q->where('type', 'ai_agent');
                } else {
                    $q->where('type', '!=', 'ai_agent');
                }
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $techChips = Portfolio::query()
            ->orderByDesc('completed_at')
            ->limit(50)
            ->get()
            ->flatMap(fn ($p) => collect($p->tech_stack ?? []))
            ->map(fn ($t) => Str::upper(is_string($t) ? $t : (string) $t))
            ->unique()
            ->filter()
            ->take(14)
            ->values();

        return view('livewire.home', [
            'portfolios' => $portfolios,
            'services' => Service::all(),
            'hero' => Setting::where('group', 'home')->get()->pluck('value', 'key'),
            'aboutPage' => Page::where('slug', 'about')->first(),
            'aiPage' => Page::where('slug', 'ai-lab')->first(),
            'portfolioTotal' => Portfolio::count(),
            'techChips' => $techChips,
        ])->layout('components.layouts.app');
    }
}
