'use client';

import { useState } from 'react';
import { FileCheck } from 'lucide-react';

export type Language = 'en' | 'id';

interface JobDescriptionInputProps {
    onChange: (jd: string) => void;
    language: Language;
}

export default function JobDescriptionInput({ onChange, language }: JobDescriptionInputProps) {
    const [jd, setJd] = useState('');

    const handleChange = (value: string) => {
        setJd(value);
        onChange(value);
    };

    const exampleJD = language === 'en'
        ? `Senior Full-Stack Developer

Required Skills:
- 5+ years of experience with React and Node.js
- Strong knowledge of TypeScript
- Experience with PostgreSQL or MongoDB
- RESTful API design and development
- Git version control
- Agile/Scrum methodology

Preferred:
- AWS or GCP experience
- Docker and Kubernetes
- CI/CD pipeline setup`
        : `Pengembang Full-Stack Senior

Keterampilan yang Diperlukan:
- Pengalaman 5+ tahun dengan React dan Node.js
- Pengetahuan kuat tentang TypeScript
- Pengalaman dengan PostgreSQL atau MongoDB
- Desain dan pengembangan RESTful API
- Kontrol versi Git
- Metodologi Agile/Scrum

Diutamakan:
- Pengalaman AWS atau GCP
- Docker dan Kubernetes
- Pengaturan pipeline CI/CD`;

    const placeholderText = language === 'en'
        ? 'Paste job description here...'
        : 'Tempelkan deskripsi pekerjaan di sini...';

    const charactersText = language === 'en'
        ? 'characters'
        : 'karakter';

    const loadExampleText = language === 'en'
        ? 'Load Example JD'
        : 'Muat Contoh JD';

    return (
        <div className="space-y-4">
            <textarea
                value={jd}
                onChange={(e) => handleChange(e.target.value)}
                placeholder={placeholderText}
                rows={12}
                className="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none text-sm"
            />

            <div className="text-sm text-gray-500 dark:text-gray-400">
                {jd.length} {charactersText}
            </div>

            {!jd && (
                <button
                    onClick={() => handleChange(exampleJD)}
                    className="w-full px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors flex items-center justify-center gap-2 text-sm text-gray-700 dark:text-gray-300"
                >
                    <FileCheck className="w-4 h-4" />
                    {loadExampleText}
                </button>
            )}
        </div>
    );
}
