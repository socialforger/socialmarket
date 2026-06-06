<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class SM_Shop_Filter_Engine {

    public function __construct() {
        // 1. Intercetta e modifica la query del database del catalogo WooCommerce
        add_action( 'woocommerce_product_query', array( $this, 'filtra_catalogo_merci_per_provincia' ), 20 );

        // 2. Protezione di cassa: convalida il carrello ad ogni aggiunta o modifica
        add_filter( 'woocommerce_add_to_cart_validation', array( $this, 'valida_restrizione_geografica_inserimento' ), 10, 3 );
        add_action( 'woocommerce_check_cart_items', array( $this, 'blindaggio_di_sicurezza_checkout_carrello' ) );
    }

    /**
     * 🔍 HELPER: Recupera la provincia attualmente impostata per la navigazione del socio
     */
    private function ottieni_provincia_corrente_socio() {
        // Se il socio è autenticato, ha la priorità il dato strutturato a DB
        if ( is_user_logged_in() ) {
            $provincia = get_user_meta( get_current_user_id(), 'sm_consumer_province', true );
            if ( ! empty( $provincia ) ) {
                return strtoupper( $provincia );
            }
        }

        // Altrimenti ripiega sul cookie di sessione raccolto dall'onboarding
        if ( isset( $_COOKIE['sm_consumer_province'] ) ) {
            return strtoupper( sanitize_text_field( $_COOKIE['sm_consumer_province'] ) );
        }

        return ''; // Nessuna provincia impostata (il catalogo mostrerà l'alert di onboarding)
    }

    /**
     * 🎯 METODO CORE: Altera la query SQL nativa per estrarre solo i contadini compatibili
     */
    public function filtra_catalogo_merci_per_provincia( $q ) {
        // Applica il filtro solo nel front-end, nelle pagine dello shop/categorie e non nei pannelli di amministrazione
        if ( is_admin() || ! $q->is_main_query() ) return;

        $provincia_socio = $this->ottieni_provincia_corrente_socio();

        // Se il socio non ha ancora espresso una preferenza, nascondiamo tutti i prodotti 
        // per costringerlo a compilare l'onboarding bloccante
        if ( empty( $provincia_socio ) ) {
            $q->set( 'post__in', array( 0 ) ); // Forza una query vuota
            return;
        }

        // Recuperiamo tutti i produttori (utenti con ruolo 'vendor') che operano nella provincia del socio
        $produttori_validi = get_users( array(
            'role'       => 'vendor',
            'fields'     => 'ID',
            'meta_query' => array(
                array(
                    'key'     => 'sm_producer_coverage_areas',
                    'value'   => '"' . $provincia_socio . '"', // Cerca la stringa serializzata nell'array del DB
                    'compare' => 'LIKE'
                )
            )
        ) );

        // Se nessun contadino opera in questa provincia, svuota il catalogo
        if ( empty( $produttori_validi ) ) {
            $q->set( 'post__in', array( 0 ) );
            return;
        }

        // Ordina a WooCommerce di mostrare solo i prodotti scritti (creati) dai produttori abilitati
        $q->set( 'author__in', $produttori_validi );
    }

    /**
     * 🛡️ VALIDATORE 1: Blocca l'aggiunta al carrello a livello di front-end di prodotti fuori zona
     */
    public function valida_restrizione_geografica_inserimento( $passed, $product_id, $quantity ) {
        $provincia_socio = $this->ottieni_provincia_corrente_socio();
        
        if ( empty( $provincia_socio ) ) {
            wc_add_notice( __( '📍 Seleziona una provincia prima di aggiungere prodotti al carrello.', 'socialmarket' ), 'error' );
            return false;
        }

        // Individua il contadino titolare del prodotto (l'autore del post)
        $id_contadino = get_post_field( 'post_author', $product_id );
        $aree_copertura = get_user_meta( $id_contadino, 'sm_producer_coverage_areas', true );

        if ( ! is_array( $aree_copertura ) || ! in_array( $provincia_socio, $aree_copertura ) ) {
            wc_add_notice( __( '❌ Questo prodotto non è disponibile per la distribuzione nella tua attuale provincia di ritiro.', 'socialmarket' ), 'error' );
            return false;
        }

        return $passed;
    }

    /**
     * 🛡️ VALIDATORE 2: Controllo finale bloccante alla cassa (Evita manomissioni o cambi provincia a carrello pieno)
     */
    public function blindaggio_di_sicurezza_checkout_carrello() {
        $provincia_socio = $this->ottieni_provincia_corrente_socio();
        $svuotare_carrello_corrotto = false;

        if ( empty( $provincia_socio ) ) {
            wc_add_notice( __( '📍 Imposta la tua area territoriale per completare la transazione.', 'socialmarket' ), 'error' );
            return;
        }

        // Esamina ogni singolo articolo presente nel carrello attivo
        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            $product_id = $cart_item['product_id'];
            $id_contadino = get_post_field( 'post_author', $product_id );
            $aree_copertura = get_user_meta( $id_contadino, 'sm_producer_coverage_areas', true );

            // Se trova un prodotto che non quadra con la provincia, lo elimina e segnala l'anomalia fiscale/logistica
            if ( ! is_array( $aree_copertura ) || ! in_array( $provincia_socio, $aree_copertura ) ) {
                WC()->cart->remove_cart_item( $cart_item_key );
                $svuotare_carrello_corrotto = true;
            }
        }

        if ( $svuotare_carrello_corrotto ) {
            wc_add_notice( __( '⚠️ Il tuo carrello è stato aggiornato: alcuni prodotti sono stati rimossi perché non distribuiti nella provincia selezionata.', 'socialmarket' ), 'error' );
        }
    }
}
new SM_Shop_Filter_Engine();
