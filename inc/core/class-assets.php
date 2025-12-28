<?php
namespace Social_Market;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Assets {

    public static function init() {
        add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_assets' ) );
        add_action( 'wp_enqueue_scripts', array( __CLASS__, 'frontend_assets' ) );
    }

    public static function admin_assets() {
        wp_enqueue_style(
            'socialmarket-admin',
            SOCIAL_MARKET_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            SOCIAL_MARKET_VERSION
        );

        wp_enqueue_script(
            'socialmarket-admin',
            SOCIAL_MARKET_PLUGIN_URL . 'assets/js/admin.js',
            array( 'jquery' ),
            SOCIAL_MARKET_VERSION,
            true
        );
    }

    public static function frontend_assets() {
        wp_enqueue_style(
            'socialmarket-frontend',
            SOCIAL_MARKET_PLUGIN_URL . 'assets/css/frontend.css',
            array(),
            SOCIAL_MARKET_VERSION
        );

        wp_enqueue_script(
            'socialmarket-frontend',
            SOCIAL_MARKET_PLUGIN_URL . 'assets/js/frontend.js',
            array( 'jquery' ),
            SOCIAL_MARKET_VERSION,
            true
        );
    }
}
