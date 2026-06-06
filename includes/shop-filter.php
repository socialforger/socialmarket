<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class SM_Shop_Filter_Engine {

    public function __construct() {
        // 1. Forza la coincidenza sul catalogo modificando la query del database
        add_action( 'woocommerce_product_query', array( $this, 'filtra_catalogo_merci_per_provincia' ), 20 );

        // 2. Blocca sul nascere l'aggiunta al carrello se le province non coincidono
        add_filter( 'woocommerce_add_to_cart_validation', array( $this, 'valida_coincidenza_geografica_inserimento' ), 10, 3 );

        // 3. Blindaggio finale alla cassa per intercettare cambi di provincia fraudolenti o fulminei
        add_action( 'woocommerce_check_cart_items', array( $this, 'blindaggio_di_sicurezza_checkout_carrello' ) );
    }

    /**
     * 🔍 HELPER: Recupera la provincia nativa del socio (Consumer)
     */
    private function ottieni_provincia_corrente_socio() {
        // Se il socio è loggato, la sorgente di verità è il metadato ufficiale di WooCommerce
        if ( is_user_logged_in() ) {
            $provincia = get_user_meta( get_current_user_id(), 'billing_province', true );
            if ( ! empty( $provincia ) ) {
                return strtoupper( sanitize_text_field( $provincia ) );
            }
        }

        // Fallback sul cookie sicuro generato dall'onboarding per utenti non ancora autenticati
        if ( isset( $_COOKIE['billing_province'] ) ) {
            return strtoupper( sanitize_text_field( $_COOKIE['billing_province'] ) );
        }

        return ''; // Nessuna provincia impostata: blocco preventivo
    }

    /**
     * 🎯 FILTRO DATABASE: Mostra solo i prodotti dove c'è coincidenza esatta tra le province
     */
    public function filtra_catalogo_merci_per_provincia( $q ) {
        // Applica le restrizioni solo nel front-end e nelle query principali dello shop
        if ( is_admin() || ! $q->is_main_query() ) return;

        $provincia_socio = $this->ottieni_provincia_corrente_socio();

        // Se il socio non è localizzato, nascondiamo istantaneamente tutto il catalogo
        if ( empty( $provincia_socio ) ) {
            $q->set( 'post__in', array( 0 ) );
            return;
        }

        // Estrae i soli produttori (vendor) la cui copertura coincide con la provincia del socio
        $produttori_coincidenti = get_users( array(
            'role'       => 'vendor',
            'fields'     => 'ID',
            'meta_query' => array(
                array(
                    'key'     => '_wcfm_vendor_shipping_zones', // Chiave nativa dei plugin esterni di delivery
                    'value'   => '"' . $provincia_socio . '"',  // Cerca la sigla ISO (es. "RM") nella stringa serializzata
                    'compare' => 'LIKE'
                )
            )
        ) );

        // Se nessun contadino copre la provincia del socio, il catalogo si azzera
        if ( empty( $produttori_coincidenti ) ) {
            $q->set( 'post__in', array( 0 ) );
            return;
        }

        // Ordina a WooCommerce di mostrare solo la merce di quegli specifici autori
        $q->set( 'author__in', $produttori_coincidenti );
    }

    /**
     * 🛡️ VALIDATORE 1: Verifica la coincidenza prima di accettare il prodotto nel carrello
     */
    public function valida_coincidenza_geografica_inserimento( $passed, $product_id, $quantity ) {
        $provincia_socio = $this->ottieni_provincia_corrente_socio();
        
        if ( empty( $provincia_socio ) ) {
            wc_add_notice( __( '📍 Seleziona una provincia operativa prima di aggiungere prodotti al carrello.', 'socialmarket' ), 'error' );
            return false;
        }

        // Identifica il contadino che ha inserito il prodotto
        $id_contadino = get_post_field( 'post_author', $product_id );
        $zone_contadino = get_user_meta( $id_contadino, '_wcfm_vendor_shipping_zones', true );

        // Normalizza il dato per la ricerca testuale
        $stringa_zone = is_array( $zone_contadino ) ? serialize( $zone_contadino ) : $zone_contadino;

        // Se la provincia del socio non è presente tra quelle del contadino, nega l'azione
        if ( empty( $stringa_zone ) || strpos( $stringa_zone, '"' . $provincia_socio . '"' ) === false ) {
            wc_add_notice( __( '❌ Errore Logistico: Questo produttore non effettua consegne nella tua provincia di ritiro.', 'socialmarket' ), 'error' );
            return false;
        }

        return $passed;
    }

    /**
     * 🛡️ VALIDATORE 2: Ispezione finale e pulizia forzata alla cassa (Anti-Frode)
     */
    public function blindaggio_di_sicurezza_checkout_carrello() {
        $provincia_socio = $this->ottieni_provincia_corrente_socio();
        $carrello_manomesso = false;

        if ( empty( $provincia_socio ) ) {
            wc_add_notice( __( '📍 Imposta la tua provincia per validare l\'ordine.', 'socialmarket' ), 'error' );
            return;
        }

        // Scansiona ogni articolo pronto al pagamento
        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            $product_id = $cart_item['product_id'];
            $id_contadino = get_post_field( 'post_author', $product_id );
            $zone_contadino = get_user_meta( $id_contadino, '_wcfm_vendor_shipping_zones', true );

            $stringa_zone = is_array( $zone_contadino ) ? serialize( $zone_contadino ) : $zone_contadino;

            // Se rileva un prodotto rimasto nel carrello le cui province non coincidono più, lo espelle
            if ( empty( $stringa_zone ) || strpos( $stringa_zone, '"' . $provincia_socio . '"' ) === false ) {
                WC()->cart->remove_cart_item( $cart_item_key );
                $carrello_manomesso = true;
            }
        }

        if ( $carrello_manomesso ) {
            wc_add_notice( __( '⚠️ Attenzione: Alcuni prodotti sono stati rimossi dal carrello poiché non distribuiti nella provincia selezionata.', 'socialmarket' ), 'error' );
        }
    }
}
new SM_Shop_Filter_Engine();
