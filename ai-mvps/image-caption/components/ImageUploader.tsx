'use client';

import { useState, useCallback, useEffect } from 'react';
import { useDropzone } from 'react-dropzone';
import { Upload, CheckCircle, AlertCircle, Loader2 } from 'lucide-react';
import type { CaptionResponse, Language } from '@/lib/schemas';

interface ImageUploaderProps {
  onCaptionGenerated: (data: CaptionResponse, imageUrl: string) => void;
  loading: boolean;
  setLoading: (loading: boolean) => void;
  language: Language;
}

export default function ImageUploader({ onCaptionGenerated, loading, setLoading, language }: ImageUploaderProps) {
  const [error, setError] = useState<string | null>(null);
  const [preview, setPreview] = useState<string | null>(null);

  useEffect(() => () => { if (preview?.startsWith('blob:')) URL.revokeObjectURL(preview); }, [preview]);

  const copy = language === 'en'
    ? { label: 'Upload image', analyzing: 'Analyzing image...', success: 'Image analyzed successfully', drop: 'Drop image here', browse: 'Drag and drop an image, or browse', formats: 'JPG, PNG, WebP, or GIF. Maximum 10MB.', error: 'Unable to process image. Try another file.' }
    : { label: 'Unggah gambar', analyzing: 'Menganalisis gambar...', success: 'Gambar berhasil dianalisis', drop: 'Letakkan gambar di sini', browse: 'Seret dan lepas gambar, atau pilih file', formats: 'JPG, PNG, WebP, atau GIF. Maksimal 10MB.', error: 'Gambar tidak dapat diproses. Coba file lain.' };

  const onDrop = useCallback(async (files: File[]) => {
    const file = files[0];
    if (!file) return;
    setError(null);
    const nextPreview = URL.createObjectURL(file);
    setPreview(nextPreview);
    setLoading(true);
    try {
      const formData = new FormData();
      formData.append('file', file);
      formData.append('language', language);
      const response = await fetch('/api/caption', { method: 'POST', body: formData });
      const data: unknown = await response.json();
      if (!response.ok) throw new Error('Unable to process image');
      onCaptionGenerated(data as CaptionResponse, nextPreview);
    } catch {
      setError(copy.error);
      setPreview(null);
    } finally { setLoading(false); }
  }, [language, onCaptionGenerated, setLoading]);

  const { getRootProps, getInputProps, isDragActive } = useDropzone({
    onDrop, accept: { 'image/jpeg': ['.jpg', '.jpeg'], 'image/png': ['.png'], 'image/webp': ['.webp'], 'image/gif': ['.gif'] },
    multiple: false, maxSize: 10 * 1024 * 1024, disabled: loading,
  });

  return <div className="space-y-4">
    <div {...getRootProps()} role="button" tabIndex={0} aria-label={copy.label} aria-busy={loading} className={`border-2 border-dashed rounded-xl p-8 text-center cursor-pointer transition-all ${isDragActive ? 'border-blue-500 bg-blue-50' : 'border-gray-300 hover:border-blue-500'} ${loading ? 'opacity-50 cursor-not-allowed' : ''}`}>
      <input {...getInputProps()} aria-label={copy.label} />
      <div className="flex flex-col items-center gap-3" aria-live="polite">
        {loading ? <Loader2 className="w-12 h-12 text-blue-500 animate-spin" aria-hidden="true" /> : preview ? <CheckCircle className="w-12 h-12 text-green-500" aria-hidden="true" /> : <Upload className="w-12 h-12 text-gray-400" aria-hidden="true" />}
        <p className="text-gray-700">{loading ? copy.analyzing : preview ? copy.success : isDragActive ? copy.drop : copy.browse}</p>
        {!loading && <p className="text-sm text-gray-500">{copy.formats}</p>}
      </div>
    </div>
    {preview && <img src={preview} alt={language === 'en' ? 'Uploaded image preview' : 'Pratinjau gambar yang diunggah'} className="w-full h-64 object-cover rounded-xl border" />}
    {error && <div role="alert" className="flex items-center gap-2 p-4 bg-red-50 border border-red-200 rounded-lg"><AlertCircle className="w-5 h-5 text-red-500" aria-hidden="true" /><p className="text-sm text-red-700">{error}</p></div>}
  </div>;
}
