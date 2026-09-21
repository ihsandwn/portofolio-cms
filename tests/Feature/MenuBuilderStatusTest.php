<?php

namespace Tests\Feature;

use App\Livewire\Admin\Menu\Builder;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class MenuBuilderStatusTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $admin = User::factory()->create();

        if (! Role::where('name', 'super-admin')->exists()) {
            Role::create(['name' => 'super-admin', 'guard_name' => 'web']);
        }

        $admin->assignRole('super-admin');

        return $admin;
    }

    private function menuWithItem(): array
    {
        $menu = Menu::create(['name' => 'primary', 'is_active' => true]);

        $item = MenuItem::create([
            'menu_id' => $menu->id,
            'title' => ['en' => 'Blog', 'id' => 'Artikel'],
            'url' => '/blog',
            'order' => 1,
        ]);

        return [$menu, $item];
    }

    public function test_new_items_default_to_active()
    {
        [$menu] = $this->menuWithItem();

        Livewire::actingAs($this->admin())
            ->test(Builder::class, ['menu' => $menu])
            ->call('create')
            ->set('title.en', 'Services')
            ->set('url', '/services')
            ->set('order', 2)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('menu_items', [
            'url' => '/services',
            'is_active' => true,
        ]);
    }

    public function test_an_item_can_be_saved_as_hidden()
    {
        [$menu] = $this->menuWithItem();

        Livewire::actingAs($this->admin())
            ->test(Builder::class, ['menu' => $menu])
            ->call('create')
            ->set('title.en', 'Draft Link')
            ->set('url', '/draft-link')
            ->set('isActive', false)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('menu_items', [
            'url' => '/draft-link',
            'is_active' => false,
        ]);

        $this->assertNotContains('/draft-link', Menu::navigation()->pluck('url')->all());
    }

    public function test_toggle_hides_then_restores_the_item_on_the_site()
    {
        [$menu, $item] = $this->menuWithItem();

        $component = Livewire::actingAs($this->admin())
            ->test(Builder::class, ['menu' => $menu]);

        $component->call('toggleActive', $item->id);
        $this->assertFalse($item->fresh()->is_active);
        $this->assertNotContains('/blog', Menu::navigation()->pluck('url')->all());

        $component->call('toggleActive', $item->id);
        $this->assertTrue($item->fresh()->is_active);
        $this->assertContains('/blog', Menu::navigation()->pluck('url')->all());
    }

    public function test_editing_an_item_loads_its_current_status()
    {
        [$menu, $item] = $this->menuWithItem();
        $item->update(['is_active' => false]);

        Livewire::actingAs($this->admin())
            ->test(Builder::class, ['menu' => $menu])
            ->call('edit', $item->id)
            ->assertSet('isActive', false);
    }

    public function test_builder_lists_hidden_items_so_they_can_be_restored()
    {
        [$menu, $item] = $this->menuWithItem();
        $item->update(['is_active' => false]);

        Livewire::actingAs($this->admin())
            ->test(Builder::class, ['menu' => $menu])
            ->assertSee('Blog')
            ->assertSee('Hidden');
    }
}
