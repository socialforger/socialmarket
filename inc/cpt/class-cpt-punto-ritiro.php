<?php
namespace Social_Market\CPT;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Punto_Ritiro {

    const POST_TYPE = 'punto_ritiro';

    public static function register() {

        $labels = array(
            'name'               => __( 'Punti di Ritiro', 'socialmarket' ),
            'singular_name'      => __( 'Punto di Ritiro', 'socialmarket' ),
            'menu_name'          => __( 'Punti di Ritiro', 'socialmarket' ),
            'add_new'            => __( 'Aggiungi nuovo', 'socialmarket' ),
            'add_new_item'       => __( 'Aggiungi Punto di Ritiro', 'socialmarket' ),
            'edit_item'          => __( 'Modifica Punto di Ritiro', 'socialmarket' ),
            'new_item'           => __( 'Nuovo Punto di Ritiro', 'socialmarket' ),
            'view_item'          => __( 'Visualizza Punto di Ritiro', 'socialmarket' ),
            'search_items'       => __( 'Cerca Punti di Ritiro', 'socialmarket' ),
            'not_found'          => __( 'Nessun Punto di Ritiro trovato', 'socialmarket' ),
        );

        $args = array(
            'label'              => __( 'Punti di Ritiro', 'socialmarket' ),
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => false,
            'supports'           => array( 'title', 'editor', 'thumbnail' ),
            'has_archive'        => true,
            'rewrite'            => array( 'slug' => 'punti-di-ritiro' ),
        );

        register_post_type( self::POST_TYPE, $args );
    }
}
