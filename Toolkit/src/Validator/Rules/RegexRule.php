<?php

declare(strict_types=1);

namespace Toolkit\Validator\Rules;

use Toolkit\Validator\Contracts\RuleInterface;

/**
 * Regex rule - validates that a value matches a regular expression pattern.
 * 
 * @package Toolkit\Validator\Rules
 */
class RegexRule implements RuleInterface
{
    /**
     * Validate that the value matches the specified regex pattern.
     * 
     * @param mixed $value The value to validate.
     * @param array $params Parameters containing the regex pattern at index 0.
     * @return bool True if the value matches the pattern, false otherwise.
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
        
        $pattern = $params[0] ?? '';
        
        if ($pattern === '') {
            return true; // No pattern provided, skip validation
        }
        
        return (bool)preg_match($pattern, $value);
    }

    /**
     * Get the error message for regex validation failure.
     * 
     * @param string $field The name of the field.
     * @param array $params Parameters containing the regex pattern at index 0.
     * @return string The error message.
     */
    public function getMessage(string $field, array $params = []): string
    {
        return "The {$field} field format is invalid.";
    }
}
