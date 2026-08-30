import { z } from 'zod';

export const CaptionSchema = z.object({
  title: z.string().min(1).max(200),
  caption: z.string().min(1).max(2000),
  categories: z.array(z.string().min(1).max(80)).max(5),
  objects: z.array(z.string().min(1).max(80)).max(20),
  colors: z.array(z.string().min(1).max(40)).max(10),
  mood: z.string().min(1).max(300),
});

export const CaptionResponseSchema = CaptionSchema.extend({
  success: z.literal(true),
  filename: z.string().min(1).max(255),
  captionedAt: z.string().datetime(),
});

export type Caption = z.infer<typeof CaptionSchema>;
export type CaptionResponse = z.infer<typeof CaptionResponseSchema>;

export const LanguageSchema = z.enum(['en', 'id']);
export type Language = z.infer<typeof LanguageSchema>;
