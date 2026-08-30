'use client';

import { Smile, Frown, Minus, Loader2 } from 'lucide-react';
import type { Language, SentimentResult } from '@/lib/schemas';

interface ResultsDashboardProps {
    result: SentimentResult | null;
    loading: boolean;
    language: Language;
}

const emotionLabels: Record<Language, Record<keyof SentimentResult['emotions'], string>> = {
    en: { joy: 'Joy', sadness: 'Sadness', anger: 'Anger', fear: 'Fear', surprise: 'Surprise' },
    id: { joy: 'Bahagia', sadness: 'Sedih', anger: 'Marah', fear: 'Takut', surprise: 'Terkejut' },
};

export default function ResultsDashboard({ result, loading, language }: ResultsDashboardProps) {
    const copy = language === 'en'
        ? { analyzing: 'Analyzing sentiment...', empty: 'Enter text to analyze sentiment', confidence: 'Confidence', emotions: 'Detected Emotions' }
        : { analyzing: 'Menganalisis sentimen...', empty: 'Masukkan teks untuk dianalisis', confidence: 'Tingkat keyakinan', emotions: 'Emosi Terdeteksi' };

    if (loading) {
        return <div role="status" aria-live="polite" className="flex flex-col items-center justify-center h-64"><Loader2 aria-hidden="true" className="w-12 h-12 text-blue-500 animate-spin mb-4" /><p className="text-gray-500 dark:text-gray-400">{copy.analyzing}</p></div>;
    }

    if (!result) {
        return <div className="flex flex-col items-center justify-center h-64 text-center"><div className="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mb-4"><Smile aria-hidden="true" className="w-8 h-8 text-gray-400" /></div><p className="text-gray-500 dark:text-gray-400">{copy.empty}</p></div>;
    }

    const icon = result.sentiment === 'Positive'
        ? <Smile aria-hidden="true" className="w-12 h-12 text-green-500" />
        : result.sentiment === 'Negative'
            ? <Frown aria-hidden="true" className="w-12 h-12 text-red-500" />
            : <Minus aria-hidden="true" className="w-12 h-12 text-gray-500" />;
    const color = result.sentiment === 'Positive' ? 'text-green-600 dark:text-green-400' : result.sentiment === 'Negative' ? 'text-red-600 dark:text-red-400' : 'text-gray-600 dark:text-gray-400';
    const sentiment = language === 'id' ? { Positive: 'Positif', Negative: 'Negatif', Neutral: 'Netral' }[result.sentiment] : result.sentiment;

    return (
        <div aria-live="polite" className="space-y-6">
            <div className="text-center p-6 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700/50 dark:to-gray-800/50 rounded-xl">
                <div className="flex justify-center mb-3">{icon}</div>
                <h3 className={`text-3xl font-bold ${color} mb-2`}>{sentiment}</h3>
                <p className="text-sm text-gray-600 dark:text-gray-400">{copy.confidence}: {result.confidence}%</p>
            </div>
            <div className="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg"><p className="text-sm text-gray-700 dark:text-gray-300">{result.explanation}</p></div>
            <div className="space-y-3">
                <h4 className="font-medium text-gray-700 dark:text-gray-300">{copy.emotions}</h4>
                {Object.entries(result.emotions).map(([emotion, value]) => (
                    <div key={emotion} className="space-y-1">
                        <div className="flex justify-between text-sm"><span className="text-gray-600 dark:text-gray-400">{emotionLabels[language][emotion as keyof SentimentResult['emotions']]}</span><span className="text-gray-700 dark:text-gray-300 font-medium">{value}%</span></div>
                        <div role="progressbar" aria-label={emotionLabels[language][emotion as keyof SentimentResult['emotions']]} aria-valuemin={0} aria-valuemax={100} aria-valuenow={value} className="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div className="bg-gradient-to-r from-blue-900 to-blue-500 h-2 rounded-full" style={{ width: `${value}%` }} />
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
}
