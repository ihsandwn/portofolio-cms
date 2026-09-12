'use client';

import { useEffect, useRef, useState } from 'react';
import { Type, BarChart3, AlertCircle } from 'lucide-react';
import TextInput from '@/components/TextInput';
import ResultsDashboard from '@/components/ResultsDashboard';
import LanguageToggle from '@/components/LanguageToggle';
import { analyzeApiResponseSchema, type AnalyzeApiResponse, type Language } from '@/lib/schemas';

export default function Home() {
  const [result, setResult] = useState<AnalyzeApiResponse | null>(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const [language, setLanguage] = useState<Language>('en');
  const controllerRef = useRef<AbortController | null>(null);

  useEffect(() => () => controllerRef.current?.abort(), []);

  const handleAnalyze = async (text: string) => {
    controllerRef.current?.abort();
    const controller = new AbortController();
    controllerRef.current = controller;
    setLoading(true);
    setError(null);

    try {
      const response = await fetch('/api/analyze', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ text, language }),
        signal: controller.signal,
      });
      const data: unknown = await response.json();

      if (!response.ok) throw new Error(language === 'en' ? 'Analysis failed. Please try again.' : 'Analisis gagal. Silakan coba lagi.');

      const parsed = analyzeApiResponseSchema.safeParse(data);
      if (!parsed.success) throw new Error(language === 'en' ? 'Invalid analysis response.' : 'Respons analisis tidak valid.');
      setResult(parsed.data);
    } catch (caught) {
      if (caught instanceof DOMException && caught.name === 'AbortError') return;
      setError(caught instanceof Error ? caught.message : language === 'en' ? 'Analysis failed.' : 'Analisis gagal.');
    } finally {
      if (controllerRef.current === controller) setLoading(false);
    }
  };

  const copy = language === 'en'
    ? { title: 'Sentiment Analyzer', subtitle: 'AI-powered text sentiment analysis and emotion detection', input: 'Text Input', results: 'Analysis Results' }
    : { title: 'Analisis Sentimen', subtitle: 'Analisis sentimen teks dan deteksi emosi dengan AI', input: 'Masukkan Teks', results: 'Hasil Analisis' };

  return (
    <main className="min-h-screen p-4 md:p-8">
      <div className="max-w-7xl mx-auto">
        <header className="mb-8">
          <div className="flex flex-col md:flex-row md:items-start gap-4">
            <div className="hidden md:block flex-1" />
            <div className="text-center flex-1">
              <h1 className="text-4xl md:text-5xl font-bold bg-gradient-to-r from-blue-900 via-blue-600 to-blue-400 bg-clip-text text-transparent mb-2">{copy.title}</h1>
              <p className="text-gray-600 dark:text-gray-400 text-lg">{copy.subtitle}</p>
            </div>
            <div className="flex-1 flex justify-center md:justify-end"><LanguageToggle onLanguageChange={setLanguage} /></div>
          </div>
        </header>
        {error && <div role="alert" aria-live="assertive" className="mb-6 flex items-center gap-2 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg"><AlertCircle aria-hidden="true" className="w-5 h-5 text-red-500 shrink-0" /><p className="text-sm text-red-700 dark:text-red-400">{error}</p></div>}
        <div className="grid lg:grid-cols-2 gap-6">
          <section aria-labelledby="input-heading" className="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 border border-gray-200 dark:border-gray-700">
            <div className="flex items-center gap-2 mb-4"><Type aria-hidden="true" className="w-5 h-5 text-blue-500" /><h2 id="input-heading" className="text-xl font-semibold text-gray-800 dark:text-gray-200">{copy.input}</h2></div>
            <TextInput onAnalyze={handleAnalyze} loading={loading} language={language} />
          </section>
          <section aria-labelledby="results-heading" className="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 border border-gray-200 dark:border-gray-700">
            <div className="flex items-center gap-2 mb-4"><BarChart3 aria-hidden="true" className="w-5 h-5 text-blue-900" /><h2 id="results-heading" className="text-xl font-semibold text-gray-800 dark:text-gray-200">{copy.results}</h2></div>
            <ResultsDashboard result={result} loading={loading} language={language} />
          </section>
        </div>
        <footer className="mt-8 text-center text-sm text-gray-500 dark:text-gray-400"><p>Powered by Gemini AI · Built with Next.js</p></footer>
      </div>
    </main>
  );
}
