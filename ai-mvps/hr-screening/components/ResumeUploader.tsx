'use client';

import { useState, useCallback } from 'react';
import { useDropzone } from 'react-dropzone';
import { Upload, FileText, CheckCircle, AlertCircle, Loader2 } from 'lucide-react';

export type Language = 'en' | 'id';

interface ResumeUploaderProps {
    jobDescription: string;
    onScreeningComplete: (result: any) => void;
    language: Language;
}

export default function ResumeUploader({ jobDescription, onScreeningComplete, language }: ResumeUploaderProps) {
    const [screening, setScreening] = useState(false);
    const [error, setError] = useState<string | null>(null);
    const [fileName, setFileName] = useState<string | null>(null);

    const onDrop = useCallback(async (acceptedFiles: File[]) => {
        const resumeFile = acceptedFiles[0];
        if (!resumeFile) return;

        if (!jobDescription || jobDescription.trim().length === 0) {
            setError(language === 'en' ? 'Please enter a job description first' : 'Harap masukkan deskripsi pekerjaan terlebih dahulu');
            return;
        }

        setError(null);
        setScreening(true);
        setFileName(resumeFile.name);

        try {
            const formData = new FormData();
            formData.append('file', resumeFile);
            formData.append('jobDescription', jobDescription);
            formData.append('language', language);

            const response = await fetch('/api/screen', {
                method: 'POST',
                body: formData,
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.error || (language === 'en' ? 'Screening failed' : 'Screening gagal'));
            }

            onScreeningComplete(data);
        } catch (err) {
            setError(err instanceof Error ? err.message : (language === 'en' ? 'Screening failed' : 'Screening gagal'));
            setFileName(null);
        } finally {
            setScreening(false);
        }
    }, [jobDescription, onScreeningComplete, language]);

    const { getRootProps, getInputProps, isDragActive } = useDropzone({
        onDrop,
        accept: { 'application/pdf': ['.pdf'] },
        multiple: false,
        maxSize: 10 * 1024 * 1024,
    });

    return (
        <div className="space-y-4">
            <div
                {...getRootProps()}
                className={`
          border-2 border-dashed rounded-xl p-8 text-center cursor-pointer transition-all
          ${isDragActive
                        ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20'
                        : 'border-gray-300 dark:border-gray-600 hover:border-blue-500 dark:hover:border-blue-400'
                    }
          ${screening ? 'opacity-50 cursor-not-allowed' : ''}
        `}
            >
                <input {...getInputProps()} disabled={screening} />

                <div className="flex flex-col items-center gap-3">
                    {screening ? (
                        <Loader2 className="w-12 h-12 text-blue-500 animate-spin" />
                    ) : fileName ? (
                        <CheckCircle className="w-12 h-12 text-green-500" />
                    ) : (
                        <Upload className="w-12 h-12 text-gray-400" />
                    )}

                    <div>
                        {screening ? (
                            <p className="text-gray-600 dark:text-gray-400">
                                {language === 'en' ? 'Screening resume...' : 'Menyaring resume...'}
                            </p>
                        ) : fileName ? (
                            <>
                                <p className="text-green-600 dark:text-green-400 font-medium">
                                    {language === 'en' ? 'Screening complete!' : 'Screening selesai!'}
                                </p>
                                <p className="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    {language === 'en' ? 'Upload another to screen again' : 'Unggah lagi untuk menyaring lagi'}
                                </p>
                            </>
                        ) : isDragActive ? (
                            <p className="text-blue-600 dark:text-blue-400">
                                {language === 'en' ? 'Drop resume here...' : 'Letakkan resume di sini...'}
                            </p>
                        ) : (
                            <>
                                <p className="text-gray-700 dark:text-gray-300 font-medium">
                                    {language === 'en' ? 'Drag & drop resume' : 'Seret & lepas resume'}
                                </p>
                                <p className="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    {language === 'en' ? 'or click to browse (max 10MB)' : 'atau klik untuk menjelajah (maks 10MB)'}
                                </p>
                                <p className="text-xs text-gray-400 dark:text-gray-500 mt-2">
                                    {language === 'en' ? 'PDF only' : 'Hanya PDF'}
                                </p>
                            </>
                        )}
                    </div>
                </div>
            </div>

            {error && (
                <div className="flex items-center gap-2 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                    <AlertCircle className="w-5 h-5 text-red-500 flex-shrink-0" />
                    <p className="text-sm text-red-700 dark:text-red-400">{error}</p>
                </div>
            )}
        </div>
    );
}
