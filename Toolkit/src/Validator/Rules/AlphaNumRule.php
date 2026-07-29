<?php

declare(strict_types=1);

namespace Toolkit\Validator\Rules;

use Toolkit\Validator\Contracts\RuleInterface;

/**
 * Validates that the value contains only alphanumeric characters.
 */
class AlphaNumRule implements RuleInterface
{
    public function validate($value, array $params = []): bool
    {
        if ($value === null || $value === '') {
            return true; // Let RequiredRule handle emptiness
        }

        $strValue = (string)$value;
        
        // Check for spaces if allowed
        if (isset($params[0]) && $params[0] === 'space') {
            return ctype_alnum(str_replace(' ', '', $strValue));
        }

        return ctype_alnum($strValue);
    }

    public function getMessage(string $field, array $params = []): string
    {
        if (isset($params[0]) && $params[0] === 'space') {
            return "The {$field} field must contain only alphanumeric characters and spaces.";
        }
        return "The {$field} field must contain only alphanumeric characters.";
    }
}
