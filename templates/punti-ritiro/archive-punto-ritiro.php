<?php
/**
 * Template: Archivio Punti di Ritiro
 *
 * Percorso nel plugin:
 *   wp-content/plugins/socialmarket/templates/punti-ritiro/archive-punto-ritiro.php
 *
 * Override nel tema:
 *   wp-content/themes/tuo-tema/socialmarket/punti-ritiro/archive-punto-ritiro.php
 */

get_header();
?>

<div class="sm-container sm-archive-punti-ritiro">

    <h1 class="sm-title">
        <?php _e( 'Punti di Ritiro', 'socialmarket' ); ?>
    </h1>

    <?php if ( have_posts() ) : ?>
        <div class="sm-grid sm-grid-punti-ritiro">
            <?php while ( have_posts() ) : the_post(); ?>
                <article class="sm-card sm-card-punto-ritiro">

                    <h2 class="sm-card-title">
                        <a href="<?php the_permalink(); ?>">
                            <?php the_title(); ?>
                        </a>
                    </h2>

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

        <p><?php _e( 'Nessun punto di ritiro trovato.', 'socialmarket' ); ?></p>

    <?php endif; ?>

</div>

<?php
get_footer();
