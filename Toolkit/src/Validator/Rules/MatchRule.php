<?php

declare(strict_types=1);

namespace Toolkit\Validator\Rules;

use Toolkit\Validator\Contracts\RuleInterface;

/**
 * Match rule - validates that a value matches another field's value.
 * 
 * @package Toolkit\Validator\Rules
 */
class MatchRule implements RuleInterface
{
    /**
     * Validate that the value matches another field's value.
     * 
     * @param mixed $value The value to validate.
     * @param array $params Parameters containing the name of the field to match at index 0.
     * @return bool True if the values match, false otherwise.
     */
    public function validate($value, array $params = []): bool
    {
        if (!isset($params[0])) {
            return true; // No field to match against, skip validation
        }
        
        $matchField = $params[0];
        $matchValue = $params[1] ?? null;
        
        // If we have a direct value to compare against
        if ($matchValue !== null) {
            return $value === $matchValue;
        }
        
        // Otherwise, this rule expects the validator to pass the actual match value
        // This is handled specially in the Validator class
        return true;
    }

    /**
     * Get the error message for match validation failure.
     * 
     * @param string $field The name of the field.
     * @param array $params Parameters containing the name of the field to match at index 0.
     * @return string The error message.
     */
    public function getMessage(string $field, array $params = []): string
    {
        $matchField = $params[0] ?? 'another field';
        return "The {$field} field must match the {$matchField} field.";
    }
}
