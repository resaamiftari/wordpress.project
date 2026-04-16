<?php
/**
 * Theme setup and helper functions.
 *
 * @package SecretFlowerShop
 */

if ( ! function_exists( 'secret_flower_shop_setup' ) ) {
    /**
     * Register core theme supports and menu locations.
     */
    function secret_flower_shop_setup() {
        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );

        add_theme_support(
            'html5',
            array(
                'search-form',
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
                'style',
                'script',
            )
        );

        register_nav_menus(
            array(
                'primary' => __( 'Primary Menu', 'secret-flower-shop' ),
            )
        );
    }
}
add_action( 'after_setup_theme', 'secret_flower_shop_setup' );

/**
 * Enqueue theme styles and scripts.
 */
function secret_flower_shop_scripts() {
    wp_enqueue_style(
        'secret-flower-shop-style',
        get_stylesheet_uri(),
        array(),
        wp_get_theme()->get( 'Version' )
    );

    wp_enqueue_script(
        'secret-flower-shop-script',
        get_template_directory_uri() . '/assets/js/theme.js',
        array(),
        wp_get_theme()->get( 'Version' ),
        true
    );
}
add_action( 'wp_enqueue_scripts', 'secret_flower_shop_scripts' );

/**
 * Register a simple primary sidebar.
 */
function secret_flower_shop_widgets_init() {
    register_sidebar(
        array(
            'name'          => __( 'Primary Sidebar', 'secret-flower-shop' ),
            'id'            => 'primary-sidebar',
            'description'   => __( 'Main sidebar area.', 'secret-flower-shop' ),
            'before_widget' => '<section class="widget">',
            'after_widget'  => '</section>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        )
    );
}
add_action( 'widgets_init', 'secret_flower_shop_widgets_init' );

/**
 * Create the Flowers category automatically if it does not exist yet.
 */
function secret_flower_shop_create_flower_category() {
    if ( ! term_exists( 'flowers', 'category' ) ) {
        wp_insert_term(
            'Flowers',
            'category',
            array(
                'slug' => 'flowers',
            )
        );
    }
}
add_action( 'after_switch_theme', 'secret_flower_shop_create_flower_category' );

/**
 * Create the core pages if they do not exist.
 */
function secret_flower_shop_create_core_pages() {
    $pages = array(
        'home'  => array(
            'post_title'   => 'Home',
            'post_content' => 'Welcome to Secret Flower Shop.',
        ),
        'shop'  => array(
            'post_title'   => 'Shop',
            'post_content' => 'Browse our flower collection below.',
        ),
        'about' => array(
            'post_title'   => 'About',
            'post_content' => 'Share your flower shop story here from the page editor.',
        ),
    );

    foreach ( $pages as $slug => $page_data ) {
        if ( ! get_page_by_path( $slug ) ) {
            wp_insert_post(
                array(
                    'post_type'    => 'page',
                    'post_status'  => 'publish',
                    'post_title'   => $page_data['post_title'],
                    'post_name'    => $slug,
                    'post_content' => $page_data['post_content'],
                )
            );
        }
    }
}

/**
 * Set front page to the Home page when possible.
 */
function secret_flower_shop_set_front_page() {
    $home_page = get_page_by_path( 'home' );

    if ( $home_page ) {
        update_option( 'show_on_front', 'page' );
        update_option( 'page_on_front', (int) $home_page->ID );
    }
}

/**
 * Create demo flower posts if there are no flower products yet.
 */
function secret_flower_shop_create_demo_flowers() {
    $flowers_term = get_term_by( 'slug', 'flowers', 'category' );

    if ( ! $flowers_term || is_wp_error( $flowers_term ) ) {
        return;
    }

    $existing_products = get_posts(
        array(
            'post_type'      => 'post',
            'posts_per_page' => 1,
            'category_name'  => 'flowers',
            'fields'         => 'ids',
        )
    );

    if ( ! empty( $existing_products ) ) {
        return;
    }

    $products = array(
        array(
            'title'   => 'Classic Red Roses',
            'content' => 'A timeless bouquet of velvety red roses, perfect for romantic gifts and special celebrations.',
            'price'   => '$39.00',
        ),
        array(
            'title'   => 'Soft Pink Tulips',
            'content' => 'Fresh pink tulips with a delicate fragrance, arranged in a minimalist floral wrap.',
            'price'   => '$31.00',
        ),
        array(
            'title'   => 'White Lily Harmony',
            'content' => 'Elegant white lilies paired with soft greenery for a calm and graceful centerpiece.',
            'price'   => '$36.00',
        ),
        array(
            'title'   => 'Spring Garden Mix',
            'content' => 'A seasonal blend of tulips, roses, and daisies in pastel tones for everyday joy.',
            'price'   => '$34.00',
        ),
    );

    foreach ( $products as $product ) {
        $post_id = wp_insert_post(
            array(
                'post_type'    => 'post',
                'post_status'  => 'publish',
                'post_title'   => $product['title'],
                'post_content' => $product['content'],
                'post_excerpt' => wp_trim_words( $product['content'], 18 ),
            )
        );

        if ( $post_id && ! is_wp_error( $post_id ) ) {
            wp_set_post_terms( $post_id, array( (int) $flowers_term->term_id ), 'category' );
            update_post_meta( $post_id, 'price', $product['price'] );
        }
    }
}

/**
 * Create a primary menu and assign Home, Shop, and About links when missing.
 */
function secret_flower_shop_create_primary_menu() {
    $locations = get_nav_menu_locations();

    if ( ! empty( $locations['primary'] ) ) {
        return;
    }

    $menu_name = 'Primary Menu';
    $menu_id   = wp_create_nav_menu( $menu_name );

    if ( is_wp_error( $menu_id ) ) {
        return;
    }

    $page_slugs = array( 'home', 'shop', 'about' );

    foreach ( $page_slugs as $slug ) {
        $page = get_page_by_path( $slug );

        if ( $page ) {
            wp_update_nav_menu_item(
                $menu_id,
                0,
                array(
                    'menu-item-title'     => $page->post_title,
                    'menu-item-object'    => 'page',
                    'menu-item-object-id' => (int) $page->ID,
                    'menu-item-type'      => 'post_type',
                    'menu-item-status'    => 'publish',
                )
            );
        }
    }

    $locations['primary'] = (int) $menu_id;
    set_theme_mod( 'nav_menu_locations', $locations );
}

/**
 * Run first-time setup for pages, menu, and sample products.
 */
function secret_flower_shop_run_first_time_setup() {
    $is_setup_done = (bool) get_option( 'secret_flower_shop_setup_done', false );

    if ( $is_setup_done ) {
        return;
    }

    secret_flower_shop_create_flower_category();
    secret_flower_shop_create_core_pages();
    secret_flower_shop_set_front_page();
    secret_flower_shop_create_demo_flowers();
    secret_flower_shop_create_primary_menu();

    update_option( 'secret_flower_shop_setup_done', 1 );
}
add_action( 'after_switch_theme', 'secret_flower_shop_run_first_time_setup' );
add_action( 'admin_init', 'secret_flower_shop_run_first_time_setup' );

/**
 * Resolve the best available Shop URL.
 *
 * @return string
 */
function secret_flower_shop_get_shop_url() {
    $shop_page = get_page_by_path( 'shop' );

    if ( $shop_page ) {
        return get_permalink( $shop_page );
    }

    $flowers_category = get_category_by_slug( 'flowers' );
    if ( $flowers_category ) {
        return get_category_link( $flowers_category->term_id );
    }

    return home_url( '/' );
}

/**
 * Helper for displaying a product price from custom fields.
 * If no custom value exists, a friendly default is shown.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function secret_flower_shop_get_price( $post_id ) {
    $raw_price = get_post_meta( $post_id, 'price', true );

    if ( '' === $raw_price ) {
        return __( '$29.00', 'secret-flower-shop' );
    }

    return wp_strip_all_tags( $raw_price );
}

/**
 * Handle dismiss action for the admin setup notice.
 */
function secret_flower_shop_handle_notice_dismiss() {
    if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
        return;
    }

    if ( empty( $_GET['sfs_dismiss_notice'] ) || '1' !== $_GET['sfs_dismiss_notice'] ) {
        return;
    }

    check_admin_referer( 'sfs_dismiss_notice' );
    update_option( 'secret_flower_shop_notice_dismissed', 1 );

    wp_safe_redirect( remove_query_arg( array( 'sfs_dismiss_notice', '_wpnonce' ) ) );
    exit;
}
add_action( 'admin_init', 'secret_flower_shop_handle_notice_dismiss' );

