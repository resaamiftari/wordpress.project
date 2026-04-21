<?php
/**
 * Footer template.
 *
 * @package SecretFlowerShop
 */
?>
    </main>

    <footer class="site-footer">
        <div class="container footer-inner">
            <p><?php esc_html_e( 'Secret Flower Shop - 12 Rose Lane, Blossom City', 'secret-flower-shop' ); ?></p>
            <p>
                <?php esc_html_e( 'Phone: +1 234 567 890', 'secret-flower-shop' ); ?> |
                <a href="https://instagram.com" target="_blank" rel="noopener noreferrer">Instagram</a> |
                <a href="https://facebook.com" target="_blank" rel="noopener noreferrer">Facebook</a>
            </p>
        </div>
    </footer>

    <aside id="shop-bag" class="shop-bag" aria-label="<?php esc_attr_e( 'Shopping bag', 'secret-flower-shop' ); ?>">
        <div class="shop-bag__header">
            <h2><?php esc_html_e( 'Your Flower Bag', 'secret-flower-shop' ); ?></h2>
            <button type="button" class="shop-bag__close" data-bag-close aria-label="<?php esc_attr_e( 'Close bag', 'secret-flower-shop' ); ?>">&times;</button>
        </div>

        <p class="shop-bag__summary">
            <span data-bag-summary-count>0</span>
            <?php esc_html_e( 'flowers collected', 'secret-flower-shop' ); ?>
        </p>

        <div class="shop-bag__body" data-bag-items>
            <p class="shop-bag__empty"><?php esc_html_e( 'Your bag is empty.', 'secret-flower-shop' ); ?></p>
        </div>

        <div class="shop-bag__footer">
            <p class="shop-bag__note"><?php esc_html_e( 'This is a simple preview bag, not a checkout system.', 'secret-flower-shop' ); ?></p>
            <button type="button" class="btn btn--ghost" data-bag-clear><?php esc_html_e( 'Clear Bag', 'secret-flower-shop' ); ?></button>
        </div>
    </aside>
</div>

<?php wp_footer(); ?>
</body>
</html>
