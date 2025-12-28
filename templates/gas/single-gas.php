<?php
/**
 * Template: Singolo GAS
 *
 * Percorso nel plugin:
 *   wp-content/plugins/socialmarket/templates/gas/single-gas.php
 *
 * Override nel tema:
 *   wp-content/themes/tuo-tema/socialmarket/gas/single-gas.php
 */

get_header();
?>

<div class="sm-container sm-single-gas">

    <h1 class="sm-title"><?php the_title(); ?></h1>

    <div class="sm-content">
        <?php the_content(); ?>
    </div>

    <div class="sm-meta sm-gas-meta">
        <?php
        // Spazio per:
        // - membri del GAS
        // - sessioni di ritiro
        // - prossima consegna
        // - calendario eventi
        ?>
    </div>

</div>

<?php
get_footer();
