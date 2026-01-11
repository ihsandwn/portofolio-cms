<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menu = Menu::create(['name' => 'primary', 'is_active' => true]);

        $items = [
            [
                'title' => ['en' => 'About', 'id' => 'Tentang'],
                'url' => '#about',
                'order' => 1,
            ],
            [
                'title' => ['en' => 'Services', 'id' => 'Layanan'],
                'url' => '#services',
                'order' => 2,
            ],
            [
                'title' => ['en' => 'Portfolio', 'id' => 'Portofolio'],
                'url' => '#portfolio',
                'order' => 3,
            ],
            [
                'title' => ['en' => 'AI Lab', 'id' => 'Lab AI'],
                'url' => '#ai-lab',
                'order' => 4,
            ],
        ];

        foreach ($items as $item) {
            MenuItem::create(array_merge($item, ['menu_id' => $menu->id]));
        }
    }
}
