<?php

// Original functions.php - yalnız language detection hissəsini düzəldib
add_action('wp_enqueue_scripts', 'enqueue_theme_scripts_with_location_map');
function enqueue_theme_scripts_with_location_map() {
    wp_enqueue_script('zefer-map', get_template_directory_uri() . '/src/main.js', [], null, true);

    if (function_exists('pll_get_the_languages')) {
        $languages = pll_get_the_languages(['raw' => 1]);
        $terms = get_terms(['taxonomy' => 'location', 'hide_empty' => false]);

        $map = [];

        foreach ($terms as $term) {
            foreach ($languages as $lang_code => $lang) {
                $translated_term_id = pll_get_term($term->term_id, $lang_code);
                if (!$translated_term_id) continue;

                $translated_term = get_term($translated_term_id, 'location');

                // Burada translated_term->slug əsas indeks kimi istifadə olunur
                $map[$translated_term->slug][$lang_code] = [
                    'name' => $translated_term->name,
                    'url'  => get_term_link($translated_term),
                    'desc' => term_description($translated_term->term_id, 'location'),
                ];
            }
        }

        wp_localize_script('zefer-map', 'locationMap', $map);
    }
}

//location options

function register_location_taxonomy() {
    $labels = array(
        'name'              => __('Locations', 'textdomain'),
        'singular_name'     => __('Location', 'textdomain'),
        'search_items'      => __('Search Locations', 'textdomain'),
        'all_items'         => __('All Locations', 'textdomain'),
        'edit_item'         => __('Edit Location', 'textdomain'),
        'update_item'       => __('Update Location', 'textdomain'),
        'add_new_item'      => __('Add New Location', 'textdomain'),
        'new_item_name'     => __('New Location Name', 'textdomain'),
        'menu_name'         => __('Locations', 'textdomain'),
    );

    $args = array(
        'labels'            => $labels,
        'hierarchical'      => false,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'rewrite'           => array('slug' => 'location'),
    );

    register_taxonomy('location', array('post'), $args);
}
add_action('init', 'register_location_taxonomy');

add_filter('pll_get_taxonomies', function($taxonomies) {
    $taxonomies[] = 'location';
    return $taxonomies;
});

function sync_polylang_locations_across_languages($post_id) {
    // Admin yoxlaması
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (wp_is_post_revision($post_id)) return;

    // Ana dil postu yoxdursa, çıx
    if (!function_exists('pll_get_post_translations')) return;

    // Bütün dillərdəki versiyaları alırıq
    $translations = pll_get_post_translations($post_id);
    $current_lang = pll_get_post_language($post_id);

    // Cari postun location terminlərini alırıq
    $current_terms = wp_get_post_terms($post_id, 'location', array('fields' => 'ids'));

    if (empty($current_terms)) return;

    // Hər bir tərcümə olunmuş posta uyğun location təyin edirik
    foreach ($translations as $lang => $translated_post_id) {
        // Eyni dildə tərcüməyə keçməyək
        if ($lang == $current_lang) continue;

        $translated_term_ids = [];

        foreach ($current_terms as $term_id) {
            $translated_term_id = pll_get_term($term_id, $lang);
            if ($translated_term_id) {
                $translated_term_ids[] = $translated_term_id;
            }
        }

        if (!empty($translated_term_ids)) {
            wp_set_post_terms($translated_post_id, $translated_term_ids, 'location', false);
        }
    }
}
add_action('save_post', 'sync_polylang_locations_across_languages', 20);

add_action('admin_menu', function() {
    add_submenu_page(
        'tools.php',
        'Location Sync',
        'Location Sync',
        'manage_options',
        'location-sync',
        'render_location_sync_page'
    );
});

function render_location_sync_page() {
    if (isset($_POST['location_sync_action'])) {
        sync_all_post_locations_polylang();
        echo '<div class="updated notice"><p><strong>Sinxronizasiya tamamlandı!</strong></p></div>';
    }
    ?>
    <div class="wrap">
        <h1>Location Sinxronizasiya</h1>
        <form method="post">
            <?php submit_button('Bütün postlar üzrə location-ları sinxron et', 'primary', 'location_sync_action'); ?>
        </form>
    </div>
    <?php
}

function sync_all_post_locations_polylang() {
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'fields' => 'ids'
    );
    $all_posts = get_posts($args);

    foreach ($all_posts as $post_id) {
        sync_polylang_locations_across_languages($post_id);
    }
}

//end location options

function my_excerpt_length( $length ) {
    return 20;
}
add_filter( 'excerpt_length', 'my_excerpt_length', 999 );

function new_excerpt_more($more) {
    return ' ...';
}
add_filter('excerpt_more', 'new_excerpt_more');

// Remove WordPress version meta in header scripts and p tags
remove_action('wp_head', 'wp_generator');
remove_filter( 'the_excerpt', 'wpautop' );
remove_filter ('term_description', 'wpautop');
remove_filter( 'the_content', 'wpautop' );

add_theme_support( 'post-thumbnails' );

function my_change_posts_order( $query ){
    if ( ! is_admin() && ( is_category() || is_tag() ) && $query->is_main_query() ) {
        $query->set( 'order', 'ASC' );
    }
};
add_action( 'pre_get_posts', 'my_change_posts_order'); 

pll_register_string('home1', 'Axtarış üçün açar sözü daxil edin');
pll_register_string('home2', 'Bülletenə keçid');
pll_register_string('home3', '© Bütün Hüquqlar Qorunur. 2022 Zəfərdən Bu Günə.');
pll_register_string('home4', 'ANA SƏHİFƏ');
pll_register_string('home5', 'HAQQIMIZDA');
pll_register_string('home6', 'VİDEOLAR');

pll_register_string('url1', '/videos');
pll_register_string('url2', '/about');
pll_register_string('url3', 'logo.svg');

pll_register_string('category1', 'BÜLLETEN SEÇ');
pll_register_string('category2', 'PDF YÜKLƏ');

// Taxonomy Location səhifəsi üçün polylang strings
pll_register_string('location_filter_title', 'XƏBƏR SEÇİMİ');
pll_register_string('location_city_select', 'Şəhər Seç');
pll_register_string('location_day_select', 'Gün');
pll_register_string('location_month_select', 'Ay');
pll_register_string('location_year_select', 'İl');
pll_register_string('location_submit_button', 'KEÇİD');
pll_register_string('location_read_more', 'ƏTRAFLI OXU');
pll_register_string('location_no_posts', 'Bu region və ya tarix üçün heç bir xəbər tapılmadı. Filtri dəyişərək yenidən cəhd edin.');
pll_register_string('location_show_all', 'BÜTÜN XƏBƏRLƏRI GÖSTƏR');

add_filter( 'https_ssl_verify', '__return_false' );