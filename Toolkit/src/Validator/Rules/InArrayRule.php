<?php

declare(strict_types=1);

namespace Toolkit\Validator\Rules;

use Toolkit\Validator\Contracts\RuleInterface;

/**
 * InArray rule - validates that a value exists in a predefined array of values.
 * 
 * @package Toolkit\Validator\Rules
 */
class InArrayRule implements RuleInterface
{
    /**
     * Validate that the value exists in the specified array.
     * 
     * @param mixed $value The value to validate.
     * @param array $params Parameters containing the array of allowed values at index 0.
     * @return bool True if the value is in the array, false otherwise.
     */
    public function validate($value, array $params = []): bool
    {
        if (!isset($params[0]) || !is_array($params[0])) {
            return true; // No array provided, skip validation
        }
        
        $allowedValues = $params[0];
        
        if (is_string($value)) {
            $value = trim($value);
        }
        
        if ($value === '' || $value === null) {
            return true; // Empty values should be handled by RequiredRule
        }
        
        return in_array($value, $allowedValues, true);
    }

    /**
     * Get the error message for in_array validation failure.
     * 
     * @param string $field The name of the field.
     * @param array $params Parameters containing the array of allowed values at index 0.
     * @return string The error message.
     */
    public function getMessage(string $field, array $params = []): string
    {
        $allowedValues = $params[0] ?? [];
        $valuesList = implode(', ', array_map(fn($v) => "'{$v}'", $allowedValues));
        return "The {$field} field must be one of: {$valuesList}.";
    }
}
