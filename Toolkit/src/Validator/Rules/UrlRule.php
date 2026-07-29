<?php

declare(strict_types=1);

namespace Toolkit\Validator\Rules;

use Toolkit\Validator\Contracts\RuleInterface;

/**
 * Validates that the value is a valid URL.
 */
class UrlRule implements RuleInterface
{
    public function validate($value, array $params = []): bool
    {
        if ($value === null || $value === '') {
            return true; // Let RequiredRule handle emptiness
        }

        return filter_var($value, FILTER_VALIDATE_URL) !== false;
    }

    public function getMessage(string $field, array $params = []): string
    {
        return "The {$field} field must be a valid URL.";
    }
}
