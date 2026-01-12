<?php

namespace App\Livewire\Admin\Blog;

use App\Models\BlogPost;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class CreateEdit extends Component
{
    use WithFileUploads;

    public $post;
    public $title = '';
    public $slug = '';
    public $excerpt = '';
    public $category_id;
    public $published_at;
    public $content_blocks = [];
    public $seo_meta = [
        'title' => '',
        'description' => '',
        'keywords' => '',
        'og_image' => '',
    ];

    protected $rules = [
        'title' => 'required|min:3',
        'slug' => 'required|unique:blog_posts,slug',
        'content_blocks' => 'array',
        'content_blocks.*.type' => 'required',
        'content_blocks.*.data' => 'nullable', // Text
        'content_blocks.*.url' => 'nullable|url', // Video
        'content_blocks.*.images' => 'nullable', // Gallery
        'content_blocks.*.file' => 'nullable', // PDF
        'content_blocks.*.title' => 'nullable', // PDF Title
    ];

    public function mount($id = null)
    {
        if ($id) {
            $this->post = BlogPost::findOrFail($id);
            $this->title = $this->post->title;
            $this->slug = $this->post->slug;
            $this->excerpt = $this->post->excerpt;
            $this->category_id = $this->post->category_id; // Keeping nullable/integer as per schema
            $this->published_at = $this->post->published_at?->format('Y-m-d\TH:i');
            $this->content_blocks = $this->post->content_blocks ?? [];
            $this->seo_meta = array_merge([
                'title' => '', 'description' => '', 'keywords' => '', 'og_image' => ''
            ], $this->post->seo_meta ?? []);
        } else {
            // Default empty block?
            // $this->addBlock('text');
        }
    }

    public function updatedTitle($value)
    {
        if (!$this->post) {
            $this->slug = Str::slug($value);
        }
    }

    public function addBlock($type)
    {
        $block = ['type' => $type];
        
        switch ($type) {
            case 'text':
                $block['data'] = '';
                break;
            case 'gallery':
                $block['images'] = [];
                break;
            case 'video':
                $block['url'] = '';
                break;
            case 'pdf':
                $block['file'] = null;
                $block['title'] = '';
                break;
        }
        
        $this->content_blocks[] = $block;
    }

    public function removeBlock($index)
    {
        unset($this->content_blocks[$index]);
        $this->content_blocks = array_values($this->content_blocks);
    }

    public function moveBlockUp($index)
    {
        if ($index > 0) {
            $temp = $this->content_blocks[$index];
            $this->content_blocks[$index] = $this->content_blocks[$index - 1];
            $this->content_blocks[$index - 1] = $temp;
        }
    }

    public function moveBlockDown($index)
    {
        if ($index < count($this->content_blocks) - 1) {
            $temp = $this->content_blocks[$index];
            $this->content_blocks[$index] = $this->content_blocks[$index + 1];
            $this->content_blocks[$index + 1] = $temp;
        }
    }

    public function save()
    {
        $this->validate([
            'title' => 'required',
            'slug' => 'required|unique:blog_posts,slug,' . ($this->post->id ?? 'NULL'),
        ]);

        // Process file uploads in content blocks
        $processedBlocks = [];
        foreach ($this->content_blocks as $block) {
            $newBlock = $block;

            // Handle Gallery
            if ($block['type'] === 'gallery' && isset($block['images']) && is_array($block['images'])) {
                $imagePaths = [];
                foreach ($block['images'] as $img) {
                    if ($img instanceof \Illuminate\Http\UploadedFile) {
                        $imagePaths[] = $img->store('blog-gallery', 'public');
                    } else {
                        $imagePaths[] = $img;
                    }
                }
                $newBlock['images'] = $imagePaths;
            }

            // Handle PDF
            if ($block['type'] === 'pdf' && isset($block['file'])) {
                if ($block['file'] instanceof \Illuminate\Http\UploadedFile) {
                    $newBlock['file'] = $block['file']->store('blog-ebooks', 'public');
                }
            }

            $processedBlocks[] = $newBlock;
        }

        $data = [
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content_blocks' => $processedBlocks,
            'seo_meta' => $this->seo_meta,
            'published_at' => $this->published_at ? \Carbon\Carbon::parse($this->published_at) : null,
            'category_id' => $this->category_id,
        ];

        if (!$this->post) {
            $data['author_id'] = auth()->id() ?? \App\Models\User::first()->id; // Fallback for dev
            $this->post = BlogPost::create($data);
        } else {
            $this->post->update($data);
        }

        session()->flash('message', 'Post saved successfully.');
        // return redirect()->route('admin.blog.index'); // Redirect or stay
    }

    public function render()
    {
        return view('livewire.admin.blog.create-edit')
            ->layout('components.layouts.admin', ['title' => $this->post ? 'Edit Post' : 'Create Post']);
    }
}
