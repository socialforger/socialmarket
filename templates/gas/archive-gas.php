<?php
/**
 * Template: Archivio GAS
 * Override path: wp-content/plugins/socialmarket/templates/gas/archive-gas.php
 */

get_header();
?>

<div class="sm-container sm-archive-gas">

    <h1 class="sm-title">
        <?php _e( 'Gruppi di Acquisto Solidale', 'socialmarket' ); ?>
    </h1>

    <?php if ( have_posts() ) : ?>
        <div class="sm-grid sm-grid-gas">
            <?php while ( have_posts() ) : the_post(); ?>
                <article class="sm-card sm-card-gas">
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

        <p><?php _e( 'Nessun GAS trovato.', 'socialmarket' ); ?></p>

    <?php endif; ?>

</div>

<?php
get_footer();
