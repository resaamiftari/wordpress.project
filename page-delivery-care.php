<?php
/**
 * Template for Delivery & Care page.
 *
 * Template Name: Delivery & Care Page
 *
 * @package SecretFlowerShop
 */

get_header();
?>

<div class="container page-layout">
    <section class="content-area">
        <?php if ( have_posts() ) : ?>
            <?php while ( have_posts() ) : the_post(); ?>
                <article <?php post_class(); ?>>
                    <h1 class="section-title"><?php the_title(); ?></h1>

                    <div class="home-highlights" aria-label="<?php esc_attr_e( 'Delivery information', 'secret-flower-shop' ); ?>">
                        <article class="home-highlight">
                            <h3><?php esc_html_e( 'Delivery Windows', 'secret-flower-shop' ); ?></h3>
                            <p><?php esc_html_e( 'Mon-Sat: 9:00 AM to 7:00 PM. Orders before 12:00 PM qualify for same-day local delivery.', 'secret-flower-shop' ); ?></p>
                        </article>
                        <article class="home-highlight">
                            <h3><?php esc_html_e( 'Service Zones', 'secret-flower-shop' ); ?></h3>
                            <p><?php esc_html_e( 'City center, North District, and Riverside areas. Extend zones and delivery fees in page content below.', 'secret-flower-shop' ); ?></p>
                        </article>
                        <article class="home-highlight">
                            <h3><?php esc_html_e( 'Care Tips', 'secret-flower-shop' ); ?></h3>
                            <p><?php esc_html_e( 'Trim stems by 1cm, refresh water every 2 days, and keep bouquets away from direct heat.', 'secret-flower-shop' ); ?></p>
                        </article>
                    </div>

                    <?php the_content(); ?>
                </article>
            <?php endwhile; ?>
        <?php endif; ?>
    </section>

    <?php get_sidebar( 'primary' ); ?>
</div>

<?php
get_footer();
