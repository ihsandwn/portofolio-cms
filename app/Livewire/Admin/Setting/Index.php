<?php

namespace App\Livewire\Admin\Setting;

use App\Models\Setting;
use Livewire\Component;

class Index extends Component
{
    // public $settings_group = []; // Removed to avoid hydration issues with grouped collections


    public function update($id, $key, $value)
    {
        $setting = Setting::find($id);
        
        // Handle nested array updates for JSON fields
        // $key might be 'value.en' or 'value.id'
        if (str_contains($key, 'value.')) {
            $lang = explode('.', $key)[1];
            $currentValue = $setting->value instanceof \App\Casts\TranslatableContent 
                ? $setting->value->getArrayCopy() 
                : (is_array($setting->value) ? $setting->value : []);
            
            $currentValue[$lang] = $value;
            $setting->value = $currentValue;
        } else {
            $setting->$key = $value;
        }

        $setting->save();
        
        $this->dispatch('saved');
    }

    public function render()
    {
        $settings_group = Setting::all()->groupBy('group');
        return view('livewire.admin.setting.index', [
            'settings_group' => $settings_group
        ])->layout('components.layouts.admin');
    }
}
