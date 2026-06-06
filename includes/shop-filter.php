<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class SM_Shop_Filter_Engine {

    public function __construct() {
        add_action( 'woocommerce_product_query', array( $this, 'filtra_catalogo_merci_per_provincia' ), 20 );
        add_filter( 'woocommerce_add_to_cart_validation', array( $this, 'valida_coincidenza_geografica_inserimento' ), 10, 3 );
        add_action( 'woocommerce_check_cart_items', array( $this, 'blindaggio_di_sicurezza_checkout_carrello' ) );
    }

    private function ottieni_provincia_corrente_socio() {
        if ( is_user_logged_in() ) {
            $provincia = get_user_meta( get_current_user_id(), 'billing_state', true );
            if ( ! empty( $provincia ) ) {
                return strtoupper( sanitize_text_field( $provincia ) );
            }
        }
        if ( isset( $_COOKIE['billing_state'] ) ) {
            return strtoupper( sanitize_text_field( $_COOKIE['billing_state'] ) );
        }
        return '';
    }

    public function filtra_catalogo_merci_per_provincia( $q ) {
        if ( is_admin() || ! $q->is_main_query() ) return;

        $provincia_socio = $this->ottieni_provincia_corrente_socio();
        if ( empty( $provincia_socio ) ) {
            $q->set( 'post__in', array( 0 ) );
            return;
        }

        // Cerca i produttori (author) che servono la provincia del socio
        $produttori_coincidenti = get_users( array(
            'role'       => 'author', 
            'fields'     => 'ID',
            'meta_query' => array(
                array(
                    'key'     => 'shipping_countries',
                    'value'   => '"' . $provincia_socio . '"',
                    'compare' => 'LIKE'
                )
            )
        ) );

        if ( empty( $produttori_coincidenti ) ) {
            $q->set( 'post__in', array( 0 ) );
            return;
        }

        $q->set( 'author__in', $produttori_coincidenti );
    }

    public function valida_coincidenza_geografica_inserimento( $passed, $product_id, $quantity ) {
        $provincia_socio = $this->ottieni_provincia_corrente_socio();
        if ( empty( $provincia_socio ) ) {
            wc_add_notice( __( '📍 Seleziona una provincia operativa prima di aggiungere prodotti al carrello.', 'socialmarket' ), 'error' );
            return false;
        }

        $id_contadino = get_post_field( 'post_author', $product_id );
        $aree_contadino = get_user_meta( $id_contadino, 'shipping_countries', true );
        $stringa_aree = maybe_serialize( $aree_contadino );

        if ( empty( $stringa_aree ) || strpos( $stringa_aree, '"' . $provincia_socio . '"' ) === false ) {
            wc_add_notice( __( '❌ Errore Logistico: Il camion di questo produttore non effettua consegne nella tua provincia.', 'socialmarket' ), 'error' );
            return false;
        }

        return $passed;
    }

    public function blindaggio_di_sicurezza_checkout_carrello() {
        if ( ! WC()->cart ) return;

        $provincia_socio = $this->ottieni_provincia_corrente_socio();
        $carrello_manomesso = false;

        if ( empty( $provincia_socio ) ) {
            wc_add_notice( __( '📍 Imposta la tua provincia per validare l\'ordine.', 'socialmarket' ), 'error' );
            return;
        }

        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            $product_id = $cart_item['product_id'];
            $id_contadino = get_post_field( 'post_author', $product_id );
            $aree_contadino = get_user_meta( $id_contadino, 'shipping_countries', true );
            $stringa_aree = maybe_serialize( $aree_contadino );

            if ( empty( $stringa_aree ) || strpos( $stringa_aree, '"' . $provincia_socio . '"' ) === false ) {
                WC()->cart->remove_cart_item( $cart_item_key );
                $carrello_manomesso = true;
            }
        }

        if ( $carrello_manomesso ) {
            wc_add_notice( __( '⚠️ Prodotti rimossi: non disponibili nella provincia selezionata.', 'socialmarket' ), 'error' );
        }
    }
}
new SM_Shop_Filter_Engine();
