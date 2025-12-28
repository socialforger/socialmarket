<?php
/**
 * Uninstall script for Social Market
 *
 * Questo file viene eseguito quando l’utente disinstalla il plugin da WordPress.
 * Rimuove opzioni, ruoli, capabilities e dati di configurazione.
 *
 * Non elimina i contenuti (CPT) per evitare perdita di dati non intenzionale.
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

/**
 * 1. Rimuovi opzioni del plugin
 */
$option_keys = [
    'socialmarket_settings',
    'socialmarket_roles_installed',
    'socialmarket_setup_completed',
    'socialmarket_newsletter_settings',
    'socialmarket_logistics_settings',
];

foreach ( $option_keys as $key ) {
    delete_option( $key );
    delete_site_option( $key );
}

/**
 * 2. Rimuovi ruoli e capabilities aggiunte dal plugin
 */
if ( function_exists( 'remove_role' ) ) {
    remove_role( 'gas_manager' );
    remove_role( 'organizzazione_manager' );
}

global $wp_roles;

if ( isset( $wp_roles ) ) {
    $caps = [
        'manage_gas',
        'manage_organizzazioni',
        'manage_eventi',
        'manage_punti_ritiro',
        'manage_fornitori',
        'manage_socialmarket_settings',
    ];

    foreach ( $wp_roles->roles as $role_key => $role ) {
        $role_obj = get_role( $role_key );
        if ( ! $role_obj ) {
            continue;
        }

        foreach ( $caps as $cap ) {
            $role_obj->remove_cap( $cap );
        }
    }
}

/**
 * 3. Rimuovi tabelle personalizzate (se presenti)
 */
global $wpdb;

$tables = [
    $wpdb->prefix . 'socialmarket_newsletter',
    $wpdb->prefix . 'socialmarket_members',
    $wpdb->prefix . 'socialmarket_logistics_sessions',
];

foreach ( $tables as $table ) {
    $wpdb->query( "DROP TABLE IF EXISTS {$table}" );
}

/**
 * 4. Rimuovi transients
 */
$transients = [
    'socialmarket_cache_events',
    'socialmarket_cache_next_delivery',
];

foreach ( $transients as $transient ) {
    delete_transient( $transient );
}

/**
 * 5. (Opzionale) Rimuovere CPT e tassonomie
 *
 * Per sicurezza NON eliminiamo i contenuti.
 * Se vuoi farlo, posso aggiungere una sezione dedicata.
 */

// Fine uninstall
