import { NextResponse } from 'next/server';

export const runtime = 'nodejs';

export async function GET() {
    return NextResponse.json({
        status: 'ok',
        message: 'Upload endpoint is reachable',
        runtime: 'nodejs',
    });
}

export async function POST() {
    return NextResponse.json({
        error: 'Testing - this endpoint is not fully configured yet',
    });
}
