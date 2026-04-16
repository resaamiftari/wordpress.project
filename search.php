<?php
/**
 * Search results template.
 *
 * @package SecretFlowerShop
 */

get_header();
?>

<div class="container page-layout">
    <section class="content-area">
        <h1 class="section-title">
            <?php
            printf(
                esc_html__( 'Search Results for: %s', 'secret-flower-shop' ),
                '<span>' . esc_html( get_search_query() ) . '</span>'
            );
            ?>
        </h1>

        <?php if ( have_posts() ) : ?>
            <?php while ( have_posts() ) : the_post(); ?>
                <article <?php post_class( 'card' ); ?>>
                    <div class="card-content">
                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <?php the_excerpt(); ?>
                    </div>
                </article>
            <?php endwhile; ?>

            <?php the_posts_pagination(); ?>
        <?php else : ?>
            <p><?php esc_html_e( 'No results found. Try another keyword.', 'secret-flower-shop' ); ?></p>
            <?php get_search_form(); ?>
        <?php endif; ?>
    </section>

    <?php get_sidebar( 'primary' ); ?>
</div>

<?php
get_footer();
