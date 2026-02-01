// ================================================================================
// Common TypeScript Types
// ================================================================================

// ============ User Types ============
export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at?: string | null;
    created_at: string;
    updated_at: string;
}

// ============ API Response Types ============
export interface ApiResponse<T = any> {
    data?: T;
    message?: string;
    status: 'success' | 'error';
    timestamp: string;
}

export interface PaginationMeta {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number | null;
    to: number | null;
}

export interface PaginatedResponse<T> extends ApiResponse<T[]> {
    meta: PaginationMeta;
}

// ============ Error Types ============
export interface ApiError {
    message: string;
    code: string;
    status: number;
    errors?: Record<string, string[]>;
}

// ============ File Upload Types ============
export interface UploadedFile {
    id: string;
    filename: string;
    originalName: string;
    size: number;
    mimeType: string;
    uploadedAt: string;
    userId?: number | null;
}

// ============ Gemini AI Types ============
export interface GeminiConfig {
    apiKey: string;
    model: string;
    temperature?: number;
    maxTokens?: number;
    topP?: number;
    topK?: number;
}

export interface GeminiMessage {
    role: 'user' | 'model';
    content: string;
}

export interface GeminiResponse {
    text: string;
    tokenCount?: number;
    finishReason?: string;
}

// ============ Embedding Types ============
export interface Embedding {
    id: string;
    documentId: string;
    chunkText: string;
    chunkIndex: number;
    embedding: number[];
    createdAt: string;
}

export interface SimilaritySearchResult {
    id: string;
    chunkText: string;
    similarity: number;
    metadata?: Record<string, any>;
}

// ============ Document Types ============
export interface Document {
    id: string;
    userId?: number | null;
    filename: string;
    fileSize: number;
    uploadedAt: string;
    processed: boolean;
    metadata?: Record<string, any>;
}

// ============ Chat Types ============
export interface ChatMessage {
    id: string;
    conversationId: string;
    role: 'user' | 'assistant' | 'system';
    content: string;
    createdAt: string;
    metadata?: Record<string, any>;
}

export interface Conversation {
    id: string;
    documentId?: string | null;
    userId?: number | null;
    title?: string;
    createdAt: string;
    updatedAt: string;
    messages?: ChatMessage[];
}

// ============ Sentiment Analysis Types ============
export interface SentimentAnalysis {
    id: string;
    userId?: number | null;
    inputText: string;
    language: string;
    overallSentiment: 'positive' | 'negative' | 'neutral' | 'mixed';
    confidence: number;
    aspects?: SentimentAspect[];
    emotions?: EmotionScores;
    createdAt: string;
    processingTimeMs: number;
}

export interface SentimentAspect {
    aspect: string;
    sentiment: 'positive' | 'negative' | 'neutral';
    confidence: number;
}

export interface EmotionScores {
    joy: number;
    anger: number;
    sadness: number;
    fear: number;
    surprise: number;
}

// ============ HR Screening Types ============
export interface ResumeData {
    id: string;
    jobId: string;
    filename: string;
    parsedData: {
        personalInfo?: {
            name?: string;
            email?: string;
            phone?: string;
            location?: string;
        };
        summary?: string;
        skills: string[];
        experience: WorkExperience[];
        education: Education[];
    };
    matchScore: number;
    createdAt: string;
}

export interface WorkExperience {
    company: string;
    position: string;
    startDate: string;
    endDate?: string | null;
    description?: string;
    skills?: string[];
}

export interface Education {
    institution: string;
    degree: string;
    field?: string;
    startDate: string;
    endDate?: string | null;
    gpa?: number;
}

export interface JobDescription {
    id: string;
    userId?: number | null;
    title: string;
    description: string;
    requiredSkills: string[];
    createdAt: string;
}

// ============ Rate Limiting Types ============
export interface RateLimitInfo {
    limit: number;
    remaining: number;
    reset: number;
    retryAfter?: number;
}

// ============ Environment Configuration Types ============
export interface AppConfig {
    apiUrl: string;
    geminiApiKey: string;
    geminiModel: string;
    databaseUrl: string;
    mongodbUri?: string;
    maxFileSize: number;
    enablePublicDemo: boolean;
    enableCache: boolean;
    cacheTTL: number;
    rateLimitMax: number;
}
