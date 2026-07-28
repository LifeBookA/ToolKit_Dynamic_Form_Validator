<?php

declare(strict_types=1);

namespace Toolkit;

/**
 * Autoloader for the Toolkit project.
 * 
 * Registers a custom autoloader that maps the Toolkit namespace to the src/ directory.
 * 
 * @package Toolkit
 */
class Autoloader
{
    /**
     * Register the autoloader with SPL.
     * 
     * @return void
     */
    public static function register(): void
    {
        spl_autoload_register(function (string $class): void {
            // Only handle classes in the Toolkit namespace
            $prefix = 'Toolkit\\';
            
            if (strpos($class, $prefix) !== 0) {
                return;
            }
            
            // Remove the prefix from the class name
            $relativeClass = substr($class, strlen($prefix));
            
            // Replace namespace separators with directory separators
            $file = __DIR__ . '/' . str_replace('\\', '/', $relativeClass) . '.php';
            
            // If the file exists, require it
            if (file_exists($file)) {
                require_once $file;
            }
        });
    }
}
