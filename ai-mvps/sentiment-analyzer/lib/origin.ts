export function isAllowedOrigin(origin: string | null, requestOrigin: string): boolean {
    if (!origin) return false;

    try {
        return new URL(origin).origin === new URL(requestOrigin).origin;
    } catch {
        return false;
    }
}
