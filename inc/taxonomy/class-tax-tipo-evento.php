<?php
namespace Social_Market\Taxonomy;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Tipo_Evento {

    const TAXONOMY = 'tipo_evento';

    public static function register() {

        $labels = array(
            'name'              => __( 'Tipi di Evento', 'socialmarket' ),
            'singular_name'     => __( 'Tipo di Evento', 'socialmarket' ),
            'search_items'      => __( 'Cerca Tipi di Evento', 'socialmarket' ),
            'all_items'         => __( 'Tutti i Tipi di Evento', 'socialmarket' ),
            'edit_item'         => __( 'Modifica Tipo di Evento', 'socialmarket' ),
            'update_item'       => __( 'Aggiorna Tipo di Evento', 'socialmarket' ),
            'add_new_item'      => __( 'Aggiungi Tipo di Evento', 'socialmarket' ),
            'new_item_name'     => __( 'Nuovo Tipo di Evento', 'socialmarket' ),
            'menu_name'         => __( 'Tipo Evento', 'socialmarket' ),
        );

        $args = array(
            'labels'            => $labels,
            'public'            => false,
            'show_ui'           => true,
            'show_admin_column' => true,
            'hierarchical'      => false,
            'rewrite'           => false,
        );

        register_taxonomy(
            self::TAXONOMY,
            array( 'sm_evento' ),
            $args
        );
    }
}
