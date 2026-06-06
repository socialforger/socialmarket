<?php
/**
 * Social Market - Decoupled Purge Engine
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit;

// Carica le configurazioni per sapere cosa rimuovere dal database
require_once plugin_dir_path( __FILE__ ) . 'includes/configuration.php';

if ( class_exists( 'SM_Plugin_Configuration' ) ) {
    foreach ( array_keys( SM_Plugin_Configuration::get_authoritative_options() ) as $option_key ) {
        delete_option( $option_key );
    }
}

// Rimuove il ruolo autista per non lasciare rimasugli nei permessi generali del sito
if ( get_role( 'local_driver' ) ) {
    remove_role( 'local_driver' );
}

global $wp_rewrite;
if ( is_object( $wp_rewrite ) ) {
    flush_rewrite_rules();
}
