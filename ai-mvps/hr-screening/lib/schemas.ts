import { z } from 'zod';

export const languageSchema = z.enum(['en', 'id']);
export type Language = z.infer<typeof languageSchema>;

export const requestFieldsSchema = z.object({
    jobDescription: z.string().trim().min(1, 'Job description is required').max(20000, 'Job description exceeds max length'),
    language: languageSchema.default('en'),
});
export type RequestFields = z.infer<typeof requestFieldsSchema>;

export const screeningResultSchema = z.object({
    overallScore: z.number().int().min(0).max(100),
    recommendation: z.enum(['Highly Recommended', 'Recommended', 'Maybe', 'Not Recommended', 'Sangat Direkomendasikan', 'Direkomendasikan', 'Mungkin', 'Tidak Direkomendasikan']),
    matchedSkills: z.array(z.string().trim().min(1).max(100)).max(50),
    missingSkills: z.array(z.string().trim().min(1).max(100)).max(50),
    experience: z.object({
        years: z.number().nonnegative().max(100),
        relevance: z.string().min(1).max(32),
    }),
    education: z.object({
        level: z.string().min(1).max(64),
        relevance: z.string().min(1).max(32),
    }),
    strengths: z.array(z.string().trim().min(1).max(200)).max(30),
    concerns: z.array(z.string().trim().min(1).max(200)).max(30),
    summary: z.string().trim().min(1).max(3000),
}).strict();

export type ScreeningResult = z.infer<typeof screeningResultSchema>;

export const screeningApiResponseSchema = screeningResultSchema.extend({
    success: z.literal(true),
    filename: z.string().min(1).max(255),
    screenedAt: z.string().datetime(),
});

export type ScreeningApiResponse = z.infer<typeof screeningApiResponseSchema>;
