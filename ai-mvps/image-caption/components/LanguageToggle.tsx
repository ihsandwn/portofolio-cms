'use client';

import { Languages } from 'lucide-react';
import type { Language } from '@/lib/schemas';

interface LanguageToggleProps {
  language: Language;
  onLanguageChange: (lang: Language) => void;
}

export default function LanguageToggle({ language, onLanguageChange }: LanguageToggleProps) {
  return (
    <button
      onClick={() => onLanguageChange(language === 'en' ? 'id' : 'en')}
      aria-label={language === 'en' ? 'Switch to Indonesian' : 'Beralih ke Bahasa Inggris'}
      className="flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors shadow-sm"
    >
      <Languages className="w-4 h-4 text-blue-600 dark:text-blue-400" aria-hidden="true" />
      <span className="text-sm font-medium text-gray-700 dark:text-gray-300">{language === 'en' ? 'English' : 'Indonesia'}</span>
    </button>
  );
}
