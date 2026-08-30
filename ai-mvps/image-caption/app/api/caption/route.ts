import { NextRequest, NextResponse } from 'next/server';
import { generateImageCaption } from '@/lib/gemini-vision';
import { LanguageSchema } from '@/lib/schemas';
import { validateImageUpload } from '@/lib/image-validation';

export const runtime = 'nodejs';
export const dynamic = 'force-dynamic';

export async function POST(request: NextRequest) {
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
