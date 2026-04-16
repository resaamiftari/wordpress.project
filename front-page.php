<?php
/**
 * Front page template.
 *
 * @package SecretFlowerShop
 */

get_header();
?>

<div class="container">
    <section class="hero">
        <h1><?php esc_html_e( 'Fresh Blossoms for Every Secret Moment', 'secret-flower-shop' ); ?></h1>
        <p><?php esc_html_e( 'Welcome to Secret Flower Shop, a gentle place for elegant bouquets and floral gifts.', 'secret-flower-shop' ); ?></p>
        <a class="btn" href="<?php echo esc_url( secret_flower_shop_get_shop_url() ); ?>"><?php esc_html_e( 'Shop Flowers', 'secret-flower-shop' ); ?></a>
    </section>

    <section>
        <h2 class="section-title"><?php esc_html_e( 'Featured Flowers', 'secret-flower-shop' ); ?></h2>

        <?php
        $featured_query = new WP_Query(
            array(
                'post_type'      => 'post',
                'posts_per_page' => 3,
                'category_name'  => 'flowers',
            )
        );
        ?>

        <?php if ( $featured_query->have_posts() ) : ?>
            <div class="grid">
                <?php while ( $featured_query->have_posts() ) : $featured_query->the_post(); ?>
                    <article <?php post_class( 'card' ); ?>>
                        <?php if ( has_post_thumbnail() ) : ?>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail( 'medium_large' ); ?>
                            </a>
                        <?php endif; ?>
                        <div class="card-content">
                            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <p class="price"><?php echo esc_html( secret_flower_shop_get_price( get_the_ID() ) ); ?></p>
                            <?php the_excerpt(); ?>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
            <?php wp_reset_postdata(); ?>
        <?php else : ?>
            <p><?php esc_html_e( 'No flower products yet. Add posts to the "Flowers" category in the dashboard.', 'secret-flower-shop' ); ?></p>
        <?php endif; ?>
    </section>
</div>

<?php
get_footer();
