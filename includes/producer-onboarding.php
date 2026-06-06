<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class SM_Producer_Onboarding {

    public function __construct() {
        add_action( 'init', array( $this, 'salva_province_logistica_produttore' ) );
        add_shortcode( 'sm_logistica_produttore', array( $this, 'renderizza_pannello_logistica_produttore' ) );
    }

    private function carica_province_da_json() {
        $json_path = plugin_dir_path( dirname( __FILE__ ) ) . 'data/province.json';
        if ( file_exists( $json_path ) ) {
            return json_decode( file_get_contents( $json_path ), true );
        }
        return array();
    }

    public function salva_province_logistica_produttore() {
        if ( ! is_user_logged_in() ) return;
        
        if ( isset( $_POST['sm_produttore_badge_nonce'] ) && wp_verify_nonce( $_POST['sm_produttore_badge_nonce'], 'sm_save_produttore_badges' ) ) {
            $user_id = get_current_user_id();
            $user = wp_get_current_user();

            if ( in_array( 'author', $user->roles ) || current_user_can( 'manage_woocommerce' ) ) {
                $province_scelte = isset( $_POST['sm_logistic_provinces'] ) ? array_map( 'sanitize_text_field', $_POST['sm_logistic_provinces'] ) : array();
                
                $province_totali = $this->carica_province_da_json();
                $province_filtrate = array_filter( $province_scelte, function($sigla) use ($province_totali) {
                    return array_key_exists( $sigla, $province_totali );
                });

                // Salvataggio nel campo nativo dei paesi di spedizione di WooCommerce
                update_user_meta( $user_id, 'shipping_countries', array_values($province_filtrate) );
                
                set_transient( 'sm_producer_save_success_' . $user_id, true, 30 );
                wp_redirect( $_SERVER['REQUEST_URI'] );
                exit;
            }
        }
    }

    public function renderizza_pannello_logistica_produttore() {
        if ( ! is_user_logged_in() ) return '<p>Effettua il login per gestire la tua logistica.</p>';
        
        $user_id = get_current_user_id();
        $user = wp_get_current_user();

        if ( ! in_array( 'author', $user->roles ) && ! current_user_can( 'manage_woocommerce' ) ) {
            return '<p>Accesso riservato ai Produttori Agricoli della rete.</p>';
        }

        $province_salvate = get_user_meta( $user_id, 'shipping_countries', true );
        if ( ! is_array( $province_salvate ) ) {
            $province_salvate = array();
        }

        $all_province = $this->carica_province_da_json();

        ob_start();
        
        if ( get_transient( 'sm_producer_save_success_' . $user_id ) ) {
            echo '<div style="background:#c6f6d5; color:#22543d; padding:15px; margin-bottom:20px; border-radius:6px; font-weight:bold;">✅ Zone operative aggiornate con successo!</div>';
            delete_transient( 'sm_producer_save_success_' . $user_id );
        }
        ?>
        <div class="sm-producer-logistic-dashboard" style="background:#fff; border:1px solid #e2e8f0; padding:25px; border-radius:8px; max-width: 600px; margin: 0 auto;">
            <h3 style="margin-top:0; color:#2f855a;">🚛 Dove operi con i tuoi prodotti?</h3>
            <p style="color:#4a5568; font-size:14px; margin-bottom:25px;">Indica i territori in cui sei in grado di distribuire la merce. I tuoi prodotti appariranno nello shop dei soci residenti esclusivamente nelle zone che aggiungerai a questa lista.</p>
            
            <div style="margin-bottom: 25px; min-height: 50px; display: flex; align-items: center;">
                <button type="button" id="sm_trigger_add_btn" style="background: #2b6cb0; color: white; border: none; padding: 12px 20px; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 14px; display: flex; align-items: center; gap: 8px; transition: background 0.2s;">
                    ➕ Aggiungi provincia dove operi
                </button>

                <select id="sm_dropdown_province" style="width: 100%; padding: 12px; border: 1px solid #cbd5e0; border-radius: 6px; background: #fff; font-size: 14px; height: 46px; display: none;">
                    <option value="">Seleziona la provincia dal listino...</option>
                    <?php foreach ( $all_province as $sigla => $nome ) : ?>
                        <option value="<?php echo esc_attr( $sigla ); ?>" data-nome="<?php echo esc_attr( $nome ); ?>">
                            <?php echo esc_html( $nome . ' (' . $sigla . ')' ); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <form method="POST" action="">
                <?php wp_nonce_field( 'sm_save_produttore_badges', 'sm_produttore_badge_nonce' ); ?>
                
                <h4 style="margin-bottom: 12px; color: #4a5568; font-size: 14px; font-weight: bold;">Le tue province di operatività attive:</h4>
                
                <div id="sm_badges_container" style="display: flex; flex-wrap: wrap; gap: 8px; padding: 15px; border: 1px solid #cbd5e0; border-radius: 6px; background: #f7fafc; min-height: 60px; margin-bottom: 25px; align-items: center;"></div>

                <button type="submit" style="background: #2f855a; color: white; border: none; width: 100%; padding: 12px; font-weight: bold; border-radius: 6px; font-size:16px; cursor:pointer;">
                    Salva Province Operative
                </button>
            </form>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const triggerBtn = document.getElementById('sm_trigger_add_btn');
            const dropdown = document.getElementById('sm_dropdown_province');
            const container = document.getElementById('sm_badges_container');
            
            const provinceIniziali = <?php echo json_encode($province_salvate); ?>;
            const dizionarioProvince = <?php echo json_encode($all_province); ?>;

            provinceIniziali.forEach(sigla => {
                if(dizionarioProvince[sigla]) {
                    creaBadge(sigla, dizionarioProvince[sigla]);
                }
            });

            triggerBtn.addEventListener('click', function() {
                triggerBtn.style.display = 'none';
                dropdown.style.display = 'block';
                dropdown.focus();
            });

            dropdown.addEventListener('change', function() {
                const sigla = dropdown.value;
                if (!sigla) return;

                const opzioneSelezionata = dropdown.options[dropdown.selectedIndex];
                const nome = opzioneSelezionata.getAttribute('data-nome');

                if (document.querySelector(`input[value="${sigla}"]`)) {
                    alert('Hai già aggiunto questa provincia.');
                    resetInterfaccia();
                    return;
                }

                creaBadge(sigla, nome);
                resetInterfaccia();
            });

            function resetInterfaccia() {
                dropdown.value = '';
                dropdown.style.display = 'none';
                triggerBtn.style.display = 'flex';
            }

            function creaBadge(sigla, nome) {
                const badge = document.createElement('div');
                badge.style.cssText = 'display: inline-flex; align-items: center; background: #ebf8ff; color: #2b6cb0; border: 1px solid #bee3f8; padding: 6px 12px; border-radius: 20px; font-size: 13px; font-weight: 500; gap: 8px;';
                
                badge.innerHTML = `
                    <span><strong>${sigla}</strong> - ${nome}</span>
                    <span class="sm-remove-badge" style="cursor: pointer; color: #e53e3e; font-weight: bold; font-size: 14px; line-height: 1;">&times;</span>
                    <input type="hidden" name="sm_logistic_provinces[]" value="${sigla}">
                `;

                badge.querySelector('.sm-remove-badge').addEventListener('click', function() {
                    badge.remove();
                });

                container.appendChild(badge);
            }
        });
        </script>
        <?php
        return ob_get_clean();
    }
}
new SM_Producer_Onboarding();
