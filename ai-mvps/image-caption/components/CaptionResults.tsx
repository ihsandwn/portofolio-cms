'use client';

import { Tag, Palette, Smile, Sparkles, Image as ImageIcon } from 'lucide-react';
import type { CaptionResponse, Language } from '@/lib/schemas';

interface CaptionResultsProps {
  result: CaptionResponse | null;
  imageUrl?: string | null;
  language: Language;
}

export default function CaptionResults({ result, imageUrl, language }: CaptionResultsProps) {
  if (!result) {
    return (
      <div className="flex flex-col items-center justify-center h-64 text-center">
        <div className="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mb-4">
          <ImageIcon className="w-8 h-8 text-gray-400" aria-hidden="true" />
        </div>
        <p className="text-gray-500 dark:text-gray-400">
          {language === 'en' ? 'Upload an image to generate captions' : 'Unggah gambar untuk menghasilkan caption'}
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
            alt="Uploaded image"
            className="w-full h-48 object-cover"
          />
        </div>
      )}

      {/* Title */}
      {result.title && (
        <div className="p-4 bg-gradient-to-r from-blue-900 to-blue-500 rounded-xl">
          <div className="flex items-start gap-2">
            <Sparkles className="w-5 h-5 text-white flex-shrink-0 mt-0.5" aria-hidden="true" />
            <div>
              <p className="text-sm text-blue-100 mb-1">{language === 'en' ? 'AI Generated Title' : 'Judul AI'}</p>
              <h3 className="text-xl font-bold text-white">{result.title}</h3>
            </div>
          </div>
        </div>
      )}

      {/* Caption */}
      {result.caption && (
        <div className="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
          <p className="text-gray-700 dark:text-gray-300 leading-relaxed">{result.caption}</p>
        </div>
      )}

      {/* Categories */}
      {result.categories && result.categories.length > 0 && (
        <div className="space-y-2">
          <div className="flex items-center gap-2 text-gray-700 dark:text-gray-300 font-medium">
            <Tag className="w-4 h-4 text-blue-500" aria-hidden="true" />
            <span className="text-sm">{language === 'en' ? 'Categories' : 'Kategori'}</span>
          </div>
          <div className="flex flex-wrap gap-2">
            {result.categories.map((category, index) => (
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
            <ImageIcon className="w-4 h-4 text-blue-500" aria-hidden="true" />
            <span className="text-sm">{language === 'en' ? 'Detected Objects' : 'Objek Terdeteksi'}</span>
          </div>
          <div className="flex flex-wrap gap-2">
            {result.objects.map((object, index) => (
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
            <Palette className="w-4 h-4 text-blue-500" aria-hidden="true" />
            <span className="text-sm">{language === 'en' ? 'Color Palette' : 'Paleta Warna'}</span>
          </div>
          <div className="flex gap-2">
            {result.colors.map((color, index) => (
              <div key={index} className="text-center">
                <div
                  className="w-12 h-12 rounded-lg border-2 border-gray-300 dark:border-gray-600 mb-1"
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
            <Smile className="w-4 h-4 text-blue-500" aria-hidden="true" />
            <span className="text-sm font-medium text-gray-700 dark:text-gray-300">
              {language === 'en' ? 'Mood & Atmosphere' : 'Suasana & Mood'}
            </span>
          </div>
          <p className="text-sm text-gray-600 dark:text-gray-400">{result.mood}</p>
        </div>
      )}
    </div>
  );
}