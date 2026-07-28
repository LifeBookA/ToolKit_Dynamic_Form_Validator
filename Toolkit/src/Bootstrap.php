<?php

declare(strict_types=1);

namespace Toolkit;

/**
 * Bootstrap class for initializing the Toolkit environment.
 * 
 * Sets up error reporting, default timezone, and registers the autoloader.
 * 
 * @package Toolkit
 */
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
