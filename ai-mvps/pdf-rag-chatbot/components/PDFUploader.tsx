'use client';

import { useState, useCallback } from 'react';
import { useDropzone } from 'react-dropzone';
import { Upload, File, CheckCircle, AlertCircle, Loader2 } from 'lucide-react';

interface PDFUploaderProps {
    onUploadSuccess: (documentId: string, filename: string, text: string) => void;
}

export default function PDFUploader({ onUploadSuccess }: PDFUploaderProps) {
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

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.error || 'Upload failed');
            }

            onUploadSuccess(data.document.id, data.document.filename, data.document.text);
        } catch (err) {
            setError(err instanceof Error ? err.message : 'Upload failed');
            setFile(null);
        } finally {
            setUploading(false);
        }
    }, [onUploadSuccess]);

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
                <input {...getInputProps()} disabled={uploading} />

                <div className="flex flex-col items-center gap-3">
                    {uploading ? (
                        <Loader2 className="w-12 h-12 text-blue-500 animate-spin" />
                    ) : file ? (
                        <CheckCircle className="w-12 h-12 text-green-500" />
                    ) : (
                        <Upload className="w-12 h-12 text-gray-400" />
                    )}

                    <div>
                        {uploading ? (
                            <p className="text-gray-600 dark:text-gray-400">Processing PDF...</p>
                        ) : file ? (
                            <>
                                <p className="text-green-600 dark:text-green-400 font-medium">
                                    File uploaded successfully!
                                </p>
                                <p className="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    {file.name}
                                </p>
                            </>
                        ) : isDragActive ? (
                            <p className="text-blue-600 dark:text-blue-400">Drop PDF here...</p>
                        ) : (
                            <>
                                <p className="text-gray-700 dark:text-gray-300 font-medium">
                                    Drag & drop a PDF file here
                                </p>
                                <p className="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    or click to browse (max 10MB)
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
