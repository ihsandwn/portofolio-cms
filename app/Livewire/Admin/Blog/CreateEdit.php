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
            $this->title = (string) $this->post->title;
            $this->slug = $this->post->slug;
            $this->excerpt = (string) $this->post->excerpt;
            $this->category_id = $this->post->category_id;
            $this->published_at = $this->post->published_at?->format('Y-m-d\TH:i');
            
            // Handle Localized Content Blocks
            $rawBlocks = $this->post->content_blocks ?? [];
            $locale = app()->getLocale();
            
            if (isset($rawBlocks['en']) || isset($rawBlocks['id'])) {
                // It's localized
                $this->content_blocks = $rawBlocks[$locale] ?? $rawBlocks['en'] ?? [];
            } else {
                // It's flat/legacy
                $this->content_blocks = $rawBlocks;
            }

            $this->seo_meta = array_merge([
                'title' => '', 'description' => '', 'keywords' => '', 'og_image' => ''
            ], $this->post->seo_meta ?? []);
        } else {
            // Default empty block
            // $this->addBlock('text');
        }
    }

    // ... (keep intermediate methods: updatedTitle, addBlock, removeBlock, moveBlockUp/Down) ...

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

        $locale = app()->getLocale();

        // MERGE LOGIC: Retrieve raw JSON, decode, update current locale, re-save
        // Note: $this->post might be null on create, so handle that.
        
        $titleData = [];
        $excerptData = [];
        $blocksData = [];

        if ($this->post) {
            // Fetch raw attributes to avoid Cast interference
            $rawAttributes = $this->post->getAttributes();
            
            $titleData = json_decode($rawAttributes['title'] ?? '{}', true);
            if (!is_array($titleData)) $titleData = ['en' => $rawAttributes['title']]; // Handle legacy string

            $excerptData = json_decode($rawAttributes['excerpt'] ?? '{}', true);
            // Excerpt might be null
            if (!is_array($excerptData)) $excerptData = $rawAttributes['excerpt'] ? ['en' => $rawAttributes['excerpt']] : [];

            $blocksData = $this->post->content_blocks ?? [];
            // If blocks are flat, convert to localized structure
            if (!empty($blocksData) && !isset($blocksData['en']) && !isset($blocksData['id'])) {
                $blocksData = ['en' => $blocksData];
            }
        }

        // Update current locale
        $titleData[$locale] = $this->title;
        $excerptData[$locale] = $this->excerpt;
        $blocksData[$locale] = $processedBlocks;

        $data = [
            'title' => $titleData,
            'slug' => $this->slug,
            'excerpt' => $excerptData,
            'content_blocks' => $blocksData,
            'seo_meta' => $this->seo_meta,
            'published_at' => $this->published_at ? \Carbon\Carbon::parse($this->published_at) : null,
            'category_id' => $this->category_id,
        ];

        if (!$this->post) {
            $data['author_id'] = auth()->id() ?? \App\Models\User::first()->id;
            $this->post = BlogPost::create($data);
        } else {
            $this->post->update($data);
        }

        session()->flash('message', 'Post saved successfully.');
        // return redirect()->route('admin.blog.index'); 
    }

    public function render()
    {
        return view('livewire.admin.blog.create-edit')
            ->layout('components.layouts.admin', ['title' => $this->post ? 'Edit Post' : 'Create Post']);
    }

    public function removeImage($blockIndex, $imageIndex)
    {
        if (isset($this->content_blocks[$blockIndex]['images'][$imageIndex])) {
            unset($this->content_blocks[$blockIndex]['images'][$imageIndex]);
            // Re-index array to prevent issues
            $this->content_blocks[$blockIndex]['images'] = array_values($this->content_blocks[$blockIndex]['images']);
        }
    }
}
