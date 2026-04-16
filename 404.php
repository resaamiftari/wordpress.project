<?php
/**
 * 404 template.
 *
 * @package SecretFlowerShop
 */

get_header();
?>

<div class="container">
    <section class="content-area not-found">
        <h1 class="section-title"><?php esc_html_e( 'Oops, this page has wilted.', 'secret-flower-shop' ); ?></h1>
        <p><?php esc_html_e( 'The page you are looking for does not exist, but you can still explore our flowers.', 'secret-flower-shop' ); ?></p>
        <p><a class="btn" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to Home', 'secret-flower-shop' ); ?></a></p>
    </section>
</div>

<?php
get_footer();
