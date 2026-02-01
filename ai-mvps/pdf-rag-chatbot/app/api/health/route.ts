import { NextResponse } from 'next/server';

export async function GET() {
    return NextResponse.json({
        status: 'ok',
        service: 'PDF RAG Chatbot',
        timestamp: new Date().toISOString(),
    });
}
