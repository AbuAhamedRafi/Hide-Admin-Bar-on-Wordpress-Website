<?php
/**
 * Plugin Name: Hide Admin Bar for Subscribers
 * Description: Hide the admin bar for subscribers but show it for administrators.
 * Version: 0.0.7
 * Author: Abu Ahamed 
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    echo("Hey, what are you trying to do?");
    exit;
}

class Hide_Admin_Bar_Plugin {

    // Constructor
    public function __construct() {
        // Add action to hide admin bar
        add_action('after_setup_theme', array($this, 'hide_admin_bar_for_subscribers'));
        // Add action to create settings page
        add_action('admin_menu', array($this, 'add_settings_page'));
        // Register settings
        add_action('admin_init', array($this, 'register_settings'));
    }

    // Function to hide admin bar for subscribers
    public function hide_admin_bar_for_subscribers() {
        // Check if user is logged in
        if (is_user_logged_in()) {
            // Get the current user
            $current_user = wp_get_current_user();
            // Check if user is a subscriber
            if (in_array('subscriber', $current_user->roles)) {
                // Check if option is enabled to hide admin bar for subscribers
                if (get_option('hide_admin_bar_for_subscribers') == 'yes') {
                    // Hide the admin bar for subscribers
                    show_admin_bar(false);
                }
            }
        }
    }

    // Function to add settings page
    public function add_settings_page() {
        // Add settings page under Settings menu
        add_options_page('Hide Admin Bar Settings', 'Hide Admin Bar', 'manage_options', 'hide-admin-bar-settings', array($this, 'render_settings_page'));
    }

    // Function to render settings page
    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h2>Hide Admin Bar Settings</h2>
            <form method="post" action="options.php">
                <?php settings_fields('hide_admin_bar_settings_group'); ?>
                <label for="hide_admin_bar_for_subscribers">
                    <input type="checkbox" id="hide_admin_bar_for_subscribers" name="hide_admin_bar_for_subscribers" value="yes" <?php checked('yes', get_option('hide_admin_bar_for_subscribers')); ?>>
                    Hide admin bar for subscribers
                </label>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    // Function to register settings
    public function register_settings() {
        // Register setting for hiding admin bar for subscribers
        register_setting('hide_admin_bar_settings_group', 'hide_admin_bar_for_subscribers');
    }
}

// Instantiate the class
new Hide_Admin_Bar_Plugin();