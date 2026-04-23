<?php
/**
 * Single post template.
 *
 * @package SecretFlowerShop
 */

get_header();
?>

<div class="container page-layout">
    <section class="content-area">
        <?php if ( have_posts() ) : ?>
            <?php while ( have_posts() ) : the_post(); ?>
                <?php
                $card_image = has_post_thumbnail()
                    ? get_the_post_thumbnail_url( get_the_ID(), 'large' )
                    : secret_flower_shop_get_fallback_flower_image( get_the_ID() );
                ?>
                <article <?php post_class(); ?>>
                    <h1 class="section-title"><?php the_title(); ?></h1>

                    <?php if ( $card_image ) : ?>
                        <div class="single-image">
                            <img src="<?php echo esc_url( $card_image ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy" />
                        </div>
                    <?php endif; ?>

                    <p class="price"><?php echo esc_html( secret_flower_shop_get_price( get_the_ID() ) ); ?></p>
                    <div class="entry-meta"><?php echo esc_html( get_the_date() ); ?></div>

                    <?php the_content(); ?>

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
                </article>
            <?php endwhile; ?>
        <?php endif; ?>
    </section>

    <?php get_sidebar( 'primary' ); ?>
</div>

<?php
get_footer();
