import { z } from 'zod';

export const languageSchema = z.enum(['en', 'id']);
export type Language = z.infer<typeof languageSchema>;

export const analyzeRequestSchema = z.object({
    text: z.string().trim().min(1, 'Text is required').max(5000, 'Text too long'),
    language: languageSchema.default('en'),
});
export type AnalyzeRequest = z.infer<typeof analyzeRequestSchema>;

export const emotionScoresSchema = z.object({
    joy: z.number().min(0).max(100),
    sadness: z.number().min(0).max(100),
    anger: z.number().min(0).max(100),
    fear: z.number().min(0).max(100),
    surprise: z.number().min(0).max(100),
}).strict();

export const sentimentResultSchema = z.object({
    sentiment: z.enum(['Positive', 'Negative', 'Neutral']),
    confidence: z.number().int().min(0).max(100),
    emotions: emotionScoresSchema,
    explanation: z.string().trim().min(1).max(1000),
}).strict();

export type SentimentResult = z.infer<typeof sentimentResultSchema>;

export const analyzeApiResponseSchema = sentimentResultSchema.extend({
    success: z.literal(true),
    analyzedAt: z.string().datetime(),
});

export type AnalyzeApiResponse = z.infer<typeof analyzeApiResponseSchema>;
