import { GoogleGenerativeAI, SchemaType } from '@google/generative-ai';
import { CaptionSchema, type Caption } from '@/lib/schemas';

const apiKey = process.env.GEMINI_API_KEY;
if (!apiKey) throw new Error('GEMINI_API_KEY is not configured');

const model = new GoogleGenerativeAI(apiKey).getGenerativeModel({
  model: process.env.GEMINI_MODEL || 'gemini-2.5-flash',
  generationConfig: {
    responseMimeType: 'application/json',
    responseSchema: {
      type: SchemaType.OBJECT,
      properties: {
        caption: { type: SchemaType.STRING }, title: { type: SchemaType.STRING },
        categories: { type: SchemaType.ARRAY, items: { type: SchemaType.STRING } },
        objects: { type: SchemaType.ARRAY, items: { type: SchemaType.STRING } },
        colors: { type: SchemaType.ARRAY, items: { type: SchemaType.STRING } }, mood: { type: SchemaType.STRING },
      }, required: ['caption', 'title', 'categories', 'objects', 'colors', 'mood'],
    },
  },
});

export async function generateImageCaption(imageData: string, language: 'en' | 'id' = 'en'): Promise<Caption> {
  const [header, base64Data] = imageData.split(',');
  const mimeType = header.match(/:(.*?);/)?.[1] || 'image/jpeg';
  const languageInstruction = language === 'id' ? 'Respond naturally in Bahasa Indonesia.' : 'Respond in English.';
  const prompt = `${languageInstruction} Analyze image. Return a short title, 1-2 sentence caption, up to 5 categories, detected objects, main colors, and mood. Do not invent unreadable details.`;

  try {
    const result = await model.generateContent([prompt, { inlineData: { data: base64Data, mimeType } }]);
    return CaptionSchema.parse(JSON.parse(result.response.text()));
  } catch {
    throw new Error('Caption generation failed');
  }
}
