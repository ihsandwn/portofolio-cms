'use client';

import { useState, useCallback } from 'react';
import { useDropzone } from 'react-dropzone';
import { Upload, File, CheckCircle, AlertCircle, Loader2 } from 'lucide-react';

interface PDFUploaderProps {
    onUploadSuccess: (documentId: string, filename: string) => void;
    language?: 'en' | 'id';
}

export default function PDFUploader({ onUploadSuccess, language = 'en' }: PDFUploaderProps) {
    const [uploading, setUploading] = useState(false);
    const [error, setError] = useState<string | null>(null);
    const [file, setFile] = useState<File | null>(null);

    const onDrop = useCallback(async (acceptedFiles: File[]) => {
        const pdfFile = acceptedFiles[0];
        if (!pdfFile) return;

        setFile(pdfFile);
        setError(null);
        setUploading(true);

        try {
            const formData = new FormData();
            formData.append('file', pdfFile);

            const response = await fetch('/api/upload', {
                method: 'POST',
                body: formData,
            });

            // Status-specific guidance; the server body stays generic by design
            // (no infrastructure details leak to the browser).
            if (response.status === 401) {
                throw new Error(language === 'en'
                    ? 'Session expired. Please request access again from the AI Lab page.'
                    : 'Sesi kedaluwarsa. Silakan minta akses lagi dari halaman AI Lab.');
            }
            if (response.status === 429) {
                throw new Error(language === 'en'
                    ? 'Too many uploads. Please wait a few minutes and try again.'
                    : 'Terlalu banyak unggahan. Tunggu beberapa menit lalu coba lagi.');
            }
            if (response.status === 413) {
                throw new Error(language === 'en'
                    ? 'File is too large. Maximum size is 10MB.'
                    : 'File terlalu besar. Ukuran maksimum 10MB.');
            }
            if (!response.ok) {
                throw new Error(language === 'en' ? 'Upload failed. Please try again.' : 'Unggah gagal. Silakan coba lagi.');
            }

            const data: unknown = await response.json().catch(() => null);
            const document = (data as { document?: { id?: unknown; filename?: unknown } } | null)?.document;
            if (typeof document?.id !== 'string' || typeof document?.filename !== 'string') {
                throw new Error(language === 'en' ? 'Upload failed. Please try again.' : 'Unggah gagal. Silakan coba lagi.');
            }

            onUploadSuccess(document.id, document.filename);
        } catch (e) {
            setError(e instanceof Error ? e.message : (language === 'en' ? 'Upload failed. Please try again.' : 'Unggah gagal. Silakan coba lagi.'));
            setFile(null);
        } finally {
            setUploading(false);
        }
    }, [language, onUploadSuccess]);

    const { getRootProps, getInputProps, isDragActive } = useDropzone({
        onDrop,
        accept: { 'application/pdf': ['.pdf'] },
        multiple: false,
        maxSize: 10 * 1024 * 1024, // 10MB
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
          ${uploading ? 'opacity-50 cursor-not-allowed' : ''}
        `}
            >
                <input {...getInputProps()} disabled={uploading} aria-label={language === 'en' ? 'Choose PDF document' : 'Pilih dokumen PDF'} />

                <div className="flex flex-col items-center gap-3" aria-live="polite">
                    {uploading ? (
                        <Loader2 aria-hidden="true" className="w-12 h-12 text-blue-500 animate-spin" />
                    ) : file ? (
                        <CheckCircle aria-hidden="true" className="w-12 h-12 text-green-500" />
                    ) : (
                        <Upload aria-hidden="true" className="w-12 h-12 text-gray-400" />
                    )}

                    <div>
                        {uploading ? (
                            <p className="text-gray-600 dark:text-gray-400">{language === 'en' ? 'Processing PDF...' : 'Memproses PDF...'}</p>
                        ) : file ? (
                            <>
                                <p className="text-green-600 dark:text-green-400 font-medium">
                                    {language === 'en' ? 'File uploaded successfully!' : 'File berhasil diunggah!'}
                                </p>
                                <p className="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    {file.name}
                                </p>
                            </>
                        ) : isDragActive ? (
                            <p className="text-blue-600 dark:text-blue-400">{language === 'en' ? 'Drop PDF here...' : 'Letakkan PDF di sini...'}</p>
                        ) : (
                            <>
                                <p className="text-gray-700 dark:text-gray-300 font-medium">
                                    {language === 'en' ? 'Drag & drop a PDF file here' : 'Seret dan lepas file PDF di sini'}
                                </p>
                                <p className="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    {language === 'en' ? 'or click to browse (max 10MB)' : 'atau klik untuk memilih (maks 10MB)'}
                                </p>
                            </>
                        )}
                    </div>
                </div>
            </div>

            {error && (
                <div role="alert" className="flex items-center gap-2 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                    <AlertCircle aria-hidden="true" className="w-5 h-5 text-red-500 flex-shrink-0" />
                    <p className="text-sm text-red-700 dark:text-red-400">{error}</p>
                </div>
            )}
        </div>
    );
}
