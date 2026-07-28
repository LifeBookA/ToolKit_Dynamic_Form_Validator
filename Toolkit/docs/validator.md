# Dynamic Form Validator Documentation

## 📖 Introduction

The Dynamic Form Validator is a powerful, extensible validation system built for PHP 8.2+. It provides a fluent interface for validating form data with support for multiple validation rules, custom error messages, and easy extensibility.

## 🎯 Features

- **9 Built-in Validation Rules**: required, email, min_length, max_length, numeric, match, regex, in_array, unique
- **Fluent Interface**: Chainable methods for easy configuration
- **Custom Error Messages**: Override default messages per field or rule
- **Data Sanitization**: Automatic trimming of string values
- **Extensible**: Add custom validation rules easily
- **No Dependencies**: Pure PHP, no external libraries required

## 🚀 Quick Start

```php
<?php

require_once '/path/to/Toolkit/src/Bootstrap.php';

use Toolkit\Bootstrap;
use Toolkit\Validator\Validator;

Bootstrap::init();

$validator = new Validator();

$data = [
    'username' => 'johndoe',
    'email' => 'john@example.com',
];

$rules = [
    'username' => 'required|min_length:3',
    'email' => 'required|email',
];

$result = $validator->validate($data, $rules);

if ($result->isValid) {
    // Process valid data
    print_r($result->cleanedData);
} else {
    // Handle errors
    foreach ($result->errors as $field => $messages) {
        echo "{$field}: " . implode(', ', $messages) . "\n";
    }
}
```

## 📋 Available Rules

### 1. Required Rule
Ensures a field is not empty.

```php
'username' => 'required'
```

### 2. Email Rule
Validates that a field contains a valid email address.

```php
'email' => 'email'
```

### 3. Min Length Rule
Ensures a string has at least the specified number of characters.

```php
'password' => 'min_length:8'
```

### 4. Max Length Rule
Ensures a string does not exceed the specified number of characters.

```php
'title' => 'max_length:100'
```

### 5. Numeric Rule
Validates that a value is numeric.

```php
'age' => 'numeric'
```

### 6. Match Rule
Ensures a field matches another field's value (useful for password confirmation).

```php
'password_confirm' => 'match:password'
```

### 7. Regex Rule
Validates against a regular expression pattern.

```php
'phone' => 'regex:/^\+?[0-9]{10,15}$/'
```

### 8. In Array Rule
Ensures a value is one of the allowed values.

```php
'role' => 'in_array:admin,user,guest'
```

### 9. Unique Rule
Validates that a value is unique. Can check against a JSON file or use a callback.

```php
// Check against JSON file
'email' => 'unique:/path/to/users.json:email'

// Check using callback
$emailValidator = function($value) {
    // Return true if value exists (making it not unique)
    return checkIfEmailExists($value);
};
// Usage requires custom rule implementation
```

## 🔧 Advanced Usage

### Combining Rules

Rules can be combined using the pipe (`|`) character:

```php
$rules = [
    'username' => 'required|min_length:3|max_length:20',
    'email' => 'required|email',
    'password' => 'required|min_length:8|max_length:128',
];
```

### Custom Error Messages

Override default messages for specific fields and rules:

```php
$customMessages = [
    'email.required' => 'Please provide your email address.',
    'email.email' => 'The email format is incorrect.',
    'password.min_length' => 'Password must be at least 8 characters.',
];

$validator->setMessages($customMessages);
```

### Adding Custom Rules

Create a custom rule by implementing the `RuleInterface`:

```php
use Toolkit\Validator\Contracts\RuleInterface;

class UppercaseRule implements RuleInterface
{
    public function validate($value, array $params = []): bool
    {
        if (!is_string($value)) {
            return true;
        }
        return ctype_upper($value);
    }

    public function getMessage(string $field, array $params = []): string
    {
        return "The {$field} field must be all uppercase.";
    }
}

// Register the custom rule
$validator->addRule('uppercase', new UppercaseRule());

// Use it
$rules = [
    'code' => 'required|uppercase',
];
```

### ValidationResult Methods

The `ValidationResult` class provides several useful methods:

```php
$result = $validator->validate($data, $rules);

// Check if validation passed
$result->isValid;  // bool

// Get all errors
$result->errors;  // array

// Get cleaned/sanitized data
$result->cleanedData;  // array

// Get first error message
$result->getFirstError();  // ?string

// Get errors for a specific field
$result->getErrorsForField('email');  // array

// Check if a field has errors
$result->hasError('email');  // bool

// Get cleaned value for a field
$result->getCleanedValue('email');  // mixed

// Convert to array
$result->toArray();  // array
```

## ⚙️ Configuration

### ValidatorConfig Class

The `ValidatorConfig` class manages default messages and language settings:

```php
use Toolkit\Validator\Config\ValidatorConfig;

// Get a default message
$message = ValidatorConfig::getMessage('required', 'username');

// Set a custom default message
ValidatorConfig::setMessage('required', 'The {field} is mandatory.');

// Change language (for future i18n support)
ValidatorConfig::setLang('en');
```

### Message Placeholders

Default messages support the following placeholders:

- `{field}` - Replaced with the field name
- `{param}` - Replaced with the rule parameter

## 📁 File Structure

```
src/Validator/
├── Contracts/
│   └── RuleInterface.php      # Interface for all rules
├── Rules/
│   ├── RequiredRule.php       # Required validation
│   ├── EmailRule.php          # Email validation
│   ├── MinLengthRule.php      # Minimum length validation
│   ├── MaxLengthRule.php      # Maximum length validation
│   ├── NumericRule.php        # Numeric validation
│   ├── MatchRule.php          # Field matching validation
│   ├── RegexRule.php          # Regex pattern validation
│   ├── InArrayRule.php        # Allowed values validation
│   └── UniqueRule.php         # Uniqueness validation
├── Exceptions/
│   └── ValidationException.php # Exception for validation errors
├── Config/
│   └── ValidatorConfig.php    # Configuration and default messages
├── Helpers/
│   └── ArrayHelper.php        # Array utility functions
├── Result/
│   └── ValidationResult.php   # Validation result container
└── Validator.php              # Main validator class
```

## 🧪 Examples

See `examples/validator_demo.php` for complete working examples including:

1. User Registration Form
2. User Login Form
3. Contact Form
4. Advanced Validation (numeric, in_array, regex)
5. Custom Error Messages
6. ValidationResult Methods

## 🐛 Error Handling

The validator throws `ValidationException` for configuration errors:

```php
use Toolkit\Validator\Exceptions\ValidationException;

try {
    $result = $validator->validate($data, ['field' => 'invalid_rule']);
} catch (ValidationException $e) {
    echo "Configuration error: " . $e->getMessage();
}
```

## 📝 Best Practices

1. **Always validate on the server side** - Client-side validation is for UX only
2. **Use specific rules** - Combine multiple rules for comprehensive validation
3. **Provide clear error messages** - Help users understand what went wrong
4. **Sanitize input** - The validator automatically trims strings
5. **Check validation result** - Always check `$result->isValid` before processing data

## 🔗 Related Documentation

- [Main README](../README.md)
- [Example Code](../examples/validator_demo.php)
