<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageSwitch extends Component
{
    public function switch($locale)
    {
        if (in_array($locale, ['en', 'id'])) {
            Session::put('locale', $locale);
            App::setLocale($locale);
            
            // Reload the document so its lang and SEO metadata match the new locale.
            return $this->redirect(request()->header('Referer'));
        }
    }

    public function render()
    {
        return view('livewire.language-switch');
    }
}
