<?php
namespace Social_Market;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Funzioni di utilità generiche.
 */
class Helpers {

    /**
     * Ritorna un valore da un array in modo sicuro.
     */
    public static function array_get( $array, $key, $default = null ) {
        return isset( $array[ $key ] ) ? $array[ $key ] : $default;
    }

    /**
     * Ritorna un valore booleano normalizzato.
     */
    public static function bool( $value ) {
        return filter_var( $value, FILTER_VALIDATE_BOOLEAN );
    }

    /**
     * Ritorna un template da /templates/ con override del tema.
     */
    public static function get_template( $template_name, $vars = array() ) {

        $template_path = SOCIAL_MARKET_PLUGIN_DIR . 'templates/' . $template_name;

        if ( ! file_exists( $template_path ) ) {
            return;
        }

        if ( ! empty( $vars ) ) {
            extract( $vars );
        }

        include $template_path;
    }

    /**
     * Debug helper.
     */
    public static function debug( $data ) {
        echo '<pre style="background:#fff;padding:10px;border:1px solid #ccc;">';
        print_r( $data );
        echo '</pre>';
    }
}
