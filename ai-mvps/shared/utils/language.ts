// Simple utility to add Indonesian language support to Gemini AI prompts

export type Language = 'en' | 'id';

export function getLanguageInstruction(language: Language, context: string = 'general'): string {
    if (language === 'id') {
        const instructions = {
            general: 'PENTING: Jawab dalam Bahasa Indonesia yang natural dan profesional.',
            sentiment: 'PENTING: Berikan analisis dalam Bahasa Indonesia. Gunakan istilah: Positif, Negatif, Netral untuk sentimen.',
            caption: 'PENTING: Berikan deskripsi dalam Bahasa Indonesia yang natural dan deskriptif.',
            hr: 'PENTING: Berikan analisis dalam Bahasa Indonesia. Gunakan: Sangat Direkomendasikan, Direkomendasikan, Mungkin, Tidak Direkomendasikan untuk rekomendasi.'
        };
        return instructions[context as keyof typeof instructions] || instructions.general;
    }
    return 'Answer in English.';
}

export const translations = {
    en: {
        // Sentiment
        positive: 'Positive',
        negative: 'Negative',
        neutral: 'Neutral',
        // HR
        highlyRecommended: 'Highly Recommended',
        recommended: 'Recommended',
        maybe: 'Maybe',
        notRecommended: 'Not Recommended',
        high: 'High',
        medium: 'Medium',
        low: 'Low',
        // Common
        loading: 'Loading...',
        upload: 'Upload',
        analyze: 'Analyze',
        results: 'Results',
    },
    id: {
        // Sentiment
        positive: 'Positif',
        negative: 'Negatif',
        neutral: 'Netral',
        // HR
        highlyRecommended: 'Sangat Direkomendasikan',
        recommended: 'Direkomendasikan',
        maybe: 'Mungkin',
        notRecommended: 'Tidak Direkomendasikan',
        high: 'Tinggi',
        medium: 'Sedang',
        low: 'Rendah',
        // Common
        loading: 'Memuat...',
        upload: 'Unggah',
        analyze: 'Analisis',
        results: 'Hasil',
    }
};

export function t(key: keyof typeof translations.en, language: Language = 'en'): string {
    return translations[language][key];
}
