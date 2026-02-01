// Gemini AI Client
import { GoogleGenerativeAI } from '@google/generative-ai';

const apiKey = process.env.GEMINI_API_KEY;
if (!apiKey) {
    throw new Error('GEMINI_API_KEY is not set');
}

const genAI = new GoogleGenerativeAI(apiKey);

export const model = genAI.getGenerativeModel({
    model: process.env.GEMINI_MODEL || 'gemini-2.5-flash',
});

export async function chatWithDocument(
    documentText: string,
    question: string,
    history: Array<{ role: string; content: string }> = []
): Promise<string> {
    const systemPrompt = `You are a helpful AI assistant that answers questions about the provided document. 
Here is the document content:

${documentText}

Please answer questions based ONLY on the information in this document. If the answer is not in the document, say so.`;

    const chat = model.startChat({
        history: [
            {
                role: 'user',
                parts: [{ text: systemPrompt }],
            },
            {
                role: 'model',
                parts: [{ text: 'I understand. I will answer questions based only on the provided document.' }],
            },
            ...history.map((msg) => ({
                role: msg.role === 'user' ? 'user' : 'model',
                parts: [{ text: msg.content }],
            })),
        ],
    });

    const result = await chat.sendMessage(question);
    return result.response.text();
}

export async function streamChatWithDocument(
    documentText: string,
    question: string,
    history: Array<{ role: string; content: string }> = [],
    language: 'en' | 'id' = 'en'
) {
    const languageInstruction = language === 'id'
        ? '\n\nPENTING: Jawab dalam Bahasa Indonesia yang natural dan profesional.'
        : '';

    const systemPrompt = `You are a helpful AI assistant that answers questions about the provided document.${languageInstruction}
Here is the document content:

${documentText}

Please answer questions based ONLY on the information in this document. If the answer is not in the document, say so.`;

    const chat = model.startChat({
        history: [
            {
                role: 'user',
                parts: [{ text: systemPrompt }],
            },
            {
                role: 'model',
                parts: [{
                    text: language === 'id'
                        ? 'Saya mengerti. Saya akan menjawab pertanyaan berdasarkan dokumen yang diberikan saja.'
                        : 'I understand. I will answer questions based only on the provided document.'
                }],
            },
            ...history.map((msg) => ({
                role: msg.role === 'user' ? 'user' : 'model',
                parts: [{ text: msg.content }],
            })),
        ],
    });

    const result = await chat.sendMessageStream(question);
    return result.stream;
}
