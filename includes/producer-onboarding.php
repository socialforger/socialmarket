<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class SM_Producer_Onboarding {

    public function __construct() {
        // Intercetta il salvataggio della logistica del produttore
        add_action( 'init', array( $this, 'salva_province_logistica_produttore' ) );

        // Registra lo shortcode per l'area riservata del contadino
        add_shortcode( 'sm_logistica_produttore', array( $this, 'renderizza_pannello_logistica_produttore' ) );
    }

    private function carica_province_da_json() {
        $json_path = plugin_dir_path( dirname( __FILE__ ) ) . 'data/province.json';
        if ( file_exists( $json_path ) ) {
            $json_data = file_get_contents( $json_path );
            return json_decode( $json_data, true );
        }
        return array();
    }

    /**
     * ⚙️ PROCESSORE: Salva l'array delle province coperte nel profilo del contadino
     */
    public function salva_province_logistica_produttore() {
        if ( ! is_user_logged_in() ) return;
        
        if ( isset( $_POST['sm_produttore_nonce_field'] ) && wp_verify_nonce( $_POST['sm_produttore_nonce_field'], 'sm_save_produttore_logistic' ) ) {
            $user_id = get_current_user_id();

            // Solo i produttori o gli amministratori possono salvare questa mappa
            if ( current_user_can( 'vendor' ) || current_user_can( 'manage_woocommerce' ) ) {
                
                // Se non ha spuntato nessuna provincia, l'array sarà vuoto
                $province_scelte = isset( $_POST['sm_logistic_provinces'] ) ? array_map( 'sanitize_text_field', $_POST['sm_logistic_provinces'] ) : array();
                
                // Salviamo le province come array serializzato nel meta del profilo utente
                update_user_meta( $user_id, 'sm_producer_coverage_areas', $province_scelte );
                
                // Aggiungiamo un feedback visivo temporaneo di successo
                set_transient( 'sm_producer_save_success_' . $user_id, true, 30 );
                
                wp_redirect( $_SERVER['REQUEST_URI'] );
                exit;
            }
        }
    }

    /**
     * 🎨 RENDERING INTERFACCIA: Una griglia responsive con le 110 province d'Italia
     */
    public function renderizza_pannello_logistica_produttore() {
        if ( ! is_user_logged_in() ) return '<p>Effettua il login per gestire la tua logistica.</p>';
        
        $user_id = get_current_user_id();
        if ( ! current_user_can( 'vendor' ) && ! current_user_can( 'manage_woocommerce' ) ) {
            return '<p>Accesso riservato ai Produttori Agricoli della rete.</p>';
        }

        // Recuperiamo le province in cui il contadino consegna già
        $province_salvate = get_user_meta( $user_id, 'sm_producer_coverage_areas', true );
        if ( ! is_array( $province_salvate ) ) {
            $province_salvate = array(); // Default vuoto se è un nuovo contadino
        }

        $all_province = $this->carica_province_da_json();

        ob_start();
        
        // Messaggio di avvenuto salvataggio
        if ( get_transient( 'sm_producer_save_success_' . $user_id ) ) {
            echo '<div style="background:#c6f6d5; color:#22543d; padding:15px; margin-bottom:20px; border-radius:6px; font-weight:bold;">✅ Zone di consegna e logistica aggiornate con successo!</div>';
            delete_transient( 'sm_producer_save_success_' . $user_id );
        }
        ?>
        <div class="sm-producer-logistic-dashboard" style="background:#fff; border:1px solid #e2e8f0; padding:25px; border-radius:8px;">
            <h3 style="margin-top:0; color:#2f855a;">🚛 Mappa della tua copertura logistica</h3>
            <p style="color:#4a5568; font-size:14px; margin-bottom:20px;">Seleziona tutte le province italiane in cui sei in grado di effettuare consegne (sia per acquisti individuali che collettivi verso gli Hub). I tuoi prodotti appariranno solo all'interno dei panieri dei soci residenti nelle zone selezionate.</p>
            
            <form method="POST" action="">
                <?php wp_nonce_field( 'sm_save_produttore_logistic', 'sm_produttore_nonce_field' ); ?>
                
                <!-- Griglia CSS a 4 colonne nativa e responsive per le 110 province -->
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 12px; max-height: 400px; overflow-y: scroll; padding:15px; border:1px solid #cbd5e0; border-radius:6px; background:#f7fafc; margin-bottom:25px;">
                    <?php foreach ( $all_province as $sigla => $nome ) : ?>
                        <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; color:#2d3748; cursor:pointer; background: #fff; padding: 6px 10px; border-radius: 4px; border: 1px solid #edf2f7;">
                            <input type="checkbox" name="sm_logistic_provinces[]" value="<?php echo esc_attr( $sigla ); ?>" <?php checked( in_array( $sigla, $province_salvate ) ); ?> style="cursor:pointer;">
                            <strong><?php echo esc_html( $sigla ); ?></strong> - <?php echo esc_html( $nome ); ?>
                        </label>
                    <?php endforeach; ?>
                </div>

                <button type="submit" class="button alt" style="background: #2f855a; color: white; border: none; width: 100%; padding: 12px; font-weight: bold; border-radius: 6px; font-size:16px; cursor:pointer;">Salva Zone Coperture Logistica</button>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }
}
new SM_Producer_Onboarding();
