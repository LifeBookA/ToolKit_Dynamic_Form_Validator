# Toolkit - PHP Utility Library

A comprehensive PHP utility library providing essential tools and components for modern PHP development.

## 📦 Overview

Toolkit is a collection of reusable PHP components designed to simplify common development tasks. Built with PHP 8.2+ and following modern best practices, it requires no external dependencies.

## 🚀 Features

- **Dynamic Form Validator** - A powerful, extensible validation system with support for multiple rules
- **Autoloader** - PSR-4 compatible autoloading without Composer
- **Bootstrap** - Easy environment setup and configuration

## 📁 Project Structure

```
Toolkit/
├── src/
│   ├── Autoloader.php      # Custom autoloader for the Toolkit namespace
│   ├── Bootstrap.php       # Environment initialization
│   └── Validator/          # Validation module
│       ├── Contracts/
│       │   └── RuleInterface.php
│       ├── Rules/
│       │   ├── RequiredRule.php
│       │   ├── EmailRule.php
│       │   ├── MinLengthRule.php
│       │   ├── MaxLengthRule.php
│       │   ├── NumericRule.php
│       │   ├── MatchRule.php
│       │   ├── RegexRule.php
│       │   ├── InArrayRule.php
│       │   └── UniqueRule.php
│       ├── Exceptions/
│       │   └── ValidationException.php
│       ├── Config/
│       │   └── ValidatorConfig.php
│       ├── Helpers/
│       │   └── ArrayHelper.php
│       ├── Result/
│       │   └── ValidationResult.php
│       └── Validator.php
├── examples/
│   └── validator_demo.php
├── docs/
│   └── validator.md
└── README.md
```

## 🛠 Installation

1. Clone or download the Toolkit repository
2. Include the Bootstrap file in your project:

```php
require_once '/path/to/Toolkit/src/Bootstrap.php';

use Toolkit\Bootstrap;
Bootstrap::init();
```

## 📖 Usage

### Quick Start with Validator

```php
use Toolkit\Validator\Validator;

$validator = new Validator();

$data = [
    'username' => 'johndoe',
    'email' => 'john@example.com',
    'password' => 'securepass123',
];

$rules = [
    'username' => 'required|min_length:3',
    'email' => 'required|email',
    'password' => 'required|min_length:8',
];

$result = $validator->validate($data, $rules);

if ($result->isValid) {
    // Data is valid
    echo "Validation passed!";
} else {
    // Handle errors
    foreach ($result->errors as $field => $messages) {
        foreach ($messages as $message) {
            echo "{$field}: {$message}\n";
        }
    }
}
```

### Available Validation Rules

| Rule | Description | Example |
|------|-------------|---------|
| `required` | Field must not be empty | `'field' => 'required'` |
| `email` | Field must be a valid email | `'email' => 'email'` |
| `min_length:n` | Minimum string length | `'password' => 'min_length:8'` |
| `max_length:n` | Maximum string length | `'title' => 'max_length:100'` |
| `numeric` | Field must be numeric | `'age' => 'numeric'` |
| `match:field` | Must match another field | `'confirm' => 'match:password'` |
| `regex:/pattern/` | Must match regex pattern | `'phone' => 'regex:/^\d+$/'` |
| `in_array:a,b,c` | Must be in allowed values | `'role' => 'in_array:admin,user'` |
| `unique:path` | Must be unique (file/callback) | `'email' => 'unique:data.json'` |

### Combining Rules

Rules can be combined using the pipe (`|`) character:

```php
$rules = [
    'email' => 'required|email',
    'username' => 'required|min_length:3|max_length:20',
];
```

### Custom Error Messages

```php
$customMessages = [
    'email.required' => 'Please provide your email address.',
    'email.email' => 'The email format is invalid.',
];

$validator->setMessages($customMessages);
```

## 📚 Documentation

For detailed documentation, see:
- [Validator Documentation](docs/validator.md)
- [Example Code](examples/validator_demo.php)

## 🧪 Running Examples

Run the demo script to see the validator in action:

```bash
# CLI mode
php examples/validator_demo.php

# Web mode
# Access via web server
```

## 🔧 Requirements

- PHP 8.2 or higher
- No external dependencies required

## 📝 License

This project is open-source and available under the MIT License.

## 🤝 Contributing

Contributions are welcome! Please feel free to submit pull requests or create issues for bugs and feature requests.

---

**Toolkit** - Making PHP development easier, one component at a time.
