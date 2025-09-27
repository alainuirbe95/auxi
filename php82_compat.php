<?php
/**
 * PHP 8.2+ Compatibility for CodeIgniter 3.x
 * 
 * This file suppresses dynamic property deprecation warnings
 * that occur when running CodeIgniter 3.x on PHP 8.2+
 */

// Only apply fixes for PHP 8.2+
if (version_compare(PHP_VERSION, '8.2.0', '>=')) {
    // Suppress dynamic property deprecation warnings
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
    
    // Set error display settings
    ini_set('display_errors', 1);
    ini_set('log_errors', 1);
    
    // Suppress specific warnings that cause issues
    set_error_handler(function($severity, $message, $file, $line) {
        // Suppress dynamic property warnings
        if (strpos($message, 'Creation of dynamic property') !== false) {
            return true; // Don't execute PHP internal error handler
        }
        
        // Let other errors pass through
        return false;
    }, E_DEPRECATED);
}
