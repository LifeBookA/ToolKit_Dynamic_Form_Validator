<?php

declare(strict_types=1);

namespace Toolkit\Validator\Rules;

use Toolkit\Validator\Contracts\RuleInterface;

/**
 * Validates that the value is between a minimum and maximum value.
 */
class BetweenRule implements RuleInterface
{
    public function validate($value, array $params = []): bool
    {
        if ($value === null || $value === '') {
            return true; // Let RequiredRule handle emptiness
        }

        if (!isset($params[0]) || !isset($params[1])) {
            throw new \InvalidArgumentException('Between rule requires min and max parameters.');
        }

        $min = (float)$params[0];
        $max = (float)$params[1];
        $numericValue = (float)$value;

        return $numericValue >= $min && $numericValue <= $max;
    }

    public function getMessage(string $field, array $params = []): string
    {
        $min = $params[0] ?? 0;
        $max = $params[1] ?? 100;
        return "The {$field} field must be between {$min} and {$max}.";
    }
}
