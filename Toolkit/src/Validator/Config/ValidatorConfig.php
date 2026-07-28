<?php

declare(strict_types=1);

namespace Toolkit\Validator\Config;

/**
 * Configuration class for the Validator.
 * 
 * Contains default messages and language settings for validation rules.
 * 
 * @package Toolkit\Validator\Config
 */
class ValidatorConfig
{
    /**
     * Current language setting.
     * 
     * @var string
     */
    public static string $lang = 'en';

    /**
     * Default error messages for each validation rule.
     * 
     * @var array<string, string>
     */
    public static array $defaultMessages = [
        'required' => 'The {field} field is required.',
        'email' => 'The {field} field must be a valid email address.',
        'min_length' => 'The {field} field must be at least {param} characters long.',
        'max_length' => 'The {field} field must not exceed {param} characters.',
        'numeric' => 'The {field} field must be a valid number.',
        'match' => 'The {field} field must match the {param} field.',
        'regex' => 'The {field} field format is invalid.',
        'in_array' => 'The {field} field must be one of: {param}.',
        'unique' => 'The {field} field must be unique.',
    ];

    /**
     * Get the error message for a specific rule.
     * 
     * @param string $rule The name of the validation rule.
     * @param string $field The name of the field being validated.
     * @param array $params Optional parameters for message replacement.
     * @return string The formatted error message.
     */
    public static function getMessage(string $rule, string $field, array $params = []): string
    {
        $message = self::$defaultMessages[$rule] ?? "The {$field} field is invalid.";
        
        // Replace {field} placeholder
        $message = str_replace('{field}', $field, $message);
        
        // Replace {param} placeholder with first param or empty string
        $paramValue = $params[0] ?? '';
        
        // If params contain an array (for in_array), format it nicely
        if (is_array($paramValue)) {
            $paramValue = implode(', ', array_map(fn($v) => "'{$v}'", $paramValue));
        }
        
        $message = str_replace('{param}', (string)$paramValue, $message);
        
        return $message;
    }

    /**
     * Set the current language.
     * 
     * @param string $lang The language code.
     * @return void
     */
    public static function setLang(string $lang): void
    {
        self::$lang = $lang;
    }

    /**
     * Get the current language.
     * 
     * @return string The current language code.
     */
    public static function getLang(): string
    {
        return self::$lang;
    }

    /**
     * Add or override a default message.
     * 
     * @param string $rule The rule name.
     * @param string $message The message template.
     * @return void
     */
    public static function setMessage(string $rule, string $message): void
    {
        self::$defaultMessages[$rule] = $message;
    }

    /**
     * Get all default messages.
     * 
     * @return array<string, string> The default messages array.
     */
    public static function getAllMessages(): array
    {
        return self::$defaultMessages;
    }
}
