<?php
/**
 * Template: Singola Organizzazione
 *
 * Percorso nel plugin:
 *   wp-content/plugins/socialmarket/templates/organizzazione/single-organizzazione.php
 *
 * Override nel tema:
 *   wp-content/themes/tuo-tema/socialmarket/organizzazione/single-organizzazione.php
 */

get_header();
?>

<div class="sm-container sm-single-organizzazione">

    <h1 class="sm-title"><?php the_title(); ?></h1>

    <div class="sm-content">
        <?php the_content(); ?>
    </div>

    <div class="sm-meta sm-organizzazione-meta">
        <?php
        // Spazio per:
        // - categorie organizzazione
        // - contatti
        // - mappa
        // - eventi collegati
        ?>
    </div>

</div>

<?php
get_footer();
