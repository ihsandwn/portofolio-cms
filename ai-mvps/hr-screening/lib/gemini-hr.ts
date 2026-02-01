// Gemini AI Client for HR Screening
import { GoogleGenerativeAI } from '@google/generative-ai';

const apiKey = process.env.GEMINI_API_KEY;
if (!apiKey) {
    throw new Error('GEMINI_API_KEY is not set');
}

const genAI = new GoogleGenerativeAI(apiKey);
export const model = genAI.getGenerativeModel({
    model: process.env.GEMINI_MODEL || 'gemini-2.5-flash',
});

export async function screenResume(resumeText: string, jobDescription: string, language: 'en' | 'id' = 'en') {
    const languageInstruction = language === 'id'
        ? 'PENTING: Berikan analisis dalam Bahasa Indonesia. Gunakan: Sangat Direkomendasikan, Direkomendasikan, Mungkin, Tidak Direkomendasikan untuk rekomendasi. Gunakan: Tinggi, Sedang, Rendah untuk relevansi.'
        : '';

    const prompt = `${languageInstruction}

You are an HR screening AI. Analyze this resume against the job description and provide a detailed screening report.

**Job Description:**
${jobDescription}

**Resume:**
${resumeText}

Provide your analysis in JSON format:
{
  "overallScore": 85,
  "recommendation": "Highly Recommended|Recommended|Maybe|Not Recommended",
  "matchedSkills": ["skill1", "skill2", "skill3"],
  "missingSkills": ["skill1", "skill2"],
  "experience": {
    "years": 5,
    "relevance": "High|Medium|Low"
  },
  "education": {
    "level": "Bachelor|Master|PhD|etc",
    "relevance": "High|Medium|Low"
  },
  "strengths": ["strength1", "strength2", "strength3"],
  "concerns": ["concern1", "concern2"],
  "summary": "Brief summary of the candidate's fit for this role"
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
        console.error('[GEMINI HR] Error:', error);
        throw new Error(`Failed to screen resume: ${error instanceof Error ? error.message : String(error)}`);
    }
}
