<?php
namespace Social_Market\Admin;

use Social_Market\CPT\GAS;
use Social_Market\CPT\Organizzazione;
use Social_Market\CPT\Eventi;
use Social_Market\CPT\Punto_Ritiro;
use Social_Market\CPT\Fornitore;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Admin_Menu {

    public static function init() {
        add_action( 'admin_menu', array( __CLASS__, 'register_menu' ) );
    }

    public static function register_menu() {

        add_menu_page(
            __( 'Social Market', 'socialmarket' ),
            __( 'Social Market', 'socialmarket' ),
            'manage_options',
            'socialmarket',
            array( __CLASS__, 'render_dashboard' ),
            'dashicons-store',
            3
        );

        add_submenu_page(
            'socialmarket',
            __( 'Dashboard', 'socialmarket' ),
            __( 'Dashboard', 'socialmarket' ),
            'manage_options',
            'socialmarket',
            array( __CLASS__, 'render_dashboard' )
        );

        add_submenu_page(
            'socialmarket',
            __( 'Newsletter', 'socialmarket' ),
            __( 'Newsletter', 'socialmarket' ),
            'manage_options',
            'socialmarket-newsletter',
            array( __CLASS__, 'render_newsletter' )
        );

        add_submenu_page(
            'socialmarket',
            __( 'Logistica', 'socialmarket' ),
            __( 'Logistica', 'socialmarket' ),
            'manage_options',
            'socialmarket-logistics',
            array( __CLASS__, 'render_logistics' )
        );

        add_submenu_page(
            'socialmarket',
            __( 'Sistema', 'socialmarket' ),
            __( 'Sistema', 'socialmarket' ),
            'manage_options',
            'socialmarket-system',
            array( __CLASS__, 'render_system' )
        );
    }

    public static function render_dashboard() {
        include SOCIAL_MARKET_PLUGIN_DIR . 'inc/admin/views/page-dashboard.php';
    }

    public static function render_newsletter() {
        include SOCIAL_MARKET_PLUGIN_DIR . 'inc/admin/views/page-newsletter.php';
    }

    public static function render_logistics() {
        include SOCIAL_MARKET_PLUGIN_DIR . 'inc/admin/views/page-logistics.php';
    }

    public static function render_system() {
        include SOCIAL_MARKET_PLUGIN_DIR . 'inc/admin/views/page-system.php';
    }
}
