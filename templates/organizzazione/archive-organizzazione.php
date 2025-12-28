<?php
/**
 * Template: Archivio Organizzazioni
 *
 * Percorso nel plugin:
 *   wp-content/plugins/socialmarket/templates/organizzazione/archive-organizzazione.php
 *
 * Override nel tema:
 *   wp-content/themes/tuo-tema/socialmarket/organizzazione/archive-organizzazione.php
 */

get_header();
?>

<div class="sm-container sm-archive-organizzazione">

    <h1 class="sm-title">
        <?php _e( 'Organizzazioni', 'socialmarket' ); ?>
    </h1>

    <?php if ( have_posts() ) : ?>
        <div class="sm-grid sm-grid-organizzazione">
            <?php while ( have_posts() ) : the_post(); ?>
                <article class="sm-card sm-card-organizzazione">
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

        <p><?php _e( 'Nessuna organizzazione trovata.', 'socialmarket' ); ?></p>

    <?php endif; ?>

</div>

<?php
get_footer();
