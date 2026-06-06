<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class SM_Consumer_Onboarding {

    public function __construct() {
        add_action( 'init', array( $this, 'salva_provincia_residenza_socio' ) );
        add_shortcode( 'sm_provincia_socio', array( $this, 'renderizza_selezionatore_provincia_socio' ) );
    }

    /**
     * 📂 SORGENTE DI VERITÀ: Carica le province ufficiali dell'APS dal JSON comune
     */
    private function carica_province_da_json() {
        $json_path = plugin_dir_path( dirname( __FILE__ ) ) . 'data/province.json';
        if ( file_exists( $json_path ) ) {
            return json_decode( file_get_contents( $json_path ), true );
        }
        return array();
    }

    /**
     * ⚙️ PROCESSORE DI SALVATAGGIO NATIVO (Popola billing_state)
     */
    public function salva_provincia_residenza_socio() {
        if ( isset( $_POST['sm_socio_provincia_nonce'] ) && wp_verify_nonce( $_POST['sm_socio_provincia_nonce'], 'sm_save_socio_provincia' ) ) {
            
            $provincia_scelta = isset( $_POST['sm_consumer_province'] ) ? strtoupper( sanitize_text_field( $_POST['sm_consumer_province'] ) ) : '';
            $province_totali  = $this->carica_province_da_json();

            // Validazione: la provincia inviata deve esistere nel JSON dell'APS
            if ( array_key_exists( $provincia_scelta, $province_totali ) ) {
                
                // 1. Se il socio è loggato, salviamo direttamente nel meta-campo nativo di WooCommerce
                if ( is_user_logged_in() ) {
                    $user_id = get_current_user_id();
                    update_user_meta( $user_id, 'billing_state', $provincia_scelta );
                }

                // 2. In ogni caso, salviamo il dato in un cookie di sessione (durata 30 giorni) 
                // per permettere lo sblocco e il filtraggio dello shop anche ai visitatori non ancora registrati
                setcookie( 'billing_state', $provincia_scelta, time() + ( DAY_IN_SECONDS * 30 ), COOKIEPATH, COOKIE_DOMAIN );
                
                // Generiamo un piccolo alert di successo temporaneo
                set_transient( 'sm_consumer_save_success_' . get_current_user_id(), true, 15 );
                
                // Ricarica la pagina per applicare istantaneamente i filtri del catalogo
                wp_redirect( $_SERVER['REQUEST_URI'] );
                exit;
            }
        }
    }

    /**
     * 🎨 INTERFACCIA DI SELEZIONE FRONTEND
     */
    public function renderizza_selezionatore_provincia_socio() {
        $provincia_corrente = '';

        // Recuperiamo la provincia già salvata (se esiste) dando priorità all'utente loggato, poi al cookie
        if ( is_user_logged_in() ) {
            $provincia_corrente = get_user_meta( get_current_user_id(), 'billing_state', true );
        }
        if ( empty( $provincia_corrente ) && isset( $_COOKIE['billing_state'] ) ) {
            $provincia_corrente = sanitize_text_field( $_COOKIE['billing_state'] );
        }

        $all_province = $this->carica_province_da_json();
        ob_start();
        
        if ( get_transient( 'sm_consumer_save_success_' . get_current_user_id() ) ) {
            echo '<div style="background:#c6f6d5; color:#22543d; padding:12px; margin-bottom:15px; border-radius:6px; font-weight:bold; font-size:14px;">📍 Sosta e mercato aggiornati in base alla tua provincia!</div>';
            delete_transient( 'sm_consumer_save_success_' . get_current_user_id() );
        }
        ?>
        <div class="sm-consumer-province-box" style="background:#f7fafc; border:1px solid #e2e8f0; padding:20px; border-radius:8px; max-width: 450px;">
            <h4 style="margin-top:0; color:#2b6cb0; margin-bottom:10px;">📍 Seleziona la tua provincia di ritiro</h4>
            <p style="color:#4a5568; font-size:13px; margin-bottom:15px;">Mostreremo nel mercato itinerante solo i produttori che portano il furgone nella tua zona.</p>
            
            <form method="POST" action="">
                <?php wp_nonce_field( 'sm_save_socio_provincia', 'sm_socio_provincia_nonce' ); ?>
                
                <div style="display:flex; gap:10px;">
                    <select name="sm_consumer_province" required style="flex-grow:1; padding:10px; border:1px solid #cbd5e0; border-radius:6px; background:#fff; font-size:14px; height:42px;">
                        <option value="">-- Scegli la tua provincia --</option>
                        <?php foreach ( $all_province as $sigla => $nome ) : ?>
                            <option value="<?php echo esc_attr( $sigla ); ?>" <?php selected( $provincia_corrente, $sigla ); ?>>
                                <?php echo esc_html( $nome . ' (' . $sigla . ')' ); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    
                    <button type="submit" style="background:#2b6cb0; color:white; border:none; padding:0 20px; font-weight:bold; border-radius:6px; font-size:14px; cursor:pointer; height:42px;">
                        Conferma
                    </button>
                </div>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }
}
new SM_Consumer_Onboarding();
