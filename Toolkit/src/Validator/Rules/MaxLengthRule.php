<?php

declare(strict_types=1);

namespace Toolkit\Validator\Rules;

use Toolkit\Validator\Contracts\RuleInterface;

/**
 * MaxLength rule - validates that a string does not exceed a maximum length.
 * 
 * @package Toolkit\Validator\Rules
 */
class MaxLengthRule implements RuleInterface
{
    /**
     * Validate that the value does not exceed the specified maximum length.
     * 
     * @param mixed $value The value to validate.
     * @param array $params Parameters containing the maximum length at index 0.
     * @return bool True if the value meets the maximum length requirement, false otherwise.
     */
    public function validate($value, array $params = []): bool
    {
        if (!is_string($value)) {
            return true; // Non-string values are not validated by this rule
        }
        
        $value = trim($value);
        
        if ($value === '') {
            return true; // Empty values should be handled by RequiredRule
        }
        
        $maxLength = $params[0] ?? PHP_INT_MAX;
        
        return strlen($value) <= (int)$maxLength;
    }

    /**
     * Get the error message for max length validation failure.
     * 
     * @param string $field The name of the field.
     * @param array $params Parameters containing the maximum length at index 0.
     * @return string The error message.
     */
    public function getMessage(string $field, array $params = []): string
    {
        $maxLength = $params[0] ?? 0;
        return "The {$field} field must not exceed {$maxLength} characters.";
    }
}
