<?php

declare(strict_types=1);

namespace Toolkit\Validator\Rules;

use Toolkit\Validator\Contracts\RuleInterface;
use Toolkit\Validator\Exceptions\ValidationException;

/**
 * Unique rule - validates that a value is unique (checked against a JSON file or callback).
 * 
 * @package Toolkit\Validator\Rules
 */
class UniqueRule implements RuleInterface
{
    /**
     * Validate that the value is unique.
     * 
     * @param mixed $value The value to validate.
     * @param array $params Parameters containing:
     *                      - Index 0: File path to JSON file OR callback function
     *                      - Index 1: (Optional) Field name in JSON to check against
     * @return bool True if the value is unique, false otherwise.
     * @throws ValidationException If the file does not exist or callback is invalid.
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
        
        if (!isset($params[0])) {
            throw new ValidationException('UniqueRule requires a file path or callback as the first parameter.');
        }
        
        $checkSource = $params[0];
        $fieldName = $params[1] ?? 'value';
        
        // If it's a callable, use it as a callback
        if (is_callable($checkSource)) {
            return !$checkSource($value);
        }
        
        // If it's a string, treat it as a file path
        if (is_string($checkSource)) {
            if (!file_exists($checkSource)) {
                // If file doesn't exist, the value is considered unique
                return true;
            }
            
            $jsonData = file_get_contents($checkSource);
            $data = json_decode($jsonData, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new ValidationException('Invalid JSON file for UniqueRule validation.');
            }
            
            if (!is_array($data)) {
                throw new ValidationException('JSON file must contain an array for UniqueRule validation.');
            }
            
            // Check if value exists in the data
            foreach ($data as $item) {
                if (is_array($item) && isset($item[$fieldName])) {
                    if ($item[$fieldName] === $value) {
                        return false; // Value already exists
                    }
                } elseif ($item === $value) {
                    return false; // Value already exists in simple array
                }
            }
            
            return true; // Value is unique
        }
        
        throw new ValidationException('UniqueRule requires a file path (string) or callback as the first parameter.');
    }

    /**
     * Get the error message for unique validation failure.
     * 
     * @param string $field The name of the field.
     * @param array $params Parameters (not used for message).
     * @return string The error message.
     */
    public function getMessage(string $field, array $params = []): string
    {
        return "The {$field} field must be unique.";
    }
}
