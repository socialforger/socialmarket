<?php
namespace Social_Market\Taxonomy;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Categorie_Organizzazione {

    const TAXONOMY = 'categoria_organizzazione';

    public static function register() {

        $labels = array(
            'name'              => __( 'Categorie Organizzazione', 'socialmarket' ),
            'singular_name'     => __( 'Categoria Organizzazione', 'socialmarket' ),
            'search_items'      => __( 'Cerca Categorie', 'socialmarket' ),
            'all_items'         => __( 'Tutte le Categorie', 'socialmarket' ),
            'edit_item'         => __( 'Modifica Categoria', 'socialmarket' ),
            'update_item'       => __( 'Aggiorna Categoria', 'socialmarket' ),
            'add_new_item'      => __( 'Aggiungi Categoria', 'socialmarket' ),
            'new_item_name'     => __( 'Nuova Categoria', 'socialmarket' ),
            'menu_name'         => __( 'Categorie Organizzazione', 'socialmarket' ),
        );

        $args = array(
            'labels'            => $labels,
            'public'            => false,
            'show_ui'           => true,
            'show_admin_column' => true,
            'hierarchical'      => true,
            'rewrite'           => false,
        );

        register_taxonomy(
            self::TAXONOMY,
            array( 'organizzazione' ),
            $args
        );
    }
}
