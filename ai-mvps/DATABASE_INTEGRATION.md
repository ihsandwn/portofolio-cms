# Database Integration for AI Products

## Overview

All 4 AI products support both **stateless local development** and **persistent production storage** through environment-based switching.

## Architecture

### Local Development (Default)
```
NODE_ENV=development (or unset)
├─ ✅ Stateless - no database needed
├─ ✅ Fast iteration
├─ ✅ Zero setup
└─ ✅ Data lives in memory only
```

### Production
```
NODE_ENV=production
├─ ✅ Supabase (PostgreSQL)
│   ├─ User authentication
│   ├─ Structured metadata
│   ├─ Query history  
│   └─ Analytics data
└─ ✅ MongoDB Atlas
    ├─ Full document storage
    ├─ Vector embeddings
    ├─ Unstructured data
    └─ Large text chunks
```

## Usage

### Shared Database Modules

Located in `ai-mvps/shared/database/`:

```typescript
// Import database utilities
import { saveToSupabase, queryFrom Supabase } from '@/shared/database';
import { saveToMongo, queryFromMongo } from '@/shared/database';

// Save data (auto-skipped in development)
await saveToSupabase('chat_history', {
  user_id: userId,
  question: 'What is AI?',
  answer: 'AI is...'
});

// Save to MongoDB (auto-skipped in development)
await saveToMongo('pdf_documents', {
  userId: userId,
  fullText: pdfText,
  vectors: embeddings
});
```

### Environment-Based Switching

The database clients automatically detect the environment:

- **Development**: Returns `null` and logs a message
- **Production**: Connects to Supabase/MongoDB and persists data

No code changes needed - just set `NODE_ENV=production`!

## Database Schemas

See [production-deployment-guide.md](file:///C:/Users/ichsa/.gemini/antigravity/brain/cb007c0e-2b0c-443f-bf6d-9c966e5d32d5/production-deployment-guide.md) for complete schema documentation.

## Setup

### Local Development
```bash
# No setup needed! Just run:
npm run dev
```

### Production
```bash
# 1. Set environment variables
cp .env.example .env.local

# 2. Add your credentials
NEXT_PUBLIC_SUPABASE_URL=...
NEXT_PUBLIC_SUPABASE_ANON_KEY=...
MONGODB_URI=...

# 3. Set production mode
NODE_ENV=production

# 4. Run
npm run build
npm start
```

## Features by MVP

| Product | Supabase Tables | MongoDB Collections | Purpose |
|---------|----------------|---------------------|---------|
| **PDF RAG Chatbot** | `chat_history` | `pdf_documents`, `vectors` | Chat history + full PDF text + embeddings |
| **Sentiment Analyzer** | `sentiment_analyses` | `sentiment_raw_data` | Results metadata + full text analysis |
| **Image Caption** | `image_captions` | `image_analysis` | Caption metadata + full analysis |
| **HR Screening** | `hr_screenings` | `resumes`, `job_descriptions` | Match metadata + full resume text |

## Benefits

✅ **Zero Setup for Development** - No database needed locally  
✅ **Production Ready** - Full persistence when deployed  
✅ **Automatic Switching** - Environment detection built-in  
✅ **Type Safe** - Full TypeScript support  
✅ **Scalable** - Separate DBs for each MVP  
✅ **Cost Effective** - Free tiers available

## Migration Path

```
Development (Stateless)
    ↓
Production Testing (Staging DB)
    ↓
Production (Live DB)
```

No code changes required at any step!
