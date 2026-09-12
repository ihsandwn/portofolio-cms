'use client';

import { useState } from 'react';
import { Send, Loader2 } from 'lucide-react';
import type { Language } from '@/lib/schemas';

interface TextInputProps {
    onAnalyze: (text: string) => void;
    loading: boolean;
    language: Language;
}

const examples: Record<Language, string[]> = {
    en: [
        "I absolutely love this product! It's amazing and exceeded all my expectations.",
        'This is terrible. Worst experience ever. Very disappointed.',
        'The weather is nice today. Going to the park.',
    ],
    id: [
        'Saya sangat menyukai produk ini! Hasilnya melebihi harapan saya.',
        'Ini sangat buruk. Pengalaman terburuk dan sangat mengecewakan.',
        'Cuaca hari ini cerah. Saya akan pergi ke taman.',
    ],
};

export default function TextInput({ onAnalyze, loading, language }: TextInputProps) {
    const [text, setText] = useState('');
    const copy = language === 'en'
        ? { label: 'Text to analyze', placeholder: 'Enter text to analyze sentiment...', analyzing: 'Analyzing...', analyze: 'Analyze Sentiment', example: 'Try an example:' }
        : { label: 'Teks untuk dianalisis', placeholder: 'Masukkan teks untuk dianalisis...', analyzing: 'Menganalisis...', analyze: 'Analisis Sentimen', example: 'Coba contoh:' };

    const handleSubmit = (event: React.FormEvent) => {
        event.preventDefault();
        if (text.trim() && !loading) onAnalyze(text);
    };

    return (
        <div className="space-y-4">
            <form onSubmit={handleSubmit} className="space-y-3">
                <label htmlFor="sentiment-text" className="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {copy.label}
                </label>
                <textarea
                    id="sentiment-text"
                    value={text}
                    onChange={(event) => setText(event.target.value)}
                    placeholder={copy.placeholder}
                    disabled={loading}
                    rows={8}
                    maxLength={5000}
                    aria-describedby="character-count"
                    className="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 resize-none"
                />
                <div id="character-count" className="text-sm text-gray-500 dark:text-gray-400">
                    {text.length} / 5000
                </div>
                <button type="submit" disabled={!text.trim() || loading} className="w-full px-6 py-3 bg-gradient-to-r from-blue-900 to-blue-500 text-white rounded-xl hover:from-blue-800 hover:to-blue-400 disabled:opacity-50 disabled:cursor-not-allowed transition-colors flex items-center justify-center gap-2 font-medium shadow-lg">
                    {loading ? <><Loader2 aria-hidden="true" className="w-5 h-5 animate-spin" />{copy.analyzing}</> : <><Send aria-hidden="true" className="w-5 h-5" />{copy.analyze}</>}
                </button>
            </form>
            <div className="pt-4 border-t border-gray-200 dark:border-gray-700">
                <p className="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">{copy.example}</p>
                <div className="space-y-2">
                    {examples[language].map((example) => (
                        <button key={example} type="button" onClick={() => setText(example)} disabled={loading} className="w-full text-left px-3 py-2 text-sm bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors disabled:opacity-50 text-gray-700 dark:text-gray-300">
                            {example}
                        </button>
                    ))}
                </div>
            </div>
        </div>
    );
}
