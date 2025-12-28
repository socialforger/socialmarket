<?php
/**
 * Partial: Blocco Prossima Consegna
 *
 * Percorso nel plugin:
 *   wp-content/plugins/socialmarket/templates/partials/blocco-prossima-consegna.php
 *
 * Override nel tema:
 *   wp-content/themes/tuo-tema/socialmarket/partials/blocco-prossima-consegna.php
 */

?>

<div class="sm-prossima-consegna">
    <h3><?php _e( 'Prossima Consegna', 'socialmarket' ); ?></h3>

    <p><?php _e( 'Le informazioni sulla prossima consegna saranno disponibili prossimamente.', 'socialmarket' ); ?></p>

    <?php
    // In futuro:
    // - integrazione con Logistics_Sessioni_Ritiro
    // - data / ora / luogo
    // - stato ordini
    ?>
</div>
