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
            update_post_meta( $post_id, 'price_value', secret_flower_shop_parse_price_value( $product['price'] ) );
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
        return secret_flower_shop_format_price( 29 );
    }

    $numeric_price = secret_flower_shop_parse_price_value( $raw_price );

    if ( $numeric_price <= 0 ) {
        return wp_strip_all_tags( $raw_price );
    }

    return secret_flower_shop_format_price( $numeric_price );
}

/**
 * Parse a price-like string into a numeric value.
 *
 * @param string $raw_price Raw price value.
 * @return float
 */
function secret_flower_shop_parse_price_value( $raw_price ) {
    $clean_value = preg_replace( '/[^0-9.]+/', '', (string) $raw_price );

    if ( '' === $clean_value ) {
        return 0.0;
    }

    return (float) $clean_value;
}

/**
 * Format a numeric price for display.
 *
 * @param float $price_value Numeric price.
 * @return string
 */
function secret_flower_shop_format_price( $price_value ) {
    return '$' . number_format_i18n( (float) $price_value, 2 );
}

/**
 * Keep numeric price metadata in sync when products are saved.
 *
 * @param int $post_id Post ID.
 */
function secret_flower_shop_sync_price_meta( $post_id ) {
    if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
        return;
    }

    if ( 'post' !== get_post_type( $post_id ) ) {
        return;
    }

    $raw_price = get_post_meta( $post_id, 'price', true );
    $price     = secret_flower_shop_parse_price_value( $raw_price );

    if ( $price > 0 ) {
        update_post_meta( $post_id, 'price_value', $price );
    } else {
        delete_post_meta( $post_id, 'price_value' );
    }
}
add_action( 'save_post_post', 'secret_flower_shop_sync_price_meta' );

/**
 * One-time migration for older products missing numeric price metadata.
 */
function secret_flower_shop_backfill_price_meta() {
    $is_backfilled = (bool) get_option( 'secret_flower_shop_price_backfill_done', false );

    if ( $is_backfilled ) {
        return;
    }

    $product_ids = get_posts(
        array(
            'post_type'      => 'post',
            'posts_per_page' => -1,
            'category_name'  => 'flowers',
            'fields'         => 'ids',
            'no_found_rows'  => true,
        )
    );

    if ( ! empty( $product_ids ) ) {
        foreach ( $product_ids as $product_id ) {
            secret_flower_shop_sync_price_meta( (int) $product_id );
        }
    }

    update_option( 'secret_flower_shop_price_backfill_done', 1 );
}
add_action( 'admin_init', 'secret_flower_shop_backfill_price_meta' );

/**
 * Read and sanitize shop filters from query parameters.
 *
 * @return array<string, mixed>
 */
function secret_flower_shop_get_shop_filters() {
    $search = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';
    $sort   = isset( $_GET['sort'] ) ? sanitize_key( wp_unslash( $_GET['sort'] ) ) : 'newest';

    $allowed_sorts = array( 'newest', 'price_asc', 'price_desc', 'title_asc' );
    if ( ! in_array( $sort, $allowed_sorts, true ) ) {
        $sort = 'newest';
    }

    $min_price = isset( $_GET['min_price'] ) ? secret_flower_shop_parse_price_value( wp_unslash( $_GET['min_price'] ) ) : 0;
    $max_price = isset( $_GET['max_price'] ) ? secret_flower_shop_parse_price_value( wp_unslash( $_GET['max_price'] ) ) : 0;

    if ( $max_price > 0 && $min_price > $max_price ) {
        $swap      = $min_price;
        $min_price = $max_price;
        $max_price = $swap;
    }

    return array(
        's'         => $search,
        'sort'      => $sort,
        'min_price' => $min_price,
        'max_price' => $max_price,
    );
}

/**
 * Build WP_Query arguments for flower products with filters.
 *
 * @param array $overrides Optional custom arguments.
 * @return array
 */
function secret_flower_shop_get_shop_query_args( $overrides = array() ) {
    $filters = secret_flower_shop_get_shop_filters();

    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => 9,
        'category_name'  => 'flowers',
        'paged'          => 1,
    );

    if ( '' !== $filters['s'] ) {
        $args['s'] = $filters['s'];
    }

    if ( $filters['min_price'] > 0 || $filters['max_price'] > 0 ) {
        $meta_query = array(
            array(
                'key'     => 'price_value',
                'type'    => 'NUMERIC',
                'compare' => 'EXISTS',
            ),
        );

        if ( $filters['min_price'] > 0 ) {
            $meta_query[] = array(
                'key'     => 'price_value',
                'value'   => $filters['min_price'],
                'type'    => 'NUMERIC',
                'compare' => '>=',
            );
        }

        if ( $filters['max_price'] > 0 ) {
            $meta_query[] = array(
                'key'     => 'price_value',
                'value'   => $filters['max_price'],
                'type'    => 'NUMERIC',
                'compare' => '<=',
            );
        }

        $args['meta_query'] = $meta_query;
    }

    switch ( $filters['sort'] ) {
        case 'price_asc':
            $args['meta_key'] = 'price_value';
            $args['orderby']  = 'meta_value_num';
            $args['order']    = 'ASC';
            break;
        case 'price_desc':
            $args['meta_key'] = 'price_value';
            $args['orderby']  = 'meta_value_num';
            $args['order']    = 'DESC';
            break;
        case 'title_asc':
            $args['orderby'] = 'title';
            $args['order']   = 'ASC';
            break;
        default:
            $args['orderby'] = 'date';
            $args['order']   = 'DESC';
            break;
    }

    return wp_parse_args( $overrides, $args );
}

