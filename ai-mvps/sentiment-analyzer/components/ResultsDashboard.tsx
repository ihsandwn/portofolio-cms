'use client';

import { Smile, Frown, Minus, Loader2 } from 'lucide-react';

interface ResultsDashboardProps {
    result: any | null;
    loading: boolean;
    language?: 'en' | 'id';
}

export default function ResultsDashboard({ result, loading }: ResultsDashboardProps) {
    if (loading) {
        return (
            <div className="flex flex-col items-center justify-center h-64">
                <Loader2 className="w-12 h-12 text-blue-500 animate-spin mb-4" />
                <p className="text-gray-500 dark:text-gray-400">Analyzing sentiment...</p>
            </div>
        );
    }

    if (!result) {
        return (
            <div className="flex flex-col items-center justify-center h-64 text-center">
                <div className="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mb-4">
                    <Smile className="w-8 h-8 text-gray-400" />
                </div>
                <p className="text-gray-500 dark:text-gray-400">
                    Enter text to analyze sentiment
                </p>
            </div>
        );
    }

    const getSentimentIcon = () => {
        switch (result.sentiment) {
            case 'Positive':
                return <Smile className="w-12 h-12 text-green-500" />;
            case 'Negative':
                return <Frown className="w-12 h-12 text-red-500" />;
            default:
                return <Minus className="w-12 h-12 text-gray-500" />;
        }
    };

    const getSentimentColor = () => {
        switch (result.sentiment) {
            case 'Positive':
                return 'text-green-600 dark:text-green-400';
            case 'Negative':
                return 'text-red-600 dark:text-red-400';
            default:
                return 'text-gray-600 dark:text-gray-400';
        }
    };

    return (
        <div className="space-y-6">
            {/* Sentiment Result */}
            <div className="text-center p-6 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700/50 dark:to-gray-800/50 rounded-xl">
                <div className="flex justify-center mb-3">
                    {getSentimentIcon()}
                </div>
                <h3 className={`text-3xl font-bold ${getSentimentColor()} mb-2`}>
                    {result.sentiment}
                </h3>
                <p className="text-sm text-gray-600 dark:text-gray-400">
                    Confidence: {result.confidence}%
                </p>
            </div>

            {/* Explanation */}
            <div className="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                <p className="text-sm text-gray-700 dark:text-gray-300">
                    {result.explanation}
                </p>
            </div>

            {/* Emotions */}
            {result.emotions && (
                <div className="space-y-3">
                    <h4 className="font-medium text-gray-700 dark:text-gray-300">
                        Detected Emotions
                    </h4>
                    {Object.entries(result.emotions).map(([emotion, value]) => {
                        const numValue = Number(value);
                        return (
                            <div key={emotion} className="space-y-1">
                                <div className="flex justify-between text-sm">
                                    <span className="capitalize text-gray-600 dark:text-gray-400">
                                        {emotion}
                                    </span>
                                    <span className="text-gray-700 dark:text-gray-300 font-medium">
                                        {numValue}%
                                    </span>
                                </div>
                                <div className="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div
                                        className="bg-gradient-to-r from-blue-900 to-blue-500 h-2 rounded-full transition-all"
                                        style={{ width: `${numValue}%` }}
                                    />
                                </div>
                            </div>
                        );
                    })}
                </div>
            )}
        </div>
    );
}
