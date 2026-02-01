// Simple in-memory storage for MVP
// In production, this would use Supabase

interface Document {
    id: string;
    filename: string;
    text: string;
    uploadedAt: string;
}

const documents = new Map<string, Document>();

export function saveDocument(id: string, filename: string, text: string): Document {
    const doc: Document = {
        id,
        filename,
        text,
        uploadedAt: new Date().toISOString(),
    };
    documents.set(id, doc);
    return doc;
}

export function getDocument(id: string): Document | undefined {
    return documents.get(id);
}

export function deleteDocument(id: string): boolean {
    return documents.delete(id);
}

export function listDocuments(): Document[] {
    return Array.from(documents.values());
}
