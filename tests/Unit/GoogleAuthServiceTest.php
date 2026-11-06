<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\GoogleAuthService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GoogleAuthServiceTest extends TestCase
{
    private GoogleAuthService $service;
    private string $testClientId = 'test-client-id.apps.googleusercontent.com';

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new GoogleAuthService();
        Config::set('services.google.client_id', $this->testClientId);
    }

    public function test_rejects_empty_id_token()
    {
        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('Google ID token is required.');

        $this->service->verifyGoogleIdToken('');
    }

    public function test_rejects_when_client_id_unconfigured()
    {
        Config::set('services.google.client_id', '');

        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('Google Sign-In is not currently configured on the server.');

        $this->service->verifyGoogleIdToken('sample-token');
    }

    public function test_rejects_invalid_token_from_google()
    {
        Http::fake([
            'https://oauth2.googleapis.com/tokeninfo*' => Http::response(['error' => 'invalid_token'], 400),
        ]);

        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('Invalid or expired Google authentication token.');

        $this->service->verifyGoogleIdToken('invalid-token');
    }

    public function test_rejects_invalid_issuer()
    {
        Http::fake([
            'https://oauth2.googleapis.com/tokeninfo*' => Http::response([
                'iss'            => 'https://attacker.com',
                'aud'            => $this->testClientId,
                'exp'            => time() + 3600,
                'email'          => 'user@example.com',
                'email_verified' => 'true',
            ], 200),
        ]);

        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('Invalid Google token issuer.');

        $this->service->verifyGoogleIdToken('sample-token');
    }

    public function test_rejects_audience_mismatch()
    {
        Http::fake([
            'https://oauth2.googleapis.com/tokeninfo*' => Http::response([
                'iss'            => 'https://accounts.google.com',
                'aud'            => 'wrong-client-id.apps.googleusercontent.com',
                'exp'            => time() + 3600,
                'email'          => 'user@example.com',
                'email_verified' => 'true',
            ], 200),
        ]);

        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('Google token was not issued for this application.');

        $this->service->verifyGoogleIdToken('sample-token');
    }

    public function test_rejects_expired_token()
    {
        Http::fake([
            'https://oauth2.googleapis.com/tokeninfo*' => Http::response([
                'iss'            => 'https://accounts.google.com',
                'aud'            => $this->testClientId,
                'exp'            => time() - 60,
                'email'          => 'user@example.com',
                'email_verified' => 'true',
            ], 200),
        ]);

        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('Google token has expired. Please sign in again.');

        $this->service->verifyGoogleIdToken('sample-token');
    }

    public function test_rejects_unverified_email()
    {
        Http::fake([
            'https://oauth2.googleapis.com/tokeninfo*' => Http::response([
                'iss'            => 'https://accounts.google.com',
                'aud'            => $this->testClientId,
                'exp'            => time() + 3600,
                'email'          => 'user@example.com',
                'email_verified' => 'false',
            ], 200),
        ]);

        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('Your Google email address is not verified by Google.');

        $this->service->verifyGoogleIdToken('sample-token');
    }

    public function test_rejects_unregistered_account_without_auto_creation()
    {
        Http::fake([
            'https://oauth2.googleapis.com/tokeninfo*' => Http::response([
                'iss'            => 'https://accounts.google.com',
                'aud'            => $this->testClientId,
                'exp'            => time() + 3600,
                'email'          => 'nonexistent@example.com',
                'email_verified' => 'true',
                'sub'            => '1234567890',
            ], 200),
        ]);

        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('No registered MSWDO beneficiary account found with this Google email. Please register first.');

        $this->service->authenticateBeneficiary('valid-token');
    }
}
