<?php
/**
 * Template for Shop page.
 *
 * Template Name: Shop Page
 *
 * @package SecretFlowerShop
 */

get_header();
?>

<div class="container page-layout">
    <section class="content-area">
        <h1 class="section-title"><?php the_title(); ?></h1>
        <?php the_content(); ?>

        <?php
        $paged = (int) get_query_var( 'paged' );
        if ( $paged < 1 ) {
            $paged = 1;
        }

        $shop_query = new WP_Query(
            array(
                'post_type'      => 'post',
                'posts_per_page' => 9,
                'category_name'  => 'flowers',
                'paged'          => $paged,
            )
        );
        ?>

        <?php if ( $shop_query->have_posts() ) : ?>
            <div class="grid">
                <?php while ( $shop_query->have_posts() ) : $shop_query->the_post(); ?>
                    <article <?php post_class( 'card' ); ?>>
                        <?php if ( has_post_thumbnail() ) : ?>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail( 'medium_large' ); ?>
                            </a>
                        <?php endif; ?>

                        <div class="card-content">
                            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                            <p class="price"><?php echo esc_html( secret_flower_shop_get_price( get_the_ID() ) ); ?></p>
                            <?php the_excerpt(); ?>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <div class="pagination-wrap">
                <?php
                echo wp_kses_post(
                    paginate_links(
                        array(
                            'total'   => (int) $shop_query->max_num_pages,
                            'current' => $paged,
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
