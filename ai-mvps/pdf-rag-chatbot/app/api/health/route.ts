import { NextResponse } from 'next/server';
import { documentCount } from '@/lib/storage';

export const runtime = 'nodejs';
export const dynamic = 'force-dynamic';

export async function GET() {
  try {
  const documents = await documentCount();
  return NextResponse.json(
    {
      status: 'ok',
      service: 'PDF RAG Chatbot',
      storage: 'postgres',
      documents,
      timestamp: new Date().toISOString(),
    },
    { headers: { 'Cache-Control': 'no-store' } }
  );
  } catch {
    return NextResponse.json({ status: 'error', storage: 'unavailable' }, { status: 503, headers: { 'Cache-Control': 'no-store' } });
  }
}