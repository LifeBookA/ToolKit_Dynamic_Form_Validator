<?php

declare(strict_types=1);

namespace Toolkit\Validator\Rules;

use Toolkit\Validator\Contracts\RuleInterface;

/**
 * Numeric rule - validates that a value is numeric.
 * 
 * @package Toolkit\Validator\Rules
 */
class NumericRule implements RuleInterface
{
    /**
     * Validate that the value is numeric.
     * 
     * @param mixed $value The value to validate.
     * @param array $params Optional parameters (not used for this rule).
     * @return bool True if the value is numeric, false otherwise.
     */
    public function validate($value, array $params = []): bool
    {
        if (!is_string($value) && !is_int($value) && !is_float($value)) {
            return false;
        }
        
        $stringValue = trim((string)$value);
        
        if ($stringValue === '') {
            return true; // Empty values should be handled by RequiredRule
        }
        
        return is_numeric($value);
    }

    /**
     * Get the error message for numeric validation failure.
     * 
     * @param string $field The name of the field.
     * @param array $params Optional parameters (not used).
     * @return string The error message.
     */
    public function getMessage(string $field, array $params = []): string
    {
        return "The {$field} field must be a valid number.";
    }
}
