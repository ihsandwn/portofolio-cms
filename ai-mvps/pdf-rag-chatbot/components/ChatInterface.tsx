'use client';

import { useState, useRef, useEffect } from 'react';
import { Send, Bot, User, Loader2 } from 'lucide-react';

interface Message {
    role: 'user' | 'assistant';
    content: string;
}

interface ChatInterfaceProps {
    documentId: string;
    language?: 'en' | 'id';
}

export default function ChatInterface({ documentId, language = 'en' }: ChatInterfaceProps) {
    const [messages, setMessages] = useState<Message[]>([]);
    const [input, setInput] = useState('');
    const [loading, setLoading] = useState(false);
    const messagesEndRef = useRef<HTMLDivElement>(null);

    const scrollToBottom = () => {
        messagesEndRef.current?.scrollIntoView({ behavior: 'smooth' });
    };

    useEffect(() => {
        scrollToBottom();
    }, [messages]);

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        if (!input.trim() || loading) return;

        const userMessage: Message = { role: 'user', content: input };
        setMessages((prev) => [...prev, userMessage]);
        setInput('');
        setLoading(true);

        try {
            const response = await fetch('/api/chat', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    documentId,
                    question: input,
                    history: messages,
                    language,
                }),
            });

            if (!response.ok) {
                throw new Error('Failed to get response');
            }

            const reader = response.body?.getReader();
            const decoder = new TextDecoder();
            let assistantMessage = '';

            if (reader) {
                while (true) {
                    const { done, value } = await reader.read();
                    if (done) break;

                    const chunk = decoder.decode(value);
                    assistantMessage += chunk;

                    // Update the last message with streaming content
                    setMessages((prev) => {
                        const newMessages = [...prev];
                        const lastMsg = newMessages[newMessages.length - 1];
                        if (lastMsg?.role === 'assistant') {
                            lastMsg.content = assistantMessage;
                        } else {
                            newMessages.push({ role: 'assistant', content: assistantMessage });
                        }
                        return newMessages;
                    });
                }
            }
        } catch (error) {
            console.error('Chat error:', error);
            setMessages((prev) => [
                ...prev,
                {
                    role: 'assistant',
                    content: 'Sorry, I encountered an error. Please try again.',
                },
            ]);
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="flex flex-col h-[500px]">
            {/* Messages */}
            <div className="flex-1 overflow-y-auto space-y-4 mb-4 pr-2">
                {messages.length === 0 ? (
                    <div className="text-center text-gray-500 dark:text-gray-400 mt-8">
                        <Bot className="w-12 h-12 mx-auto mb-2 opacity-50" />
                        <p>Ask me anything about the document!</p>
                    </div>
                ) : (
                    messages.map((message, index) => (
                        <div
                            key={index}
                            className={`flex gap-3 ${message.role === 'user' ? 'justify-end' : 'justify-start'
                                }`}
                        >
                            {message.role === 'assistant' && (
                                <div className="flex-shrink-0 w-8 h-8 rounded-full bg-gradient-to-br from-blue-900 to-blue-600 flex items-center justify-center">
                                    <Bot className="w-5 h-5 text-white" />
                                </div>
                            )}

                            <div
                                className={`max-w-[80%] rounded-2xl px-4 py-3 ${message.role === 'user'
                                    ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white'
                                    : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200'
                                    }`}
                            >
                                <p className="whitespace-pre-wrap text-sm">{message.content}</p>
                            </div>

                            {message.role === 'user' && (
                                <div className="flex-shrink-0 w-8 h-8 rounded-full bg-gradient-to-br from-gray-600 to-gray-700 flex items-center justify-center">
                                    <User className="w-5 h-5 text-white" />
                                </div>
                            )}
                        </div>
                    ))
                )}
                {loading && messages[messages.length - 1]?.role !== 'assistant' && (
                    <div className="flex gap-3 justify-start">
                        <div className="flex-shrink-0 w-8 h-8 rounded-full bg-gradient-to-br from-blue-900 to-blue-600 flex items-center justify-center">
                            <Bot className="w-5 h-5 text-white" />
                        </div>
                        <div className="bg-gray-100 dark:bg-gray-700 rounded-2xl px-4 py-3">
                            <Loader2 className="w-5 h-5 text-gray-500 animate-spin" />
                        </div>
                    </div>
                )}
                <div ref={messagesEndRef} />
            </div>

            {/* Input Form */}
            <form onSubmit={handleSubmit} className="flex gap-2">
                <input
                    type="text"
                    value={input}
                    onChange={(e) => setInput(e.target.value)}
                    placeholder="Ask a question about the document..."
                    disabled={loading}
                    className="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50"
                />
                <button
                    type="submit"
                    disabled={!input.trim() || loading}
                    className="px-6 py-3 bg-gradient-to-r from-blue-900 to-blue-500 text-white rounded-xl hover:from-blue-800 hover:to-blue-400 disabled:opacity-50 disabled:cursor-not-allowed transition-all flex items-center gap-2 font-medium shadow-lg"
                >
                    {loading ? (
                        <Loader2 className="w-5 h-5 animate-spin" />
                    ) : (
                        <><Send className="w-5 h-5" /> Send</>
                    )}
                </button>
            </form>
        </div>
    );
}
