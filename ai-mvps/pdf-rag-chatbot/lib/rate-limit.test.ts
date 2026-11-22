import { describe, expect, it } from 'vitest';
import { RateLimiter } from '../lib/rate-limit';

describe('RateLimiter sliding window', () => {
  it('allows up to the limit then denies within the window', () => {
    const limiter = new RateLimiter(3, 60_000, () => 1_000);
    expect(limiter.check('ip:/api/upload').allowed).toBe(true);
    expect(limiter.check('ip:/api/upload').allowed).toBe(true);
    expect(limiter.check('ip:/api/upload').allowed).toBe(true);
    expect(limiter.check('ip:/api/upload').allowed).toBe(false);
  });

  it('resets after the window passes and isolates keys', () => {
    let now = 0;
    const limiter = new RateLimiter(1, 1_000, () => now);
    expect(limiter.check('a').allowed).toBe(true);
    expect(limiter.check('b').allowed).toBe(true);
    expect(limiter.check('a').allowed).toBe(false);
    now = 2_000;
    expect(limiter.check('a').allowed).toBe(true);
  });
});
