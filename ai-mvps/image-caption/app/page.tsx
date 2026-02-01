'use client';

import { useState } from 'react';
import { Sparkles, Image as ImageIcon, Tag } from 'lucide-react';
import ImageUploader from '@/components/ImageUploader';
import CaptionResults from '@/components/CaptionResults';
import LanguageToggle from '@/components/LanguageToggle';

export type Language = 'en' | 'id';

export default function Home() {
  const [result, setResult] = useState<any | null>(null);
  const [loading, setLoading] = useState(false);
  const [language, setLanguage] = useState<Language>('en');

  const handleCaptionGenerated = (data: any) => {
    setResult(data);
  };

  return (
    <main className="min-h-screen p-4 md:p-8">
      <div className="max-w-7xl mx-auto">
        {/* Header */}
        <header className="mb-8">
          <div className="flex justify-between items-center mb-6">
            <div className="flex-1" />
            <div className="text-center flex-1">
              <h1 className="text-4xl md:text-5xl font-bold bg-gradient-to-r from-blue-900 via-blue-600 to-blue-400 bg-clip-text text-transparent mb-2">
                {language === 'en' ? 'Image Caption & Categorizer' : 'Caption & Kategorisasi Gambar'}
              </h1>
              <p className="text-gray-600 dark:text-gray-400 text-lg">
                {language === 'en' ? 'AI-powered image analysis and automatic categorization' : 'Analisis gambar dan kategorisasi otomatis dengan AI'}
              </p>
            </div>
            <div className="flex-1 flex justify-end">
              <LanguageToggle onLanguageChange={setLanguage} />
            </div>
          </div>
        </header>

        {/* Main Content */}
        <div className="grid lg:grid-cols-2 gap-6">
          {/* Image Upload */}
          <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 border border-gray-200 dark:border-gray-700">
            <div className="flex items-center gap-2 mb-4">
              <ImageIcon className="w-5 h-5 text-blue-500" />
              <h2 className="text-xl font-semibold text-gray-800 dark:text-gray-200">
                {language === 'en' ? 'Upload Image' : 'Unggah Gambar'}
              </h2>
            </div>
            <ImageUploader onCaptionGenerated={handleCaptionGenerated} loading={loading} setLoading={setLoading} language={language} />
          </div>

          {/* Results */}
          <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 border border-gray-200 dark:border-gray-700">
            <div className="flex items-center gap-2 mb-4">
              <Tag className="w-5 h-5 text-blue-900" />
              <h2 className="text-xl font-semibold text-gray-800 dark:text-gray-200">
                {language === 'en' ? 'Analysis Results' : 'Hasil Analisis'}
              </h2>
            </div>
            <CaptionResults result={result} loading={loading} language={language} />
          </div>
        </div>

        {/* Footer */}
        <footer className="mt-8 text-center text-sm text-gray-500 dark:text-gray-400">
          <p>Powered by Gemini AI • Built with Next.js</p>
        </footer>
      </div>
    </main>
  );
}
