import { Pool } from 'pg';
import type { Query } from './neon-storage';

// One pool per server process (survives dev HMR). Neon's pooled DATABASE_URL already
// carries sslmode=require, so no extra ssl config.
const scope = globalThis as typeof globalThis & { __pdfRagPool?: Pool };

export const query: Query = (sql, values) => {
  const url = process.env.DATABASE_URL;
  if (!url) return Promise.reject(new Error('DATABASE_URL is not set'));
  scope.__pdfRagPool ??= new Pool({ connectionString: url, max: 3, idleTimeoutMillis: 10_000, connectionTimeoutMillis: 10_000 });
  return scope.__pdfRagPool.query(sql, values as unknown[] | undefined);
};
