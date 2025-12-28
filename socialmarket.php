<?php
/**
 * Plugin Name: Social Market
 * Plugin URI:  https://github.com/socialforger/socialmarket
 * Description: Social Commerce Platform for WooCommerce.
 * Author:      Socialforger
 * Version:     0.1.0
 * Text Domain: socialmarket
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'SOCIAL_MARKET_VERSION', '0.1.0' );
define( 'SOCIAL_MARKET_PLUGIN_FILE', __FILE__ );
define( 'SOCIAL_MARKET_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SOCIAL_MARKET_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Carica l'autoloader.
require_once SOCIAL_MARKET_PLUGIN_DIR . 'inc/core/class-autoloader.php';

Social_Market\Autoloader::init();

/**
 * Avvia il plugin.
 */
function socialmarket_init() {
    $plugin = Social_Market\Plugin::instance();
}
add_action( 'plugins_loaded', 'socialmarket_init' );

/**
 * Attivazione.
 */
function socialmarket_activate() {
    if ( ! class_exists( 'Social_Market\Plugin' ) ) {
        require_once SOCIAL_MARKET_PLUGIN_DIR . 'inc/core/class-plugin.php';
    }

    Social_Market\Plugin::instance()->activate();
}
register_activation_hook( __FILE__, 'socialmarket_activate' );

/**
 * Disattivazione.
 */
function socialmarket_deactivate() {
    if ( class_exists( 'Social_Market\Plugin' ) ) {
        Social_Market\Plugin::instance()->deactivate();
    }
}
register_deactivation_hook( __FILE__, 'socialmarket_deactivate' );
