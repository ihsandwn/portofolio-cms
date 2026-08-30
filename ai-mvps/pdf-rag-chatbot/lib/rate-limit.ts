export class RateLimiter {
  private requests = new Map<string, number[]>();
  private windowMs: number;
  private maxRequests: number;
  private nowFn: () => number;

  constructor(maxRequests: number, windowMs: number, nowFn: () => number = () => Date.now()) {
    this.maxRequests = maxRequests;
    this.windowMs = windowMs;
    this.nowFn = nowFn;
  }

  check(key: string, now?: number): { allowed: boolean; resetAt: number } {
    const timestamp = now ?? this.nowFn();
    const requests = this.requests.get(key) || [];
    const resetAt = timestamp - this.windowMs;

    const filtered = requests.filter(ts => ts > resetAt);
    filtered.push(timestamp);
    this.requests.set(key, filtered);

    return { allowed: filtered.length <= this.maxRequests, resetAt };
  }
}