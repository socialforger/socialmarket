<?php
/**
 * Partial: Form Newsletter
 *
 * Percorso nel plugin:
 *   wp-content/plugins/socialmarket/templates/partials/form-newsletter.php
 *
 * Override nel tema:
 *   wp-content/themes/tuo-tema/socialmarket/partials/form-newsletter.php
 */

?>

<form class="sm-newsletter-form" method="post">

    <h3><?php _e( 'Iscriviti alla Newsletter', 'socialmarket' ); ?></h3>

    <p>
        <label>
            <?php _e( 'Email', 'socialmarket' ); ?><br>
            <input type="email" name="sm_newsletter_email" required>
        </label>
    </p>

    <p>
        <button type="submit" class="button">
            <?php _e( 'Iscriviti', 'socialmarket' ); ?>
        </button>
    </p>

    <?php
    // In futuro:
    // - integrazione con Newsletter_Manager
    // - segmentazione
    // - double opt-in
    ?>
</form>
