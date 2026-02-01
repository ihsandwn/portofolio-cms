// Database Abstraction Layer
// Provides unified interface for both Supabase and MongoDB

export { getSupabaseClient, isDatabaseEnabled as isSupabaseEnabled, saveToSupabase, queryFromSupabase } from './supabase';
export { getMongoClient, getMongoDb, getMongoCollection, isDatabaseEnabled as isMongoEnabled, saveToMongo, queryFromMongo, closeMongoConnection } from './mongodb';

/**
 * Check if any database is enabled
 */
export function isDatabaseAvailable(): boolean {
    return process.env.NODE_ENV === 'production';
}

/**
 * Get environment mode
 */
export function getEnvironmentMode(): 'development' | 'production' {
    return process.env.NODE_ENV === 'production' ? 'production' : 'development';
}

/**
 * Log database status
 */
export function logDatabaseStatus(): void {
    const mode = getEnvironmentMode();
    console.log(`[DATABASE] Running in ${mode} mode`);

    if (mode === 'development') {
        console.log('[DATABASE] Stateless mode - no persistent storage');
    } else {
        console.log('[DATABASE] Production mode - persistent storage enabled');
    }
}
