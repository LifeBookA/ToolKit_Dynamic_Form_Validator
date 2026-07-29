<?php

declare(strict_types=1);

namespace Toolkit\Validator\Helpers;

/**
 * Helper class for array operations.
 * 
 * Provides utility methods for working with arrays, including
 * dot notation access and error formatting.
 * 
 * @package Toolkit\Validator\Helpers
 */
class ArrayHelper
{
    /**
     * Get a value from an array using dot notation.
     * 
     * Example: $array = ['user' => ['name' => 'John']]
     *          ArrayHelper::get($array, 'user.name') returns 'John'
     * 
     * @param array $array The array to search.
     * @param string $key The key in dot notation (e.g., 'user.name').
     * @param mixed $default Default value if key is not found.
     * @return mixed The value at the specified key or the default value.
     */
    public static function get(array $array, string $key, mixed $default = null): mixed
    {
        return self::dot($array, $key, $default);
    }

    /**
     * Get a value from an array using dot notation.
     * 
     * Example: $array = ['user' => ['name' => 'John']]
     *          ArrayHelper::dot($array, 'user.name') returns 'John'
     * 
     * @param array $array The array to search.
     * @param string $key The key in dot notation (e.g., 'user.name').
     * @param mixed $default Default value if key is not found.
     * @return mixed The value at the specified key or the default value.
     */
    public static function dot(array $array, string $key, mixed $default = null): mixed
    {
        if ($key === '') {
            return $array;
        }
        
        $keys = explode('.', $key);
        $current = $array;
        
        foreach ($keys as $k) {
            if (!is_array($current) || !array_key_exists($k, $current)) {
                return $default;
            }
            $current = $current[$k];
        }
        
        return $current;
    }

    /**
     * Set a value in an array using dot notation.
     * 
     * Example: $array = []
     *          ArrayHelper::setDot($array, 'user.name', 'John')
     *          $array becomes ['user' => ['name' => 'John']]
     * 
     * @param array $array The array to modify (passed by reference).
     * @param string $key The key in dot notation.
     * @param mixed $value The value to set.
     * @return void
     */
    public static function setDot(array &$array, string $key, mixed $value): void
    {
        $keys = explode('.', $key);
        $current = &$array;
        
        while (count($keys) > 1) {
            $k = array_shift($keys);
            
            if (!isset($current[$k]) || !is_array($current[$k])) {
                $current[$k] = [];
            }
            
            $current = &$current[$k];
        }
        
        $current[array_shift($keys)] = $value;
    }

    /**
     * Flatten an array of errors into a single string.
     * 
     * @param array $errors Associative array of field => error messages.
     * @param string $separator The separator between error messages.
     * @return string The flattened error string.
     */
    public static function flattenErrors(array $errors, string $separator = "\n"): string
    {
        $flatMessages = [];
        
        foreach ($errors as $field => $messages) {
            if (is_array($messages)) {
                foreach ($messages as $message) {
                    $flatMessages[] = "{$field}: {$message}";
                }
            } else {
                $flatMessages[] = "{$field}: {$messages}";
            }
        }
        
        return implode($separator, $flatMessages);
    }

    /**
     * Check if an array is associative (not sequential).
     * 
     * @param array $array The array to check.
     * @return bool True if the array is associative, false otherwise.
     */
    public static function isAssociative(array $array): bool
    {
        if (empty($array)) {
            return false;
        }
        
        return array_keys($array) !== range(0, count($array) - 1);
    }
}
