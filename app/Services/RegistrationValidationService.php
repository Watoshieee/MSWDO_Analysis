<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class RegistrationValidationService
{
    /**
     * Validate name fields (first, middle, last)
     * - Allow single-letter names
     * - Allow alphabetic characters including accented letters
     * - Allow hyphens and apostrophes
     * - No numbers or special characters except - and '
     */
    public static function validateName(string $name, bool $required = true): array
    {
        $errors = [];
        
        if ($required && empty(trim($name))) {
            $errors[] = 'This field is required.';
            return $errors;
        }
        
        if (!empty(trim($name))) {
            $trimmed = trim($name);
            
            // Check length (1-50 characters)
            if (mb_strlen($trimmed) < 1) {
                $errors[] = 'Name must be at least 1 character.';
            }
            
            if (mb_strlen($trimmed) > 50) {
                $errors[] = 'Name must not exceed 50 characters.';
            }
            
            // Check for valid characters (letters, spaces, hyphens, apostrophes, accented letters)
            if (!preg_match("/^[a-zA-ZÀ-ÿ\s'\-\.]+$/u", $trimmed)) {
                $errors[] = 'Name can only contain letters, spaces, hyphens, and apostrophes.';
            }
            
            // Check for numbers
            if (preg_match('/\d/', $trimmed)) {
                $errors[] = 'Name cannot contain numbers.';
            }
        }
        
        return $errors;
    }

    /**
     * Validate email - must be Gmail only and verify it exists
     */
    public static function validateEmail(string $email): array
    {
        $errors = [];
        $email = strtolower(trim($email));
        
        if (empty($email)) {
            $errors[] = 'Email is required.';
            return $errors;
        }
        
        // Check valid email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format.';
            return $errors;
        }
        
        // Check if it's a Gmail address
        if (!str_ends_with($email, '@gmail.com')) {
            $errors[] = 'Only Gmail addresses (@gmail.com) are allowed.';
            return $errors;
        }
        
        // Extract local part (before @)
        $localPart = explode('@', $email)[0];
        
        // Gmail-specific validation rules
        // 1. Must be 6-30 characters
        if (strlen($localPart) < 6 || strlen($localPart) > 30) {
            $errors[] = 'Gmail username must be between 6 and 30 characters.';
        }
        
        // 2. Can only contain letters, numbers, and periods
        if (!preg_match('/^[a-z0-9.]+$/', $localPart)) {
            $errors[] = 'Gmail address can only contain letters, numbers, and periods.';
        }
        
        // 3. Cannot start or end with a period
        if (str_starts_with($localPart, '.') || str_ends_with($localPart, '.')) {
            $errors[] = 'Gmail address cannot start or end with a period.';
        }
        
        // 4. Cannot have consecutive periods
        if (strpos($localPart, '..') !== false) {
            $errors[] = 'Gmail address cannot contain consecutive periods.';
        }
        
        // If format validation passed, verify Gmail exists
        if (empty($errors)) {
            try {
                $verificationResult = EmailVerificationService::verifyEmailExists($email);
                
                if (!$verificationResult['valid']) {
                    $errors[] = $verificationResult['message'];
                } else if (isset($verificationResult['warning']) && $verificationResult['warning']) {
                    // Log warning but allow registration
                    Log::warning('Email verification warning for: ' . $email . ' - ' . $verificationResult['message']);
                }
            } catch (\Exception $e) {
                // If verification service fails, log but don't block registration
                Log::error('Email verification service error: ' . $e->getMessage());
                // Continue with registration
            }
        }
        
        return $errors;
    }

    /**
     * Validate Philippine mobile number
     * - Must start with 63+ (country code)
     * - Must have exactly 10 digits after 63
     * - Must start with 9 after country code
     * - No more than 3 consecutive repeating digits
     */
    public static function validateMobileNumber(string $mobileNumber): array
    {
        $errors = [];
        $mobile = trim($mobileNumber);
        
        if (empty($mobile)) {
            $errors[] = 'Mobile number is required.';
            return $errors;
        }
        
        // Check format: must start with +63
        if (!str_starts_with($mobile, '+63')) {
            $errors[] = 'Mobile number must start with +63.';
            return $errors;
        }
        
        // Extract digits after +63
        $digits = substr($mobile, 3);
        
        // Check if exactly 10 digits
        if (!preg_match('/^\d{10}$/', $digits)) {
            $errors[] = 'Mobile number must have exactly 10 digits after +63.';
            return $errors;
        }
        
        // Check if starts with 9
        if (!str_starts_with($digits, '9')) {
            $errors[] = 'Mobile number must start with 9 after +63.';
        }
        
        // Check for more than 3 consecutive repeating digits
        if (preg_match('/(\d)\1{3,}/', $digits)) {
            $errors[] = 'Mobile number contains too many repeating digits. Please enter a valid number.';
        }
        
        // Check for obviously fake patterns
        $fakePatterns = [
            '0000000000', '1111111111', '2222222222', '3333333333', '4444444444',
            '5555555555', '6666666666', '7777777777', '8888888888', '9999999999',
            '1234567890', '0987654321', '1234567891', '9876543210'
        ];
        
        if (in_array($digits, $fakePatterns)) {
            $errors[] = 'Please enter a valid mobile number.';
        }
        
        return $errors;
    }

    /**
     * Generate a secure random password
     * - 12 characters long
     * - Contains uppercase, lowercase, numbers, and special characters
     */
    public static function generateSecurePassword(): string
    {
        $uppercase = 'ABCDEFGHJKLMNPQRSTUVWXYZ'; // Removed I, O for clarity
        $lowercase = 'abcdefghjkmnpqrstuvwxyz'; // Removed i, l, o for clarity
        $numbers = '23456789'; // Removed 0, 1 for clarity
        $special = '@$!%*?&#';
        
        $password = '';
        
        // Ensure at least one of each type
        $password .= $uppercase[random_int(0, strlen($uppercase) - 1)];
        $password .= $lowercase[random_int(0, strlen($lowercase) - 1)];
        $password .= $numbers[random_int(0, strlen($numbers) - 1)];
        $password .= $special[random_int(0, strlen($special) - 1)];
        
        // Fill the rest randomly (total 12 characters)
        $allChars = $uppercase . $lowercase . $numbers . $special;
        for ($i = 4; $i < 12; $i++) {
            $password .= $allChars[random_int(0, strlen($allChars) - 1)];
        }
        
        // Shuffle the password
        return str_shuffle($password);
    }

    /**
     * Validate all registration data
     */
    public static function validateRegistration(array $data): array
    {
        $errors = [];
        
        // Validate first name
        $firstNameErrors = self::validateName($data['first_name'] ?? '', true);
        if (!empty($firstNameErrors)) {
            $errors['first_name'] = $firstNameErrors;
        }
        
        // Validate middle name (optional)
        if (!empty($data['middle_name'])) {
            $middleNameErrors = self::validateName($data['middle_name'], false);
            if (!empty($middleNameErrors)) {
                $errors['middle_name'] = $middleNameErrors;
            }
        }
        
        // Validate last name
        $lastNameErrors = self::validateName($data['last_name'] ?? '', true);
        if (!empty($lastNameErrors)) {
            $errors['last_name'] = $lastNameErrors;
        }
        
        // Validate email
        $emailErrors = self::validateEmail($data['email'] ?? '');
        if (!empty($emailErrors)) {
            $errors['email'] = $emailErrors;
        }
        
        // Validate mobile number
        $mobileErrors = self::validateMobileNumber($data['mobile_number'] ?? '');
        if (!empty($mobileErrors)) {
            $errors['mobile_number'] = $mobileErrors;
        }
        
        return $errors;
    }
}
