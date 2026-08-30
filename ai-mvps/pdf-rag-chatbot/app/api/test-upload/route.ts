export const runtime = 'nodejs';
export const dynamic = 'force-dynamic';

export async function GET() {
  return new Response('Upload endpoint is reachable', { status: 200 });
}