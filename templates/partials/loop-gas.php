<?php
/**
 * Partial: Loop GAS
 *
 * Percorso nel plugin:
 *   wp-content/plugins/socialmarket/templates/partials/loop-gas.php
 *
 * Override nel tema:
 *   wp-content/themes/tuo-tema/socialmarket/partials/loop-gas.php
 */

if ( ! have_posts() ) {
    echo '<p>' . __( 'Nessun GAS disponibile.', 'socialmarket' ) . '</p>';
    return;
}
?>

<div class="sm-grid sm-grid-gas">
    <?php while ( have_posts() ) : the_post(); ?>
        <article class="sm-card sm-card-gas">
            <h3 class="sm-card-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h3>
            <div class="sm-card-excerpt">
                <?php the_excerpt(); ?>
            </div>
        </article>
    <?php endwhile; ?>
</div>
