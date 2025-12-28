<?php
/**
 * Partial: Loop Eventi
 *
 * Percorso nel plugin:
 *   wp-content/plugins/socialmarket/templates/partials/loop-evento.php
 *
 * Override nel tema:
 *   wp-content/themes/tuo-tema/socialmarket/partials/loop-evento.php
 */

if ( ! have_posts() ) {
    echo '<p>' . __( 'Nessun evento disponibile.', 'socialmarket' ) . '</p>';
    return;
}
?>

<div class="sm-grid sm-grid-eventi">
    <?php while ( have_posts() ) : the_post(); ?>
        <article class="sm-card sm-card-evento">

            <h3 class="sm-card-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h3>

            <div class="sm-card-meta">
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

            <div class="sm-card-excerpt">
                <?php the_excerpt(); ?>
            </div>

        </article>
    <?php endwhile; ?>
</div>
