import { NextResponse } from 'next/server';

export async function GET() {
    return NextResponse.json({
        status: 'ok',
        service: 'HR Screening Agent',
        timestamp: new Date().toISOString(),
    });
}
