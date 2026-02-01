'use client';

import { useState } from 'react';
import { Sparkles, FileText, Target, TrendingUp } from 'lucide-react';
import ResumeUploader from '@/components/ResumeUploader';
import JobDescriptionInput from '@/components/JobDescriptionInput';
import ScreeningResults from '@/components/ScreeningResults';
import LanguageToggle from '@/components/LanguageToggle';

export type Language = 'en' | 'id';

export default function Home() {
  const [jobDescription, setJobDescription] = useState('');
  const [screeningResult, setScreeningResult] = useState<any | null>(null);
  const [language, setLanguage] = useState<Language>('en');

  const handleJobDescriptionChange = (jd: string) => {
    setJobDescription(jd);
  };

  const handleScreeningComplete = (result: any) => {
    setScreeningResult(result);
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
                {language === 'en' ? 'Automated HR Screening' : 'Screening HR Otomatis'}
              </h1>
              <p className="text-gray-600 dark:text-gray-400 text-lg">
                {language === 'en' ? 'AI-powered resume screening and job description matching' : 'Screening resume dan pencocokan job description dengan AI'}
              </p>
            </div>
            <div className="flex-1 flex justify-end">
              <LanguageToggle onLanguageChange={setLanguage} />
            </div>
          </div>
        </header>

        {/* Main Content - 3 Column Grid */}
        <div className="grid lg:grid-cols-3 gap-6 mb-6">
          {/* JD Input */}
          <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 border border-gray-200 dark:border-gray-700">
            <div className="flex items-center gap-2 mb-4">
              <Target className="w-5 h-5 text-blue-500" />
              <h2 className="text-xl font-semibold text-gray-800 dark:text-gray-200">
                {language === 'en' ? 'Job Description' : 'Deskripsi Pekerjaan'}
              </h2>
            </div>
            <JobDescriptionInput onChange={handleJobDescriptionChange} language={language} />
          </div>

          {/* Resume Upload */}
          <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 border border-gray-200 dark:border-gray-700">
            <div className="flex items-center gap-2 mb-4">
              <FileText className="w-5 h-5 text-blue-600" />
              <h2 className="text-xl font-semibold text-gray-800 dark:text-gray-200">
                {language === 'en' ? 'Upload Resume' : 'Unggah Resume'}
              </h2>
            </div>
            <ResumeUploader jobDescription={jobDescription} onScreeningComplete={handleScreeningComplete} language={language} />
          </div>

          {/* Results */}
          <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 border border-gray-200 dark:border-gray-700">
            <div className="flex items-center gap-2 mb-4">
              <TrendingUp className="w-5 h-5 text-blue-900" />
              <h2 className="text-xl font-semibold text-gray-800 dark:text-gray-200">
                {language === 'en' ? 'Screening Results' : 'Hasil Screening'}
              </h2>
            </div>
            <ScreeningResults result={screeningResult} language={language} />
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
