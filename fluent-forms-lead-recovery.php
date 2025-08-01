<?php
/**
 * Plugin Name: Fluent Forms Lead Recovery
 * Plugin URI: https://github.com/yourusername/fluent-forms-lead-recovery
 * Description: Rescue abandoned form submissions by sending partial entries via webhook to your CRM or marketing automation tool.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://yourwebsite.com
 * Text Domain: fluent-forms-lead-recovery
 * Domain Path: /languages
 * License: GPL v3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Requires at least: 5.0
 * Requires PHP: 7.0
 * 
 * Fluent Forms Lead Recovery is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * Fluent Forms Lead Recovery is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main plugin class
 */
final class Fluent_Forms_Lead_Recovery {
    
    /**
     * Plugin version
     * 
     * @var string
     */
    const VERSION = '1.0.0';
    
    /**
     * Plugin instance
     * 
     * @var Fluent_Forms_Lead_Recovery
     */
    private static $instance = null;
    
    /**
     * Plugin admin class
     * 
     * @var Fluent_Forms_Lead_Recovery_Admin
     */
    public $admin;
    
    /**
     * Plugin settings
     * 
     * @var array
     */
    private $settings;
    
    /**
     * Main plugin instance
     * 
     * @return Fluent_Forms_Lead_Recovery
     */
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
    
    /**
     * Private constructor
     */
    private function __construct() {
        $this->define_constants();
        $this->includes();
        $this->init_hooks();
        
        do_action('fflr/loaded');
    }
    
    /**
     * Define plugin constants
     */
    private function define_constants() {
        define('FFLR_VERSION', self::VERSION);
        define('FFLR_PLUGIN_FILE', __FILE__);
        define('FFLR_PLUGIN_BASENAME', plugin_basename(__FILE__));
        define('FFLR_PLUGIN_PATH', plugin_dir_path(__FILE__));
        define('FFLR_PLUGIN_URL', plugin_dir_url(__FILE__));
        define('FFLR_ASSETS_URL', FFLR_PLUGIN_URL . 'assets/');
    }
    
    /**
     * Include required files
     */
    private function includes() {
        // Admin
        require_once FFLR_PLUGIN_PATH . 'includes/class-fluent-forms-lead-recovery-admin.php';
        
        // Core
        require_once FFLR_PLUGIN_PATH . 'includes/class-fluent-forms-lead-recovery-webhook.php';
        require_once FFLR_PLUGIN_PATH . 'includes/class-fluent-forms-lead-recovery-settings.php';
        
        // Helpers
        require_once FFLR_PLUGIN_PATH . 'includes/helpers.php';
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        register_activation_hook(FFLR_PLUGIN_FILE, [$this, 'activate']);
        register_deactivation_hook(FFLR_PLUGIN_FILE, [$this, 'deactivate']);
        
        add_action('plugins_loaded', [$this, 'init_plugin']);
        add_action('admin_notices', [$this, 'admin_notices']);
    }
    
    /**
     * Initialize plugin
     */
    public function init_plugin() {
        // Check if Fluent Forms is active
        if (!$this->is_fluent_forms_active()) {
            return;
        }
        
        // Load text domain
        load_plugin_textdomain('fluent-forms-lead-recovery', false, dirname(FFLR_PLUGIN_BASENAME) . '/languages');
        
        // Initialize classes
        $this->admin = new Fluent_Forms_Lead_Recovery_Admin();
        $this->settings = new Fluent_Forms_Lead_Recovery_Settings();
        new Fluent_Forms_Lead_Recovery_Webhook();
        
        // Fire init action
        do_action('fflr/init');
    }
    
    /**
     * Check if Fluent Forms is active
     * 
     * @return bool
     */
    public function is_fluent_forms_active() {
        return defined('FLUENTFORM_VERSION') || defined('FLUENTFORMPRO_VERSION');
    }
    
    /**
     * Display admin notices
     */
    public function admin_notices() {
        if (!$this->is_fluent_forms_active() && current_user_can('activate_plugins')) {
            $fluent_forms_url = 'https://wordpress.org/plugins/fluentform/';
            $message = sprintf(
                __('Fluent Forms Lead Recovery requires <a href="%s" target="_blank">Fluent Forms</a> plugin to be installed and activated.', 'fluent-forms-lead-recovery'),
                $fluent_forms_url
            );
            
            echo '<div class="notice notice-error is-dismissible"><p>' . wp_kses_post($message) . '</p></div>';
        }
    }
    
    /**
     * Plugin activation
     */
    public function activate() {
        // Create default settings on activation
        $default_settings = [
            'global_webhook_url' => '',
            'enabled_forms' => [],
            'debug_mode' => false,
        ];
        
        update_option('fflr_settings', $default_settings, 'no');
        
        // Flush rewrite rules
        flush_rewrite_rules();
        
        do_action('fflr/activated');
    }
    
    /**
     * Plugin deactivation
     */
    public function deactivate() {
        // Flush rewrite rules
        flush_rewrite_rules();
        
        do_action('fflr/deactivated');
    }
    
    /**
     * Get plugin settings
     * 
     * @param string $key Optional setting key
     * @param mixed $default Default value if setting not found
     * 
     * @return mixed
     */
    public function get_settings($key = null, $default = null) {
        if (empty($this->settings)) {
            $this->settings = get_option('fflr_settings', []);
        }
        
        if ($key && is_string($key)) {
            return isset($this->settings[$key]) ? $this->settings[$key] : $default;
        }
        
        return $this->settings;
    }
}

/**
 * Main instance of the plugin
 * 
 * @return Fluent_Forms_Lead_Recovery
 */
function fflr() {
    return Fluent_Forms_Lead_Recovery::instance();
}

// Initialize the plugin
fflr();