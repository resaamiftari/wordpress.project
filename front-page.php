<?php
/**
 * Front page template.
 *
 * @package SecretFlowerShop
 */

get_header();

$shop_metrics = secret_flower_shop_get_shop_metrics();
?>

<div class="container">
    <section class="hero">
        <h1><?php esc_html_e( 'Fresh Blossoms for Every Secret Moment', 'secret-flower-shop' ); ?></h1>
        <p><?php esc_html_e( 'Welcome to Secret Flower Shop, a gentle place for elegant bouquets and floral gifts.', 'secret-flower-shop' ); ?></p>
        <a class="btn" href="<?php echo esc_url( secret_flower_shop_get_shop_url() ); ?>"><?php esc_html_e( 'Shop Flowers', 'secret-flower-shop' ); ?></a>

        <div class="hero-metrics" aria-label="<?php esc_attr_e( 'Shop highlights', 'secret-flower-shop' ); ?>">
            <div class="hero-metric">
                <strong><?php echo esc_html( number_format_i18n( (int) $shop_metrics['count'] ) ); ?></strong>
                <span><?php esc_html_e( 'Flower Products', 'secret-flower-shop' ); ?></span>
            </div>
            <div class="hero-metric">
                <strong><?php echo esc_html( secret_flower_shop_format_price( (float) $shop_metrics['avg_price'] ) ); ?></strong>
                <span><?php esc_html_e( 'Average Price', 'secret-flower-shop' ); ?></span>
            </div>
            <div class="hero-metric">
                <strong>
                    <?php
                    if ( $shop_metrics['min_price'] > 0 && $shop_metrics['max_price'] > 0 ) {
                        echo esc_html( secret_flower_shop_format_price( (float) $shop_metrics['min_price'] ) . ' - ' . secret_flower_shop_format_price( (float) $shop_metrics['max_price'] ) );
                    } else {
                        echo esc_html( __( 'Seasonal', 'secret-flower-shop' ) );
                    }
                    ?>
                </strong>
                <span><?php esc_html_e( 'Price Range', 'secret-flower-shop' ); ?></span>
            </div>
        </div>
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
                    <?php
                    $card_image = has_post_thumbnail()
                        ? get_the_post_thumbnail_url( get_the_ID(), 'medium_large' )
                        : secret_flower_shop_get_fallback_flower_image( get_the_ID() );
                    ?>
                    <article <?php post_class( 'card' ); ?>>
                        <?php if ( $card_image ) : ?>
                            <a href="<?php the_permalink(); ?>">
                                <img src="<?php echo esc_url( $card_image ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy" />
                            </a>
                        <?php endif; ?>
                        <div class="card-content">
                            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <p class="price"><?php echo esc_html( secret_flower_shop_get_price( get_the_ID() ) ); ?></p>
                            <?php the_excerpt(); ?>
                            <button
                                type="button"
                                class="btn bag-button"
                                data-bag-add
                                data-title="<?php echo esc_attr( get_the_title() ); ?>"
                                data-price="<?php echo esc_attr( secret_flower_shop_get_price( get_the_ID() ) ); ?>"
                                data-image="<?php echo esc_url( $card_image ); ?>"
                                data-url="<?php echo esc_url( get_permalink() ); ?>"
                            >
                                <?php esc_html_e( 'Add to Bag', 'secret-flower-shop' ); ?>
                            </button>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
            <?php wp_reset_postdata(); ?>
        <?php else : ?>
            <p><?php esc_html_e( 'No flower products yet. Add posts to the "Flowers" category in the dashboard.', 'secret-flower-shop' ); ?></p>
        <?php endif; ?>
    </section>

    <section class="home-highlights" aria-label="<?php esc_attr_e( 'Store promises', 'secret-flower-shop' ); ?>">
        <article class="home-highlight">
            <h3><?php esc_html_e( 'Same-Day Bouquet Prep', 'secret-flower-shop' ); ?></h3>
            <p><?php esc_html_e( 'Orders before noon are hand-arranged the same day for local delivery windows.', 'secret-flower-shop' ); ?></p>
        </article>
        <article class="home-highlight">
            <h3><?php esc_html_e( 'Freshness Promise', 'secret-flower-shop' ); ?></h3>
            <p><?php esc_html_e( 'Every stem is sourced in small batches and checked before arrangement.', 'secret-flower-shop' ); ?></p>
        </article>
        <article class="home-highlight">
            <h3><?php esc_html_e( 'Signature Floral Notes', 'secret-flower-shop' ); ?></h3>
            <p><?php esc_html_e( 'Each bouquet includes a care card and optional handwritten message.', 'secret-flower-shop' ); ?></p>
        </article>
    </section>
</div>

<?php
get_footer();
