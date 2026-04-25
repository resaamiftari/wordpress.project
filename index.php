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
        <section class="flower-gallery" aria-label="Featured flowers">
            <h2 class="section-title"><?php esc_html_e( 'Featured Flowers', 'secret-flower-shop' ); ?></h2>
            <?php
            $flower_catalog_ids = secret_flower_shop_get_unique_flower_post_ids( 10 );
            ?>

            <?php if ( ! empty( $flower_catalog_ids ) ) : ?>
                <div class="flower-gallery__grid">
                    <?php foreach ( $flower_catalog_ids as $flower_post_id ) : ?>
                        <?php
                        $flower_image_url = has_post_thumbnail( $flower_post_id )
                            ? get_the_post_thumbnail_url( $flower_post_id, 'large' )
                            : secret_flower_shop_get_fallback_flower_image( $flower_post_id );
                        $flower_label     = get_the_title( $flower_post_id );
                        $flower_permalink = get_permalink( $flower_post_id );
                        ?>
                        <figure class="flower-gallery__item">
                            <?php if ( $flower_image_url ) : ?>
                                <a href="<?php echo esc_url( $flower_permalink ); ?>">
                                    <img src="<?php echo esc_url( $flower_image_url ); ?>" alt="<?php echo esc_attr( $flower_label ); ?>" loading="lazy" />
                                </a>
                            <?php endif; ?>
                            <figcaption>
                                <strong><?php echo esc_html( $flower_label ); ?></strong><br />
                                <a href="<?php echo esc_url( $flower_permalink ); ?>"><?php esc_html_e( 'View Flower', 'secret-flower-shop' ); ?></a>
                            </figcaption>
                        </figure>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <p><?php esc_html_e( 'No flower images found in assets/images/flowers.', 'secret-flower-shop' ); ?></p>
            <?php endif; ?>
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
