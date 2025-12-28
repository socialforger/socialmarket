<?php
/**
 * Template: Singolo Evento
 *
 * Percorso nel plugin:
 *   wp-content/plugins/socialmarket/templates/eventi/single-evento.php
 *
 * Override nel tema:
 *   wp-content/themes/tuo-tema/socialmarket/eventi/single-evento.php
 */

get_header();
?>

<div class="sm-container sm-single-evento">

    <h1 class="sm-title"><?php the_title(); ?></h1>

    <div class="sm-meta sm-evento-meta">
        <?php
        echo get_the_term_list(
            get_the_ID(),
            'tipo_evento',
            '<span class="sm-event-type">',
            ', ',
            '</span>'
        );
        ?>
    </div>

    <div class="sm-content">
        <?php the_content(); ?>
    </div>

    <div class="sm-extra sm-evento-extra">
        <?php
        // Qui in futuro potremo aggiungere:
        // - data evento
        // - luogo
        // - organizzatore
        // - eventi correlati
        ?>
    </div>

</div>

<?php
get_footer();
