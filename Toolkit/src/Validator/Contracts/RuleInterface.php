<?php

declare(strict_types=1);

namespace Toolkit\Validator\Contracts;

/**
 * Interface for validation rules.
 * 
 * All validation rules must implement this interface to ensure consistent behavior.
 * 
 * @package Toolkit\Validator\Contracts
 */
interface RuleInterface
{
    /**
     * Validate a value against the rule.
     * 
     * @param mixed $value The value to validate.
     * @param array $params Optional parameters for the rule.
     * @return bool True if validation passes, false otherwise.
     */
    public function validate($value, array $params = []): bool;

    /**
     * Get the error message for this rule.
     * 
     * @param string $field The name of the field being validated.
     * @param array $params Optional parameters for the rule.
     * @return string The error message.
     */
    public function getMessage(string $field, array $params = []): string;
}
