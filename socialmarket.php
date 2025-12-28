<?php
/**
 * Plugin Name: Social Market
 * Plugin URI: https://github.com/socialforger/socialmarket
 * Description: Social Commerce Platform for WooCommerce.
 * Version: 1.0.0
 * Author: Socialforger
 * Text Domain: socialmarket
 * Domain Path: /languages
 * License: GPLv2 or later
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * ---------------------------------------------------------
 * 1. Costanti del plugin
 * ---------------------------------------------------------
 */
define( 'SOCIALMARKET_VERSION', '1.0.0' );
define( 'SOCIALMARKET_PLUGIN_FILE', __FILE__ );
define( 'SOCIALMARKET_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SOCIALMARKET_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'SOCIALMARKET_TEMPLATES_DIR', SOCIALMARKET_PLUGIN_DIR . 'templates/' );

/**
 * ---------------------------------------------------------
 * 2. Autoloader
 * ---------------------------------------------------------
 */
require_once SOCIALMARKET_PLUGIN_DIR . 'inc/core/class-autoloader.php';
SocialMarket\Core\Autoloader::init();

/**
 * ---------------------------------------------------------
 * 3. Caricamento text domain
 * ---------------------------------------------------------
 */
function socialmarket_load_textdomain() {
    load_plugin_textdomain(
        'socialmarket',
        false,
        dirname( plugin_basename( __FILE__ ) ) . '/languages/'
    );
}
add_action( 'plugins_loaded', 'socialmarket_load_textdomain' );

/**
 * ---------------------------------------------------------
 * 4. Attivazione / Disattivazione
 * ---------------------------------------------------------
 */
register_activation_hook( __FILE__, function() {
    SocialMarket\Setup\Activation::run();
});

register_deactivation_hook( __FILE__, function() {
    // Se in futuro servirà qualcosa alla disattivazione, lo aggiungiamo qui.
});

/**
 * ---------------------------------------------------------
 * 5. Inizializzazione del plugin
 * ---------------------------------------------------------
 */
function socialmarket_init_plugin() {

    // Core
    SocialMarket\Core\Plugin::init();
    SocialMarket\Core\Assets::init();
    SocialMarket\Core\Roles::init();
    SocialMarket\Core\Settings::init();

    // CPT
    SocialMarket\CPT\CPT_Organizzazione::init();
    SocialMarket\CPT\CPT_GAS::init();
    SocialMarket\CPT\CPT_Eventi::init();
    SocialMarket\CPT\CPT_Punto_Ritiro::init();
    SocialMarket\CPT\CPT_Fornitore::init();

    // Tassonomie
    SocialMarket\Taxonomy\Tax_Tipo_Evento::init();
    SocialMarket\Taxonomy\Tax_Categorie_Organizzazione::init();

    // Moduli
    SocialMarket\Modules\Newsletter\Newsletter_Manager::init();
    SocialMarket\Modules\Newsletter\Newsletter_Segmentation::init();
    SocialMarket\Modules\Newsletter\Newsletter_Newspack::init();

    SocialMarket\Modules\Membership\Membership_Manager::init();
    SocialMarket\Modules\Membership\Membership_GAS::init();

    SocialMarket\Modules\Logistics\Logistics_Sessioni_Ritiro::init();
    SocialMarket\Modules\Logistics\Logistics_Fresco::init();

    SocialMarket\Modules\Events\Events_Calendar::init();
    SocialMarket\Modules\Events\Events_Visibility::init();
    SocialMarket\Modules\Events\Events_Notifications::init();

    SocialMarket\Modules\SEO\SEO_Integration::init();

    // Integrazioni
    SocialMarket\Integrations\WC_Integration::init();
    SocialMarket\Integrations\Blocksy_Integration::init();
    SocialMarket\Integrations\Newspack_Integration::init();

    // Admin
    if ( is_admin() ) {
        SocialMarket\Admin\Admin_Menu::init();
        SocialMarket\Admin\Admin_Pages::init();
        SocialMarket\Admin\Admin_Columns::init();
    }
}
add_action( 'plugins_loaded', 'socialmarket_init_plugin', 20 );

/**
 * ---------------------------------------------------------
 * 6. Template Loader (override-friendly)
 * ---------------------------------------------------------
 */
function socialmarket_locate_template( $template, $name, $path ) {

    $theme_path = trailingslashit( get_stylesheet_directory() ) . 'socialmarket/' . $path . $name;
    $plugin_path = SOCIALMARKET_TEMPLATES_DIR . $path . $name;

    if ( file_exists( $theme_path ) ) {
        return $theme_path;
    }

    if ( file_exists( $plugin_path ) ) {
        return $plugin_path;
    }

    return $template;
}
