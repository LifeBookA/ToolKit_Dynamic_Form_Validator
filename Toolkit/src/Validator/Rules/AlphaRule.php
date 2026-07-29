<?php

declare(strict_types=1);

namespace Toolkit\Validator\Rules;

use Toolkit\Validator\Contracts\RuleInterface;

/**
 * Validates that the value contains only alphabetic characters.
 */
class AlphaRule implements RuleInterface
{
    public function validate($value, array $params = []): bool
    {
        if ($value === null || $value === '') {
            return true; // Let RequiredRule handle emptiness
        }

        $strValue = (string)$value;
        
        // Check for spaces if allowed
        if (isset($params[0]) && $params[0] === 'space') {
            return ctype_alpha(str_replace(' ', '', $strValue));
        }

        return ctype_alpha($strValue);
    }

    public function getMessage(string $field, array $params = []): string
    {
        if (isset($params[0]) && $params[0] === 'space') {
            return "The {$field} field must contain only alphabetic characters and spaces.";
        }
        return "The {$field} field must contain only alphabetic characters.";
    }
}
