<?php
/**
 * Header template.
 *
 * @package SecretFlowerShop
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="site-wrapper">
    <header class="site-header">
        <div class="container header-inner">
            <div class="site-branding">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <?php bloginfo( 'name' ); ?>
                </a>
            </div>

            <button class="menu-toggle" aria-expanded="false" aria-controls="primary-navigation">
                <?php esc_html_e( 'Menu', 'secret-flower-shop' ); ?>
            </button>

            <nav id="primary-navigation" class="site-nav" aria-label="<?php esc_attr_e( 'Primary Menu', 'secret-flower-shop' ); ?>">
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'primary',
                        'menu_class'     => 'primary-menu',
                        'container'      => false,
                        'fallback_cb'    => 'wp_page_menu',
                    )
                );
                ?>
            </nav>

            <div class="header-search">
                <?php get_search_form(); ?>
            </div>

            <a class="bag-link" href="#shop-bag" aria-controls="shop-bag">
                <?php esc_html_e( 'Bag', 'secret-flower-shop' ); ?>
                <span class="bag-count" data-bag-count>0</span>
            </a>
        </div>
    </header>

    <main class="site-main">
