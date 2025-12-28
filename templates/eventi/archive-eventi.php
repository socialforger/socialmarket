<?php
/**
 * Template: Archivio Eventi
 *
 * Percorso nel plugin:
 *   wp-content/plugins/socialmarket/templates/eventi/archive-eventi.php
 *
 * Override nel tema:
 *   wp-content/themes/tuo-tema/socialmarket/eventi/archive-eventi.php
 */

get_header();
?>

<div class="sm-container sm-archive-eventi">

    <h1 class="sm-title">
        <?php _e( 'Eventi', 'socialmarket' ); ?>
    </h1>

    <?php if ( have_posts() ) : ?>
        <div class="sm-grid sm-grid-eventi">
            <?php while ( have_posts() ) : the_post(); ?>
                <article class="sm-card sm-card-evento">

                    <h2 class="sm-card-title">
                        <a href="<?php the_permalink(); ?>">
                            <?php the_title(); ?>
                        </a>
                    </h2>

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

        <div class="sm-pagination">
            <?php the_posts_pagination(); ?>
        </div>

    <?php else : ?>

        <p><?php _e( 'Nessun evento trovato.', 'socialmarket' ); ?></p>

    <?php endif; ?>

</div>

<?php
get_footer();
