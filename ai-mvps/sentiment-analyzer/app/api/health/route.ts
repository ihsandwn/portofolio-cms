import { NextResponse } from 'next/server';

export async function GET() {
    return NextResponse.json({
        status: 'ok',
        service: 'Sentiment Analyzer',
        timestamp: new Date().toISOString(),
    });
}
