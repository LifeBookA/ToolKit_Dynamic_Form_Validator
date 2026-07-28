<?php

declare(strict_types=1);

namespace Toolkit\Validator\Rules;

use Toolkit\Validator\Contracts\RuleInterface;

/**
 * MinLength rule - validates that a string has at least a minimum length.
 * 
 * @package Toolkit\Validator\Rules
 */
class MinLengthRule implements RuleInterface
{
    /**
     * Validate that the value has at least the specified minimum length.
     * 
     * @param mixed $value The value to validate.
     * @param array $params Parameters containing the minimum length at index 0.
     * @return bool True if the value meets the minimum length requirement, false otherwise.
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
        
        $minLength = $params[0] ?? 0;
        
        return strlen($value) >= (int)$minLength;
    }

    /**
     * Get the error message for min length validation failure.
     * 
     * @param string $field The name of the field.
     * @param array $params Parameters containing the minimum length at index 0.
     * @return string The error message.
     */
    public function getMessage(string $field, array $params = []): string
    {
        $minLength = $params[0] ?? 0;
        return "The {$field} field must be at least {$minLength} characters long.";
    }
}
