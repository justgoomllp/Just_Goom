<?php

namespace App\Support;

/**
 * Shared safe-text patterns for user-facing name / title fields.
 * Blocks special characters such as + - & . ' ( ) @ # etc.
 */
class SafeText
{
    /** Letters and spaces only (person names). */
    public const PERSON = '/^[a-zA-Z]+(?:\s[a-zA-Z]+)*$/';

    /** Letters, numbers, and spaces only (titles, company-like names). */
    public const TITLE = '/^[a-zA-Z0-9]+(?:\s[a-zA-Z0-9]+)*$/';

    public static function personRule(): string
    {
        return 'regex:'.self::PERSON;
    }

    public static function titleRule(): string
    {
        return 'regex:'.self::TITLE;
    }

    public static function personMessage(string $field = 'This field'): string
    {
        return $field.' may only contain letters and spaces.';
    }

    public static function titleMessage(string $field = 'This field'): string
    {
        return $field.' may only contain letters, numbers, and spaces.';
    }
}
