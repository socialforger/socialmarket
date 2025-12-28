<?php
namespace Social_Market;

use Social_Market\CPT\GAS;
use Social_Market\CPT\Organizzazione;
use Social_Market\CPT\Eventi;
use Social_Market\CPT\Punto_Ritiro;
use Social_Market\CPT\Fornitore;

use Social_Market\Taxonomy\Tipo_Evento;
use Social_Market\Taxonomy\Categorie_Organizzazione;

use Social_Market\Admin\Admin_Menu;

use Social_Market\Setup\Activation;
use Social_Market\Setup\Setup_Wizard;

use Social_Market\Assets;
use Social_Market\Settings;
use Social_Market\Roles;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Plugin {

    /**
     * Singleton instance.
     *
     * @var Plugin
     */
    private static $instance;

    /**
     * Returns the plugin instance.
     *
     * @return Plugin
     */
    public static function instance() {
        if ( ! isset( self::$instance ) ) {
            self::$instance = new self();
            self::$instance->init_hooks();
        }

        return self::$instance;
    }

    /**
     * Private constructor.
     */
    private function __construct() {}

    /**
     * Initialize plugin hooks.
     */
    private function init_hooks() {

        // Load translations
        add_action( 'init', array( $this, 'load_textdomain' ) );

        // Register CPTs and Taxonomies
        add_action( 'init', array( $this, 'register_cpts' ), 5 );
        add_action( 'init', array( $this, 'register_taxonomies' ), 6 );

        // Core systems
        Assets::init();
        Settings::init();
        Roles::init();

        // Admin
        Admin_Menu::init();

        // Setup Wizard
        Setup_Wizard::init();

        // Future modules (newsletter, membership, logistics, events)
        // will be initialized here.
    }

    /**
     * Load plugin textdomain.
     */
    public function load_textdomain() {
        load_plugin_textdomain(
            'socialmarket',
            false,
            dirname( plugin_basename( SOCIAL_MARKET_PLUGIN_FILE ) ) . '/languages'
        );
    }

    /**
     * Register all Custom Post Types.
     */
    public function register_cpts() {
        Organizzazione::register();
        GAS::register();
        Eventi::register();
        Punto_Ritiro::register();
        Fornitore::register();
    }

    /**
     * Register all taxonomies.
     */
    public function register_taxonomies() {
        Tipo_Evento::register();
        Categorie_Organizzazione::register();
    }

    /**
     * Plugin activation logic.
     */
    public function activate() {
        Activation::run();
    }

    /**
     * Plugin deactivation logic.
     */
    public function deactivate() {
        Roles::remove_roles();
        flush_rewrite_rules();
    }
}
