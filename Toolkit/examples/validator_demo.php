<?php

declare(strict_types=1);

/**
 * Validator Demo - Example usage of the Dynamic Form Validator
 * 
 * This file demonstrates three different validation scenarios:
 * 1. User Registration (required, email, min_length, match)
 * 2. User Login (required, min_length)
 * 3. Contact Form (required, email, max_length)
 * 
 * @package Toolkit\Examples
 */

// Bootstrap the Toolkit
require_once __DIR__ . '/../src/Bootstrap.php';

use Toolkit\Validator\Validator;
use Toolkit\Validator\Config\ValidatorConfig;

// Initialize the environment (done automatically by Bootstrap.php)

// Determine if we're running in CLI or web mode
$isCli = php_sapi_name() === 'cli';

/**
 * Output a message based on the environment.
 * 
 * @param string $message The message to output.
 * @param bool $isError Whether this is an error message.
 * @return void
 */
function output(string $message, bool $isError = false): void
{
    global $isCli;
    
    if ($isCli) {
        if ($isError) {
            echo "❌ ERROR: {$message}\n";
        } else {
            echo "✅ {$message}\n";
        }
    } else {
        $class = $isError ? 'error' : 'success';
        echo "<div class='{$class}'>" . htmlspecialchars($message) . "</div>\n";
    }
}

/**
 * Output a section header.
 * 
 * @param string $title The section title.
 * @return void
 */
function section(string $title): void
{
    global $isCli;
    
    if ($isCli) {
        echo "\n" . str_repeat('=', 60) . "\n";
        echo "📋 {$title}\n";
        echo str_repeat('=', 60) . "\n";
    } else {
        echo "<h2>" . htmlspecialchars($title) . "</h2>\n";
    }
}

/**
 * Output validation results.
 * 
 * @param array $result The validation result array.
 * @return void
 */
function displayResults(array $result): void
{
    global $isCli;
    
    if ($result['isValid']) {
        output("Validation PASSED! Data is valid.");
        output("Cleaned Data: " . json_encode($result['cleanedData'], JSON_PRETTY_PRINT));
    } else {
        output("Validation FAILED!", true);
        
        foreach ($result['errors'] as $field => $messages) {
            foreach ($messages as $message) {
                output("{$field}: {$message}", true);
            }
        }
    }
}

// ============================================================================
// SCENARIO 1: User Registration
// Rules: required, email, min_length, match (password confirmation)
// ============================================================================

section('Scenario 1: User Registration');

$validator = new Validator();

$registrationRules = [
    'username' => 'required|min_length:3',
    'email' => 'required|email',
    'password' => 'required|min_length:8',
    'password_confirm' => 'required|match:password',
];

// Test Case 1A: Valid registration data
output('Test 1A: Valid registration data');
$validRegistrationData = [
    'username' => 'johndoe',
    'email' => 'john@example.com',
    'password' => 'securepass123',
    'password_confirm' => 'securepass123',
];

$result = $validator->validate($validRegistrationData, $registrationRules);
displayResults($result->toArray());

// Test Case 1B: Invalid registration data (multiple errors)
output('Test 1B: Invalid registration data (multiple errors)');
$invalidRegistrationData = [
    'username' => 'jo',  // Too short
    'email' => 'invalid-email',  // Invalid email
    'password' => 'short',  // Too short
    'password_confirm' => 'different',  // Doesn't match
];

$result = $validator->validate($invalidRegistrationData, $registrationRules);
displayResults($result->toArray());

// Test Case 1C: Missing required fields
output('Test 1C: Missing required fields');
$missingData = [
    'username' => '',
    'email' => null,
];

$result = $validator->validate($missingData, $registrationRules);
displayResults($result->toArray());

// ============================================================================
// SCENARIO 2: User Login
// Rules: required, min_length
// ============================================================================

section('Scenario 2: User Login');

$loginRules = [
    'username' => 'required|min_length:3',
    'password' => 'required|min_length:6',
];

// Test Case 2A: Valid login data
output('Test 2A: Valid login data');
$validLoginData = [
    'username' => 'admin',
    'password' => 'admin123',
];

$result = $validator->validate($validLoginData, $loginRules);
displayResults($result->toArray());

// Test Case 2B: Invalid login data
output('Test 2B: Invalid login data');
$invalidLoginData = [
    'username' => 'ab',  // Too short
    'password' => '12',  // Too short
];

