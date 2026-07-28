<?php

declare(strict_types=1);

namespace Toolkit\Validator\Exceptions;

use Exception;

/**
 * Exception class for validation-related errors.
 * 
 * Thrown when there are issues with validation rules or configuration,
 * not for data validation failures (those are handled by ValidationResult).
 * 
 * @package Toolkit\Validator\Exceptions
 */
class ValidationException extends Exception
{
    /**
     * Create a new validation exception.
     * 
     * @param string $message The exception message.
     * @param int $code The exception code.
     * @param Exception|null $previous Previous exception if any.
     */
    public function __construct(
        string $message = 'Validation error occurred',
        int $code = 0,
        ?Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
