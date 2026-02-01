// Shared Supabase Client with Environment-Based Switching
// Stateless in development, persistent in production

import { createClient, SupabaseClient } from '@supabase/supabase-js';

const IS_PRODUCTION = process.env.NODE_ENV === 'production';
const SUPABASE_URL = process.env.NEXT_PUBLIC_SUPABASE_URL;
const SUPABASE_ANON_KEY = process.env.NEXT_PUBLIC_SUPABASE_ANON_KEY;

let supabaseClient: SupabaseClient | null = null;

/**
 * Get Supabase client (production only)
 * Returns null in development to keep it stateless
 */
export function getSupabaseClient(): SupabaseClient | null {
    if (!IS_PRODUCTION) {
        console.log('[SUPABASE] Running in development mode - database disabled (stateless)');
        return null;
    }

    if (!SUPABASE_URL || !SUPABASE_ANON_KEY) {
        console.error('[SUPABASE] Missing environment variables');
        return null;
    }

    if (!supabaseClient) {
        supabaseClient = createClient(SUPABASE_URL, SUPABASE_ANON_KEY, {
            auth: {
                persistSession: true,
                autoRefreshToken: true,
            },
        });
        console.log('[SUPABASE] Client initialized');
    }

    return supabaseClient;
}

/**
 * Check if database is available
 */
export function isDatabaseEnabled(): boolean {
    return IS_PRODUCTION && !!SUPABASE_URL && !!SUPABASE_ANON_KEY;
}

/**
 * Save data to Supabase (production only)
 */
export async function saveToSupabase<T>(
    table: string,
    data: T
): Promise<{ success: boolean; error?: string }> {
    const client = getSupabaseClient();

    if (!client) {
        return { success: true }; // Silently skip in development
    }

    try {
        const { error } = await client.from(table).insert(data);

        if (error) {
            console.error(`[SUPABASE] Error saving to ${table}:`, error);
            return { success: false, error: error.message };
        }

        return { success: true };
    } catch (err) {
        console.error(`[SUPABASE] Exception saving to ${table}:`, err);
        return { success: false, error: String(err) };
    }
}

/**
 * Query data from Supabase (production only)
 */
export async function queryFromSupabase<T>(
    table: string,
    filters?: Record<string, unknown>
): Promise<{ data: T[] | null; error?: string }> {
    const client = getSupabaseClient();

    if (!client) {
        return { data: [] }; // Return empty in development
    }

    try {
        let query = client.from(table).select('*');

        if (filters) {
            Object.entries(filters).forEach(([key, value]) => {
                query = query.eq(key, value);
            });
        }

        const { data, error } = await query;

        if (error) {
            console.error(`[SUPABASE] Error querying ${table}:`, error);
            return { data: null, error: error.message };
        }

        return { data: data as T[] };
    } catch (err) {
        console.error(`[SUPABASE] Exception querying ${table}:`, err);
        return { data: null, error: String(err) };
    }
}

export default getSupabaseClient;
