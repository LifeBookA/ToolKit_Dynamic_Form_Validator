<?php

declare(strict_types=1);

namespace Toolkit\Validator\Result;

/**
 * Result class for validation operations.
 * 
 * Contains the validation result including validity status,
 * error messages, and cleaned data.
 * 
 * @package Toolkit\Validator\Result
 */
class ValidationResult
{
    /**
     * Whether the validation passed.
     * 
     * @var bool
     */
    public readonly bool $isValid;

    /**
     * Array of error messages indexed by field name.
     * 
     * @var array<string, array<int, string>>
     */
    public readonly array $errors;

    /**
     * Array of cleaned/sanitized data.
     * 
     * @var array<string, mixed>
     */
    public readonly array $cleanedData;

    /**
     * Create a new validation result.
     * 
     * @param bool $isValid Whether the validation passed.
     * @param array $errors Array of error messages.
     * @param array $cleanedData Array of cleaned data.
     */
    public function __construct(
        bool $isValid,
        array $errors,
        array $cleanedData
    ) {
        $this->isValid = $isValid;
        $this->errors = $errors;
        $this->cleanedData = $cleanedData;
    }

    /**
     * Convert the result to an associative array.
     * 
     * @return array{isValid: bool, errors: array, cleanedData: array} The result as an array.
     */
    public function toArray(): array
    {
        return [
            'isValid' => $this->isValid,
            'errors' => $this->errors,
            'cleanedData' => $this->cleanedData,
        ];
    }

    /**
     * Get the first error message.
     * 
     * @return string|null The first error message or null if no errors.
     */
    public function getFirstError(): ?string
    {
        if (empty($this->errors)) {
            return null;
        }
        
        foreach ($this->errors as $field => $messages) {
            if (is_array($messages) && !empty($messages)) {
                return $messages[0];
            }
        }
        
        return null;
    }

    /**
     * Get all error messages for a specific field.
     * 
     * @param string $field The field name.
     * @return array<int, string> Array of error messages for the field.
     */
    public function getErrorsForField(string $field): array
    {
        return $this->errors[$field] ?? [];
    }

    /**
     * Check if a specific field has errors.
     * 
     * @param string $field The field name.
     * @return bool True if the field has errors, false otherwise.
     */
    public function hasError(string $field): bool
    {
        return isset($this->errors[$field]) && !empty($this->errors[$field]);
    }

    /**
     * Get a cleaned value for a specific field.
     * 
     * @param string $field The field name.
     * @param mixed $default Default value if field doesn't exist.
     * @return mixed The cleaned value or default.
     */
    public function getCleanedValue(string $field, mixed $default = null): mixed
    {
        return $this->cleanedData[$field] ?? $default;
    }

    /**
     * Add an error message for a field.
     * Note: This is primarily used internally during validation.
     * 
     * @param string $field The field name.
     * @param string $message The error message.
     * @return void
     * 
     * @internal This method is intended for internal use during validation.
     */
    public function addError(string $field, string $message): void
    {
        // This method exists for compatibility but shouldn't be used
        // on a readonly result object. It's here for the interface spec.
        throw new \LogicException('ValidationResult is immutable. Errors should be set during construction.');
    }
}
