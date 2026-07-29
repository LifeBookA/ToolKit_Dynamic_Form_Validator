<?php

declare(strict_types=1);

/**
 * Bootstrap class for initializing the Toolkit environment.
 * 
 * Sets up error reporting, default timezone, and registers the autoloader.
 * 
 * @package Toolkit
 */

// Define the base path for the Toolkit
if (!defined('TOOLKIT_BASE_PATH')) {
    define('TOOLKIT_BASE_PATH', dirname(__DIR__));
}

// Require the Autoloader class explicitly before using it
require_once __DIR__ . '/Autoloader.php';

namespace Toolkit;

class Bootstrap
{
    /**
     * Initialize the Toolkit environment.
     * 
     * @return void
     */
    public static function init(): void
    {
        // Set error reporting to maximum
        error_reporting(E_ALL);
        ini_set('display_errors', '1');
        
        // Set default timezone
        date_default_timezone_set('UTC');
        
        // Register the autoloader
        Autoloader::register();
    }
}
