'use client';

import { useState, useCallback } from 'react';
import { useDropzone } from 'react-dropzone';
import { Upload, Image as ImageIcon, CheckCircle, AlertCircle, Loader2 } from 'lucide-react';

interface ImageUploaderProps {
    onCaptionGenerated: (data: any, imageUrl: string) => void;
    loading?: boolean;
    setLoading?: (loading: boolean) => void;
    language?: 'en' | 'id';
}

export default function ImageUploader({ onCaptionGenerated, loading, setLoading, language = 'en' }: ImageUploaderProps) {
    const [uploading, setUploading] = useState(false);
    const [error, setError] = useState<string | null>(null);
    const [preview, setPreview] = useState<string | null>(null);

    const onDrop = useCallback(async (acceptedFiles: File[]) => {
        const imageFile = acceptedFiles[0];
        if (!imageFile) return;

        setError(null);
        setUploading(true);

        // Create preview
        const reader = new FileReader();
        reader.onload = (e) => {
            setPreview(e.target?.result as string);
        };
        reader.readAsDataURL(imageFile);

        try {
            const formData = new FormData();
            formData.append('file', imageFile);

            const response = await fetch('/api/caption', {
                method: 'POST',
                body: formData,
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.error || 'Caption generation failed');
            }

            onCaptionGenerated(data, data.imageUrl);
        } catch (err) {
            setError(err instanceof Error ? err.message : 'Upload failed');
            setPreview(null);
        } finally {
            setUploading(false);
        }
    }, [onCaptionGenerated]);

    const { getRootProps, getInputProps, isDragActive } = useDropzone({
        onDrop,
        accept: { 'image/*': ['.jpg', '.jpeg', '.png', '.webp', '.gif'] },
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
          ${uploading ? 'opacity-50 cursor-not-allowed' : ''}
        `}
            >
                <input {...getInputProps()} disabled={uploading} />

                <div className="flex flex-col items-center gap-3">
                    {uploading ? (
                        <Loader2 className="w-12 h-12 text-blue-500 animate-spin" />
                    ) : preview ? (
                        <CheckCircle className="w-12 h-12 text-green-500" />
                    ) : (
                        <Upload className="w-12 h-12 text-gray-400" />
                    )}

                    <div>
                        {uploading ? (
                            <p className="text-gray-600 dark:text-gray-400">Analyzing image...</p>
                        ) : preview ? (
                            <>
                                <p className="text-green-600 dark:text-green-400 font-medium">
                                    Image analyzed successfully!
                                </p>
                                <p className="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    Upload another to analyze again
                                </p>
                            </>
                        ) : isDragActive ? (
                            <p className="text-blue-600 dark:text-blue-400">Drop image here...</p>
                        ) : (
                            <>
                                <p className="text-gray-700 dark:text-gray-300 font-medium">
                                    Drag & drop an image here
                                </p>
                                <p className="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    or click to browse (max 10MB)
                                </p>
                                <p className="text-xs text-gray-400 dark:text-gray-500 mt-2">
                                    Supports: JPG, PNG, WebP, GIF
                                </p>
                            </>
                        )}
                    </div>
                </div>
            </div>

            {preview && (
                <div className="relative rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                    <img
                        src={preview}
                        alt="Preview"
                        className="w-full h-64 object-cover"
                    />
                </div>
            )}

            {error && (
                <div className="flex items-center gap-2 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                    <AlertCircle className="w-5 h-5 text-red-500 flex-shrink-0" />
                    <p className="text-sm text-red-700 dark:text-red-400">{error}</p>
                </div>
            )}
        </div>
    );
}
