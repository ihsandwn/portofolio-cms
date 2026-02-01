# Directory Structure

This directory contains all AI/Agentic MVP products that integrate with the Laravel CMS Portfolio.

## Structure

```
ai-mvps/
├── shared/                  # Shared utilities across all MVPs
│   ├── auth/               # Authentication bridge with Laravel
│   ├── types/              # TypeScript type definitions
│   └── utils/              # Common utility functions
├── pdf-rag-chatbot/        # MVP 1: PDF RAG Chatbot (Beta)
├── sentiment-analyzer/     # MVP 2: Sentiment Analysis Agent (Alpha)
├── rag-knowledge-system/   # MVP 3: RAG Knowledge Retrieval System
└── hr-screening-agent/     # MVP 4: Automated HR Screening Agent
```

## Shared Resources

### Authentication (`shared/auth/`)
- Laravel Sanctum integration
- NextAuth.js configuration
- Token validation utilities

### Types (`shared/types/`)
- Common TypeScript interfaces
- API response types
- Database schema types

### Utils (`shared/utils/`)
- Error handling
- API client wrappers
- Common validations

## MVP Projects

Each MVP is a standalone Next.js application with:
- Independent deployment on Vercel
- Shared authentication with Laravel CMS
- Public demo mode available
- Own database tables/collections

## Getting Started

1. Follow the setup guide in `../external-services-setup-guide.md`
2. Copy `.env.mvp.template` to each MVP directory as `.env.local`
3. Fill in your credentials
4. Start development: `npm run dev`

## Integration with Laravel CMS

All MVPs connect to Laravel CMS at: `http://localhost:8000/api`

API Endpoints:
- `GET /api/validate-token` - Validate Sanctum token
- `POST /api/mvp/token` - Create new token
- `DELETE /api/mvp/tokens` - Revoke tokens
- `GET /api/mvp/health` - Health check
