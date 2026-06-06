<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class SM_Plugin_Configuration {

    /**
     * Elenco dei plugin dello stack obbligatori per il funzionamento.
     */
    public static function get_required_stack_plugins() {
        return array(
            'woocommerce'                                         => 'woocommerce/woocommerce.php',
            'wallet-system-for-woocommerce'                       => 'wallet-system-for-woocommerce/wallet-system-for-woocommerce.php',
            'membership-for-woocommerce'                          => 'membership-for-woocommerce/membership-for-woocommerce.php',
            'login-me-now'                                        => 'login-me-now/login-me-now.php',
            'delivery-drivers-manager'                            => 'delivery-drivers-manager/delivery-drivers-manager.php',
            'local-delivery-drivers-for-woocommerce'              => 'local-delivery-drivers-for-woocommerce/local-delivery-drivers-for-woocommerce.php',
            'donation-platform-for-woocommerce'                   => 'donation-platform-for-woocommerce/donation-platform-for-woocommerce.php',
            'mailerpress'                                         => 'mailerpress/mailerpress.php',
            'order-picking-for-woocommerce'                       => 'order-picking-for-woocommerce/order-picking-for-woocommerce.php',
            'woocommerce-pdf-invoices-packing-slips'              => 'woocommerce-pdf-invoices-packing-slips/woocommerce-pdf-invoices-packing-slips.php',
            'woocommerce-pdf-invoices-italian-add-on'             => 'woocommerce-pdf-invoices-italian-add-on/woocommerce-pdf-invoices-italian-add-on.php',
            'powerfulwp-order-delivery-scheduler-for-woocommerce' => 'powerfulwp-order-delivery-scheduler-for-woocommerce/powerfulwp-order-delivery-scheduler-for-woocommerce.php',
            'wp-all-import'                                       => 'wp-all-import/wp-all-import.php',
            'woocommerce-xml-csv-product-import'                  => 'woocommerce-xml-csv-product-import/woocommerce-xml-csv-product-import.php',
            'wp-all-export'                                       => 'wp-all-export/wp-all-export.php',
            'wc-shipping-packages'                                => 'wc-shipping-packages/wc-shipping-packages.php',
            'wp-esg'                                              => 'wp-esg/wp-esg.php'
        );
    }

    /**
     * Scansione dello stack per rilevare plugin disattivi
     */
    public static function check_missing_stack_plugins() {
        if ( ! function_exists( 'is_plugin_active' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $missing_plugins = array();
        foreach ( self::get_required_stack_plugins() as $slug => $file_path ) {
            if ( ! is_plugin_active( $file_path ) ) {
                $missing_plugins[$slug] = $file_path;
            }
        }
        return $missing_plugins;
    }

    /**
     * CONFIGURAZIONE TOTALE DI WOOCOMMERCE E DEI PLUGIN PARTNER
     * Mappa tutte le chiavi della tabella wp_options per l'allineamento automatico.
     */
    public static function get_authoritative_options() {
        return array(
            // 1. CONFIGURAZIONI GENERALI FISCALI E VALUTA
            'woocommerce_calc_taxes'                     => 'no',         // Disattiva calcolo tasse (regime istituzionale APS)
            'woocommerce_currency'                       => 'EUR',        // Forza valuta in Euro
            'woocommerce_currency_pos'                   => 'right_space',// Simbolo a destra spaziato (es. 40,00 €)
            'woocommerce_price_thousand_sep'             => '.',          // Separatore migliaia italiano
            'woocommerce_price_decimal_sep'              => ',',          // Separatore decimali italiano
            'woocommerce_price_num_decimals'             => '2',          // Due cifre decimali standard

            // 2. RESTRIZIONI GEOGRAFICHE DI VENDITA E SPEDIZIONE
            'woocommerce_allowed_countries'              => 'specific',   // Limita le vendite a nazioni specifiche
            'woocommerce_specific_allowed_countries'     => array( 'IT' ),// Solo Italia ammessa nel paniere
            'woocommerce_ship_to_countries'              => 'all',        // Consente spedizioni nelle nazioni permesse
            'woocommerce_default_customer_address'       => 'base',       // Geolocalizzazione predefinita basata sulla sede dell'APS
            'woocommerce_ship_to_destination'            => 'shipping',   // Forza la spedizione sull'indirizzo del cliente (no fatturazione)

            // 3. POLITICHE DI REGISTRAZIONE E ACCOUNT (Blindatura Onboarding)
            'woocommerce_enable_signup_and_login_from_checkout' => 'no', // Impedisce la registrazione caotica al checkout
            'woocommerce_enable_myaccount_registration'          => 'no', // Chiude il form di registrazione standard sulla pagina My Account
            'woocommerce_registration_generate_username'        => 'yes',// Generazione username automatica da codice
            'woocommerce_registration_generate_password'        => 'yes',// Generazione password automatica da codice
            'woocommerce_logout_redirect_item'                  => 'home',// Mandi l'utente in homepage al logout

            // 4. AZIONI DEL CARRELLO E COMPORTAMENTO PRODOTTI
            'woocommerce_cart_redirect_after_add'        => 'no',         // Evita il redirect forzato al carrello (navigazione fluida)
            'woocommerce_enable_ajax_add_to_cart'        => 'yes',        // Attiva l'aggiunta al carrello via AJAX senza ricaricare la pagina
            
            // 5. GESTIONE DEL MAGAZZINO E SCORTE
            'woocommerce_manage_stock'                   => 'yes',        // Attiva la gestione dell'inventario merci dei contadini
            'woocommerce_hold_stock_minutes'             => '60',         // Tempo massimo di blocco merce nel carrello prima dello sblocco
            'woocommerce_notify_no_stock'                => 'yes',        // Avvisa la segreteria quando un prodotto agricolo è esaurito
            'woocommerce_notify_low_stock'               => 'yes',        // Avvisa la segreteria quando le scorte sono in esaurimento
            'woocommerce_notify_low_stock_amount'        => '2',          // Soglia minima di allarme scorte
            'woocommerce_stock_display_format'           => 'always',     // Mostra sempre le quantità disponibili (es. "Rimasti solo 3 pezzi")

            // 6. RECENSIONI E VALUTAZIONI (Disattivate per preservare la pulizia)
            'woocommerce_enable_reviews'                 => 'no',         // Spegne i commenti sui prodotti ortofrutticoli
            'woocommerce_enable_review_rating'           => 'no',         // Spegne le stelline di valutazione

            // 7. PARAMETRI INTERNI WALLET SYSTEM FOR WOOCOMMERCE
            'wallet_system_enable_gateway_charge'        => 'yes',        // Forza il ribaltamento delle commissioni Stripe/PayPal sul socio
            
            // 8. CONFIGURAZIONE SHIPPING PACKAGES (Orchestratore Spedizioni Contadini)
            'wcspp_configuration_settings' => array(
                'enabled'          => 'yes',
                'group_by'         => 'product_author', // Sdoppia dinamicamente il carrello in base al contadino autore
                'package_title'    => 'Prodotti da: {package_name}',
                'merge_same_rates' => 'no'
            )
        );
    }

    /**
     * ESEGUE L'ALLINEAMENTO FORZATO DEI PARAMETRI DEL DATABASE
     */
    public static function run_activation_setup() {
        foreach ( self::get_authoritative_options() as $key => $value ) {
            update_option( $key, $value );
        }

        // Genera il ruolo "Driver" per gli autisti se non presente nell'installazione
        if ( ! get_role( 'local_driver' ) ) {
            add_role( 'local_driver', 'MES Autista Locale', array(
                'read'         => true,
                'edit_posts'   => false,
                'upload_files' => true
            ) );
        }

        // Forza WooCommerce a rigenerare gli endpoint interni delle pagine speciali
        flush_rewrite_rules();
    }
}
