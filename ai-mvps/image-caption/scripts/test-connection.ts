
import fs from 'fs';
import path from 'path';
import { createClient } from '@supabase/supabase-js';
import { MongoClient } from 'mongodb';

// Load Env from .env.local
function loadEnv() {
    // Try current dir .env.local
    let envPath = path.resolve(process.cwd(), '.env.local');
    if (!fs.existsSync(envPath)) {
        // Try parent dir .env.local (shared)
        envPath = path.resolve(process.cwd(), '../.env.local');
    }

    if (fs.existsSync(envPath)) {
        console.log(`[INFO] Loading env from: ${envPath}`);
        const content = fs.readFileSync(envPath, 'utf-8');
        content.split('\n').forEach(line => {
            const match = line.match(/^([^=]+)=(.*)$/);
            if (match) {
                const key = match[1].trim();
                const value = match[2].trim().replace(/^["']|["']$/g, '');
                if (!process.env[key]) process.env[key] = value;
            }
        });
    } else {
        console.warn('[WARN] No .env.local found. Testing with existing env vars or failing.');
    }
}

async function testConnection() {
    loadEnv();
    console.log('\n🔍 Testing Image Caption Database Dependencies...\n');

    // 1. MongoDB Test
    const mongoUri = process.env.MONGODB_URI;
    if (mongoUri) {
        console.log('👉 Connecting to MongoDB...');
        try {
            const client = new MongoClient(mongoUri);
            await client.connect();
            await client.db().command({ ping: 1 });
            console.log('✅ MongoDB: Connection Successful!');
            await client.close();
        } catch (e: any) {
            console.error('❌ MongoDB Error:', e.message);
        }
    } else {
        console.log('⚠️ MongoDB: MONGODB_URI not set (Skipping connection test)');
        console.log('   (Dependencies check: mongodb package is importable ✅)');
    }

    // 2. Supabase Test
    const sbUrl = process.env.NEXT_PUBLIC_SUPABASE_URL;
    const sbKey = process.env.NEXT_PUBLIC_SUPABASE_ANON_KEY;

    if (sbUrl && sbKey) {
        console.log('\n👉 Connecting to Supabase...');
        try {
            const sb = createClient(sbUrl, sbKey);
            const { error } = await sb.from('random_table_check').select('count');
            // If we get a response (even 404/PGRST204), we connected.
            if (error && error.code !== 'PGRST204' && !error.message.includes('fetch failed')) {
                // Connection likely worked but table missing
                console.log('✅ Supabase: Connection Successful (Service Reachable)');
            } else if (!error || error.code === 'PGRST204') {
                console.log('✅ Supabase: Connection Successful');
            } else {
                console.error('❌ Supabase Error:', error.message);
            }
        } catch (e: any) {
            console.error('❌ Supabase Exception:', e.message);
        }
    } else {
        console.log('\n⚠️ Supabase: Credentials not set (Skipping connection test)');
        console.log('   (Dependencies check: @supabase/supabase-js package is importable ✅)');
    }
}

testConnection().catch(console.error);
