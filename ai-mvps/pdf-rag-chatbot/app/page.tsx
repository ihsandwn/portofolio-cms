'use client';

import { useState } from 'react';
import { Upload, MessageSquare, FileText, Sparkles } from 'lucide-react';
import PDFUploader from '@/components/PDFUploader';
import ChatInterface from '@/components/ChatInterface';
import LanguageToggle from '@/components/LanguageToggle';

export type Language = 'en' | 'id';

export default function Home() {
  const [documentId, setDocumentId] = useState<string | null>(null);
  const [language, setLanguage] = useState<Language>('en');
  const [documentName, setDocumentName] = useState<string>('');

  const handleUploadSuccess = (id: string, name: string) => {
    setDocumentId(id);
    setDocumentName(name);
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
                PDF RAG Chatbot
              </h1>
              <p className="text-gray-600 dark:text-gray-400 text-lg">
                {language === 'en' ? 'Upload a PDF document and chat with it using AI' : 'Unggah dokumen PDF dan ngobrol dengan AI'}
              </p>
            </div>
            <div className="flex-1 flex justify-end">
              <LanguageToggle onLanguageChange={setLanguage} />
            </div>
          </div>
        </header>

        {/* Main Content */}
        <div className="grid lg:grid-cols-2 gap-6">
          {/* Upload Section */}
          <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 border border-gray-200 dark:border-gray-700">
            <div className="flex items-center gap-2 mb-4">
              <Upload className="w-5 h-5 text-blue-500" />
              <h2 className="text-xl font-semibold text-gray-800 dark:text-gray-200">
                {language === 'en' ? 'Upload Document' : 'Unggah Dokumen'}
              </h2>
            </div>
            <PDFUploader onUploadSuccess={handleUploadSuccess} />

            {documentId && (
              <div className="mt-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                <div className="flex items-center gap-2 text-green-700 dark:text-green-400">
                  <FileText className="w-4 h-4" />
                  <span className="text-sm font-medium">
                    {language === 'en' ? 'Document ready' : 'Dokumen siap'}: <strong>{documentName}</strong>
                  </span>
                </div>
              </div>
            )}
          </div>

          {/* Chat Section */}
          <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 border border-gray-200 dark:border-gray-700">
            <div className="flex items-center gap-2 mb-4">
              <MessageSquare className="w-5 h-5 text-blue-900" />
              <h2 className="text-xl font-semibold text-gray-800 dark:text-gray-200">
                {language === 'en' ? 'Chat with Document' : 'Ngobrol dengan Dokumen'}
              </h2>
            </div>
            {documentId ? (
              <ChatInterface documentId={documentId} language={language} />
            ) : (
              <div className="flex flex-col items-center justify-center h-64 text-center">
                <FileText className="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" />
                <p className="text-gray-500 dark:text-gray-400">
                  {language === 'en' ? 'Upload a PDF document to start chatting' : 'Unggah dokumen PDF untuk mulai ngobrol'}
                </p>
              </div>
            )}
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
