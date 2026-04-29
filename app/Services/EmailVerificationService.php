<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class EmailVerificationService
{
    /**
     * Verify if email address exists and is valid
     * Uses multiple verification methods for reliability
     */
    public static function verifyEmailExists(string $email): array
    {
        $email = strtolower(trim($email));
        
        // Method 1: DNS MX Record Check
        $mxCheck = self::checkMXRecords($email);
        if (!$mxCheck['valid']) {
            return $mxCheck;
        }
        
        // Method 2: SMTP Verification (if available)
        $smtpCheck = self::smtpVerification($email);
        if (!$smtpCheck['valid']) {
            return $smtpCheck;
        }
        
        // Method 3: Format and Pattern Validation
        $formatCheck = self::advancedFormatValidation($email);
        if (!$formatCheck['valid']) {
            return $formatCheck;
        }
        
        return [
            'valid' => true,
            'message' => 'Email verified successfully',
            'confidence' => 'high'
        ];
    }
    
    /**
     * Check DNS MX records for email domain
     */
    private static function checkMXRecords(string $email): array
    {
        $domain = substr(strrchr($email, "@"), 1);
        
        if (empty($domain)) {
            return [
                'valid' => false,
                'message' => 'Invalid email format - missing domain.'
            ];
        }
        
        // Check if domain has MX records
        $mxRecords = [];
        $hasMX = getmxrr($domain, $mxRecords);
        
        if (!$hasMX || empty($mxRecords)) {
            return [
                'valid' => false,
                'message' => 'Email domain does not exist or cannot receive emails.'
            ];
        }
        
        return [
            'valid' => true,
            'message' => 'Domain has valid MX records'
        ];
    }
    
    /**
     * SMTP verification - check if mailbox exists
     */
    private static function smtpVerification(string $email): array
    {
        try {
            $domain = substr(strrchr($email, "@"), 1);
            $mxHosts = [];
            
            if (!getmxrr($domain, $mxHosts)) {
                return [
                    'valid' => false,
                    'message' => 'Unable to find mail server for this domain.'
                ];
            }
            
            // Try to connect to the first MX host
            $mxHost = $mxHosts[0];
            $timeout = 10;
            
            // Suppress warnings for socket connection
            $socket = @fsockopen($mxHost, 25, $errno, $errstr, $timeout);
            
            if (!$socket) {
                // Connection failed - might be firewall or network issue
                // Log but don't block registration
                Log::warning("SMTP connection failed for {$email}: {$errstr}");
                return [
                    'valid' => true, // Allow registration
                    'message' => 'Email format valid (SMTP check unavailable)',
                    'warning' => true
                ];
            }
            
            // Read initial server response
            $response = fgets($socket, 1024);
            
            // Send HELO
            fputs($socket, "HELO " . gethostname() . "\r\n");
            $response = fgets($socket, 1024);
            
            // Send MAIL FROM
            fputs($socket, "MAIL FROM: <noreply@mswdo.gov.ph>\r\n");
            $response = fgets($socket, 1024);
            
            // Send RCPT TO - this checks if the email exists
            fputs($socket, "RCPT TO: <{$email}>\r\n");
            $response = fgets($socket, 1024);
            
            // Send QUIT
            fputs($socket, "QUIT\r\n");
            fclose($socket);
            
            // Parse response code
            $responseCode = intval(substr($response, 0, 3));
            
            // Response codes:
            // 250 = OK (mailbox exists)
            // 550 = Mailbox unavailable (doesn't exist)
            // 551 = User not local
            // 552 = Exceeded storage allocation
            // 553 = Mailbox name not allowed
            // 554 = Transaction failed
            
            if (in_array($responseCode, [550, 551, 553, 554])) {
                return [
                    'valid' => false,
                    'message' => 'This email address does not exist. Please verify your Gmail address.'
                ];
            }
            
            // Gmail often returns 250 even for non-existent emails (privacy)
            // So we accept it but note the limitation
            return [
                'valid' => true,
                'message' => 'Email verification passed'
            ];
            
        } catch (\Exception $e) {
            Log::error("SMTP verification error for {$email}: " . $e->getMessage());
            
            // On error, allow registration but log it
            return [
                'valid' => true,
                'message' => 'Email format valid (verification unavailable)',
                'warning' => true
            ];
        }
    }
    
    /**
     * Advanced format validation for Gmail
     */
    private static function advancedFormatValidation(string $email): array
    {
        // Check for common typos in Gmail domain
        $commonTypos = [
            'gmial.com', 'gmai.com', 'gamil.com', 'gmil.com',
            'gmail.co', 'gmail.cm', 'gmail.om', 'gmaill.com',
            'g-mail.com', 'gmail.con', 'gmail.cmo'
        ];
        
        foreach ($commonTypos as $typo) {
            if (str_ends_with($email, '@' . $typo)) {
                return [
                    'valid' => false,
                    'message' => 'Did you mean @gmail.com? Please check your email address.'
                ];
            }
        }
        
        // Check for suspicious patterns
        $localPart = explode('@', $email)[0];
        
        // Check for too many consecutive same characters
        if (preg_match('/(.)\1{4,}/', $localPart)) {
            return [
                'valid' => false,
                'message' => 'Email address contains suspicious pattern. Please use a valid Gmail address.'
            ];
        }
        
        // Check for test/fake email patterns
        $suspiciousPatterns = [
            'test', 'fake', 'dummy', 'sample', 'example',
            'asdf', 'qwerty', '12345', 'abcdef'
        ];
        
        foreach ($suspiciousPatterns as $pattern) {
            if (stripos($localPart, $pattern) !== false && strlen($localPart) <= 10) {
                Log::warning("Suspicious email pattern detected: {$email}");
                // Don't block, just log
            }
        }
        
        return [
            'valid' => true,
            'message' => 'Format validation passed'
        ];
    }
    
    /**
     * Quick validation for real-time feedback (less strict)
     */
    public static function quickValidation(string $email): array
    {
        $email = strtolower(trim($email));
        
        // Basic format check
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                'valid' => false,
                'message' => 'Invalid email format.'
            ];
        }
        
        // Gmail check
        if (!str_ends_with($email, '@gmail.com')) {
            return [
                'valid' => false,
                'message' => 'Only Gmail addresses are allowed.'
            ];
        }
        
        // Quick MX check
        $domain = 'gmail.com';
        if (!checkdnsrr($domain, 'MX')) {
            return [
                'valid' => false,
                'message' => 'Unable to verify Gmail servers.'
            ];
        }
        
        return [
            'valid' => true,
            'message' => 'Email format valid'
        ];
    }
}
