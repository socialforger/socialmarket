<?php
namespace Social_Market\CPT;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Fornitore {

    const POST_TYPE = 'fornitore';

    public static function register() {

        $labels = array(
            'name'               => __( 'Fornitori', 'socialmarket' ),
            'singular_name'      => __( 'Fornitore', 'socialmarket' ),
            'menu_name'          => __( 'Fornitori', 'socialmarket' ),
            'add_new'            => __( 'Aggiungi nuovo', 'socialmarket' ),
            'add_new_item'       => __( 'Aggiungi Fornitore', 'socialmarket' ),
            'edit_item'          => __( 'Modifica Fornitore', 'socialmarket' ),
            'new_item'           => __( 'Nuovo Fornitore', 'socialmarket' ),
            'view_item'          => __( 'Visualizza Fornitore', 'socialmarket' ),
            'search_items'       => __( 'Cerca Fornitori', 'socialmarket' ),
            'not_found'          => __( 'Nessun Fornitore trovato', 'socialmarket' ),
        );

        $args = array(
            'label'              => __( 'Fornitori', 'socialmarket' ),
            'labels'             => $labels,
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => false,
            'supports'           => array( 'title', 'editor', 'thumbnail' ),
        );

        register_post_type( self::POST_TYPE, $args );
    }
}