$result = $validator->validate($invalidLoginData, $loginRules);
displayResults($result->toArray());

// ============================================================================
// SCENARIO 3: Contact Form
// Rules: required, email, max_length
// ============================================================================

section('Scenario 3: Contact Form');

$contactRules = [
    'name' => 'required|max_length:50',
    'email' => 'required|email',
    'subject' => 'required|max_length:100',
    'message' => 'required|max_length:500',
];

// Test Case 3A: Valid contact form
output('Test 3A: Valid contact form');
$validContactData = [
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'subject' => 'Inquiry about services',
    'message' => 'Hello, I would like to know more about your services.',
];

$result = $validator->validate($validContactData, $contactRules);
displayResults($result->toArray());

// Test Case 3B: Message too long
output('Test 3B: Message too long');
$invalidContactData = [
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'subject' => 'Test',
    'message' => str_repeat('A', 501),  // 501 characters, exceeds limit
];

$result = $validator->validate($invalidContactData, $contactRules);
displayResults($result->toArray());

// ============================================================================
// SCENARIO 4: Advanced Features
// Using numeric, in_array, and regex rules
// ============================================================================

section('Scenario 4: Advanced Validation (numeric, in_array, regex)');

$advancedRules = [
    'age' => 'required|numeric',
    'role' => 'required|in_array:admin,user,guest',
    'phone' => 'regex:/^\+?[0-9]{10,15}$/',
];

// Test Case 4A: Valid advanced data
output('Test 4A: Valid advanced data');
$validAdvancedData = [
    'age' => '25',
    'role' => 'admin',
    'phone' => '+1234567890',
];

$result = $validator->validate($validAdvancedData, $advancedRules);
displayResults($result->toArray());

// Test Case 4B: Invalid advanced data
output('Test 4B: Invalid advanced data');
$invalidAdvancedData = [
    'age' => 'not-a-number',
    'role' => 'superuser',  // Not in allowed list
    'phone' => 'invalid',
];

$result = $validator->validate($invalidAdvancedData, $advancedRules);
displayResults($result->toArray());

// ============================================================================
// SCENARIO 5: Custom Error Messages
// ============================================================================

section('Scenario 5: Custom Error Messages');

$customMessages = [
    'email.required' => 'Please provide your email address.',
    'email.email' => 'The email address format is incorrect.',
    'password.min_length' => 'Password must be at least 8 characters for security.',
];

$validatorWithCustomMessages = new Validator();
$validatorWithCustomMessages->setMessages($customMessages);

output('Test 5: Validation with custom messages');
$customMessageData = [
    'email' => 'not-an-email',
    'password' => '123',
];

$result = $validatorWithCustomMessages->validate(
    $customMessageData,
    ['email' => 'required|email', 'password' => 'min_length:8'],
    $customMessages
);

if (!$result->isValid) {
    output('Custom messages are working:');
    foreach ($result->errors as $field => $messages) {
        foreach ($messages as $message) {
            output("  → {$message}", true);
        }
    }
}

// ============================================================================
// SCENARIO 6: ValidationResult Methods
// ============================================================================

section('Scenario 6: ValidationResult Methods');

output('Test 6: Demonstrating ValidationResult methods');

$testData = ['email' => 'invalid'];
$testRules = ['email' => 'required|email'];

$result = $validator->validate($testData, $testRules);

output('isValid: ' . ($result->isValid ? 'true' : 'false'));
output('getFirstError(): ' . ($result->getFirstError() ?? 'null'));
output('hasError("email"): ' . ($result->hasError('email') ? 'true' : 'false'));
output('getErrorsForField("email"): ' . json_encode($result->getErrorsForField('email')));
output('toArray(): ' . json_encode($result->toArray(), JSON_PRETTY_PRINT));

// ============================================================================
// SCENARIO 7: New Advanced Rules (url, ip, date, between, alpha, alpha_num)
// ============================================================================

section('Scenario 7: New Advanced Rules (url, ip, date, between, alpha, alpha_num)');

$newAdvancedRules = [
    'website' => 'required|url',
    'ip_address' => 'required|ip:ipv4',
    'birth_date' => 'required|date:Y-m-d',
    'score' => 'required|between:0,100',
    'first_name' => 'required|alpha:space',
    'username2' => 'required|alpha_num',
];

