// Gemini Vision AI Client for Image Captioning
import { GoogleGenerativeAI } from '@google/generative-ai';

const apiKey = process.env.GEMINI_API_KEY;
if (!apiKey) {
    throw new Error('GEMINI_API_KEY is not set');
}

const genAI = new GoogleGenerativeAI(apiKey);
export const model = genAI.getGenerativeModel({
    model: process.env.GEMINI_MODEL || 'gemini-2.5-flash',
});

export async function generateImageCaption(imageData: string, language: 'en' | 'id' = 'en') {
    const languageInstruction = language === 'id'
        ? 'PENTING: Berikan deskripsi dalam Bahasa Indonesia yang natural dan deskriptif.'
        : '';

    const prompt = `${languageInstruction}

Analyze this image and provide:
1. A detailed descriptive caption (1-2 sentences)
2. A short title (5-7 words)
3. Categories (up to 5 relevant tags)
4. Main objects/subjects detected
5. Color palette (main colors)
6. Mood/atmosphere

Respond in JSON format:
{
  "caption": "Detailed description here",
  "title": "Short title",
  "categories": ["tag1", "tag2", "tag3"],
  "objects": ["object1", "object2"],
  "colors": ["color1", "color2", "color3"],
  "mood": "mood description"
}`;

    try {
        // Parse base64 image data
        const [header, base64Data] = imageData.split(',');
        const mimeType = header.match(/:(.*?);/)?.[1] || 'image/jpeg';

        const result = await model.generateContent([
            prompt,
            {
                inlineData: {
                    data: base64Data,
                    mimeType,
                },
            },
        ]);

        const response = result.response.text();

        // Extract JSON from response
        const jsonMatch = response.match(/\{[\s\S]*\}/);
        if (jsonMatch) {
            return JSON.parse(jsonMatch[0]);
        }

        throw new Error('Invalid response format');
    } catch (error) {
        console.error('[GEMINI VISION] Error:', error);
        throw new Error(`Failed to generate caption: ${error instanceof Error ? error.message : String(error)}`);
    }
}
