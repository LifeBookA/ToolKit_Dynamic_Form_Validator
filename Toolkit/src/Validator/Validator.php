<?php

declare(strict_types=1);

namespace Toolkit\Validator;

use Toolkit\Validator\Contracts\RuleInterface;
use Toolkit\Validator\Exceptions\ValidationException;
use Toolkit\Validator\Result\ValidationResult;
use Toolkit\Validator\Config\ValidatorConfig;
use Toolkit\Validator\Helpers\ArrayHelper;
use Toolkit\Validator\Rules\MatchRule;

/**
 * Main Validator class for dynamic form validation.
 * 
 * Provides a fluent interface for adding validation rules and
 * validating data against those rules.
 * 
 * @package Toolkit\Validator
 */
class Validator
{
    /**
     * Array of registered validation rules.
     * 
     * @var array<string, RuleInterface>
     */
    protected array $rules = [];

    /**
     * Custom error messages.
     * 
     * @var array<string, string>
     */
    protected array $messages = [];

    /**
     * Configuration instance.
     * 
     * @var ValidatorConfig
     */
    protected ValidatorConfig $config;

    /**
     * Built-in rule mappings (rule name => class).
     * 
     * @var array<string, string>
     */
    protected static array $builtInRules = [
        'required' => Rules\RequiredRule::class,
        'email' => Rules\EmailRule::class,
        'min_length' => Rules\MinLengthRule::class,
        'max_length' => Rules\MaxLengthRule::class,
        'numeric' => Rules\NumericRule::class,
        'match' => Rules\MatchRule::class,
        'regex' => Rules\RegexRule::class,
        'in_array' => Rules\InArrayRule::class,
        'unique' => Rules\UniqueRule::class,
        'url' => Rules\UrlRule::class,
        'ip' => Rules\IpRule::class,
        'date' => Rules\DateRule::class,
        'between' => Rules\BetweenRule::class,
        'alpha' => Rules\AlphaRule::class,
        'alpha_num' => Rules\AlphaNumRule::class,
    ];

    /**
     * Create a new Validator instance.
     * 
     * @param ValidatorConfig|null $config Optional configuration instance.
     */
    public function __construct(?ValidatorConfig $config = null)
    {
        $this->config = $config ?? new ValidatorConfig();
        
        // Register built-in rules by default
        foreach (self::$builtInRules as $name => $class) {
            $this->rules[$name] = new $class();
        }
    }

    /**
     * Add a custom validation rule.
     * 
     * @param string $name The name of the rule.
     * @param RuleInterface $rule The rule instance.
     * @return self
     */
    public function addRule(string $name, RuleInterface $rule): self
    {
        $this->rules[$name] = $rule;
        return $this;
    }

    /**
     * Set custom error messages.
     * 
     * @param array<string, string> $messages Associative array of field.rule => message.
     * @return self
     */
    public function setMessages(array $messages): self
    {
        $this->messages = array_merge($this->messages, $messages);
        return $this;
    }

    /**
     * Validate data against the specified rules.
     * 
     * @param array<string, mixed> $data The data to validate.
     * @param array<string, string|array> $rules Array of field => rule(s).
     * @param array<string, string> $messages Optional custom messages.
     * @return ValidationResult The validation result.
     * @throws ValidationException If an invalid rule is specified.
     */
    public function validate(array $data, array $rules, array $messages = []): ValidationResult
    {
        $errors = [];
        $cleanedData = [];
        
        // Merge custom messages
        $this->messages = array_merge($this->messages, $messages);
        
        foreach ($rules as $field => $fieldRules) {
            // Get the raw value from data
            $rawValue = ArrayHelper::dot($data, $field);
            
            // Sanitize the value
            $cleanedValue = $this->sanitize($rawValue);
            $cleanedData[$field] = $cleanedValue;
            
            // Normalize rules to array
            $ruleStrings = is_string($fieldRules) ? explode('|', $fieldRules) : $fieldRules;
            
            foreach ($ruleStrings as $ruleString) {
                // Parse the rule string
                [$ruleName, $params] = $this->parseRule($ruleString);
                
                // Check if rule exists
                if (!isset($this->rules[$ruleName])) {
                    throw new ValidationException("Validation rule '{$ruleName}' does not exist.");
                }
                
                $rule = $this->rules[$ruleName];
                
                // Special handling for match rule - need to get the other field's value
                if ($rule instanceof MatchRule && isset($params[0])) {
                    $matchField = $params[0];
                    $matchValue = ArrayHelper::dot($data, $matchField);
                    $params[1] = $matchValue; // Pass the actual value to compare
                }
                
                // Validate the value
                if (!$rule->validate($cleanedValue, $params)) {
                    // Get error message
                    $errorMessage = $this->getErrorMessage($field, $ruleName, $params);
                    
                    if (!isset($errors[$field])) {
                        $errors[$field] = [];
                    }
                    
                    $errors[$field][] = $errorMessage;
                }
            }
        }
        
        return new ValidationResult(
            empty($errors),
            $errors,
            $cleanedData
        );
    }

