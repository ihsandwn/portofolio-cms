type Clock = () => number;

type RateLimitOptions = {
    limit: number;
    windowMs: number;
    now?: Clock;
};

type RateLimitResult = {
    allowed: boolean;
    retryAfterSeconds: number;
};

type Entry = {
    count: number;
    resetAt: number;
};

export function createRateLimiter({ limit, windowMs, now = Date.now }: RateLimitOptions) {
    const entries = new Map<string, Entry>();

    return {
        consume(key: string): RateLimitResult {
            const timestamp = now();
            const existing = entries.get(key);
            const entry = !existing || existing.resetAt <= timestamp
                ? { count: 0, resetAt: timestamp + windowMs }
                : existing;

            entry.count += 1;
            entries.set(key, entry);

            return {
                allowed: entry.count <= limit,
                retryAfterSeconds: Math.max(1, Math.ceil((entry.resetAt - timestamp) / 1000)),
            };
        },
    };
}

export const analyzeRateLimiter = createRateLimiter({
    limit: 10,
    windowMs: 10 * 60 * 1000,
});
