<?php

declare(strict_types=1);

namespace Toolkit\Validator\Rules;

use Toolkit\Validator\Contracts\RuleInterface;

/**
 * Email rule - validates that a value is a valid email address.
 * 
 * @package Toolkit\Validator\Rules
 */
class EmailRule implements RuleInterface
{
    /**
     * Validate that the value is a valid email address.
     * 
     * @param mixed $value The value to validate.
     * @param array $params Optional parameters (not used for this rule).
     * @return bool True if the value is a valid email, false otherwise.
     */
    public function validate($value, array $params = []): bool
    {
        if (!is_string($value)) {
            return false;
        }
        
        $value = trim($value);
        
        if ($value === '') {
            return true; // Empty values should be handled by RequiredRule
        }
        
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Get the error message for email validation failure.
     * 
     * @param string $field The name of the field.
     * @param array $params Optional parameters (not used).
     * @return string The error message.
     */
    public function getMessage(string $field, array $params = []): string
    {
        return "The {$field} field must be a valid email address.";
    }
}
