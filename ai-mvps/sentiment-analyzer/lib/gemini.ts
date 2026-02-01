// Gemini AI Client for Sentiment Analysis
import { GoogleGenerativeAI } from '@google/generative-ai';

const apiKey = process.env.GEMINI_API_KEY;
if (!apiKey) {
    throw new Error('GEMINI_API_KEY is not set');
}

const genAI = new GoogleGenerativeAI(apiKey);
export const model = genAI.getGenerativeModel({
    model: process.env.GEMINI_MODEL || 'gemini-2.5-flash',
});

export async function analyzeSentiment(text: string, language: 'en' | 'id' = 'en') {
    const languageInstruction = language === 'id'
        ? 'PENTING: Berikan analisis dalam Bahasa Indonesia. Gunakan Positif, Negatif, atau Netral untuk sentimen.'
        : '';

    const prompt = `${languageInstruction}

Analyze the sentiment of the following text. Provide:
1. Overall sentiment (Positive, Negative, or Neutral)
2. Confidence score (0-100)
3. Key emotions detected (joy, sadness, anger, fear, surprise)
4. Brief explanation

Text: "${text}"

Respond in JSON format:
{
  "sentiment": "Positive|Negative|Neutral",
  "confidence": 85,
  "emotions": {
    "joy": 70,
    "sadness": 10,
    "anger": 5,
    "fear": 5,
    "surprise": 10
  },
  "explanation": "Brief explanation here"
}`;

    try {
        const result = await model.generateContent(prompt);
        const response = result.response.text();

        // Extract JSON from response
        const jsonMatch = response.match(/\{[\s\S]*\}/);
        if (jsonMatch) {
            return JSON.parse(jsonMatch[0]);
        }

        throw new Error('Invalid response format');
    } catch (error) {
        console.error('[GEMINI] Error:', error);
        throw new Error(`Failed to analyze sentiment: ${error instanceof Error ? error.message : String(error)}`);
    }
}