/**
 * Build simple storefront metrics.
 *
 * @return array<string, float|int>
 */
function secret_flower_shop_get_shop_metrics() {
    $product_ids = get_posts(
        array(
            'post_type'      => 'post',
            'posts_per_page' => -1,
            'category_name'  => 'flowers',
            'fields'         => 'ids',
            'no_found_rows'  => true,
        )
    );

    $count       = is_array( $product_ids ) ? count( $product_ids ) : 0;
    $price_total = 0.0;
    $min_price   = 0.0;
    $max_price   = 0.0;

    if ( ! empty( $product_ids ) ) {
        foreach ( $product_ids as $product_id ) {
            $price = (float) get_post_meta( (int) $product_id, 'price_value', true );

            if ( $price <= 0 ) {
                $price = secret_flower_shop_parse_price_value( get_post_meta( (int) $product_id, 'price', true ) );
            }

            if ( $price > 0 ) {
                $price_total += $price;
                $min_price    = ( 0 === $min_price ) ? $price : min( $min_price, $price );
                $max_price    = max( $max_price, $price );
            }
        }
    }

    return array(
        'count'     => $count,
        'avg_price' => ( $count > 0 && $price_total > 0 ) ? ( $price_total / $count ) : 0,
        'min_price' => $min_price,
        'max_price' => $max_price,
    );
}

/**
 * Resolve a fallback flower image URL from post title/content keywords.
 *
 * @param int $post_id Optional post ID.
 * @return string
 */
function secret_flower_shop_get_fallback_flower_image( $post_id = 0 ) {
    $post_id = $post_id ? (int) $post_id : get_the_ID();

    if ( ! $post_id ) {
        return '';
    }

    $title   = (string) get_the_title( $post_id );
    $content = (string) get_post_field( 'post_content', $post_id );
    $text    = strtolower( wp_strip_all_tags( $title . ' ' . $content ) );

    $image_file = '';

    if ( false !== strpos( $text, 'tulip' ) ) {
        $image_file = 'tulips.png';
    } elseif ( false !== strpos( $text, 'lily' ) || false !== strpos( $text, 'lilies' ) ) {
        $image_file = 'lilies.png';
    } elseif ( false !== strpos( $text, 'rose' ) || false !== strpos( $text, 'roses' ) ) {
        $image_file = 'roses.png';
    }

    if ( '' === $image_file ) {
        return '';
    }

    return get_template_directory_uri() . '/assets/images/flowers/' . $image_file;
}

/**
 * Get a simple bag URL anchor.
 *
 * @return string
 */
function secret_flower_shop_get_bag_url() {
    return '#shop-bag';
}

/**
 * Enqueue a small data object for the front-end bag UI.
 */
function secret_flower_shop_inline_bag_data() {
    wp_add_inline_script(
        'secret-flower-shop-script',
        'window.SecretFlowerShopBag = ' . wp_json_encode(
            array(
                'currencySymbol' => '$',
                'emptyText'      => __( 'Your bag is empty.', 'secret-flower-shop' ),
                'bagTitle'       => __( 'Your Flower Bag', 'secret-flower-shop' ),
            )
        ) . ';',
        'before'
    );
}
add_action( 'wp_enqueue_scripts', 'secret_flower_shop_inline_bag_data', 20 );

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

    $new_post_url   = admin_url( 'post-new.php' );
    $posts_url      = admin_url( 'edit.php' );
    $categories_url = admin_url( 'edit-tags.php?taxonomy=category' );
    $dismiss_url   = wp_nonce_url(
        add_query_arg( 'sfs_dismiss_notice', '1', admin_url() ),
        'sfs_dismiss_notice'
    );
    ?>
    <div class="notice notice-info">
        <p><strong><?php esc_html_e( 'Secret Flower Shop Quick Guide', 'secret-flower-shop' ); ?></strong></p>
        <p>
            <?php esc_html_e( 'Add Posts in the Flowers category to create flower products, then add a Featured Image and a price custom field.', 'secret-flower-shop' ); ?>
            <?php esc_html_e( 'The theme shows an Add to Bag experience without a checkout system.', 'secret-flower-shop' ); ?>
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
