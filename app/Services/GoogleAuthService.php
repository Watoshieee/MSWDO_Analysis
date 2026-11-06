<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleAuthService
{
    /**
     * Verify a Google ID token server-side.
     *
     * Validates:
     * - Cryptographic signature: verified via Google's X.509 public certificates (RS256/OpenSSL)
     *   with tokeninfo endpoint fallback.
     * - Issuer (iss): accounts.google.com or https://accounts.google.com
     * - Audience (aud): matches configured Google Web Client ID
     * - Expiry (exp): token must not be expired
     * - Email verification: email_verified claim must strictly be true
     *
     * @param string $idToken
     * @return array{sub: string, email: string, name: string}
     * @throws AuthenticationException
     */
    public function verifyGoogleIdToken(string $idToken): array
    {
        $idToken = trim($idToken);
        if ($idToken === '') {
            throw new AuthenticationException('Google ID token is required.');
        }

        $expectedClientId = config('services.google.client_id');
        if (empty($expectedClientId)) {
            Log::error('Google Sign-In attempted but GOOGLE_CLIENT_ID is not configured in services.google.client_id.');
            throw new AuthenticationException('Google Sign-In is not currently configured on the server.');
        }

        $payload = null;

        // 1. Attempt local cryptographic RSA-SHA256 signature verification using Google's published certs
        try {
            $payload = $this->verifyJwtSignatureLocally($idToken);
        } catch (AuthenticationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::debug('Local JWT signature check exception, falling back to tokeninfo: ' . $e->getMessage());
            $payload = null;
        }

        // 2. If local verification was inconclusive (e.g. key cache miss or cert fetch error), verify via Google tokeninfo
        if ($payload === null) {
            try {
                $response = Http::timeout(10)->get('https://oauth2.googleapis.com/tokeninfo', [
                    'id_token' => $idToken,
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to communicate with Google tokeninfo endpoint: ' . $e->getMessage());
                throw new AuthenticationException('Unable to verify Google credentials at this time. Please try again.');
            }

            if (!$response->successful()) {
                Log::warning('Google tokeninfo rejected token', [
                    'status' => $response->status(),
                    'error'  => $response->json('error_description') ?? $response->body(),
                ]);
                throw new AuthenticationException('Invalid or expired Google authentication token.');
            }

            $payload = $response->json();
        }

        // 3. Verify issuer
        $issuer = $payload['iss'] ?? '';
        if ($issuer !== 'accounts.google.com' && $issuer !== 'https://accounts.google.com') {
            Log::warning('Google token verification failed: invalid issuer', ['iss' => $issuer]);
            throw new AuthenticationException('Invalid Google token issuer.');
        }

        // 4. Verify audience matches configured Web Client ID
        $audience = $payload['aud'] ?? '';
        if ($audience !== $expectedClientId) {
            Log::warning('Google token verification failed: audience mismatch', [
                'expected' => $expectedClientId,
                'received' => $audience,
            ]);
            throw new AuthenticationException('Google token was not issued for this application.');
        }

        // 5. Verify token expiration
        $exp = isset($payload['exp']) ? (int) $payload['exp'] : 0;
        if ($exp < time()) {
            throw new AuthenticationException('Google token has expired. Please sign in again.');
        }

        // 6. Verify email existence and that Google marked email as verified
        $email = strtolower(trim((string) ($payload['email'] ?? '')));
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new AuthenticationException('Google account does not have a valid email address.');
        }

        $emailVerified = $payload['email_verified'] ?? false;
        $isVerified = ($emailVerified === true || $emailVerified === 'true' || $emailVerified === 1 || $emailVerified === '1');
        if (!$isVerified) {
            throw new AuthenticationException('Your Google email address is not verified by Google.');
        }

        return [
            'sub'   => (string) ($payload['sub'] ?? ''),
            'email' => $email,
            'name'  => (string) ($payload['name'] ?? ''),
        ];
    }

    /**
     * Cryptographically verify the RSA-SHA256 signature of a Google JWT using Google's X.509 certificates.
     *
     * @param string $idToken
     * @return array|null Returns parsed payload if signature is valid, null if key/certs unavailable
     * @throws AuthenticationException if signature verification explicitly fails (forged/tampered)
     */
    protected function verifyJwtSignatureLocally(string $idToken): ?array
    {
        $parts = explode('.', $idToken);
        if (count($parts) !== 3) {
            return null;
        }

        $headerJson = $this->base64UrlDecode($parts[0]);
        $payloadJson = $this->base64UrlDecode($parts[1]);
        $signature = $this->base64UrlDecode($parts[2]);

        $header = json_decode($headerJson, true);
        $payload = json_decode($payloadJson, true);

        if (!is_array($header) || !is_array($payload) || empty($header['kid']) || ($header['alg'] ?? '') !== 'RS256') {
            return null;
        }

        $certs = $this->getGooglePublicCerts();
        $kid = $header['kid'];

        if (empty($certs[$kid])) {
            // Cert might have rotated; clear cache and try once more
            Cache::forget('google_oauth2_certs');
            $certs = $this->getGooglePublicCerts();
        }

        if (empty($certs[$kid])) {
            return null; // Key not found in current certs, defer to tokeninfo
        }

        $pubKey = openssl_pkey_get_public($certs[$kid]);
        if (!$pubKey) {
            return null;
        }

        $signedData = $parts[0] . '.' . $parts[1];
        $verification = openssl_verify($signedData, $signature, $pubKey, OPENSSL_ALGO_SHA256);

        if ($verification === 1) {
            return $payload;
        }

        if ($verification === 0) {
            Log::warning('Google JWT RSA signature verification failed: token signature is invalid or tampered.');
            throw new AuthenticationException('Invalid Google token signature.');
        }

        return null;
    }

    /**
     * Retrieve and cache Google's public X.509 certificates for 1 hour.
     *
     * @return array<string, string>
     */
    protected function getGooglePublicCerts(): array
    {
        return Cache::remember('google_oauth2_certs', 3600, function () {
            try {
                $response = Http::timeout(5)->get('https://www.googleapis.com/oauth2/v1/certs');
                if ($response->successful()) {
                    return $response->json() ?? [];
                }
            } catch (\Exception $e) {
                Log::warning('Unable to fetch Google public certs: ' . $e->getMessage());
            }
            return [];
        });
    }

    /**
     * Safe base64url decode helper.
     */
    protected function base64UrlDecode(string $data): string
    {
        $remainder = strlen($data) % 4;
        if ($remainder) {
            $data .= str_repeat('=', 4 - $remainder);
        }
        return (string) base64_decode(strtr($data, '-_', '+/'));
    }

    /**
     * Authenticate an existing beneficiary account using verified Google credentials.
     *
     * Strict MSWDO security rules:
     * - No automatic account creation.
     * - Only beneficiary role (role=user). Admin & superadmin are blocked.
     * - Must be already registered, soft-delete excluded.
     * - Must have verified email on MSWDO (email OTP flow preserved).
     * - Must not have pending or rejected valid ID verification.
     * - Must have status=active (cannot be pending or inactive).
     * - Must belong to an authorized municipality.
     *
     * @param string $idToken
     * @return User
     * @throws AuthenticationException
     */
    public function authenticateBeneficiary(string $idToken): User
    {
        $payload = $this->verifyGoogleIdToken($idToken);

        $user = User::where('email', $payload['email'])
            ->whereNull('deleted_at')
            ->first();

        if (!$user) {
            throw new AuthenticationException('No registered MSWDO beneficiary account found with this Google email. Please register first.');
        }

        // 1. Role check: Beneficiaries only initially
        if ($user->role !== User::ROLE_USER) {
            throw new AuthenticationException('Google Sign-In is only available for regular beneficiary accounts.');
        }

        // 2. Email verification on MSWDO (preserving OTP lifecycle, never bypassing)
        if (!$user->hasVerifiedEmail()) {
            throw new AuthenticationException('Email not yet verified on your MSWDO account. Please complete email OTP verification first.');
        }

        // 3. Valid ID verification checks
        if ($user->isIdVerificationPending()) {
            throw new AuthenticationException('Your valid ID is still under review. Please wait for admin approval before you can sign in.');
        }

        if ($user->isIdVerificationRejected()) {
            $reasonMsg = $user->id_rejection_reason ? ' Reason: ' . $user->id_rejection_reason : '';
            throw new AuthenticationException('Your valid ID was declined by the administrator.' . $reasonMsg);
        }

        // 4. Account status checks
        if ($user->status === 'pending') {
            throw new AuthenticationException('Your registration has been successfully submitted. Your account is currently under verification. Please wait for your account to be approved before signing in.');
        }

        if ($user->status !== 'active') {
            throw new AuthenticationException('Your account is inactive. Please contact your municipal office.');
        }

        // 5. Municipality restriction check
        if (!in_array($user->municipality, User::getMunicipalities(), true)) {
            throw new AuthenticationException('Your account municipality is not authorized for access.');
        }

        return $user;
    }
}
