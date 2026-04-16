<?php
/**
 * Primary sidebar.
 *
 * @package SecretFlowerShop
 */
?>
<aside class="sidebar" aria-label="<?php esc_attr_e( 'Sidebar', 'secret-flower-shop' ); ?>">
    <?php if ( is_active_sidebar( 'primary-sidebar' ) ) : ?>
        <?php dynamic_sidebar( 'primary-sidebar' ); ?>
    <?php else : ?>
        <section class="widget">
            <h2 class="widget-title"><?php esc_html_e( 'Flower Categories', 'secret-flower-shop' ); ?></h2>
            <ul>
                <?php
                wp_list_categories(
                    array(
                        'title_li' => '',
                    )
                );
                ?>
            </ul>
        </section>
    <?php endif; ?>
</aside>
