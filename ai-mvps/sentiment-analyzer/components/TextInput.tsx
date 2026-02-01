'use client';

import { useState } from 'react';
import { Send, Loader2 } from 'lucide-react';

interface TextInputProps {
    onAnalyze: (text: string) => void;
    loading: boolean;
    language?: 'en' | 'id';
}

export default function TextInput({ onAnalyze, loading }: TextInputProps) {
    const [text, setText] = useState('');

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        if (text.trim() && !loading) {
            onAnalyze(text);
        }
    };

    const examples = [
        "I absolutely love this product! It's amazing and exceeded all my expectations.",
        "This is terrible. Worst experience ever. Very disappointed.",
        "The weather is nice today. Going to the park.",
    ];

    return (
        <div className="space-y-4">
            <form onSubmit={handleSubmit} className="space-y-3">
                <textarea
                    value={text}
                    onChange={(e) => setText(e.target.value)}
                    placeholder="Enter text to analyze sentiment..."
                    disabled={loading}
                    rows={8}
                    maxLength={5000}
                    className="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 resize-none"
                />

                <div className="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                    <span>{text.length} / 5000</span>
                </div>

                <button
                    type="submit"
                    disabled={!text.trim() || loading}
                    className="w-full px-6 py-3 bg-gradient-to-r from-blue-900 to-blue-500 text-white rounded-xl hover:from-blue-800 hover:to-blue-400 disabled:opacity-50 disabled:cursor-not-allowed transition-all flex items-center justify-center gap-2 font-medium shadow-lg"
                >
                    {loading ? (
                        <>
                            <Loader2 className="w-5 h-5 animate-spin" />
                            Analyzing...
                        </>
                    ) : (
                        <>
                            <Send className="w-5 h-5" />
                            Analyze Sentiment
                        </>
                    )}
                </button>
            </form>

            <div className="pt-4 border-t border-gray-200 dark:border-gray-700">
                <p className="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">
                    Try an example:
                </p>
                <div className="space-y-2">
                    {examples.map((example, index) => (
                        <button
                            key={index}
                            onClick={() => setText(example)}
                            disabled={loading}
                            className="w-full text-left px-3 py-2 text-sm bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors disabled:opacity-50 text-gray-700 dark:text-gray-300"
                        >
                            {example}
                        </button>
                    ))}
                </div>
            </div>
        </div>
    );
}
