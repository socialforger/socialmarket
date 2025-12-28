<?php
namespace Social_Market;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Autoloader {

    /**
     * Inizializza l'autoloader.
     */
    public static function init() {
        spl_autoload_register( array( __CLASS__, 'autoload' ) );
    }

    /**
     * Autoload delle classi del plugin.
     */
    public static function autoload( $class ) {

        // Namespace del plugin
        $prefix = __NAMESPACE__ . '\\';

        // Se la classe non appartiene al namespace Social_Market, ignora
        if ( strpos( $class, $prefix ) !== 0 ) {
            return;
        }

        // Rimuove il namespace e converte in percorso file
        $relative_class = substr( $class, strlen( $prefix ) );

        // Converte namespace → path
        // Social_Market\CPT\GAS → cpt/class-gas.php
        $relative_class = str_replace( '\\', '/', strtolower( $relative_class ) );
        $relative_class = 'class-' . str_replace( '_', '-', $relative_class ) . '.php';

        // Percorsi da controllare
        $paths = array(
            SOCIAL_MARKET_PLUGIN_DIR . 'inc/core/' . $relative_class,
            SOCIAL_MARKET_PLUGIN_DIR . 'inc/cpt/' . $relative_class,
            SOCIAL_MARKET_PLUGIN_DIR . 'inc/taxonomy/' . $relative_class,
            SOCIAL_MARKET_PLUGIN_DIR . 'inc/modules/' . $relative_class,
            SOCIAL_MARKET_PLUGIN_DIR . 'inc/modules/newsletter/' . $relative_class,
            SOCIAL_MARKET_PLUGIN_DIR . 'inc/modules/membership/' . $relative_class,
            SOCIAL_MARKET_PLUGIN_DIR . 'inc/modules/logistics/' . $relative_class,
            SOCIAL_MARKET_PLUGIN_DIR . 'inc/modules/events/' . $relative_class,
            SOCIAL_MARKET_PLUGIN_DIR . 'inc/modules/seo/' . $relative_class,
            SOCIAL_MARKET_PLUGIN_DIR . 'inc/integrations/' . $relative_class,
            SOCIAL_MARKET_PLUGIN_DIR . 'inc/admin/' . $relative_class,
        );

        // Cerca il file
        foreach ( $paths as $file ) {
            if ( file_exists( $file ) ) {
                require_once $file;
                return;
            }
        }
    }
}
