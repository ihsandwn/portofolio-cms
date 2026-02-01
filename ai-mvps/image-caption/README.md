# Image Caption & Categorizer - MVP 3

AI-powered image captioning and categorization using Google Gemini Vision AI.

## Features

✅ **Image Upload** - Drag & drop with instant preview  
✅ **AI Captions** - Auto-generated descriptive captions  
✅ **Smart Titles** - Short, catchy titles  
✅ **Auto Categorization** - AI-detected tags and categories  
✅ **Object Detection** - Identify main subjects in image  
✅ **Color Palette** - Extract dominant colors  
✅ **Mood Analysis** - Detect atmosphere and feeling  
✅ **Dark Blue Theme** - Matches Laravel CMS design

## Tech Stack

- **Next.js** - React framework
- **TypeScript** - Type safety
- **Tailwind CSS** - Styling
- **Gemini 2.5-Flash (Vision)** - AI image analysis
- **React Dropzone** - File upload
- **Lucide React** - Icons

## Getting Started

### 1. Start Development Server

```bash
npm run dev
```

Open [http://localhost:3002](http://localhost:3002)

### 2. Upload & Analyze

- Drag and drop an image (JPG, PNG, WebP, GIF)
- Or click to browse (max 10MB)
- View AI-generated captions and categories instantly!

## Project Structure

```
image-caption/
├── app/
│   ├── api/
│   │   ├── caption/route.ts    # Image analysis endpoint
│   │   └── health/route.ts     # Health check
│   ├── layout.tsx              # Root layout
│   └── page.tsx                # Main page
├── components/
│   ├── ImageUploader.tsx       # Drag & drop uploader
│   └── CaptionResults.tsx      # Results visualization
├── lib/
│   └── gemini-vision.ts        # Gemini Vision AI client
└── .env.local                  # Environment variables
```

## API Endpoints

### POST /api/caption
Analyze image and generate captions.

**Request:** `multipart/form-data` with `file` field

**Response:**
```json
{
  "success": true,
  "title": "Sunset Over Mountains",
  "caption": "A breathtaking view of golden sunset...",
  "categories": ["nature", "landscape", "sunset"],
  "objects": ["mountains", "sky", "clouds"],
  "colors": ["orange", "purple", "blue"],
  "mood": "Peaceful and serene",
  "imageUrl": "data:image/jpeg;base64,...",
  "generatedAt": "2026-01-31T..."
}
```

### GET /api/health
Health check endpoint.

## How It Works

1. **Upload**: User drops an image
2. **Convert**: Image converted to base64
3. **Gemini Vision**: AI analyzes image content
4. **Extract**: Parse structured data from AI
5. **Display**: Beautiful visualization with categories, colors, mood

## Features in Detail

### Caption Generation
- **Detailed Caption**: 1-2 sentence description
- **Short Title**: 5-7 words catchy title
- **Context-aware**: Understands scene and subjects

### Categorization
- **Smart Tags**: Relevant category tags
- **Auto-detect**: Categories based on content
- **Visual Display**: Color-coded badges

### Object Detection
- **Main Subjects**: Key objects in image
- **Accurate**: Powered by Gemini Vision
- **Multiple Objects**: Detects several items

### Color Palette
- **Dominant Colors**: Main colors in image
- **Visual Swatches**: Color preview boxes
- **Named Colors**: Human-readable names

### Mood Analysis
- **Atmosphere**: Overall feeling
- **Emotion**: Detected mood
- **Contextual**: Based on composition

## Limitations

- Max image size: 10MB
- Supported formats: JPG, PNG, WebP, GIF
- Analysis time: 2-4 seconds
- English captions primarily

## Next Steps

- [ ] Add MongoDB storage for image library
- [ ] Batch upload multiple images
- [ ] Export captions as CSV
- [ ] Multi-language captions
- [ ] Image enhancement suggestions
- [ ] Deploy to Vercel

---

**Ready to use!** 🚀  
Open http://localhost:3002 and start generating captions!
