<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class SM_Consumer_Onboarding {

    public function __construct() {
        add_action( 'init', array( $this, 'processa_selezione_provincia_cookie' ) );
        add_shortcode( 'sm_selettore_provincia_socio', array( $this, 'renderizza_form_provincia' ) );
        add_action( 'woocommerce_before_shop_loop', array( $this, 'mostra_banner_onboarding_shop' ), 5 );
    }

    private function carica_province_da_json() {
        $json_path = plugin_dir_path( dirname( __FILE__ ) ) . 'data/province.json';
        if ( file_exists( $json_path ) ) {
            $json_data = file_get_contents( $json_path );
            return json_decode( $json_data, true );
        }
        return array();
    }

    public function processa_selezione_provincia_cookie() {
        if ( isset( $_POST['sm_socio_nonce_field'] ) && wp_verify_nonce( $_POST['sm_socio_nonce_field'], 'sm_save_socio_province' ) ) {
            if ( ! empty( $_POST['sm_socio_province'] ) ) {
                $provincia = strtoupper( sanitize_text_field( $_POST['sm_socio_province'] ) );
                $province_valide = $this->carica_province_da_json();

                if ( array_key_exists( $provincia, $province_valide ) ) {
                    setcookie( 'sm_consumer_province', $provincia, time() + ( 86400 * 30 ), COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true );
                    if ( is_user_logged_in() ) {
                        update_user_meta( get_current_user_id(), 'sm_consumer_province', $provincia );
                    }
                    wp_redirect( $_SERVER['REQUEST_URI'] );
                    exit;
                }
            }
        }
    }

    public function renderizza_form_provincia() {
        $provincia_corrente = '';
        if ( is_user_logged_in() ) {
            $provincia_corrente = get_user_meta( get_current_user_id(), 'sm_consumer_province', true );
        }
        if ( empty( $provincia_corrente ) && isset( $_COOKIE['sm_consumer_province'] ) ) {
            $provincia_corrente = sanitize_text_field( $_COOKIE['sm_consumer_province'] );
        }
        
        $province_attive = $this->carica_province_da_json();

        ob_start();
        ?>
        <div class="sm-consumer-selector-box" style="max-width: 450px; margin: 15px auto; padding: 20px; border: 1px solid #cbd5e0; border-radius: 8px; background: #fff;">
            <form method="POST" action="">
                <?php wp_nonce_field( 'sm_save_socio_province', 'sm_socio_nonce_field' ); ?>
                <label style="display: block; font-weight: bold; margin-bottom: 10px; color: #2d3748;">📍 In quale provincia vuoi fare la spesa?</label>
                <div style="display: flex; gap: 10px;">
                    <select name="sm_socio_province" style="flex-grow: 1; padding: 10px; border-radius: 6px; border: 1px solid #cbd5e0;" required>
                        <option value="">Scegli...</option>
                        <?php foreach ( $province_attive as $sigla => $nome ) : ?>
                            <option value="<?php echo esc_attr( $sigla ); ?>" <?php selected( $provincia_corrente, $sigla ); ?>><?php echo esc_html( $nome ); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="button alt" style="background: #2f855a; color: white; border: none; padding: 10px 20px; border-radius: 6px; font-weight: bold;">Conferma</button>
                </div>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }

    public function mostra_banner_onboarding_shop() {
        $provincia = is_user_logged_in() ? get_user_meta( get_current_user_id(), 'sm_consumer_province', true ) : '';
        if ( empty( $provincia ) && isset( $_COOKIE['sm_consumer_province'] ) ) {
            $provincia = sanitize_text_field( $_COOKIE['sm_consumer_province'] );
        }

        if ( ! empty( $provincia ) ) {
            $province = $this->carica_province_da_json();
            echo '<div style="background: #ebf8ff; border-left: 4px solid #3182ce; padding: 12px; margin-bottom: 20px; color: #2b6cb0; border-radius: 4px;">🛒 Spesa locale filtrata per la zona di: <strong>' . esc_html($province[$provincia]) . '</strong></div>';
            return;
        }

        echo '<div style="background: #fff5f5; border: 1px solid #feb2b2; padding: 20px; margin-bottom: 30px; border-radius: 8px; text-align: center;">';
        echo '<h4 style="color: #c53030; margin-top:0;">⚠️ Catalogo non configurato</h4>';
        echo $this->renderizza_form_provincia();
        echo '</div>';
    }
}
new SM_Consumer_Onboarding();