    /**
     * Parse a rule string into name and parameters.
     * 
     * Examples:
     *   'required' => ['required', []]
     *   'min_length:5' => ['min_length', [5]]
     *   'in_array:admin,user' => ['in_array', [['admin', 'user']]]
     * 
     * @param string $rule The rule string.
     * @return array{0: string, 1: array} Array containing rule name and parameters.
     */
    protected function parseRule(string $rule): array
    {
        $parts = explode(':', $rule, 2);
        $ruleName = trim($parts[0]);
        $params = [];
        
        if (isset($parts[1]) && $parts[1] !== '') {
            $paramString = $parts[1];
            
            // Handle special cases like in_array with comma-separated values
            if ($ruleName === 'in_array') {
                $params[] = explode(',', $paramString);
            } elseif ($ruleName === 'regex') {
                // Regex patterns may contain commas, so we don't split them
                $params[] = $paramString;
            } else {
                // Split by comma for multiple parameters
                $params = array_map('trim', explode(',', $paramString));
            }
        }
        
        return [$ruleName, $params];
    }

    /**
     * Sanitize a value.
     * 
     * Currently trims strings, but can be extended for more complex sanitization.
     * 
     * @param mixed $value The value to sanitize.
     * @return mixed The sanitized value.
     */
    protected function sanitize(mixed $value): mixed
    {
        if (is_string($value)) {
            return trim($value);
        }
        
        return $value;
    }

    /**
     * Get the error message for a validation failure.
     * 
     * @param string $field The field name.
     * @param string $ruleName The rule name.
     * @param array $params The rule parameters.
     * @return string The error message.
     */
    protected function getErrorMessage(string $field, string $ruleName, array $params): string
    {
        // Check for custom message with field.rule format
        $customKey = "{$field}.{$ruleName}";
        if (isset($this->messages[$customKey])) {
            return $this->replacePlaceholders($this->messages[$customKey], $field, $params);
        }
        
        // Check for custom message with just field
        if (isset($this->messages[$field])) {
            return $this->replacePlaceholders($this->messages[$field], $field, $params);
        }
        
        // Use default message from config
        return ValidatorConfig::getMessage($ruleName, $field, $params);
    }

    /**
     * Replace placeholders in an error message.
     * 
     * @param string $message The message template.
     * @param string $field The field name.
     * @param array $params The rule parameters.
     * @return string The formatted message.
     */
    protected function replacePlaceholders(string $message, string $field, array $params): string
    {
        $message = str_replace('{field}', $field, $message);
        
        $paramValue = $params[0] ?? '';
        if (is_array($paramValue)) {
            $paramValue = implode(', ', array_map(fn($v) => "'{$v}'", $paramValue));
        }
        
        $message = str_replace('{param}', (string)$paramValue, $message);
        
        return $message;
    }

    /**
     * Get all registered rules.
     * 
     * @return array<string, RuleInterface>
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    /**
     * Check if a rule exists.
     * 
     * @param string $name The rule name.
     * @return bool True if the rule exists, false otherwise.
     */
    public function hasRule(string $name): bool
    {
        return isset($this->rules[$name]);
    }
}
