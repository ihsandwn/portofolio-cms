// Shared MongoDB Client with Environment-Based Switching
// Stateless in development, persistent in production

import { MongoClient, Db, Collection } from 'mongodb';

const IS_PRODUCTION = process.env.NODE_ENV === 'production';
const MONGODB_URI = process.env.MONGODB_URI;
const MONGODB_DB_NAME = process.env.MONGODB_DB_NAME || 'ai_products';

let mongoClient: MongoClient | null = null;
let mongoDb: Db | null = null;

/**
 * Get MongoDB client (production only)
 * Returns null in development to keep it stateless
 */
export async function getMongoClient(): Promise<MongoClient | null> {
    if (!IS_PRODUCTION) {
        console.log('[MONGODB] Running in development mode - database disabled (stateless)');
        return null;
    }

    if (!MONGODB_URI) {
        console.error('[MONGODB] Missing MONGODB_URI environment variable');
        return null;
    }

    if (!mongoClient) {
        try {
            mongoClient = new MongoClient(MONGODB_URI);
            await mongoClient.connect();
            console.log('[MONGODB] Client connected');
        } catch (error) {
            console.error('[MONGODB] Connection error:', error);
            return null;
        }
    }

    return mongoClient;
}

/**
 * Get MongoDB database instance
 */
export async function getMongoDb(): Promise<Db | null> {
    if (!IS_PRODUCTION) {
        return null;
    }

    if (!mongoDb) {
        const client = await getMongoClient();
        if (client) {
            mongoDb = client.db(MONGODB_DB_NAME);
        }
    }

    return mongoDb;
}

/**
 * Get MongoDB collection
 */
export async function getMongoCollection<T = unknown>(
    collectionName: string
): Promise<Collection<T> | null> {
    const db = await getMongoDb();

    if (!db) {
        return null;
    }

    return db.collection<T>(collectionName);
}

/**
 * Check if database is available
 */
export function isDatabaseEnabled(): boolean {
    return IS_PRODUCTION && !!MONGODB_URI;
}

/**
 * Save document to MongoDB (production only)
 */
export async function saveToMongo<T>(
    collectionName: string,
    document: T
): Promise<{ success: boolean; insertedId?: string; error?: string }> {
    if (!IS_PRODUCTION) {
        return { success: true }; // Silently skip in development
    }

    try {
        const collection = await getMongoCollection<T>(collectionName);

        if (!collection) {
            return { success: false, error: 'Database not available' };
        }

        const result = await collection.insertOne(document as any);

        return {
            success: true,
            insertedId: result.insertedId.toString(),
        };
    } catch (error) {
        console.error(`[MONGODB] Error saving to ${collectionName}:`, error);
        return { success: false, error: String(error) };
    }
}

/**
 * Query documents from MongoDB (production only)
 */
export async function queryFromMongo<T>(
    collectionName: string,
    filter: Record<string, unknown> = {}
): Promise<{ data: T[] | null; error?: string }> {
    if (!IS_PRODUCTION) {
        return { data: [] }; // Return empty in development
    }

    try {
        const collection = await getMongoCollection<T>(collectionName);

        if (!collection) {
            return { data: null, error: 'Database not available' };
        }

        const data = await collection.find(filter as any).toArray();

        return { data: data as T[] };
    } catch (error) {
        console.error(`[MONGODB] Error querying ${collectionName}:`, error);
        return { data: null, error: String(error) };
    }
}

/**
 * Close MongoDB connection (call on app shutdown)
 */
export async function closeMongoConnection(): Promise<void> {
    if (mongoClient) {
        await mongoClient.close();
        mongoClient = null;
        mongoDb = null;
        console.log('[MONGODB] Connection closed');
    }
}

export default getMongoClient;
