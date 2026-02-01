# PDF RAG Chatbot - MVP

A simplified AI-powered PDF chatbot that lets you upload PDF documents and ask questions about them using Google Gemini AI.

## Features

✅ **PDF Upload** - Drag & drop interface with file validation  
✅ **Text Extraction** - Automatic PDF text extraction  
✅ **AI Chat** - Ask questions about your documents  
✅ **Streaming Responses** - Real-time AI answers  
✅ **Modern UI** - Beautiful gradient design with dark mode support

## Tech Stack

- **Next.js** - React framework with App Router
- **TypeScript** - Type-safe development
- **Tailwind CSS** - Utility-first styling
- **Gemini 2.5-Flash** - AI model for chat
- **pdf-parse** - PDF text extraction
- **Lucide React** - Icons

## Getting Started

### 1. Start Development Server

```bash
npm run dev
```

Open [http://localhost:3000](http://localhost:3000)

### 2. Upload a PDF

- Drag and drop a PDF file (max 10MB)
- Wait for processing to complete

### 3. Chat with Your Document

- Ask questions about the PDF content
- Get AI-powered answers in real-time

## Environment Variables

All credentials are already configured in `.env.local`:

- `GEMINI_API_KEY` - Google Gemini API key
- `GEMINI_MODEL` - gemini-2.5-flash
- `NEXT_PUBLIC_LARAVEL_API_URL` - Laravel CMS endpoint

## Project Structure

```
pdf-rag-chatbot/
├── app/
│   ├── api/
│   │   ├── upload/route.ts    # PDF upload endpoint
│   │   ├── chat/route.ts      # Chat with streaming
│   │   └── health/route.ts    # Health check
│   ├── layout.tsx             # Root layout
│   └── page.tsx               # Main page
├── components/
│   ├── PDFUploader.tsx        # File upload component
│   └── ChatInterface.tsx      # Chat UI with streaming
├── lib/
│   ├── gemini.ts              # Gemini AI client
│   ├── pdf-processor.ts       # PDF text extraction
│   └── storage.ts             # In-memory storage
└── .env.local                 # Environment variables
```

## API Endpoints

### POST /api/upload
Upload and process a PDF file.

**Request:** `multipart/form-data` with `file` field  
**Response:**
```json
{
  "success": true,
  "document": {
    "id": "uuid",
    "filename": "example.pdf",
    "textLength": 5000,
    "uploadedAt": "2026-01-31T..."
  }
}
```

### POST /api/chat
Chat with the uploaded document.

**Request:**
```json
{
  "documentId": "uuid",
  "question": "What is this document about?",
  "history": []
}
```

**Response:** Streamed text response

### GET /api/health
Health check endpoint.

## Current Limitations (Simplified MVP)

- ⚠️ **In-Memory Storage** - Documents reset on server restart
- ⚠️ **No Vector Embeddings** - Uses full document context
- ⚠️ **Single Document** - One document at a time
- ⚠️ **No Authentication** - Public access only

## Next Steps (Full RAG Version)

- [ ] Add Supabase PostgreSQL storage with pgvector
- [ ] Implement vector embeddings for longer documents
- [ ] Support multiple documents
- [ ] Add NextAuth.js authentication with Laravel CMS
- [ ] Add chat history persistence
- [ ] Deploy to Vercel

## Testing

1. **Health Check:**
   ```bash
   curl http://localhost:3000/api/health
   ```

2. **Upload a PDF:**
   - Open http://localhost:3000
   - Drag and drop any PDF file
   - Verify "File uploaded successfully" message

3. **Chat:**
   - Type a question about the PDF
   - Verify streaming AI response

## Troubleshooting

**Port 3000 already in use?**
```bash
# Change port in package.json
"dev": "next dev -p 3001"
```

**PDF upload fails?**
- Check file size (max 10MB)
- Check file type (must be .pdf)
- Check console for errors

**Chat not working?**
- Verify `GEMINI_API_KEY` in `.env.local`
- Check browser console for errors
- Check server logs in terminal

## Built With

This is a simplified MVP. The full version will include:
- Vector embeddings with pgvector
- Multi-document support
- Authentication with Laravel CMS
- Persistent storage with Supabase
- Enhanced RAG capabilities

---

**Ready to use!** 🚀  
Open http://localhost:3000 and start chatting with your PDFs!
