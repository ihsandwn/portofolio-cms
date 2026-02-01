'use client';

import { useState } from 'react';
import { Languages } from 'lucide-react';

export type Language = 'en' | 'id';

interface LanguageToggleProps {
    onLanguageChange?: (lang: Language) => void;
}

export default function LanguageToggle({ onLanguageChange }: LanguageToggleProps) {
    const [language, setLanguage] = useState<Language>('en');

    const handleToggle = () => {
        const newLang: Language = language === 'en' ? 'id' : 'en';
        setLanguage(newLang);
        onLanguageChange?.(newLang);
    };

    return (
        <button
            onClick={handleToggle}
            className="flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors shadow-sm"
            title={language === 'en' ? 'Switch to Indonesian' : 'Beralih ke Bahasa Inggris'}
        >
            <Languages className="w-4 h-4 text-blue-600 dark:text-blue-400" />
            <span className="text-sm font-medium text-gray-700 dark:text-gray-300">
                {language === 'en' ? 'English' : 'Indonesia'}
            </span>
        </button>
    );
}
