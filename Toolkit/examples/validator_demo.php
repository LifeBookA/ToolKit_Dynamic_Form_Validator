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
// Final Summary
// ============================================================================

section('Demo Complete');

if ($isCli) {
    echo "\nAll validation scenarios have been executed.\n";
    echo "Review the output above to see how the validator handles different cases.\n\n";
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
