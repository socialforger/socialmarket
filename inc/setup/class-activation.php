<?php
namespace Social_Market\Setup;

use Social_Market\Roles;
use Social_Market\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Activation {

    /**
     * Called on plugin activation.
     */
    public static function run() {

        // Register CPT and Taxonomy before flushing rewrites
        $plugin = Plugin::instance();
        $plugin->register_cpts();
        $plugin->register_taxonomies();

        // Create roles
        Roles::init();

        // Default options
        self::create_default_options();

        // Flush rewrites
        flush_rewrite_rules();
    }

    /**
     * Create default plugin options.
     */
    private static function create_default_options() {

        $defaults = array(
            'association_name' => '',
        );

        if ( ! get_option( 'socialmarket_settings' ) ) {
            update_option( 'socialmarket_settings', $defaults );
        }
    }
}
