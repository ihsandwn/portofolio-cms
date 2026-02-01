'use client';

import { Tag, Palette, Smile, Sparkles, ImageIcon } from 'lucide-react';

interface CaptionResultsProps {
    result: any | null;
    imageUrl?: string | null;
    loading?: boolean;
    language?: 'en' | 'id';
}

export default function CaptionResults({ result, imageUrl, loading, language }: CaptionResultsProps) {
    if (!result) {
        return (
            <div className="flex flex-col items-center justify-center h-64 text-center">
                <div className="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mb-4">
                    <ImageIcon className="w-8 h-8 text-gray-400" />
                </div>
                <p className="text-gray-500 dark:text-gray-400">
                    Upload an image to generate captions
                </p>
            </div>
        );
    }

    return (
        <div className="space-y-6">
            {/* Image Preview */}
            {imageUrl && (
                <div className="relative rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                    <img
                        src={imageUrl}
                        alt="Uploaded"
                        className="w-full h-48 object-cover"
                    />
                </div>
            )}

            {/* Title */}
            {result.title && (
                <div className="p-4 bg-gradient-to-r from-blue-900 to-blue-500 rounded-xl">
                    <div className="flex items-start gap-2">
                        <Sparkles className="w-5 h-5 text-white flex-shrink-0 mt-0.5" />
                        <div>
                            <p className="text-sm text-blue-100 mb-1">AI Generated Title</p>
                            <h3 className="text-xl font-bold text-white">{result.title}</h3>
                        </div>
                    </div>
                </div>
            )}

            {/* Caption */}
            {result.caption && (
                <div className="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                    <p className="text-gray-700 dark:text-gray-300 leading-relaxed">
                        {result.caption}
                    </p>
                </div>
            )}

            {/* Categories */}
            {result.categories && result.categories.length > 0 && (
                <div className="space-y-2">
                    <div className="flex items-center gap-2 text-gray-700 dark:text-gray-300 font-medium">
                        <Tag className="w-4 h-4 text-blue-500" />
                        <span className="text-sm">Categories</span>
                    </div>
                    <div className="flex flex-wrap gap-2">
                        {result.categories.map((category: string, index: number) => (
                            <span
                                key={index}
                                className="px-3 py-1 bg-gradient-to-r from-blue-100 to-blue-50 dark:from-blue-900/30 dark:to-blue-800/30 text-blue-700 dark:text-blue-300 rounded-full text-sm border border-blue-200 dark:border-blue-700"
                            >
                                {category}
                            </span>
                        ))}
                    </div>
                </div>
            )}

            {/* Objects */}
            {result.objects && result.objects.length > 0 && (
                <div className="space-y-2">
                    <div className="flex items-center gap-2 text-gray-700 dark:text-gray-300 font-medium">
                        <ImageIcon className="w-4 h-4 text-blue-500" />
                        <span className="text-sm">Detected Objects</span>
                    </div>
                    <div className="flex flex-wrap gap-2">
                        {result.objects.map((object: string, index: number) => (
                            <span
                                key={index}
                                className="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg text-sm"
                            >
                                {object}
                            </span>
                        ))}
                    </div>
                </div>
            )}

            {/* Colors */}
            {result.colors && result.colors.length > 0 && (
                <div className="space-y-2">
                    <div className="flex items-center gap-2 text-gray-700 dark:text-gray-300 font-medium">
                        <Palette className="w-4 h-4 text-blue-500" />
                        <span className="text-sm">Color Palette</span>
                    </div>
                    <div className="flex gap-2">
                        {result.colors.map((color: string, index: number) => (
                            <div key={index} className="text-center">
                                <div className="w-12 h-12 rounded-lg border-2 border-gray-300 dark:border-gray-600 mb-1"
                                    style={{ backgroundColor: color.toLowerCase() }}
                                />
                                <span className="text-xs text-gray-600 dark:text-gray-400 capitalize">
                                    {color}
                                </span>
                            </div>
                        ))}
                    </div>
                </div>
            )}

            {/* Mood */}
            {result.mood && (
                <div className="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <div className="flex items-center gap-2 mb-2">
                        <Smile className="w-4 h-4 text-blue-500" />
                        <span className="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Mood & Atmosphere
                        </span>
                    </div>
                    <p className="text-sm text-gray-600 dark:text-gray-400">
                        {result.mood}
                    </p>
                </div>
            )}
        </div>
    );
}