// Test Case 7A: Valid new advanced data
output('Test 7A: Valid new advanced data');
$validNewAdvancedData = [
    'website' => 'https://example.com/path?query=1',
    'ip_address' => '192.168.1.1',
    'birth_date' => '1990-05-15',
    'score' => '85',
    'first_name' => 'John Doe',
    'username2' => 'john123',
];

$result = $validator->validate($validNewAdvancedData, $newAdvancedRules);
displayResults($result->toArray());

// Test Case 7B: Invalid new advanced data
output('Test 7B: Invalid new advanced data');
$invalidNewAdvancedData = [
    'website' => 'not-a-url',
    'ip_address' => '999.999.999.999',
    'birth_date' => 'invalid-date',
    'score' => '150',
    'first_name' => 'John123',
    'username2' => 'john_@#$',
];

$result = $validator->validate($invalidNewAdvancedData, $newAdvancedRules);
displayResults($result->toArray());

// ============================================================================
// SCENARIO 8: Nested Data Validation with ArrayHelper
// ============================================================================

section('Scenario 8: Nested Data Validation');

use Toolkit\Validator\Helpers\ArrayHelper;

output('Test 8: Testing ArrayHelper with nested arrays');

$nestedArray = [
    'user' => [
        'profile' => [
            'name' => 'John',
            'email' => 'john@example.com'
        ],
        'settings' => [
            'theme' => 'dark',
            'notifications' => true
        ]
    ],
    'posts' => [
        ['title' => 'First Post', 'views' => 100],
        ['title' => 'Second Post', 'views' => 200]
    ]
];

output('Nested array structure created');
output('Accessing user.profile.name: ' . ArrayHelper::get($nestedArray, 'user.profile.name'));
output('Accessing user.settings.theme: ' . ArrayHelper::get($nestedArray, 'user.settings.theme'));
output('Accessing posts.0.title: ' . ArrayHelper::get($nestedArray, 'posts.0.title'));
output('Accessing posts.1.views: ' . ArrayHelper::get($nestedArray, 'posts.1.views'));
output('Accessing non-existent key: ' . var_export(ArrayHelper::get($nestedArray, 'user.profile.age'), true));

// ============================================================================
// SCENARIO 9: Chaining Multiple Rules on Same Field
// ============================================================================

section('Scenario 9: Complex Validation Chains');

$complexRules = [
    'product_code' => 'required|alpha_num|min_length:5|max_length:10',
    'price' => 'required|numeric|between:0.01,9999.99',
    'description' => 'max_length:1000',
];

output('Test 9A: Valid complex data');
$validComplexData = [
    'product_code' => 'ABC123',
    'price' => '99.99',
    'description' => 'A great product!',
];

$result = $validator->validate($validComplexData, $complexRules);
displayResults($result->toArray());

output('Test 9B: Invalid complex data (multiple rule failures)');
$invalidComplexData = [
    'product_code' => 'AB!@',  // Not alphanumeric, too short
    'price' => '-50',  // Below minimum
    'description' => str_repeat('Long description ', 100),  // Too long
];

$result = $validator->validate($invalidComplexData, $complexRules);
displayResults($result->toArray());

// ============================================================================
// Final Summary
// ============================================================================

section('Demo Complete');

if ($isCli) {
    echo "\nAll validation scenarios have been executed.\n";
    echo "Review the output above to see how the validator handles different cases.\n\n";
    echo "📊 Summary:\n";
    echo "  - Original scenarios: 6\n";
    echo "  - New scenarios added: 3\n";
    echo "  - Total test cases: 15+\n";
    echo "  - New rules added: url, ip, date, between, alpha, alpha_num\n";
    echo "  - Total available rules: 15\n\n";
} else {
    echo '<p>All validation scenarios have been executed. Review the output above.</p>';
}

// If running in CLI, exit cleanly
if ($isCli) {
    exit(0);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validator Demo</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
        h2 { color: #333; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .success { color: #28a745; background: #d4edda; padding: 10px; margin: 10px 0; border-radius: 5px; }
        .error { color: #dc3545; background: #f8d7da; padding: 10px; margin: 10px 0; border-radius: 5px; }
        pre { background: #f4f4f4; padding: 15px; border-radius: 5px; overflow-x: auto; }
    </style>
</head>
<body>
<?php
// The HTML output is handled by the functions above when not in CLI mode
?>
</body>
</html>
