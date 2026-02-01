# AI/Agentic Products

This directory contains all AI/Agentic products that integrate with the Laravel CMS Portfolio.

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

## 🚀 Vercel Deployment Guide

This guide covers how to deploy all 4 AI products to Vercel from your existing repository.

### 📋 Prerequisites

1.  **Vercel Account**: [Sign up here](https://vercel.com/signup).
2.  **GitHub Repository**: Ensure your project is pushed to GitHub.
3.  **Production CMS URL**: You need the URL of your live Laravel Portfolio (e.g., `https://your-portfolio.com`).

### � Deployment Steps (Repeat for EACH App)

You will create **4 separate projects** in Vercel, one for each AI MVP.

#### 1. Import Project
1.  Go to the [Vercel Dashboard](https://vercel.com/dashboard) and click **"Add New..."** -> **"Project"**.
2.  Import your **CMS Portfolio Repository** (the one containing `ai-mvps`).

#### 2. Configure Project (CRITICAL PART)
*> Do not click "Deploy" yet! You must modify the settings first.*

**A. Project Name**
Give it a clear name, e.g., `ai-pdf-rag`, `ai-sentiment`, etc.

**B. Root Directory (The Magic Step)**
Since your apps are inside a subdirectory, you must tell Vercel where to look.
1.  Click **Edit** next to "Root Directory".
2.  Select the specific folder for the app you are deploying:
    *   **PDF Chatbot**: `ai-mvps/pdf-rag-chatbot`
    *   **Sentiment**: `ai-mvps/sentiment-analyzer`
    *   **Image Caption**: `ai-mvps/image-caption`
    *   **HR Screening**: `ai-mvps/hr-screening`

**C. Environment Variables**
Add the following variables in the "Environment Variables" section:

| Variable | Value |
| :--- | :--- |
| `GEMINI_API_KEY` | Your Gemini API Key (copy from local `.env`) |
| `NEXT_PUBLIC_LARAVEL_API_URL` | **Your Production CMS URL** (e.g., `https://your-portfolio.com`) **NOT localhost** |

#### 3. Deploy
Click **Deploy**. Vercel will build the app.

---

### 🔄 Post-Deployment Updates (In Laravel CMS)

Once deployed, Vercel will give you a domain for each app (e.g., `https://ai-pdf-rag.vercel.app`). You must update your Laravel CMS Database so it redirects valid tokens to the correct live URL.

1.  Open your **Production Database** (e.g., via phpMyAdmin, Sequel Ace, or `php artisan tinker`).
2.  Find the `portfolios` table.
3.  Update the `url` column for each AI Product to match its new Vercel URL:
    *   **PDF RAG**: `http://localhost:3000` ➔ `https://ai-pdf-rag.vercel.app`
    *   **Sentiment**: `http://localhost:3001` ➔ `https://ai-sentiment.vercel.app`
    *   **Image Caption**: `http://localhost:3002` ➔ `https://ai-image-caption.vercel.app`
    *   **HR Screening**: `http://localhost:3003` ➔ `https://ai-hr-screening.vercel.app`

### ✅ Verification
1.  Go to your Live Portfolio.
2.  Click **"Request Access"** for an AI Tool.
3.  The link should verify you on Laravel and redirect you securely to the Vercel App.
4.  The Vercel App should verify the cookie and let you in!