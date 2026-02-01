// ================================================================================
// Laravel Sanctum Authentication Bridge
// ================================================================================
// This utility handles authentication between Next.js and Laravel CMS

export interface LaravelUser {
    id: number;
    name: string;
    email: string;
    email_verified_at?: string | null;
    created_at: string;
    updated_at: string;
}

export interface ValidateTokenResponse {
    valid: boolean;
    user: LaravelUser;
    timestamp: string;
}

export interface CreateTokenResponse {
    token: string;
    user: LaravelUser;
}

export class LaravelAuthError extends Error {
    constructor(
        message: string,
        public status?: number,
        public code?: string
    ) {
        super(message);
        this.name = 'LaravelAuthError';
    }
}

/**
 * Validate a Sanctum token with Laravel API
 * @param token - Sanctum bearer token
 * @returns User data if valid
 * @throws LaravelAuthError if validation fails
 */
export async function validateToken(
    token: string
): Promise<ValidateTokenResponse> {
    const apiUrl = process.env.NEXT_PUBLIC_LARAVEL_API_URL || 'http://localhost:8000';

    try {
        const response = await fetch(`${apiUrl}/api/validate-token`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`,
            },
            credentials: 'include',
        });

        if (!response.ok) {
            throw new LaravelAuthError(
                'Token validation failed',
                response.status,
                'TOKEN_INVALID'
            );
        }

        const data = await response.json();
        return data;
    } catch (error) {
        if (error instanceof LaravelAuthError) {
            throw error;
        }
        throw new LaravelAuthError(
            'Network error during token validation',
            undefined,
            'NETWORK_ERROR'
        );
    }
}

/**
 * Create a new Sanctum token for MVP access
 * @param credentials - Email and password
 * @param deviceName - Name for the token
 * @returns Token and user data
 * @throws LaravelAuthError if creation fails
 */
export async function createMvpToken(
    credentials: { email: string; password: string },
    deviceName: string = 'AI Product Client'
): Promise<CreateTokenResponse> {
    const apiUrl = process.env.NEXT_PUBLIC_LARAVEL_API_URL || 'http://localhost:8000';

    try {
        // First, authenticate with Laravel
        const loginResponse = await fetch(`${apiUrl}/login`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            credentials: 'include',
            body: JSON.stringify(credentials),
        });

        if (!loginResponse.ok) {
            throw new LaravelAuthError(
                'Login failed',
                loginResponse.status,
                'LOGIN_FAILED'
            );
        }

        // Then create the MVP token
        const tokenResponse = await fetch(`${apiUrl}/api/mvp/token`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            credentials: 'include',
            body: JSON.stringify({ device_name: deviceName }),
        });

        if (!tokenResponse.ok) {
            throw new LaravelAuthError(
                'Token creation failed',
                tokenResponse.status,
                'TOKEN_CREATE_FAILED'
            );
        }

        const data = await tokenResponse.json();
        return data;
    } catch (error) {
        if (error instanceof LaravelAuthError) {
            throw error;
        }
        throw new LaravelAuthError(
            'Network error during token creation',
            undefined,
            'NETWORK_ERROR'
        );
    }
}

/**
 * Revoke all MVP tokens for the authenticated user
 * @param token - Sanctum bearer token
 * @throws LaravelAuthError if revocation fails
 */
export async function revokeMvpTokens(token: string): Promise<void> {
    const apiUrl = process.env.NEXT_PUBLIC_LARAVEL_API_URL || 'http://localhost:8000';

    try {
        const response = await fetch(`${apiUrl}/api/mvp/tokens`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`,
            },
            credentials: 'include',
        });

        if (!response.ok) {
            throw new LaravelAuthError(
                'Token revocation failed',
                response.status,
                'TOKEN_REVOKE_FAILED'
            );
        }
    } catch (error) {
        if (error instanceof LaravelAuthError) {
            throw error;
        }
        throw new LaravelAuthError(
            'Network error during token revocation',
            undefined,
            'NETWORK_ERROR'
        );
    }
}

/**
 * Check if Laravel API is healthy
 * @returns true if API is responding
 */
export async function checkApiHealth(): Promise<boolean> {
    const apiUrl = process.env.NEXT_PUBLIC_LARAVEL_API_URL || 'http://localhost:8000';

    try {
        const response = await fetch(`${apiUrl}/api/mvp/health`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
            },
        });

        return response.ok;
    } catch (error) {
        return false;
    }
}