/**
 * Show a quick dashboard guide for adding flower products.
 */
function secret_flower_shop_admin_quick_guide_notice() {
    if ( ! current_user_can( 'edit_posts' ) ) {
        return;
    }

    if ( get_option( 'secret_flower_shop_notice_dismissed', false ) ) {
        return;
    }

    $screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
    if ( ! $screen || 'dashboard' !== $screen->id ) {
        return;
    }

    $new_post_url  = admin_url( 'post-new.php' );
    $posts_url     = admin_url( 'edit.php' );
    $categories_url = admin_url( 'edit-tags.php?taxonomy=category' );
    $dismiss_url   = wp_nonce_url(
        add_query_arg( 'sfs_dismiss_notice', '1', admin_url() ),
        'sfs_dismiss_notice'
    );
    ?>
    <div class="notice notice-info">
        <p><strong><?php esc_html_e( 'Secret Flower Shop Quick Guide', 'secret-flower-shop' ); ?></strong></p>
        <p>
            <?php esc_html_e( 'To add products, create a Post and set Category to "Flowers".', 'secret-flower-shop' ); ?>
            <?php esc_html_e( 'Then add a Featured Image and optional custom field: price (example: $35.00).', 'secret-flower-shop' ); ?>
        </p>
        <p>
            <a class="button button-primary" href="<?php echo esc_url( $new_post_url ); ?>"><?php esc_html_e( 'Add Flower Product', 'secret-flower-shop' ); ?></a>
            <a class="button" href="<?php echo esc_url( $posts_url ); ?>"><?php esc_html_e( 'View All Posts', 'secret-flower-shop' ); ?></a>
            <a class="button" href="<?php echo esc_url( $categories_url ); ?>"><?php esc_html_e( 'Manage Categories', 'secret-flower-shop' ); ?></a>
            <a class="button-link" href="<?php echo esc_url( $dismiss_url ); ?>"><?php esc_html_e( 'Dismiss', 'secret-flower-shop' ); ?></a>
        </p>
    </div>
    <?php
}
add_action( 'admin_notices', 'secret_flower_shop_admin_quick_guide_notice' );
