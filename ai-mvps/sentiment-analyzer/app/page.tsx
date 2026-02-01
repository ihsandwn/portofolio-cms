'use client';

import { useState } from 'react';
import { Sparkles, Type, BarChart3 } from 'lucide-react';
import TextInput from '@/components/TextInput';
import ResultsDashboard from '@/components/ResultsDashboard';
import LanguageToggle from '@/components/LanguageToggle';

export type Language = 'en' | 'id';

export default function Home() {
  const [result, setResult] = useState<any | null>(null);
  const [loading, setLoading] = useState(false);
  const [language, setLanguage] = useState<Language>('en');

  const handleAnalyze = async (text: string) => {
    setLoading(true);
    try {
      const response = await fetch('/api/analyze', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ text, language }),
      });

      const data = await response.json();
      setResult(data);
    } catch (error) {
      console.error('Analysis failed:', error);
    } finally {
      setLoading(false);
    }
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
                {language === 'en' ? 'Sentiment Analyzer' : 'Analisis Sentimen'}
              </h1>
              <p className="text-gray-600 dark:text-gray-400 text-lg">
                {language === 'en' ? 'AI-powered text sentiment analysis and emotion detection' : 'Analisis sentimen teks dan deteks emosi dengan AI'}
              </p>
            </div>
            <div className="flex-1 flex justify-end">
              <LanguageToggle onLanguageChange={setLanguage} />
            </div>
          </div>
        </header>

        {/* Main Content */}
        <div className="grid lg:grid-cols-2 gap-6">
          {/* Text Input */}
          <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 border border-gray-200 dark:border-gray-700">
            <div className="flex items-center gap-2 mb-4">
              <Type className="w-5 h-5 text-blue-500" />
              <h2 className="text-xl font-semibold text-gray-800 dark:text-gray-200">
                {language === 'en' ? 'Text Input' : 'Masukkan Teks'}
              </h2>
            </div>
            <TextInput onAnalyze={handleAnalyze} loading={loading} language={language} />
          </div>

          {/* Results */}
          <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 border border-gray-200 dark:border-gray-700">
            <div className="flex items-center gap-2 mb-4">
              <BarChart3 className="w-5 h-5 text-blue-900" />
              <h2 className="text-xl font-semibold text-gray-800 dark:text-gray-200">
                {language === 'en' ? 'Analysis Results' : 'Hasil Analisis'}
              </h2>
            </div>
            <ResultsDashboard result={result} loading={loading} language={language} />
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
