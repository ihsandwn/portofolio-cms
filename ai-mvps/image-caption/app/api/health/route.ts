import { NextResponse } from 'next/server';

export async function GET() {
    return NextResponse.json({
        status: 'ok',
        service: 'Image Caption & Categorizer',
        timestamp: new Date().toISOString(),
    });
}
