<?php

declare(strict_types=1);

namespace Toolkit\Validator\Rules;

use Toolkit\Validator\Contracts\RuleInterface;

/**
 * Required rule - validates that a field is not empty.
 * 
 * @package Toolkit\Validator\Rules
 */
class RequiredRule implements RuleInterface
{
    /**
     * Validate that the value is not empty.
     * 
     * @param mixed $value The value to validate.
     * @param array $params Optional parameters (not used for this rule).
     * @return bool True if the value is not empty, false otherwise.
     */
    public function validate($value, array $params = []): bool
    {
        if ($value === null) {
            return false;
        }
        
        if (is_string($value)) {
            return trim($value) !== '';
        }
        
        if (is_array($value)) {
            return count($value) > 0;
        }
        
        return true;
    }

    /**
     * Get the error message for required validation failure.
     * 
     * @param string $field The name of the field.
     * @param array $params Optional parameters (not used).
     * @return string The error message.
     */
    public function getMessage(string $field, array $params = []): string
    {
        return "The {$field} field is required.";
    }
}
