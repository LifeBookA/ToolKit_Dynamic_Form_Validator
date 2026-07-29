<?php

declare(strict_types=1);

namespace Toolkit\Validator\Rules;

use Toolkit\Validator\Contracts\RuleInterface;

/**
 * Validates that the value is a valid date.
 */
class DateRule implements RuleInterface
{
    public function validate($value, array $params = []): bool
    {
        if ($value === null || $value === '') {
            return true; // Let RequiredRule handle emptiness
        }

        $format = $params[0] ?? 'Y-m-d';
        
        $dateTime = \DateTime::createFromFormat($format, (string)$value);
        
        return $dateTime && $dateTime->format($format) === (string)$value;
    }

    public function getMessage(string $field, array $params = []): string
    {
        $format = $params[0] ?? 'Y-m-d';
        return "The {$field} field must be a valid date in format: {$format}.";
    }
}
