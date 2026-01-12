<?php

namespace Tests\Feature;

use App\Livewire\Admin\Blog\CreateEdit;
use App\Livewire\Admin\Blog\Index;
use App\Livewire\Public\Blog\Show;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

class BlogModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_blog_post_with_blocks()
    {
        $admin = User::factory()->create();
        
        // Ensure role exists as per Spatie permission
        if (!Role::where('name', 'super-admin')->exists()) {
             Role::create(['name' => 'super-admin', 'guard_name' => 'web']);
        }
        $admin->assignRole('super-admin');

        Livewire::actingAs($admin)
            ->test(CreateEdit::class)
            ->set('title', 'Test Blog Post') // Triggers slug update via updatedTitle usually, or manual
            ->set('slug', 'test-blog-post')
            ->call('addBlock', 'text')
            ->set('content_blocks.0.data', '<p>Hello World</p>')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('blog_posts', [
            'slug' => 'test-blog-post',
        ]);
        
        $post = \App\Models\BlogPost::where('slug', 'test-blog-post')->first();
        $this->assertEquals('Test Blog Post', (string)$post->title);
        
        $post = BlogPost::where('slug', 'test-blog-post')->first();
        $this->assertCount(1, $post->content_blocks);
        $this->assertEquals('text', $post->content_blocks[0]['type']);
    }

    public function test_public_can_view_blog_post()
    {
        $user = User::factory()->create();
        $post = BlogPost::factory()->create([
            'title' => 'Public Post',
            'slug' => 'public-post',
            'content_blocks' => [
                ['type' => 'text', 'data' => 'Welcome Content'],
            ],
            'published_at' => now(),
            'author_id' => $user->id,
        ]);

        $response = $this->get(route('blog.show', 'public-post'));
        $response->assertStatus(200);
        $response->assertSee('Public Post');
        $response->assertSee('Welcome Content');
        $response->assertSee('Home');
        $response->assertSee('Blog');
        $response->assertSee(route('blog.index'));
    }

    public function test_public_can_view_mixed_content()
    {
        $user = User::factory()->create();
        $post = BlogPost::factory()->create([
            'title' => 'Mixed Post',
            'slug' => 'mixed-post',
            'content_blocks' => [
                ['type' => 'text', 'data' => 'Text Content'],
                ['type' => 'video', 'url' => 'https://youtube.com/watch?v=12345'],
                ['type' => 'pdf', 'file' => 'test.pdf', 'title' => 'Test PDF'],
            ],
            'published_at' => now(),
            'author_id' => $user->id,
        ]);

        $response = $this->get(route('blog.show', 'mixed-post'));
        $response->assertStatus(200);
        $response->assertSee('Text Content');
        // Check for video iframe
        $response->assertSee('iframe');
        $response->assertSee('youtube.com/embed/12345');
        // Check for PDF
        $response->assertSee('test.pdf');
    }

    public function test_admin_can_view_blog_index()
    {
        $admin = User::factory()->create();
        
        // Ensure role exists as per Spatie permission
        if (!Role::where('name', 'super-admin')->exists()) {
             Role::create(['name' => 'super-admin', 'guard_name' => 'web']);
        }
        $admin->assignRole('super-admin');

        BlogPost::factory()->create(['title' => 'Index Test Post']);

        Livewire::actingAs($admin)
            ->test(Index::class) 
            ->assertStatus(200)
            ->assertSee('Index Test Post');
    }

    public function test_public_can_view_blog_index()
    {
        BlogPost::factory()->create(['title' => 'Public Index Post', 'published_at' => now()]);

        $response = $this->get(route('blog.index'));
        $response->assertStatus(200);
        $response->assertSee('Public Index Post');
    }
}
