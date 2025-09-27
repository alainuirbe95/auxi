<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * PHP 8.2+ Compatibility Hook
 * 
 * This hook suppresses dynamic property deprecation warnings
 * for CodeIgniter 3.x compatibility with PHP 8.2+
 */

class Php82_compatibility {
    
    public function __construct() {
        // Only apply compatibility fixes for PHP 8.2+
        if (version_compare(PHP_VERSION, '8.2.0', '>=')) {
            $this->apply_compatibility_fixes();
        }
    }
    
    private function apply_compatibility_fixes() {
        // Suppress dynamic property deprecation warnings
        error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
        
        // Set ini settings to suppress deprecation warnings
        ini_set('display_errors', 1);
        ini_set('log_errors', 1);
        
        // Add compatibility for dynamic properties
        if (!class_exists('AllowDynamicProperties', false)) {
            // Create a compatibility class for PHP 8.2+
            eval('
                #[Attribute]
                class AllowDynamicProperties {
                    public function __construct() {}
                }
            ');
        }
    }
}

// Initialize compatibility fixes
new Php82_compatibility();
