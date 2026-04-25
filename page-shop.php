<?php
/**
 * Template for Shop page.
 *
 * Template Name: Shop Page
 *
 * @package SecretFlowerShop
 */

get_header();

$shop_filters = secret_flower_shop_get_shop_filters();
?>

<div class="container page-layout">
    <section class="content-area">
        <h1 class="section-title"><?php the_title(); ?></h1>
        <?php the_content(); ?>

        <form class="shop-filters" method="get" action="<?php echo esc_url( get_permalink() ); ?>">
            <div class="shop-filters__field">
                <label for="shop-search"><?php esc_html_e( 'Search Flowers', 'secret-flower-shop' ); ?></label>
                <input id="shop-search" type="search" name="s" value="<?php echo esc_attr( $shop_filters['s'] ); ?>" placeholder="<?php esc_attr_e( 'Rose, tulip, lily...', 'secret-flower-shop' ); ?>" />
            </div>

            <div class="shop-filters__field">
                <label for="shop-min-price"><?php esc_html_e( 'Min Price', 'secret-flower-shop' ); ?></label>
                <input id="shop-min-price" type="number" name="min_price" min="0" step="0.01" value="<?php echo esc_attr( $shop_filters['min_price'] > 0 ? $shop_filters['min_price'] : '' ); ?>" placeholder="0" />
            </div>

            <div class="shop-filters__field">
                <label for="shop-max-price"><?php esc_html_e( 'Max Price', 'secret-flower-shop' ); ?></label>
                <input id="shop-max-price" type="number" name="max_price" min="0" step="0.01" value="<?php echo esc_attr( $shop_filters['max_price'] > 0 ? $shop_filters['max_price'] : '' ); ?>" placeholder="99" />
            </div>

            <div class="shop-filters__field">
                <label for="shop-sort"><?php esc_html_e( 'Sort', 'secret-flower-shop' ); ?></label>
                <select id="shop-sort" name="sort">
                    <option value="newest" <?php selected( $shop_filters['sort'], 'newest' ); ?>><?php esc_html_e( 'Newest', 'secret-flower-shop' ); ?></option>
                    <option value="price_asc" <?php selected( $shop_filters['sort'], 'price_asc' ); ?>><?php esc_html_e( 'Price: Low to High', 'secret-flower-shop' ); ?></option>
                    <option value="price_desc" <?php selected( $shop_filters['sort'], 'price_desc' ); ?>><?php esc_html_e( 'Price: High to Low', 'secret-flower-shop' ); ?></option>
                    <option value="title_asc" <?php selected( $shop_filters['sort'], 'title_asc' ); ?>><?php esc_html_e( 'Name: A-Z', 'secret-flower-shop' ); ?></option>
                </select>
            </div>

            <div class="shop-filters__actions">
                <button type="submit" class="btn"><?php esc_html_e( 'Apply Filters', 'secret-flower-shop' ); ?></button>
                <a class="btn btn--ghost" href="<?php echo esc_url( get_permalink() ); ?>"><?php esc_html_e( 'Reset', 'secret-flower-shop' ); ?></a>
            </div>
        </form>

        <?php
        $paged = (int) get_query_var( 'paged' );
        if ( $paged < 1 ) {
            $paged = 1;
        }

        $shop_query = new WP_Query(
            secret_flower_shop_get_shop_query_args(
                array(
                    'paged' => $paged,
                )
            )
        );
        ?>

        <?php if ( $shop_query->have_posts() ) : ?>
            <div class="grid">
                <?php while ( $shop_query->have_posts() ) : $shop_query->the_post(); ?>
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
                            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
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

            <div class="pagination-wrap">
                <?php
                $query_params = array();

                if ( '' !== $shop_filters['s'] ) {
                    $query_params['s'] = $shop_filters['s'];
                }

                if ( $shop_filters['min_price'] > 0 ) {
                    $query_params['min_price'] = $shop_filters['min_price'];
                }

                if ( $shop_filters['max_price'] > 0 ) {
                    $query_params['max_price'] = $shop_filters['max_price'];
                }

                if ( 'newest' !== $shop_filters['sort'] ) {
                    $query_params['sort'] = $shop_filters['sort'];
                }

                echo wp_kses_post(
                    paginate_links(
                        array(
                            'total'   => (int) $shop_query->max_num_pages,
                            'current' => $paged,
                            'base'    => str_replace( 999999999, '%#%', esc_url_raw( get_pagenum_link( 999999999 ) ) ),
                            'format'  => '?paged=%#%',
                            'add_args'=> $query_params,
                        )
                    )
                );
                ?>
            </div>
            <?php wp_reset_postdata(); ?>
        <?php else : ?>
            <p><?php esc_html_e( 'No flower products found right now. Please add posts under the Flowers category.', 'secret-flower-shop' ); ?></p>
        <?php endif; ?>
    </section>

    <?php get_sidebar( 'primary' ); ?>
</div>

<?php
get_footer();
