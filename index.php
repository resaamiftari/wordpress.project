<?php
/**
 * Main fallback template.
 *
 * @package SecretFlowerShop
 */

get_header();
?>

<div class="container page-layout">
    <section class="content-area">
        <?php // Add image files to assets/images/flowers/: tulips.png, lilies.png, roses.png ?>
        <section class="flower-gallery" aria-label="Featured flowers">
            <h2 class="section-title"><?php esc_html_e( 'Featured Flowers', 'secret-flower-shop' ); ?></h2>
            <div class="flower-gallery__grid">
                <figure class="flower-gallery__item">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/flowers/tulips.png' ); ?>" alt="Tulips" loading="lazy" />
                    <figcaption><?php esc_html_e( 'Tulips', 'secret-flower-shop' ); ?></figcaption>
                </figure>
                <figure class="flower-gallery__item">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/flowers/lilies.png' ); ?>" alt="Lilies" loading="lazy" />
                    <figcaption><?php esc_html_e( 'Lilies', 'secret-flower-shop' ); ?></figcaption>
                </figure>
                <figure class="flower-gallery__item">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/flowers/roses.png' ); ?>" alt="Roses" loading="lazy" />
                    <figcaption><?php esc_html_e( 'Roses', 'secret-flower-shop' ); ?></figcaption>
                </figure>
            </div>
        </section>

        <h1 class="section-title"><?php esc_html_e( 'Latest Stories', 'secret-flower-shop' ); ?></h1>

        <?php if ( have_posts() ) : ?>
            <?php while ( have_posts() ) : the_post(); ?>
                <article <?php post_class( 'card' ); ?>>
                    <?php if ( has_post_thumbnail() ) : ?>
                        <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail( 'large' ); ?>
                        </a>
                    <?php endif; ?>
                    <div class="card-content">
                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <div class="entry-meta"><?php echo esc_html( get_the_date() ); ?></div>
                        <?php the_excerpt(); ?>
                    </div>
                </article>
            <?php endwhile; ?>

            <?php the_posts_pagination(); ?>
        <?php else : ?>
            <p><?php esc_html_e( 'No posts found yet.', 'secret-flower-shop' ); ?></p>
        <?php endif; ?>
    </section>

    <?php get_sidebar( 'primary' ); ?>
</div>

<?php
get_footer();
