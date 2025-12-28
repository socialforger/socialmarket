<?php
/**
 * Template: Singolo Punto di Ritiro
 *
 * Percorso nel plugin:
 *   wp-content/plugins/socialmarket/templates/punti-ritiro/single-punto-ritiro.php
 *
 * Override nel tema:
 *   wp-content/themes/tuo-tema/socialmarket/punti-ritiro/single-punto-ritiro.php
 */

get_header();
?>

<div class="sm-container sm-single-punto-ritiro">

    <h1 class="sm-title"><?php the_title(); ?></h1>

    <div class="sm-content">
        <?php the_content(); ?>
    </div>

    <div class="sm-meta sm-punto-ritiro-meta">
        <?php
        // Qui in futuro potremo aggiungere:
        // - indirizzo
        // - orari
        // - mappa
        // - sessioni di ritiro collegate
        ?>
    </div>

</div>

<?php
get_footer();
