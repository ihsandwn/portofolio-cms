import { NextResponse } from 'next/server';
import { documentCount } from '@/lib/storage';

export const runtime = 'nodejs';
export const dynamic = 'force-dynamic';

export async function GET() {
  return NextResponse.json(
    {
      status: 'ok',
      service: 'PDF RAG Chatbot',
      storage: 'memory',
      documents: documentCount(),
      timestamp: new Date().toISOString(),
    },
    { headers: { 'Cache-Control': 'no-store' } }
  );
}