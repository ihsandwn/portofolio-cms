import { NextRequest, NextResponse } from 'next/server';
import { generateImageCaption } from '@/lib/gemini-vision';
import { LanguageSchema } from '@/lib/schemas';
import { validateImageUpload } from '@/lib/image-validation';
import { validateAccessToken } from '@/lib/auth';

export const runtime = 'nodejs';
export const dynamic = 'force-dynamic';

export async function POST(request: NextRequest) {
  const token = request.cookies.get('mvp-access-image-caption')?.value;
  if (!token || !(await validateAccessToken(token, request.nextUrl.origin))) {
    return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
  }

  try {
    const formData = await request.formData();
    const fileValue = formData.get('file');
    const languageResult = LanguageSchema.safeParse(formData.get('language') ?? 'en');
    if (!(fileValue instanceof File) || !languageResult.success) {
      return NextResponse.json({ error: 'Invalid request' }, { status: 400 });
    }

    const { buffer, mime } = await validateImageUpload(fileValue);
    const imageData = `data:${mime};base64,${Buffer.from(buffer).toString('base64')}`;
    const caption = await generateImageCaption(imageData, languageResult.data);

    return NextResponse.json({
      success: true,
      filename: fileValue.name,
      ...caption,
      captionedAt: new Date().toISOString(),
    });
  } catch {
    return NextResponse.json({ error: 'Unable to process image' }, { status: 400 });
  }
}
