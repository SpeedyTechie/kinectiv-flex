<?php
/**
 * Set up theme defaults and register support for various WordPress features
 */
if (!function_exists('ks_setup')) {
	function ks_setup() {
		// Let WordPress manage the document title
		add_theme_support('title-tag');

		// Switch default core markup for search form, comment form, and comments to output valid HTML5
		add_theme_support('html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
            'style',
            'script'
		));
	}
}
add_action('after_setup_theme', 'ks_setup');


/**
 * Set the content width in pixels
 */
function ks_content_width() {
	$GLOBALS['content_width'] = apply_filters('ks_content_width', 640);
}
add_action('after_setup_theme', 'ks_content_width', 0);


/**
 * Enqueue scripts and styles
 */
function kinectiv_flex_scripts() {
    $f_google_api_key = get_field('config_apis_google', 'option');

	wp_enqueue_style('kinectiv-flex-style', get_stylesheet_directory_uri() . '/style.min.css', array(), '1.0.0');
	wp_enqueue_style('kinectiv-flex-vendor-style', get_stylesheet_directory_uri() . '/css/vendor.min.css', array(), '1.0.0');
	wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,400;0,700;1,400;1,700&family=Roboto+Slab:wght@700&display=swap', array(), null);
    
    wp_deregister_script('wp-embed');
    wp_deregister_script('jquery');
    wp_enqueue_script('jquery', get_template_directory_uri() . '/js/jquery-3.6.0.min.js', array(), null, true);
	wp_enqueue_script('kinectiv-flex-script', get_template_directory_uri() . '/js/script.min.js', array('jquery'), '1.0.0', true);
    wp_enqueue_script('google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . $f_google_api_key . '&callback=initGoogleMaps', array('kinectiv-flex-script'), null, true);
    
    wp_localize_script('kinectiv-flex-script', 'wpVars', array(
        'ajaxURL' => admin_url('admin-ajax.php'),
        'themeMaps' => kf_color_theme_maps(),
        'auxThemeMaps' => kf_aux_color_theme_maps()
    ));
}
add_action('wp_enqueue_scripts', 'kinectiv_flex_scripts');

function ks_admin_scripts() {
    wp_enqueue_style('kf-admin-css', get_stylesheet_directory_uri() . '/css/wp-admin.css', array(), '1.0.0');
    
	wp_enqueue_script('kf-admin-js', get_template_directory_uri() . '/js/wp-admin.js', array(), '1.0.0', true);
    
    wp_localize_script('kf-admin-js', 'wpVars', array(
        'wysiwygConfigs' => ks_wysiwyg_configs(),
        'colorList' => kf_color_id_list(),
        'themeMaps' => kf_color_theme_maps(),
        'auxThemeMaps' => kf_aux_color_theme_maps(),
        'colorSwatchList' => kf_color_swatch_list(),
        'buttonColorVariations' => kf_button_color_variation_list()
    ));
}
add_action('admin_enqueue_scripts', 'ks_admin_scripts');


/**
 * Register custom post types and taxonomies
 */
function kf_post_types() {
    register_post_type('event', array(
        'labels' => array(
            'name' => 'Events',
            'singular_name' => 'Event',
            'add_new_item' => 'Add Event',
            'edit_item' => 'Edit Event',
            'new_item' => 'New Event',
            'view_item' => 'View Event',
            'view_items' => 'View Events',
            'search_items' => 'Search Events',
            'not_found' => 'No events found',
            'not_found_in_trash' => 'No events found in trash',
            'all_items' => 'All Events',
            'archives' => 'Event Archives',
            'attributes' => 'Event Attributes',
            'insert_into_item' => 'Insert into event',
            'uploaded_to_this_item' => 'Uploaded to this event',
            'item_published' => 'Event published.',
            'item_published_privately' => 'Event published privately.',
            'item_reverted_to_draft' => 'Event reverted to draft.',
            'item_scheduled' => 'Event scheduled.',
            'item_updated' => 'Event updated.'
        ),
        'public' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-calendar-alt',
        'supports' => array('title', 'revisions'),
        'rewrite' => array(
            'with_front' => false
        )
    ));
}
add_action('init', 'kf_post_types');


/**
 * Formatted copyright year
 */
function kf_get_copyright_year() {
    $year = '2021'; // year site was first published
    $today_dt = current_datetime();
    
    // if the current year is not the publish year, convert to a range (e.g. "2018-2020")
    if ($today_dt->format('Y') != $year) {
        $year .= '-' . $today_dt->format('Y');
    }
    
    return $year;
}


/**
 * Prepend copyright year to ACF field
 */
function kf_prepend_copyright_year_to_field($field) {
    $field['prepend'] = '&copy; Copyright ' . kf_get_copyright_year();
    
    return $field;
}
add_filter('acf/prepare_field/key=field_5f22b95009b0c', 'kf_prepend_copyright_year_to_field');


/**
 * Get color ID
 */
function color_id($theme, $color_num, $return = false) {
    // list of color IDs in order for each theme
    $theme_maps = kf_color_theme_maps();
    
    if (isset($theme_maps[$theme][$color_num])) {
        // return color ID
        if ($return) {
            return $theme_maps[$theme][$color_num];
        } else {
            echo $theme_maps[$theme][$color_num];
        }
    }
    
    // if the provided theme doesn't exist, return an empty string
    if ($return) {
        return '';
    }
}


/**
 * Get auxiliary color ID
 */
function aux_color_id($theme, $color_num, $return = false) {
    // list of auxiliary color IDs in order for each theme
    $aux_theme_maps = kf_aux_color_theme_maps();
    
    if (isset($aux_theme_maps[$theme][$color_num])) {
        // return color ID
        if ($return) {
            return $aux_theme_maps[$theme][$color_num];
        } else {
            echo $aux_theme_maps[$theme][$color_num];
        }
    }
    
    if ($return) {
        return ''; // if the provided theme/number doesn't exist, return an empty string
    }
}


/**
 * Get list of color classes for common components
 */
function component_colors($theme, $component, $return = false) {
    // list of components and their associated color classes
    $component_classes = array(
        'button' =>  'c_bg_' . color_id($theme, 3, true) . ' c_h_bg_' . color_id($theme, 4, true) . ' c_color_' . color_id($theme, 0, true) . ' c_h_color_' . color_id($theme, 0, true),
        'arrow-link' => 'c_color_' . color_id($theme, 5, true) . ' c_h_color_' . color_id($theme, 4, true)
    );

    if (isset($component_classes[$component])) {
        // return class list
        if ($return) {
            return $component_classes[$component];
        } else {
            echo $component_classes[$component];
        }
    }

    if ($return) {
        return ''; // if the provided component doesn't exist, return an empty string
    }
}


/**
 * Get color theme maps
 */
function kf_color_theme_maps() {
    return array(
        'main' => array('a0', 'a1', 'a2', 'a3', 'a4', 'a5'),
        'main-dark' => array('a5', 'a4', 'a3', 'a2', 'a1', 'a0'),
        'alt' => array('b0', 'b1', 'b2', 'b3', 'b4', 'b5'),
        'alt-dark' => array('b5', 'b4', 'b3', 'b2', 'b1', 'b0')
    );
}


/**
 * Get auxiliary color theme maps
 */
function kf_aux_color_theme_maps() {
    return array(
        'main' => array('ax0', 'ax1'),
        'main-dark' => array('ax1', 'ax0'),
        'alt' => array('bx0', 'bx1'),
        'alt-dark' => array('bx1', 'bx0')
    );
}


/**
 * Get list of theme inverses
 */
function kf_color_theme_inverses() {
    return array(
        'main' => 'main-dark',
        'main-dark' => 'main',
        'alt' => 'alt-dark',
        'alt-dark' => 'alt'
    );
}


/**
 * Get list of color ID definitions
 */
function kf_color_id_list() {
    return array(
        'a0' => '#fff',
        'a1' => '#f3f3f3',
        'a2' => '#a8a8a8',
        'a3' => '#6d6d6d',
        'a4' => '#3d3d3d',
        'a5' => '#000',
        'ax0' => '#f3b0b0', // aux - error light (main)
        'ax1' => '#b42222', // aux - error dark (main)
        'b0' => '#fafdff',
        'b1' => '#c8e0f1',
        'b2' => '#90a6cb',
        'b3' => '#38536f',
        'b4' => '#253143',
        'b5' => '#161a1e',
        'bx0' => '#f3b0a0', // aux - error light (alt)
        'bx1' => '#b42212' // aux - error dark (alt)
    );
}


/**
 * Get list of colors to include in color picker swatches
 */
function kf_color_swatch_list() {
    return array(
        'a0',
        'a1',
        'a2',
        'a3',
        'a4',
        'a5',
        'b0',
        'b1',
        'b2',
        'b3',
        'b4',
        'b5'
    );
}


/**
 * Add custom classes to tags in WYSIWYG content
 */
function kf_add_wysiwyg_classes($html, $class_list) {
    // enable user error handling (to prevent warnings caused by HTML5 and SVG tags)
    libxml_clear_errors();
    $libxml_err_prev = libxml_use_internal_errors(true);
    
    $dom = new DOMDocument();
    $dom->loadHTML('<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>' . $html); // load HTML with proper encoding
    
    // check the dom for each type of element and add the corresponding classes
    foreach ($class_list as $element => $classes) {
        foreach($dom->getElementsByTagName($element) as $dom_element) {
            $dom_element->setAttribute('class', trim($dom_element->getAttribute('class') . ' ' . $classes)); // set the class attribute
        }
    }
    
    // build string of all elements (to exclude <html> and <body> wrappers that are added automatically)
    $final_html = '';
    if ($dom->getElementsByTagName('body')->item(0)->childNodes > 0) {
        foreach ($dom->getElementsByTagName('body')->item(0)->childNodes as $node) {
            $final_html .= $dom->saveHTML($node);
        }
    }
    
    // log errors and reset error handling
    foreach (libxml_get_errors() as $error) {
        error_log('Suppressed LibXMLError: ' . print_r($error, true));
    }
    libxml_clear_errors();
    libxml_use_internal_errors($libxml_err_prev);
    
    return $final_html; // return final modified HTML
}

function kf_acf_format_wysiwyg_value($value, $post_id, $field) {
    return kf_wysiwyg_text_classes($value);
}
add_filter('acf/format_value/type=wysiwyg', 'kf_acf_format_wysiwyg_value', 15, 3); // add classes to ACF WYSIWYG fields

function kf_wysiwyg_text_classes($html) {
    // list of classes to add to various elements
    $class_list = array(
        'h1' => 'title title_lg',
        'h2' => 'title',
        'h3' => 'text text_xl text_normal text_line_1-4',
        'h4' => 'text text_md text_bold',
        'h5' => 'text text_md text_bold',
        'h6' => 'text text_sm text_bold',
        'figcaption' => 'text text_xs text_line_1-4'
    );
    
    return kf_add_wysiwyg_classes($html, $class_list);
}

function kf_wysiwyg_color_classes($html, $theme) {
    // list of classes to add to various elements
    $class_list = array(
        'h3' => 'c_color_' . color_id($theme, 3, true),
        'h5' => 'c_color_' . color_id($theme, 3, true),
        'blockquote' => 'c_color_' . color_id($theme, 3, true),
        'hr' => 'c_bg_' . color_id($theme, 2, true),
        'figcaption' => 'c_color_' . color_id($theme, 3, true)
    );
    
    if ($theme == 'main-dark' || $theme == 'alt-dark') {
        $class_list['a'] = 'light';
    }
    
    return kf_add_wysiwyg_classes($html, $class_list);
}


/**
 * Get section styles and classes
 */
function kf_section_bg_styles($options, $default = 0) {
    $styles = array(
        'style' => '',
        'classes' =>  ''
    );
    
    if ($options['color_theme']) {
        $styles['classes'] .= ' c_bg_' . color_id($options['color_theme'], $default, true); // set default bg to base color of theme
    }
    
    if ($options['bg_type'] == 'color') {
        $styles['style'] .= ' background-color: ' . $options['bg_color'] . ';'; // add bg color
    } elseif ($options['bg_type'] == 'image') {
        $styles['style'] .= ' background-image: url(\'' . $options['bg_image']['url'] . '\');'; // add bg image url
        
        // add advanced bg styles
        $image_styles = kf_advanced_bg_image_styles($options['bg_advanced']);
        $styles['style'] .= $image_styles['style'];
        $styles['classes'] .= $image_styles['classes'];
    }
    
    return $styles;
}

function kf_section_padding_styles($options, $options_prev, $options_next) {
    $padding_sizes = array('xs', 'sm', 'md', 'lg', 'xl', 'hg'); // list of available padding sizes in order from smallest to largest
    $styles = array(
        'style' => '',
        'classes' => ''
    );
    
    $padding = array(
        'top' => ($options['padding_vertical'] == 'separate') ? $options['padding_top'] : $options['padding_vertical'],
        'bottom' => ($options['padding_vertical'] == 'separate') ? $options['padding_bottom'] : $options['padding_vertical']
    );
    $bg = kf_get_bg_color($options);
    
    foreach (array('top' => $options_prev, 'bottom' => $options_next) as $loc => $neighbor) {
        if ($padding[$loc] == 'none' || !$options['padding_collapse-' . $loc] || !$bg || !$neighbor) continue; // leave padding as set if there's already no padding, padding collapse is disabled, the bg is not a solid color, or there's no neighboring section
        
        $neighbor_loc = ($loc == 'top') ? 'bottom' : 'top'; // get opposite of current location to use for neighbor
        $neighbor_padding = ($neighbor['padding_vertical'] == 'separate') ? $neighbor['padding_' . $neighbor_loc] : $neighbor['padding_vertical']; // get padding from relevant location from neighbor
        $neighbor_bg = kf_get_bg_color($neighbor);
        
        if (!$neighbor['padding_vertical'] || $neighbor_padding == 'none' || !$neighbor['padding_collapse-' . $neighbor_loc] || $bg != $neighbor_bg) continue; // leave padding as set if the neighbor doesn't have padding options, neighbor has no padding, neighbor's padding collapse is disabled, or neighbor's bg is different from the current section
        
        // get index of sizes for current and neighbor padding so they can be compared
        $size_levels = array(
            'current' => array_search($padding[$loc], $padding_sizes),
            'neighbor' => array_search($neighbor_padding, $padding_sizes)
        );
        
        if ($size_levels['current'] !== false && $size_levels['neighbor'] !== false) {
            if ($loc == 'top') $size_levels['neighbor']++; // if this is the top of the current section, increase the neighbor size by one to break ties
            
            if ($size_levels['current'] < $size_levels['neighbor']) {
                $padding[$loc] = 'none'; // set padding to none if the current section has less padding than the neighbor
            }
        }
    }
    
    if ($padding['top'] == $padding['bottom']) {
        $styles['classes'] .= ' section_y_' . $padding['top']; // add vertical padding class
    } else {
        $styles['classes'] .= ' section_top_' . $padding['top']; // add top padding class
        $styles['classes'] .= ' section_bottom_' . $padding['bottom']; // add bottom padding class
    }
    
    return $styles;
}

function kf_get_bg_color($options) {
    $hex = null;
    
    if ($options['bg_type'] == 'default') {
        $hex = kf_color_id_list()[color_id($options['color_theme'], 0, true)]; // if this section is using the default bg, get the hex code of the base color of the current theme
    } elseif ($options['bg_type'] == 'color') {
        $hex = $options['bg_color']; // if this section is using a custom bg color, get the hex code
    }
    
    $hex = kf_hex_3_to_6($hex); // convert 3 to 6 digit hex
    
    return $hex;
}

function kf_hex_3_to_6($hex) {
    // convert 3 to 6 digit hex
    if (strlen($hex) == 4) {
        $hex = '#' . $hex[1] . $hex[1] . $hex[2] . $hex[2] . $hex[3] . $hex[3];
    }
    
    return $hex;
}


/**
 * Get styles/classes for advanced bg image options
 */
function kf_advanced_bg_image_styles($options) {
    $styles = array(
        'style' => '',
        'classes' =>  ' bg-image'
    );
    
    // add bg size and repeat options
    if ($options['image_size'] != 'cover') {
        if ($options['image_size'] == 'contain') {
            $styles['classes'] .= ' bg-image_size_contain'; // add bg size contain class
        } elseif ($options['image_size'] == 'custom') {
            // add custom bg size to style attribute
            $bg_w = $options['image_custom-size']['width'] ? $options['image_custom-size']['width'] . $options['image_custom-size']['unit'] : 'auto';
            $bg_h = $options['image_custom-size']['height'] ? $options['image_custom-size']['height'] . $options['image_custom-size']['unit'] : 'auto';
            $styles['style'] .= ' background-size: ' . $bg_w . ' ' . $bg_h . ';';
        }

        $styles['classes'] .= ' bg-image_repeat_' . $options['image_repeat']; // add bg repeat class
    }

    $styles['classes'] .= ' bg-image_pos_' . $options['image_position']['x'] . '-' . $options['image_position']['y']; // add bg position class
    
    // add fallback bg color
    if ($options['image_color']) {
        $styles['style'] .= ' background-color: ' . $options['image_color'] . ';';
    }
    
    return $styles;
}


/**
 * Get page ancestors
 */
function kf_get_ancestors($post_id) {
    $post_type = get_post_type($post_id); // get post type
    $front_id = intval(get_option('page_on_front')); // get front page ID
    $ancestors = array(); // create an array to store ancestors (format URL => Page Title)
    
    // if there's a static front page (and it isn't the current page), add it as the first ancestor
    if ($front_id > 0 && $post_id != $front_id) {
        $ancestors[get_permalink($front_id)] = get_the_title($front_id);
    }
    
    // if this is a 404 page, return only the front page as an ancestor
    if (is_404()) {
        return $ancestors;
    }
    
    // build breadcrumb array based on the post type
    if ($post_type == 'post') {
        $f_posts_page = get_field('config_posts_page', 'option');
        
        if ($f_posts_page) {
            $ancestors = kf_get_ancestors($f_posts_page->ID); // use the posts page ancestors as a starting point
            $ancestors[get_permalink($f_posts_page->ID)] = $f_posts_page->post_title; // add the posts page itself to the ancestors array
        }
    } elseif ($post_type == 'event') {
        $f_events_page = get_field('config_events_page', 'option');
        
        if ($f_events_page) {
            $ancestors = kf_get_ancestors($f_events_page->ID); // use the events page ancestors as a starting point
            $ancestors[get_permalink($f_events_page->ID)] = $f_events_page->post_title; // add the events page itself to the ancestors array
        }
    } else {
        $wp_ancestors = get_post_ancestors($post_id); // get list of post ancestors from WordPress
        
        if (in_array($front_id, $wp_ancestors)) {
            $ancestors = array(); // if the front page is one of the ancestors returned by WordPress, remove it from the ancestors array
        }
        
        // add the ancestors returned by WordPress to the array
        foreach (array_reverse($wp_ancestors) as $anc_id) {
            $ancestors[get_permalink($anc_id)] = get_the_title($anc_id);
        }
    }
    
    return $ancestors;
}


/**
 * Get posts
 */
function kf_custom_query($type, $single_page = false, $page_num = 1, $special = null) {
    // standardize number of posts per page based on type
    $per_page = array(
        'post' => 12,
        'event' => 12
    );
    
    if (array_key_exists($type, $per_page)) {
        // base arguments
        $query_args = array(
            'post_type' => $type,
            'post_status' => 'publish',
            'posts_per_page' => $single_page ? $single_page : $per_page[$type],
            'paged' => $page_num
        );
        
        // add event arguments
        if ($type == 'event') {
            $today_dt = new DateTime('00:00:00', wp_timezone());
            
            if ($special == 'past') {
                // past events
                $query_args['meta_query'] = array(
                    'relation' => 'AND',
                    'order_num_start' => array(
                        'key' => '_kf_event_order_num_start',
                        'compare' => 'EXISTS',
                        'type' => 'NUMERIC'
                    ),
                    'order_num_end' => array(
                        'key' => '_kf_event_order_num_end',
                        'value' => $today_dt->format('YmdHi'),
                        'compare' => '<',
                        'type' => 'NUMERIC'
                    )
                );
                $query_args['orderby'] = array(
                    'order_num_start' => 'DESC',
                    'order_num_end' => 'DESC'
                );
            } else {
                // future events
                $query_args['meta_query'] = array(
                    'relation' => 'AND',
                    'order_num_start' => array(
                        'key' => '_kf_event_order_num_start',
                        'compare' => 'EXISTS',
                        'type' => 'NUMERIC'
                    ),
                    'order_num_end' => array(
                        'key' => '_kf_event_order_num_end',
                        'value' => $today_dt->format('YmdHi'),
                        'compare' => '>=',
                        'type' => 'NUMERIC'
                    )
                );
                $query_args['orderby'] = array(
                    'order_num_start' => 'ASC',
                    'order_num_end' => 'ASC'
                );
            }
        }
        
        
        $custom_query = new WP_Query($query_args); // execute query
        
        return $custom_query;
    }
}


/**
 * AJAX Grid Load
 */
function kf_ajax_grid_load() {
    $type = $_POST['type'];
    $special = $_POST['special'];
    $page_num = $_POST['page_num'];
    $passthrough_data = json_decode(stripslashes($_POST['passthrough_data']), true);
    
    $load_query = kf_custom_query($type, false, $page_num, $special);
    
    if ($load_query->posts) {
        foreach ($load_query->posts as $p_post) {
            global $post;
            $post = get_post($p_post);
            setup_postdata($p_post);
            ?>
            <div class="tile-grid__item tile-grid__item_3">
                <?php get_template_part('template-parts/preview', $type, $passthrough_data); ?>
            </div>
            <?php
            wp_reset_postdata();
        }
    }
    
    if ($load_query->max_num_pages > $page_num) {
        echo '<span id="more-pages"></span>';
    }

    die();
}
add_action('wp_ajax_nopriv_kf_grid_load', 'kf_ajax_grid_load');
add_action('wp_ajax_kf_grid_load', 'kf_ajax_grid_load');


/**
 * Event date/time ACF validation
 */
function kf_validate_event_dates($valid, $value, $field, $input) {
    if ($valid) {
        if ($value) {
            if (strtotime($value) <= strtotime($_POST['acf']['field_5f5921839dc3a']['field_5f59238da9dc3'])) {
                $valid = 'The end date must be after the start date. For single day events, leave the "End Date" field empty.';
            }
        }
    }
    
    return $valid;
}
add_filter('acf/validate_value/key=field_5f5923a1a9dc4', 'kf_validate_event_dates', 10, 4);

function kf_validate_event_times($valid, $value, $field, $input) {
    if ($valid) {
        if ($value) {
            if (strtotime($value) <= strtotime($_POST['acf']['field_5f5921839dc3a']['field_5f59244d92ede'])) {
                $valid = 'The end time must be after the start time.';
            }
        }
    }
    return $valid;
}
add_filter('acf/validate_value/key=field_5f59248492edf', 'kf_validate_event_times', 10, 4);


/**
 * Check if event is in the past
 */
function kf_is_past_event($event_id) {
    $today_dt = new DateTime('00:00:00', wp_timezone());
    
    $order_num_end = intval(get_post_meta($event_id, '_kf_event_order_num_end', true));
    $today_num = intval($today_dt->format('YmdHi'));
    
    if ($order_num_end < $today_num) {
        return true;
    }
    
    return false;
}


/**
 * Generate order strings for events
 */
function kf_event_acf_save_post($post_id) {
    if (get_post_type($post_id) == 'event') {
        $f_date = get_field('event_details_date', $post_id);
        
        $date_dt = new DateTime($f_date['date'], wp_timezone());
        $date_end_dt = $f_date['end-date'] ? new DateTime($f_date['end-date'], wp_timezone()) : null;
        $time_dt = $f_date['time'] ? new DateTime($f_date['time'], wp_timezone()) : null;
        $time_end_dt = $f_date['end-time'] ? new DateTime($f_date['end-time'], wp_timezone()) : null;
        
        // generate order strings
        $order_num_start = $date_dt->format('Ymd');
        $order_num_end = $date_dt->format('Ymd');
        if ($date_end_dt) {
            $order_num_end = $date_end_dt->format('Ymd');
        }
        if ($time_dt) {
            $order_num_start .= $time_dt->format('Hi');
            if (!$date_end_dt && $time_end_dt) {
                $order_num_end .= $time_end_dt->format('Hi');
            } else {
                $order_num_end .= '2359'; // use an end time of 11:59pm
            }
        } else {
            $order_num_start .= '0000'; // use a start time of 12:00am
            $order_num_end .= '2359'; // use an end time of 11:59pm
        }
        
        // add order strings to post meta
        update_post_meta($post_id, '_kf_event_order_num_start', $order_num_start);
        update_post_meta($post_id, '_kf_event_order_num_end', $order_num_end);
    }
}
add_action('acf/save_post', 'kf_event_acf_save_post');


/**
 * Customize admin columns for events (add date/time column)
 */
function kf_event_custom_columns($columns) {
    $new_columns['cb'] = $columns['cb'];
    $new_columns['title'] = $columns['title'];
    $new_columns['event_date'] = 'Date & Time';
    unset($columns['cb']);
    unset($columns['title']);
    $new_columns = array_merge($new_columns, $columns);
    
    return $new_columns;
}
add_filter('manage_event_posts_columns', 'kf_event_custom_columns');

function kf_event_custom_column_content($column_name, $post_id) {
    if ($column_name == 'event_date') {
        $f_date = get_field('event_details_date', $post_id);
        
        $date_dt = new DateTime($f_date['date'], wp_timezone());
        $date_end_dt = $f_date['end-date'] ? new DateTime($f_date['end-date'], wp_timezone()) : null;
        $time_dt = $f_date['time'] ? new DateTime($f_date['time'], wp_timezone()) : null;
        $time_end_dt = $f_date['end-time'] ? new DateTime($f_date['end-time'], wp_timezone()) : null;
        
        // create date string
        $date_string = $date_dt->format('F j, Y');
        if ($date_end_dt) {
            $date_string = $date_dt->format(($date_dt->format('Y') == $date_end_dt->format('Y')) ? 'F j' : 'F j, Y') . ' - ' . $date_end_dt->format('F j, Y'); // include the year in the start date only if it differs from the end date's year
        }
        
        // create time string
        $time_string = null;
        if ($time_dt) {
            $time_string = $time_dt->format('g:ia');

            // if this is a single day event and there's an end time, add it to the string
            if (!$date_end_dt && $time_end_dt) {
                $time_string .= ' - ' . $time_end_dt->format('g:ia');
            }
        }
        
        echo $date_string;
        if ($time_string) {
            echo '<br />' . $time_string;
        }
    }
}
add_action('manage_event_posts_custom_column', 'kf_event_custom_column_content', 10, 2);

function kf_manage_event_sortable_columns($columns) {
    $columns['event_date'] = 'column_event_date';
    
    return $columns;
}
add_filter('manage_edit-event_sortable_columns', 'kf_manage_event_sortable_columns');

function kf_event_custom_column_sorting($query) {
    if(is_admin()) {
        if($query->get('orderby') == 'column_event_date') {
            $meta_query = array(
                'relation' => 'AND',
                'order_num_start' => array(
                    'key' => '_kf_event_order_num_start'
                ),
                'order_num_end' => array(
                    'key' => '_kf_event_order_num_end'
                )
            );
            $orderby = array(
                'order_num_start' => $query->get('order'),
                'order_num_end' => $query->get('order')
            );
            
            $query->set('meta_query', $meta_query);
            $query->set('orderby', $orderby);
        }
    }
}
add_action('pre_get_posts', 'kf_event_custom_column_sorting');


/**
 * Get sharing image/description from flex content
 */
function kf_flex_sharing_image($content) {
    $meta_image = null;
    
    if (is_array($content)) {
        foreach ($content as $section) {
            if ($section['acf_fc_layout'] == 'header') {
                if ($section['options']['bg_type'] == 'image') {
                    $meta_image = $section['options']['bg_image'];

                    break;
                }
            } elseif ($section['acf_fc_layout'] == 'image') {
                $meta_image = $section['image'];

                break;
            } elseif ($section['acf_fc_layout'] == 'slider') {
                foreach ($section['slides'] as $slide) {
                    $meta_image = $slide['image'];

                    break 2;
                }
            } elseif ($section['acf_fc_layout'] == 'gallery_masonry') {
                foreach ($section['images'] as $image) {
                    if (kf_is_sharing_image($image['image'])) {
                        $meta_image = $image['image'];

                        break 2;
                    }
                }
            } elseif ($section['acf_fc_layout'] == 'gallery_rows') {
                foreach ($section['rows'] as $row) {
                    foreach ($row['images'] as $image) {
                        if (kf_is_sharing_image($image['image'])) {
                            $meta_image = $image['image'];
    
                            break 3;
                        }
                    }
                }
            } elseif ($section['acf_fc_layout'] == 'blocks') {
                $meta_image = $section['image'];

                break;
            }
        }
    }
    
    return $meta_image;
}
function kf_flex_sharing_desc($content) {
    $meta_description = '';
    
    if (is_array($content)) {
        foreach ($content as $section) {
            if ($section['acf_fc_layout'] == 'header') {
                if ($section['text']) {
                    $meta_description = $section['text'];

                    break;
                } elseif ($section['text-blocks']) {
                    foreach ($section['text-blocks'] as $block) {
                        if ($block['text']) {
                            $meta_description = $block['text'];

                            break 2;
                        }
                    }
                }
            } elseif ($section['acf_fc_layout'] == 'text' || $section['acf_fc_layout'] == 'blocks' || $section['acf_fc_layout'] == 'cta' || $section['acf_fc_layout'] == 'logos' || $section['acf_fc_layout'] == 'form' || $section['acf_fc_layout'] == 'contact') {
                if ($section['text']) {
                    $meta_description = $section['text'];

                    break;
                }
            } elseif ($section['acf_fc_layout'] == 'columns') {
                if ($section['blocks']) {
                    foreach ($section['blocks'] as $block) {
                        if ($block['text']) {
                            $meta_description = $block['text'];

                            break 2;
                        }
                    }
                }
            } elseif ($section['acf_fc_layout'] == 'slider') {
                if ($section['slides'] && $section['options']['layout_content']) {
                    foreach ($section['slides'] as $slide) {
                        if ($slide['text']) {
                            $meta_description = $slide['text'];

                            break 2;
                        }
                    }
                }
            } elseif ($section['acf_fc_layout'] == 'embed') {
                if ($section['caption']) {
                    $meta_description = $section['caption'];

                    break;
                }
            }
        }
    }
    
    return $meta_description;
}


/**
 * Check if an image is suitable for use as a sharing image
 */
function kf_is_sharing_image($image) {
    if (is_array($image)) {
        if ($image['width'] >= 600 && $image['width'] >= $image['height']) {
            return true;
        }
    }
    
    return false;
}


/**
 * Add ACF options page
 */
if (function_exists('acf_add_options_page')) {
    $options_page = acf_add_options_page(array(
		'page_title' 	=> 'Site Options',
		'capability'	=> 'edit_theme_options'
	));
    
    acf_add_options_sub_page(array(
        'page_title' => 'Header & Footer',
        'parent_slug' 	=> $options_page['menu_slug']
    ));
    acf_add_options_sub_page(array(
        'page_title' => '404 Page',
        'parent_slug' 	=> $options_page['menu_slug']
    ));
    acf_add_options_sub_page(array(
        'page_title' => 'Site Configuration',
        'parent_slug' 	=> $options_page['menu_slug']
    ));
    acf_add_options_sub_page(array(
        'page_title' => 'Appearance',
        'parent_slug' 	=> $options_page['menu_slug']
    ));
}


/**
 * Purge Kinsta Cache when ACF options page is updated
 */
function ks_save_options_page($post_id) {
    // check if this is an options page
    if ($post_id == 'options') {
        // check if this site is hosted on a Kinsta production environment with caching
        if (wp_get_environment_type() == 'production' && class_exists('Kinsta\Cache')) {
            wp_remote_get('https://localhost/kinsta-clear-cache-all', [
               'sslverify' => false, 
               'timeout'   => 5
            ]); // purge the cache
        }
    }
}
add_action('acf/save_post', 'ks_save_options_page');


/**
 * ACF - skip validation of specified fields
 */
function kf_acf_skip_validation($valid, $value, $field, $input_name) {
    $skip_validation_names = $_POST['_kf_acf_skip_validation'];
    
    if ($skip_validation_names && in_array($input_name, $skip_validation_names)) {
        $valid = true;
    }
    
    return $valid;
}
add_filter('acf/validate_value', 'kf_acf_skip_validation', 10, 4);


/**
 * Custom button color variations
 */
function kf_button_color_classes($theme, $button_variation) {
    $variation_list = kf_button_color_variation_list();

    if (array_key_exists($button_variation, $variation_list)) {
        return 'custom-color-' . $button_variation;
    } else {
        return component_colors($theme, 'button', true);
    }
}

function kf_get_button_color_variation_unique_key($name) {
    return sanitize_html_class(sanitize_title($name . '-' . hash('crc32', $name))); // generate unique key for color variation
}

function kf_button_color_variation_list() {
    $f_variations = get_field('appearance_buttons_variations', 'option');

    $list = array();
    if ($f_variations) {
        foreach ($f_variations as $variation) {
            $unique_key = kf_get_button_color_variation_unique_key($variation['name']);
    
            $list[$unique_key] = array(
                'default' => array(
                    'bg' => $variation['default']['bg'],
                    'text' => $variation['default']['text']
                ),
                'hover' => array(
                    'bg' => $variation['hover']['bg'],
                    'text' => $variation['hover']['text']
                )
            );
        }
    }
    
    return $list;
}

function kf_load_custom_button_variations($field) {
    $f_variations = get_field('appearance_buttons_variations', 'option');

    if ($f_variations) {
        // add variations to options field
        foreach ($f_variations as $variation) {
            $choice_key = kf_get_button_color_variation_unique_key($variation['name']);
            $field['choices'][$choice_key] = esc_html($variation['name']);
        }
    }

    return $field;
}
add_filter('acf/load_field/key=field_62fa6590f7171', 'kf_load_custom_button_variations'); // add variations to color options field

function kf_validate_button_color_variation_names($valid, $value, $field, $input) {
    if ($valid) {
        if ($value) {
            $variation_names = array();

            foreach ($value as $variation) {
                $unique_key = kf_get_button_color_variation_unique_key($variation['field_62f6a67d01b04']);

                if (in_array($unique_key, $variation_names)) {
                    $valid = 'Each variation must have a unique name.';

                    break;
                } else {
                    $variation_names[] = $unique_key;
                }
            }
        }
    }
    
    return $valid;
}
add_filter('acf/validate_value/key=field_62f6a55c01b03', 'kf_validate_button_color_variation_names', 10, 4); // validate variation names to ensure no duplicates

function kf_add_button_color_variation_css() {
    $f_variations = get_field('appearance_buttons_variations', 'option');

    if ($f_variations) {
        // generate CSS
        $style = '';
        foreach ($f_variations as $variation) {
            $class_name = '.custom-color-' . kf_get_button_color_variation_unique_key($variation['name']);

            $style .= $class_name . ',' . $class_name . ':visited{background-color:' . $variation['default']['bg'] . ';color:' . $variation['default']['text'] . ';}';
            $style .= $class_name . ':hover,' . $class_name . ':focus,' . $class_name . ':active' . '{background-color:' . $variation['hover']['bg'] . ';color:' . $variation['hover']['text'] . ';}';
        }

        wp_add_inline_style('kinectiv-flex-style', $style);
    }
}
add_action('wp_enqueue_scripts', 'kf_add_button_color_variation_css');


/**
 * Remove unnecessary panels/controls from WP Customizer
 */
function ks_customize_register($wp_customize) {
    $wp_customize->remove_control('site_icon'); // remove site icon control
    $wp_customize->remove_panel('nav_menus'); // remove menus panel
}
add_action('customize_register', 'ks_customize_register', 20);


/**
 * Disable WP Theme Editor
 */	
define('DISALLOW_FILE_EDIT', true);


/**
 * Add site icon
 */
function ks_favicon() {
  echo '<link rel="icon" type="image/x-icon" href="' . get_stylesheet_directory_uri() . '/images/favicon.ico" />';
}
add_action('wp_head', 'ks_favicon');
add_action('admin_head', 'ks_favicon');


/**
 * Show Gravity Form
 */
function kf_show_gform($form_id, $theme, $button_variation = 'default') {
    // temporarily store the theme as a field value in order to pass it to the form
    $field_values = array(
        '_kf_temp_color_theme' => $theme,
        '_kf_temp_button_variation' => $button_variation
    );
    ?>
    <div class="gravity-form-wrap">
        <?php gravity_form($form_id, false, false, false, $field_values, true, 0); ?>
    </div>
    <?php
}


/**
 * Gravity Forms - hide "Add Form" WYSIWYG button
 */
add_filter('gform_display_add_form_button', '__return_false');


/**
 * Gravity Forms - remove unwanted field types
 */
function kf_filter_gform_add_field_buttons($field_groups) {
    foreach ($field_groups as $group_i => $group) {
        $fields_to_remove = array();
        
        foreach($group['fields'] as $field_i => $field) {
            if ($field['value'] == GFCommon::get_field_type_title('page') ||
                $field['value'] == GFCommon::get_field_type_title('post_excerpt') ||
                $field['value'] == GFCommon::get_field_type_title('post_image')) {
                unset($field_groups[$group_i]['fields'][$field_i]);
            }
        }
    }

    return $field_groups;
}
add_filter('gform_add_field_buttons', 'kf_filter_gform_add_field_buttons');


/**
 * Gravity Forms - store theme in ACF store for access while modifying form markup
 */
function kf_get_theme_gform_form_args($form_args) {
    $passthrough = array();

    if (isset($form_args['field_values']['_kf_temp_color_theme'])) {
        $passthrough['theme'] = $form_args['field_values']['_kf_temp_color_theme'];
    }
    if (isset($form_args['field_values']['_kf_temp_button_variation'])) {
        $passthrough['button_variation'] = $form_args['field_values']['_kf_temp_button_variation'];
    }

    if ($passthrough) {
        acf_register_store('passthrough_form', $passthrough);
    }
    
    return $form_args;
}
add_filter('gform_form_args', 'kf_get_theme_gform_form_args');

function kf_reset_theme_gform_get_form_filter($form_string, $form) {
    acf_register_store('passthrough_form', array());
    
    return $form_string;
}
add_filter('gform_get_form_filter', 'kf_reset_theme_gform_get_form_filter', 100, 2);


/**
 * Gravity Forms - add js to modify default field settings
 */
function kf_gform_editor_js() {
    ?>
    <script type="text/javascript">
        // set file upload to default to multiple files
        function SetDefaultValues_fileupload(field) {
            field.multipleFiles = true;
        }
    </script>
    <?php
}
add_action('gform_editor_js', 'kf_gform_editor_js');


/**
 * Gravity Forms - modify field content
 */
function kf_gform_field_content($field_content, $field) {
    // modify fields only on front-end
    if (!is_admin()) {
        $passthrough = acf_get_store('passthrough_form');
        $pt_theme = null;
        $pt_button_variation = null;
        if ($passthrough) {
            $pt_theme = $passthrough->get('theme');
            $pt_button_variation = $passthrough->get('button_variation');
        }
        
        $theme = isset($pt_theme) ? $pt_theme : 'main';
        $button_variation = isset($pt_button_variation) ? $pt_button_variation : 'default';

        // get class list for button colors
        $button_color_classes = kf_button_color_classes($theme, $button_variation);

        
        // enable user error handling (to prevent warnings caused by HTML5 and SVG tags)
        libxml_clear_errors();
        $libxml_err_prev = libxml_use_internal_errors(true);
        
        $dom = new DOMDocument();
        $dom->loadHTML('<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>' . $field_content); // load HTML with proper encoding
        
        
        // modifications for specific field types
        if ($field->type == 'section') {
            // add classes to section title (or remove if there's no title)
            foreach ($dom->getElementsByTagName('h3') as $h3_element) {
                $classes = explode(' ', $h3_element->getAttribute('class'));
                
                if (in_array('gsection_title', $classes)) {
                    if ($field->label) {
                        $classes[] = 'text text_xl text_normal text_line_1-4 c_color_' . color_id($theme, 3, true);
                        $h3_element->setAttribute('class', implode(' ', $classes));
                    } else {
                        $h3_element->parentNode->removeChild($h3_element); // if there's no title text, just remove the element
                    }
                }
            }
            
            // add classes to section description
            foreach ($dom->getElementsByTagName('div') as $div_element) {
                $classes = explode(' ', $div_element->getAttribute('class'));
                
                if (in_array('gsection_description', $classes)) {
                    $classes[] = 'text c_color_' . color_id($theme, 3, true);
                    $div_element->setAttribute('class', implode(' ', $classes));
                }
            }
        } elseif ($field->type == 'address') {
            // add color classes to copy values checkbox
            foreach ($dom->getElementsByTagName('input') as $input_element) {
                $classes = explode(' ', $input_element->getAttribute('class'));
                
                if (in_array('copy_values_activated', $classes)) {
                    $classes[] = 'c_color_' . color_id($theme, 3, true) . ' c_aft_color_' . color_id($theme, 5, true);
                    $input_element->setAttribute('class', implode(' ', $classes));
                }
            }
        } elseif ($field->type == 'fileupload' || ($field->type == 'post_custom_field' && $field->inputType == 'fileupload')) {
            $drop_area = null;
            
            // add color classes to button
            foreach ($dom->getElementsByTagName('button') as $button_element) {
                $classes = explode(' ', $button_element->getAttribute('class'));
                
                if (in_array('gform_button_select_files', $classes)) {
                    $classes[] = $button_color_classes;
                    $button_element->setAttribute('class', implode(' ', $classes));
                }
            }
            
            // add color classes to drop area
            foreach ($dom->getElementsByTagName('div') as $div_element) {
                $classes = explode(' ', $div_element->getAttribute('class'));
                
                if (in_array('gform_drop_area', $classes)) {
                    $classes[] = 'c_border_' . color_id($theme, 1, true);
                    $div_element->setAttribute('class', implode(' ', $classes));
                    
                    $drop_area = $div_element;
                }
            }
            
            // move extension list inside drop-area, make it visibile, and add text/color classes
            foreach ($dom->getElementsByTagName('div') as $div_element) {
                $div_classes = explode(' ', $div_element->getAttribute('class'));
                
                if (in_array('ginput_container', $div_classes)) {
                    foreach ($div_element->childNodes as $div_child_element) {
                        if ($div_child_element->nodeName == 'span') {
                            $div_child_classes = explode(' ', $div_child_element->getAttribute('class'));
                            
                            if (in_array('gform_fileupload_rules', $div_child_classes)) {
                                $remove_key = array_search('screen-reader-text', $div_child_classes);
                                if ($remove_key !== false) {
                                    unset($div_child_classes[$remove_key]);
                                }
                                $div_child_classes[] = 'text text_xs text_italic text_line_1-4 c_color_' . color_id($theme, 3, true); 
                                $div_child_element->setAttribute('class', implode(' ', $div_child_classes));
                                
                                // move span within drop area
                                if ($drop_area) {
                                    $drop_area->appendChild($div_child_element);
                                }
                            }
                        }
                    }
                }
            }
            
            // add custom file previews div
            foreach ($dom->getElementsByTagName('div') as $div_element) {
                $classes = explode(' ', $div_element->getAttribute('class'));
                
                if (in_array('ginput_container_fileupload', $classes)) {
                    $previews_div = $dom->createElement('div');
                    $previews_div->setAttribute('class', 'kfgf-file-previews');
                    $div_element->appendChild($previews_div);
                    
                    // create prototype of preview
                    ob_start();
                    ?>
                    <div class="kfgf-file-previews__proto">
                        <div class="kfgf-file-previews__item kfgf-file-previews__item_error kfgf-file-previews__item_proto">
                            <p class="kfgf-file-previews__error-text text text_xs text_italic text_line_1-4 c_color_<?php aux_color_id($theme, 1); ?>"></p>
                        </div>
                        <div class="kfgf-file-previews__item kfgf-file-previews__item_preview kfgf-file-previews__item_new kfgf-file-previews__item_proto">
                            <div class="kfgf-file-previews__preview">
                                <div class="kfgf-file-previews__box c_bg_<?php color_id($theme, 1); ?> c_bfr_bg_<?php aux_color_id($theme, 0); ?>">
                                    <p class="kfgf-file-previews__name text text_xs text_line_1-4 c_color_<?php color_id($theme, 5); ?>"></p>
                                </div>
                                <div class="kfgf-file-previews__bottom">
                                    <div class="kfgf-file-previews__bottom-main">
                                        <p class="kfgf-file-previews__status text text_xs text_line_1-4 c_color_<?php color_id($theme, 5); ?>"></p>
                                        <div class="kfgf-file-previews__progress kfgf-file-previews__progress_hidden c_bg_<?php color_id($theme, 1); ?>">
                                            <div class="kfgf-file-previews__progress-bar c_bg_<?php color_id($theme, 4); ?>"></div>
                                        </div>
                                    </div>
                                    <div class="kfgf-file-previews__bottom-side">
                                        <button type="button" class="kfgf-file-previews__cancel c_h-parent" title="Remove File">
                                            <span class="screen-reader-text">Remove File</span>
                                            <svg viewBox="0 0 27.03 27.04" class="kfgf-file-previews__cancel-x">
                                                <polygon class="kfgf-file-previews__cancel-x-fill c_fill_<?php color_id($theme, 3); ?> c_h-child_fill_<?php color_id($theme, 5); ?>" points="17.45 13.52 17.45 13.51 27.03 3.94 23.09 0 13.52 9.58 3.94 0 0 3.94 9.58 13.51 9.57 13.52 9.58 13.52 0 23.1 3.94 27.04 13.51 17.46 23.09 27.04 27.03 23.1 17.45 13.52 17.45 13.52"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    $proto_html = ob_get_clean();
                    
                    $proto_dom = new DOMDocument();
                    $proto_dom->loadHTML('<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>' . $proto_html);
                    
                    $proto = $proto_dom->getElementsByTagName('div')->item(0);
                    $proto = $dom->importNode($proto, true);
                    $previews_div->appendChild($proto);
                }
            }
        } elseif ($field->type == 'list' || ($field->type == 'post_custom_field' && $field->inputType == 'list')) {
            // add color classes to add/remove buttons and remove img tags
            foreach ($dom->getElementsByTagName('button') as $button_element) {
                $classes = explode(' ', $button_element->getAttribute('class'));
                
                if (in_array('add_list_item', $classes) || in_array('delete_list_item', $classes)) {
                    $classes[] = 'c_color_' . color_id($theme, 3, true) . ' c_h_color_' . color_id($theme, 5, true) . ' c_bfr_color_' . color_id($theme, 1, true) . ' c_aft_color_' . color_id($theme, 1, true);
                    $button_element->setAttribute('class', implode(' ', $classes));
                }
            }
        } elseif ($field->type == 'consent') {
            // add color classes to checkbox
            foreach ($dom->getElementsByTagName('input') as $input_element) {
                if ($input_element->getAttribute('type') == 'checkbox') {
                    $classes = 'c_color_' . color_id($theme, 3, true) . ' c_aft_color_' . color_id($theme, 5, true);
                    $input_element->setAttribute('class', trim($input_element->getAttribute('class') . ' ' . $classes));
                }
            }
        } elseif ($field->type == 'stripe_creditcard') {
            // add color classes to Stripe element
            foreach ($dom->getElementsByTagName('div') as $div_element) {
                $div_classes = explode(' ', $div_element->getAttribute('class'));

                if (in_array('ginput_container_creditcard', $div_classes)) {
                    $div_id = $div_element->getAttribute('id');

                    foreach ($div_element->getElementsByTagName('div') as $div_2_element) {
                        if ($div_2_element->getAttribute('id') == $div_id . '_1') {
                            $div_2_classes = 'c_color_' . color_id($theme, 1, true);
                            $div_2_element->setAttribute('class', trim($div_2_element->getAttribute('class') . ' ' . $div_2_classes));
                            
                            // add data attribute for validation message classes (added by JS)
                            $validation_classes = 'text text_xs text_italic text_line_1-4 c_color_' . aux_color_id($theme, 1, true);
                            $div_2_element->setAttribute('data-validation-classes', $validation_classes);
                        }
                    }
                }
            }
        } elseif ($field->type == 'date') {
            // disable autocomplete if datepicker is enabled
            foreach ($dom->getElementsByTagName('input') as $input_element) {
                $classes = explode(' ', $input_element->getAttribute('class'));
                
                if (in_array('datepicker', $classes)) {
                    $input_element->setAttribute('autocomplete', 'off');
                }
            }
        }
        
        // add classes to field labels
        foreach ($dom->getElementsByTagName('label') as $label_element) {
            $classes = explode(' ', $label_element->getAttribute('class'));
            
            if (in_array('gfield_label', $classes)) {
                $classes[] = 'text text_bold text_line_1-4';
                if ($field->failed_validation) {
                    $classes[] = 'c_color_' . aux_color_id($theme, 1, true);
                }
                
                $label_element->setAttribute('class', implode(' ', $classes));
            }
        }
        
        // add classes to fieldset legends (to match standard labels)
        foreach ($dom->getElementsByTagName('legend') as $legend_element) {
            $classes = explode(' ', $legend_element->getAttribute('class'));
            
            if (in_array('gfield_label', $classes)) {
                $classes[] = 'text text_bold text_line_1-4';
                if ($field->failed_validation) {
                    $classes[] = 'c_color_' . aux_color_id($theme, 1, true);
                }
                
                $legend_element->setAttribute('class', implode(' ', $classes));
            }
        }
        
        // add text classes to complex field wrappers
        foreach ($dom->getElementsByTagName('div') as $div_element) {
            $classes = explode(' ', $div_element->getAttribute('class'));
            
            if (in_array('ginput_complex', $classes) || in_array('clear-multi', $classes)) {
                $classes[] = 'text text_xs text_line_1-4';
                $div_element->setAttribute('class', implode(' ', $classes));
            }
        }
        
        // add classes to field descriptions (and validation messages)
        foreach ($dom->getElementsByTagName('div') as $div_element) {
            $classes = explode(' ', $div_element->getAttribute('class'));
            
            if (in_array('gfield_description', $classes) || (in_array('instruction', $classes) && in_array('validation_message', $classes))) {
                $classes[] = 'text';
                if (!in_array('gfield_consent_description', $classes)) {
                    $classes[] = 'text_line_1-4';
                }
                if (in_array('validation_message', $classes)) {
                    $classes[] = 'text_xs text_italic c_color_' . aux_color_id($theme, 1, true);
                } else {
                    $classes[] = 'c_color_' . color_id($theme, 3, true);
                }
                
                $div_element->setAttribute('class', implode(' ', $classes));
            }
        }
        
        // add classes to inputs
        $input_types_to_modify = array('text', 'password', 'number', 'tel', 'url', 'email');
        foreach ($dom->getElementsByTagName('input') as $input_element) {
            $type = $input_element->getAttribute('type');
            
            if (in_array($type, $input_types_to_modify)) {
                $classes = 'c_bg_' . color_id($theme, 1, true) . ' c_color_' . color_id($theme, 5, true) . ' c_placeholder_' . color_id($theme, 2, true);
                $input_element->setAttribute('class', trim($input_element->getAttribute('class') . ' ' . $classes));
            }
        }
        
        // add classes to textareas
        foreach ($dom->getElementsByTagName('textarea') as $textarea_element) {
            $classes = 'c_bg_' . color_id($theme, 1, true) . ' c_color_' . color_id($theme, 5, true) . ' c_placeholder_' . color_id($theme, 2, true);
            $textarea_element->setAttribute('class', trim($textarea_element->getAttribute('class') . ' ' . $classes));
        }
        
        // add classes and style to selects
        foreach ($dom->getElementsByTagName('select') as $select_element) {
            $classes = 'c_bg_' . color_id($theme, 1, true) . ' c_color_' . color_id($theme, 5, true) . ' c_placeholder_' . color_id($theme, 2, true);
            $select_element->setAttribute('class', trim($select_element->getAttribute('class') . ' ' . $classes));
            
            if (!$select_element->hasAttribute('multiple')) {
                $style = 'background-image: url(\'' . get_stylesheet_directory_uri() . '/images/dynamic/control-arrow-down.php?color=' . color_id($theme, 5, true) . '\');';
                $select_element->setAttribute('style', trim($select_element->getAttribute('style') . ' ' . $style));
            }
        }
        
        // add color classes to checkboxes and radio buttons, add classes to checkbox select all button
        foreach ($dom->getElementsByTagName('div') as $div_element) {
            $div_classes = explode(' ', $div_element->getAttribute('class'));

            if (in_array('gfield_checkbox', $div_classes)) {
                foreach ($div_element->getElementsByTagName('input') as $input_element) {
                    $input_classes = 'c_color_' . color_id($theme, 3, true) . ' c_aft_color_' . color_id($theme, 5, true);
                    $input_element->setAttribute('class', trim($input_element->getAttribute('class') . ' ' . $input_classes));
                }
                foreach ($div_element->getElementsByTagName('button') as $button_element) {
                    $button_classes = 'button ' . $button_color_classes;
                    $button_element->setAttribute('class', trim($button_element->getAttribute('class') . ' ' . $button_classes));
                }
            } elseif (in_array('gfield_radio', $div_classes)) {
                foreach ($div_element->getElementsByTagName('input') as $input_element) {
                    $input_classes = 'c_color_' . color_id($theme, 3, true) . ' c_aft_color_' . color_id($theme, 5, true);
                    $input_element->setAttribute('class', trim($input_element->getAttribute('class') . ' ' . $input_classes));
                }
            }
        }
        
        // add text classes to product quantity sub-labels, product price labels, product prices, shipping prices, and total prices
        foreach ($dom->getElementsByTagName('span') as $span_element) {
            $classes = explode(' ', $span_element->getAttribute('class'));
            
            if (in_array('ginput_quantity_label', $classes)) {
                $classes[] = 'text text_xs text_line_1-4';
                $span_element->setAttribute('class', implode(' ', $classes));
            }
            
            if (in_array('ginput_product_price_label', $classes) || in_array('ginput_product_price', $classes) || in_array('ginput_shipping_price', $classes)) {
                $classes[] = 'text text_italic text_line_1-4 c_color_' . color_id($theme, 3, true);
                $span_element->setAttribute('class', implode(' ', $classes));
            }
            
            if (in_array('ginput_total', $classes)) {
                $classes[] = 'text text_lg text_bold text_line_1-4 c_color_' . color_id($theme, 3, true);
                $span_element->setAttribute('class', implode(' ', $classes));
            }
        }
        foreach ($dom->getElementsByTagName('label') as $label_element) {
            $classes = explode(' ', $label_element->getAttribute('class'));
            
            if (in_array('ginput_product_price_label', $classes)) {
                $classes[] = 'text text_italic text_line_1-4 c_color_' . color_id($theme, 3, true);
                $label_element->setAttribute('class', implode(' ', $classes));
            }
        }
        foreach ($dom->getElementsByTagName('input') as $input_element) {
            $classes = explode(' ', $input_element->getAttribute('class'));
            
            if (in_array('ginput_product_price', $classes) || in_array('ginput_shipping_price', $classes)) {
                $classes[] = 'gform-text-input-reset text text_italic text_line_1-4 c_color_' . color_id($theme, 3, true);
                $input_element->setAttribute('class', implode(' ', $classes));
            }
            
            if (in_array('ginput_total', $classes)) {
                $classes[] = 'text text_lg text_bold text_line_1-4 c_color_' . color_id($theme, 3, true);
                $input_element->setAttribute('class', implode(' ', $classes));
            }
        }
        foreach ($dom->getElementsByTagName('div') as $div_element) {
            $classes = explode(' ', $div_element->getAttribute('class'));
            
            if (in_array('ginput_container_total', $classes)) {
                $classes[] = 'text text_lg text_bold text_line_1-4 c_color_' . color_id($theme, 3, true);
                $div_element->setAttribute('class', implode(' ', $classes));
            }
        }
        
        // disable autocomplete by default
        foreach ($dom->getElementsByTagName('input') as $input_element) {
            $input_element->setAttribute('autocomplete', 'off');
        }
        foreach ($dom->getElementsByTagName('select') as $select_element) {
            $select_element->setAttribute('autocomplete', 'off');
        }
        foreach ($dom->getElementsByTagName('textarea') as $textarea_element) {
            $textarea_element->setAttribute('autocomplete', 'off');
        }
        
        // add autocomplete attributes for specific field types
        if ($field->type == 'name') {
            // add correct autocomplete attribute to each field
            foreach ($dom->getElementsByTagName('span') as $span_element) {
                $classes = explode(' ', $span_element->getAttribute('class'));
                
                if (in_array('name_prefix', $classes)) {
                    foreach ($span_element->getElementsByTagName('select') as $select_element) {
                        $select_element->setAttribute('autocomplete', 'honorific-prefix');
                    }
                } elseif (in_array('name_first', $classes)) {
                    foreach ($span_element->getElementsByTagName('input') as $input_element) {
                        $input_element->setAttribute('autocomplete', 'given-name');
                    }
                } elseif (in_array('name_middle', $classes)) {
                    foreach ($span_element->getElementsByTagName('input') as $input_element) {
                        $input_element->setAttribute('autocomplete', 'additional-name');
                    }
                } elseif (in_array('name_last', $classes)) {
                    foreach ($span_element->getElementsByTagName('input') as $input_element) {
                        $input_element->setAttribute('autocomplete', 'family-name');
                    }
                } elseif (in_array('name_suffix', $classes)) {
                    foreach ($span_element->getElementsByTagName('input') as $input_element) {
                        $input_element->setAttribute('autocomplete', 'honorific-suffix');
                    }
                }
            }
        } elseif ($field->type == 'phone') {
            foreach ($dom->getElementsByTagName('input') as $input_element) {
                $input_element->setAttribute('autocomplete', 'tel');
            }
        } elseif ($field->type == 'address') {
            // add correct autocomplete attribute to each field
            foreach ($dom->getElementsByTagName('span') as $span_element) {
                $classes = explode(' ', $span_element->getAttribute('class'));
                
                if (in_array('address_line_1', $classes)) {
                    foreach ($span_element->getElementsByTagName('input') as $input_element) {
                        $input_element->setAttribute('autocomplete', 'address-line1');
                    }
                } elseif (in_array('address_line_2', $classes)) {
                    foreach ($span_element->getElementsByTagName('input') as $input_element) {
                        $input_element->setAttribute('autocomplete', 'address-line2');
                    }
                } elseif (in_array('address_city', $classes)) {
                    foreach ($span_element->getElementsByTagName('input') as $input_element) {
                        $input_element->setAttribute('autocomplete', 'address-level2');
                    }
                } elseif (in_array('address_state', $classes)) {
                    foreach ($span_element->getElementsByTagName('input') as $input_element) {
                        $input_element->setAttribute('autocomplete', 'address-level1');
                    }
                    foreach ($span_element->getElementsByTagName('select') as $select_element) {
                        $select_element->setAttribute('autocomplete', 'address-level1');
                    }
                } elseif (in_array('address_zip', $classes)) {
                    foreach ($span_element->getElementsByTagName('input') as $input_element) {
                        $input_element->setAttribute('autocomplete', 'postal-code');
                    }
                } elseif (in_array('address_country', $classes)) {
                    foreach ($span_element->getElementsByTagName('select') as $select_element) {
                        $select_element->setAttribute('autocomplete', 'country-name');
                    }
                }
            }
            
            // disable autocomplete for copy values option checkbox
            foreach ($dom->getElementsByTagName('div') as $div_element) {
                $classes = explode(' ', $div_element->getAttribute('class'));
                
                if (in_array('copy_values_option_container', $classes)) {
                    foreach ($div_element->getElementsByTagName('input') as $input_element) {
                        $input_element->setAttribute('autocomplete', 'off');
                    }
                }
            }
        } elseif ($field->type == 'website') {
            foreach ($dom->getElementsByTagName('input') as $input_element) {
                $input_element->setAttribute('autocomplete', 'url');
            }
        } elseif ($field->type == 'email') {
            foreach ($dom->getElementsByTagName('input') as $input_element) {
                $input_element->setAttribute('autocomplete', 'email');
            }
        } elseif ($field->type == 'stripe_creditcard') {
            foreach ($dom->getElementsByTagName('input') as $input_element) {
                if ($input_element->getAttribute('type') == 'text' && $input_element->getAttribute('aria-hidden') != 'true') {
                    $input_element->setAttribute('autocomplete', 'cc-name');
                }
            }
        }
        
        
        // build string of all elements (to exclude <html> and <body> wrappers that are added automatically)
        $field_content = '';
        if ($dom->getElementsByTagName('body')->item(0)->childNodes > 0) {
            foreach ($dom->getElementsByTagName('body')->item(0)->childNodes as $node) {
                $field_content .= $dom->saveHTML($node);
            }
        }
        
        // log errors and reset error handling
        foreach (libxml_get_errors() as $error) {
            error_log('Suppressed LibXMLError: ' . print_r($error, true));
        }
        libxml_clear_errors();
        libxml_use_internal_errors($libxml_err_prev);
    }
 
    return $field_content;
}
add_filter('gform_field_content', 'kf_gform_field_content', 10, 2);


/**
 * Gravity Forms - customize validation message
 */
function kf_gform_validation_message($message, $form) {
    $passthrough = acf_get_store('passthrough_form');
    $pt_theme = null;
    if ($passthrough) {
        $pt_theme = $passthrough->get('theme');
    }

    $theme = isset($pt_theme) ? $pt_theme : 'main';
    
    // enable user error handling (to prevent warnings caused by HTML5 and SVG tags)
    libxml_clear_errors();
    $libxml_err_prev = libxml_use_internal_errors(true);
    
    $dom = new DOMDocument();
    $dom->loadHTML('<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>' . $message); // load HTML with proper encoding
    
    
    // add text/color classes
    foreach ($dom->getElementsByTagName('h2') as $h2_element) {
        $classes = explode(' ', $h2_element->getAttribute('class'));
        
        if (in_array('gform_submission_error', $classes)) {
            // replace h2 with p
            $p_element = $dom->createElement('p');
            foreach ($h2_element->childNodes as $child) {
                $p_element->appendChild($child);
            }
            $p_element->textContent = $h2_element->textContent;
            foreach ($h2_element->attributes as $attribute) {
                $p_element->setAttribute($attribute->name, $attribute->value);
            }
            $h2_element->parentNode->replaceChild($p_element, $h2_element);
            
            $classes[] = 'text text_italic text_line_1-4 c_bg_' . aux_color_id($theme, 1, true) . ' c_color_' . color_id($theme, 0, true);
            $p_element->setAttribute('class', implode(' ', $classes));
        }
    }
    
    
    // build string of all elements (to exclude <html> and <body> wrappers that are added automatically)
    $message = '';
    if ($dom->getElementsByTagName('body')->item(0)->childNodes > 0) {
        foreach ($dom->getElementsByTagName('body')->item(0)->childNodes as $node) {
            $message .= $dom->saveHTML($node);
        }
    }
    
    // log errors and reset error handling
    foreach (libxml_get_errors() as $error) {
        error_log('Suppressed LibXMLError: ' . print_r($error, true));
    }
    libxml_clear_errors();
    libxml_use_internal_errors($libxml_err_prev);
    
    return $message;
}
add_filter('gform_validation_message', 'kf_gform_validation_message', 10, 2);


/**
 * Gravity Forms - disable theme CSS
 */
add_filter('gform_disable_form_theme_css', '__return_true');


/**
 * Gravity Forms - set Stripe styles
 */
function kf_gform_set_stripe_styles($cardStyles, $formID){
    $passthrough = acf_get_store('passthrough_form');
    $pt_theme = null;
    if ($passthrough) {
        $pt_theme = $passthrough->get('theme');
    }

    $theme = isset($pt_theme) ? $pt_theme : 'main';
    
    $cardStyles['base'] = array(
        'backgroundColor' => kf_color_id_list()[color_id($theme, 1, true)],
        'color' => kf_color_id_list()[color_id($theme, 5, true)],
        'fontSize' => '16px',
        'lineHeight' => '1.4',
        '::placeholder' => array(
            'color' => kf_color_id_list()[color_id($theme, 2, true)]
        )
    );
    $cardStyles['invalid'] = array(
        'color' => kf_color_id_list()[aux_color_id($theme, 1, true)]
    );
    
    return $cardStyles;
}
add_filter('gform_stripe_elements_style', 'kf_gform_set_stripe_styles', 10, 2);


/**
 * Gravity Forms - customize submit button
 */
function kf_gform_submit_button($button, $form) {
    $passthrough = acf_get_store('passthrough_form');
    $pt_theme = null;
    $pt_button_variation = null;
    if ($passthrough) {
        $pt_theme = $passthrough->get('theme');
        $pt_button_variation = $passthrough->get('button_variation');
    }

    $theme = isset($pt_theme) ? $pt_theme : 'main';
    $button_variation = isset($pt_button_variation) ? $pt_button_variation : 'default';

    // get class list for button colors
    $button_color_classes = kf_button_color_classes($theme, $button_variation);

    
    // enable user error handling (to prevent warnings caused by HTML5 and SVG tags)
    libxml_clear_errors();
    $libxml_err_prev = libxml_use_internal_errors(true);
    
    $dom = new DOMDocument();
    $dom->loadHTML('<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>' . $button); // load HTML with proper encoding
    
    $input_element = $dom->getElementsByTagName('input')->item(0); // get default button
    
    // create new button and duplicate all attributes from existing button
    $button_element = $dom->createElement('button');
    $button_element->appendChild($dom->createTextNode($input_element->getAttribute('value')));
    $input_element->removeAttribute('value');
    foreach($input_element->attributes as $attribute) {
        $button_element->setAttribute($attribute->name, $attribute->value);
    }
    
    // add classes to new button
    $classes = $button_color_classes;
    $button_element->setAttribute('class', trim($button_element->getAttribute('class') . ' ' . $classes));
    
    $input_element->parentNode->replaceChild($button_element, $input_element); // replace existing button with new button
    
    $button = $dom->saveHtml($button_element);
    
    // log errors and reset error handling
    foreach (libxml_get_errors() as $error) {
        error_log('Suppressed LibXMLError: ' . print_r($error, true));
    }
    libxml_clear_errors();
    libxml_use_internal_errors($libxml_err_prev);

    return $button; // return HTML for new button
}
add_filter('gform_submit_button', 'kf_gform_submit_button', 10, 2);


/**
 * Gravity Forms - customize save and continue link
 */
function kf_gform_savecontinue_link($link, $form) {
    $passthrough = acf_get_store('passthrough_form');
    $pt_theme = null;
    $pt_button_variation = null;
    if ($passthrough) {
        $pt_theme = $passthrough->get('theme');
        $pt_button_variation = $passthrough->get('button_variation');
    }

    $theme = isset($pt_theme) ? $pt_theme : 'main';
    $button_variation = isset($pt_button_variation) ? $pt_button_variation : 'default';

    // get class list for button colors
    $button_color_classes = kf_button_color_classes($theme, $button_variation);
    
    
    // enable user error handling (to prevent warnings caused by HTML5 and SVG tags)
    libxml_clear_errors();
    $libxml_err_prev = libxml_use_internal_errors(true);
    
    $dom = new DOMDocument();
    $dom->loadHTML('<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>' . $link); // load HTML with proper encoding
    
    $button_element = $dom->getElementsByTagName('button')->item(0); // get default button
    
    if ($button_element) {
        // add classes to link
        $classes = $button_color_classes;
        $button_element->setAttribute('class', trim($button_element->getAttribute('class') . ' ' . $classes));


        $link = $dom->saveHtml($button_element);
    }
    
    // log errors and reset error handling
    foreach (libxml_get_errors() as $error) {
        error_log('Suppressed LibXMLError: ' . print_r($error, true));
    }
    libxml_clear_errors();
    libxml_use_internal_errors($libxml_err_prev);
    
    return $link;
}
add_filter('gform_savecontinue_link', 'kf_gform_savecontinue_link', 10, 2);


/**
 * Gravity Forms - customize confirmations
 */
function kf_modify_gform_confirmation($message, $theme) {
    $message = wpautop($message); // auto add <p> tags
    $message = kf_wysiwyg_text_classes($message); // add text classes
    $message = kf_wysiwyg_color_classes($message, $theme); // add color classes
    $message = '<div class="text text_wrap' . (($theme == 'main-dark' || $theme == 'alt-dark') ? ' light' : '') . '">' . $message . '</div>'; // add text wrapper
    
    return $message;
}

function kf_gform_pre_process_confirmation($form) {
    if (is_array($form['confirmations'])) {
        parse_str(GFForms::post('gform_field_values'), $field_values);
    
        $theme = isset($field_values['_kf_temp_color_theme']) ? $field_values['_kf_temp_color_theme'] : 'main';
        
        // apply WYSIWYG styling functions to text confirmations
        foreach ($form['confirmations'] as &$confirmation) {
            if ($confirmation['type'] == 'message' && !empty($confirmation['message'])) {
                $confirmation['message'] = kf_modify_gform_confirmation($confirmation['message'], $theme);
            }
        }
    }
    
    return $form;
}
add_filter('gform_pre_process', 'kf_gform_pre_process_confirmation');

function kf_gform_pre_confirmation_save($confirmation, $form) {
    $confirmation['disableAutoformat'] = true; // disable nl2br
    
    return $confirmation;
}
add_filter('gform_pre_confirmation_save', 'kf_gform_pre_confirmation_save', 10, 2);


/**
 * Gravity Forms - customize back-end confirmation WYSIWYG editor
 */
function ks_gform_confirmation_wp_editor_settings($settings, $editor_id) {
    $gf_subview = GFSettings::get_subview();
    
    if ($editor_id == '_gform_setting_message' && $gf_subview == 'confirmation') {
        $settings['quicktags'] = false; // disable "text" tab
    }
    
    return $settings;
}
add_filter('wp_editor_settings', 'ks_gform_confirmation_wp_editor_settings', 10, 2); // customize wp_editor settings

function ks_gform_confirmation_tiny_mce_before_init($mce_init, $editor_id) {
    $gf_subview = GFSettings::get_subview();

    if ($editor_id == '_gform_setting_message' && $gf_subview == 'confirmation') {
        $mce_init = ks_configure_tinymce($mce_init, 'Standard', false);
    }

    return $mce_init;
}
add_filter('tiny_mce_before_init', 'ks_gform_confirmation_tiny_mce_before_init', 10, 2); // customize toolbar


/**
 * Gravity Forms - customize save and continue confirmations
 */
function kf_gform_pre_replace_merge_tags_save_continue($text, $form, $entry, $url_encode, $esc_html, $nl2br, $format) {
    // if this is a subsequent load of a save and continue confirmation, modify it
    if (isset($_POST['gform_send_resume_link']) && kf_get_gform_mode() == 'render') {
        parse_str(GFForms::post('gform_field_values'), $field_values);
        
        $theme = isset($field_values['_kf_temp_color_theme']) ? $field_values['_kf_temp_color_theme'] : 'main';
        
        
        $text = kf_modify_gform_confirmation($text, $theme);
    }
    
    // manually replace save form with customizations
    if (strpos($text, '{save_email_input}') !== false) {
        parse_str(GFForms::post('gform_field_values'), $field_values);
        
        $theme = isset($field_values['_kf_temp_color_theme']) ? $field_values['_kf_temp_color_theme'] : 'main';
        $button_variation = isset($field_values['_kf_temp_button_variation']) ? $field_values['_kf_temp_button_variation'] : 'default';

        // get class list for button colors
        $button_color_classes = kf_button_color_classes($theme, $button_variation);
        
        
        $form_id = intval($form['id']);
		$ajax = isset($_POST['gform_ajax']);
        $action = esc_url(remove_query_arg('gf_token')) . GFFormDisplay::get_anchor($form, $ajax)['id'];
        $resume_email = isset($_POST['gform_resume_email']) ? rgpost('gform_resume_email') : null;
        ob_start();
        ?>
        <div class="form_saved_message_emailform">
            <form action="<?php echo $action; ?>" method="POST" id="gform_<?php echo $form_id; ?>"<?php echo $ajax ? ' target="gform_ajax_frame_' . $form_id . '"' : ''; ?>>
                <?php if ($ajax): ?>
                <input type="hidden" name="gform_ajax" value="<?php echo esc_attr('form_id=' . $form_id . '&amp;title=1&amp;description=1&amp;tabindex=1'); ?>" />
                <input type="hidden" name="gform_field_values" value="<?php echo esc_attr('_kf_temp_color_theme=' . $theme . '&_kf_temp_button_varation=' . $button_variation); ?>" />
                <input type="hidden" class="gform_hidden" name="is_submit_<?php echo $form_id; ?>" value="1" />
                <input type="hidden" class="gform_hidden" name="gform_submit" value="<?php echo $form_id; ?>" />
                <?php endif; ?>
                <label for="gform_resume_email" class="gform_resume_email_label gfield_label text text_bold text_line_1-4<?php if (!is_null($resume_email) && !GFCommon::is_valid_email($resume_email)) { echo ' c_color_' . aux_color_id($theme, 1, true); } ?>" aria-describedby="email-validation-error">Email Address</label>
                <input type="<?php echo RGFormsModel::is_html5_enabled() ? 'email' : 'text'; ?>" name="gform_resume_email" value="<?php echo esc_attr($resume_email); ?>" class="c_bg_<?php color_id($theme, 1); ?> c_color_<?php color_id($theme, 5); ?> c_placeholder_<?php color_id($theme, 2); ?>" />
                <?php if (!is_null($resume_email) && !GFCommon::is_valid_email($resume_email)) { ?><div class="gform_validation_message text text_line_1-4 text_xs text_italic c_color_<?php aux_color_id($theme, 1); ?>" id="email-validation-error" aria-live="assertive">Please enter a valid email address.</div><?php } ?>
                <input type="hidden" name="gform_resume_token" value="{save_token}" />
                <input type="hidden" name="gform_send_resume_link" value="<?php echo $form_id; ?>" />
                <input type="hidden" class="gform_hidden" name="is_submit_<?php echo $form_id; ?>" value="1">
                <input type="hidden" class="gform_hidden" name="gform_submit" value="<?php echo $form_id; ?>">
                <button type="submit" name="gform_send_resume_link_button" id="gform_send_resume_link_button_<?php echo $form_id; ?>" class="button <?php echo $button_color_classes; ?>"<?php echo $ajax ? ' onclick="jQuery(\'#gform_' . $form_id . '\').trigger(\'submit\',[true]);"' : '' ?>>Send Link</button>
                <?php if (rgar($form, 'requireLogin')) { echo wp_nonce_field('gform_send_resume_link', '_gform_send_resume_link_nonce', true, false); } ?>
            </form>
            <script>if (typeof enhanceMouseFocusUpdate === 'function') { enhanceMouseFocusUpdate(); }</script>
        </div>
        <?php
        $resume_form = ob_get_clean();
		$text = str_replace('{save_email_input}', $resume_form, $text);
    }
    
    return $text;
}
add_filter('gform_pre_replace_merge_tags', 'kf_gform_pre_replace_merge_tags_save_continue', 10, 7);


/**
 * Gravity Forms - store variable to indicate if the form is being processed or rendered
 */
$GLOBALS['kf_gform_mode'] = null;

function kf_gform_form_args_update_mode_render($form_args) {
    $GLOBALS['kf_gform_mode'] = 'render';
    
    return $form_args;
}
add_filter('gform_form_args', 'kf_gform_form_args_update_mode_render');

function kf_gform_pre_process_update_mode_process($form) {
    $GLOBALS['kf_gform_mode'] = 'process';
    
    return $form;
}
add_filter('gform_pre_process', 'kf_gform_pre_process_update_mode_process');

function kf_get_gform_mode() {
    return $GLOBALS['kf_gform_mode'];
}


/**
 * Gravity Forms - add custom field settings
 */
function kf_gform_field_standard_settings_custom_settings($index, $form_id) {
    // checkbox/radio horizontal layout option
    if ($index == 1600) {
        ?>
        <li class="kfgf_horizontal_layout_setting field_setting">
            <label for="field_kfgf_horizontal_layout" class="section_label">Layout</label>
            <select id="field_kfgf_horizontal_layout" onchange="SetFieldProperty('kfgfHorizontalLayout', jQuery(this).val());">
                <option value="vertical">Vertical</option>
                <option value="horizontal">Horizontal</option>
            </select>
        </li>
        <?php
    }
}
add_action('gform_field_standard_settings', 'kf_gform_field_standard_settings_custom_settings', 10, 2); // add markup for standard settings

function kf_gform_editor_js_custom_settings() {
    // checkbox/radio horizontal layout option
    ?>
    <script type='text/javascript'>
        // add setting to correct field types
        fieldSettings.checkbox += ', .kfgf_horizontal_layout_setting';
        fieldSettings.radio += ', .kfgf_horizontal_layout_setting';
        
        // initialize value on field load
        jQuery(document).on('gform_load_field_settings', function(event, field, form) {
            jQuery('#field_kfgf_horizontal_layout').val(!field.kfgfHorizontalLayout ? 'vertical' : field.kfgfHorizontalLayout);
        });
    </script>
    <?php
}
add_action('gform_editor_js', 'kf_gform_editor_js_custom_settings'); // add js for settings

function kf_gform_field_css_class_custom_settings($classes, $field, $form) {
    // checkbox/radio horizontal layout option
    if ($field->type == 'checkbox' || $field->type == 'radio' || $field->inputType == 'checkbox' || $field->inputType == 'radio') {
        if ($field->kfgfHorizontalLayout == 'horizontal') {
            $classes .= ' field_kfgf_layout_horizontal';
        }
    }
    
    return $classes;
}
add_filter('gform_field_css_class', 'kf_gform_field_css_class_custom_settings', 10, 3); // add classes to field markup based on settings


/**
 * Customize post password form
 */
function kf_post_password_form($output, $post) {
    $theme = 'main';
    
    $field_id = 'pwbox-' . (empty($post->ID) ? rand() : $post->ID);
    
    ob_start();
    ?>
    <form action="<?php echo esc_url(site_url('wp-login.php?action=postpass', 'login_post')); ?>" class="post-password-form" method="post">
        <p class="post-password-form__text text">A password is required to view this content.</p>
        <div class="post-password-form__field">
            <label for="<?php echo $field_id; ?>" class="post-password-form__label text text_bold text_line_1-4">Password</label>
            <input type="password" name="post_password" size="20" id="<?php echo $field_id; ?>" class="c_bg_<?php color_id($theme, 1); ?> c_color_<?php color_id($theme, 5); ?> c_placeholder_<?php color_id($theme, 2); ?>" />
        </div>
        <button type="submit" class="post-password-form__button button <?php component_colors($theme, 'button'); ?>">Enter</button>
    </form>
    <?php
    $output = ob_get_clean();
    
    return $output;
}
add_filter('the_password_form', 'kf_post_password_form', 10, 2);


/**
 * Remove private/protected prefix from post titles
 */
function kf_remove_private_protected_prefix() {
    return '%s';
}
add_filter('private_title_format', 'kf_remove_private_protected_prefix');
add_filter('protected_title_format', 'kf_remove_private_protected_prefix');


/**
 * Disable comments
 */
function ks_disable_comments_post_types_support() {
	$post_types = get_post_types();
	foreach ($post_types as $post_type) {
		if(post_type_supports($post_type, 'comments')) {
			remove_post_type_support($post_type, 'comments');
			remove_post_type_support($post_type, 'trackbacks');
		}
	}
}
add_action('admin_init', 'ks_disable_comments_post_types_support'); // disable support for comments and trackbacks for all post types

add_filter('comments_open', '__return_false', 20); // close comments
add_filter('pings_open', '__return_false', 20); // close pings

add_filter('comments_array', '__return_empty_array'); // return empty comments array

function ks_disable_comments_admin_menu() {
	remove_menu_page('edit-comments.php');
    remove_submenu_page('options-general.php', 'options-discussion.php');
}
add_action('admin_menu', 'ks_disable_comments_admin_menu'); // remove comments and discussion settings from admin menu

function ks_disable_comments_admin_bar($wp_admin_bar) {
    $wp_admin_bar->remove_node('comments');
}
add_action('admin_bar_menu', 'ks_disable_comments_admin_bar', 999); // remove comments links from admin bar

function ks_disable_comments_admin_redirect() {
	global $pagenow;
	if ($pagenow == 'edit-comments.php' || $pagenow == 'options-discussion.php') {
		wp_redirect(admin_url());
        exit;
	}
}
add_action('admin_init', 'ks_disable_comments_admin_redirect'); // redirect any user trying to access comments page

function ks_disable_comments_dashboard() {
	remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
}
add_action('admin_init', 'ks_disable_comments_dashboard'); // remove comments metabox from dashboard


/**
 * Disable search
 */
function ks_disable_search($query, $error = true) {
    if (is_search() && !is_admin()) {
        $query->is_search = false;
        $query->query_vars[s] = false;
        $query->query[s] = false;

        if ($error == true) {
            $query->is_404 = true;
        }
    }
}

add_action('parse_query', 'ks_disable_search');
add_filter('get_search_form', '__return_null');


/**
 * Customize order of admin menu items
 */
function ks_admin_menu_order($menu_order) {
    // list of items keyed by the item they should be located after
    $relocate_after = array(
        'separator1' => array('edit.php?post_type=page'),
        'separator2' => array('acf-options-header-footer', 'separator-last')
    );
    
    // create a list of all menu items that will be relocated
    $to_relocate = array();
    foreach ($relocate_after as $set) {
        $to_relocate = array_merge($to_relocate, $set);
    }
    
    // build new array and with items relocated
    $custom_order = array();
    foreach ($menu_order as $item) {
        // only process this item if it will not be relocated
        if (!in_array($item, $to_relocate)) {
            $custom_order[] = $item; // add this item to the array
            
            // if there are items to be located after this item, add them to the array also
            if (array_key_exists($item, $relocate_after)) {
                $custom_order = array_merge($custom_order, $relocate_after[$item]);
            }
        }
    }
    
    return $custom_order;
}
add_filter('custom_menu_order', '__return_true'); // enable menu_order filter
add_filter('menu_order', 'ks_admin_menu_order'); // filter menu order


/**
 * Disable archive pages for Posts
 */
function ks_disable_post_archives($query){
    if((!is_front_page() && is_home()) || is_category() || is_tag() || is_author() || is_date()) {
        global $wp_query;
        $wp_query->set_404();
        status_header(404);
        nocache_headers();
    }
}
add_action('parse_query', 'ks_disable_post_archives');


/**
 * Unregister default taxonomies for Posts
 */
function ks_unregister_default_taxonomies() {
    unregister_taxonomy_for_object_type('category', 'post'); // unregister categories for posts
    unregister_taxonomy_for_object_type('post_tag', 'post'); // unregister tags for posts
}
add_action('init', 'ks_unregister_default_taxonomies');


/**
 * Remove oEmbed discovery links and REST API endpoint
 */
remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('rest_api_init', 'wp_oembed_register_route');


/**
 * Remove unnecessary header code
 */
remove_action('wp_head', 'rsd_link'); // remove RSD link used by blog clients
remove_action('wp_head', 'wlwmanifest_link'); // remove Windows Live Writer client link
remove_action('wp_head', 'wp_shortlink_wp_head'); // remove shortlink
remove_action('wp_head', 'wp_generator'); // remove generator meta tag
remove_action('wp_head', 'rest_output_link_wp_head'); // remove REST links


/**
 * Dequeue WP Block Editor styles
 */
function ks_dequeue_block_styles(){
    wp_dequeue_style('wp-block-library');
}
add_action('wp_enqueue_scripts', 'ks_dequeue_block_styles', 100);


/**
 * Disable WordPress emojis
 */
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_styles', 'print_emoji_styles');
remove_filter('the_content_feed', 'wp_staticize_emoji');
remove_filter('comment_text_rss', 'wp_staticize_emoji');
remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

add_filter('emoji_svg_url', '__return_false', 10, 2);

function ks_tinymce_disable_emojis($plugins) {
    if (is_array($plugins)) {
        return array_diff($plugins, array('wpemoji'));
    } else {
        return array();
    }
}
add_filter('tiny_mce_plugins', 'ks_tinymce_disable_emojis'); // disable wpemoji TinyMCE plugin


/**
 * Add ACF WYSIWYG height setting
 */
function ks_acf_wysiwyg_field_height_setting($field) {
	acf_render_field_setting($field, array(
		'label'	=> 'Height',
		'name' => 'editor_height',
		'type' => 'number',
        'placeholder' => '300',
        'append' => 'px'
	));
}
add_action('acf/render_field_settings/type=wysiwyg', 'ks_acf_wysiwyg_field_height_setting'); // add setting to adjust field height

function ks_acf_wysiwyg_field_height_script($field) {
    if ($field['editor_height']) { ?>
        <style type="text/css">
            textarea[name="<?php echo $field['name']; ?>"] {
                height: <?php echo $field['editor_height']; ?>px !important;
            }
        </style>
        <script type="text/javascript">
            jQuery(function() {
                jQuery('textarea[name="<?php echo $field['name']; ?>"]').css('height', '<?php echo $field['editor_height']; ?>px');
                jQuery('iframe#' + jQuery('textarea[name="<?php echo $field['name']; ?>"]').attr('id') + '_ifr').css('height', '<?php echo $field['editor_height']; ?>px');
            });
        </script>
    <?php }

    return $field;
}
add_filter('acf/prepare_field/type=wysiwyg', 'ks_acf_wysiwyg_field_height_script'); // add js to adjust field height


/**
 * Custom WYSIWYG configurations
 */
function ks_wysiwyg_configs() {
    $configs = array();

    // add config: Full
    $configs['Full'] = array(
        'toolbars' => array(
            1 => array('formatselect', 'bold', 'italic', 'bullist', 'numlist', 'blockquote', 'alignleft', 'aligncenter', 'alignright', 'link', 'wp_more', 'spellchecker', 'fullscreen', 'wp_adv'),
			2 => array('strikethrough', 'hr', 'forecolor', 'pastetext', 'removeformat', 'charmap', 'outdent', 'indent', 'undo', 'redo', 'wp_help')
        ),
        'formats' => array(
            'p' => 'Text',
            'h2' => 'Heading 2',
            'h3' => 'Heading 3',
            'h4' => 'Heading 4',
            'h5' => 'Heading 5'
        )
    );

    // add config: Standard
    $configs['Standard'] = array(
        'toolbars' => array(
            1 => array('formatselect', 'bold', 'italic', 'underline', 'strikethrough', 'blockquote', 'bullist', 'numlist', 'link', 'hr', 'undo', 'redo', 'wp_adv'),
            2 => array('alignleft', 'aligncenter', 'alignright', 'removeformat', 'fullscreen')
        ),
        'formats' => array(
            'p' => 'Text',
            'h2' => 'Heading 2',
            'h3' => 'Heading 3',
            'h4' => 'Heading 4',
            'h5' => 'Heading 5'
        ),
        'elements' => array(
            'p' => array(),
            'h2' => array(),
            'h3' => array(),
            'h4' => array(),
            'h5' => array(),
            'strong' => array(
                'synonyms' => array('b')
            ),
            'em' => array(
                'synonyms' => array('i')
            ),
            'span' => array(
                'styles' => array('text-decoration')
            ),
            'del' => array(
                'synonyms' => array('s')
            ),
            'blockquote' => array(),
            'ul' => array(),
            'ol' => array(),
            'li' => array(),
            'a' => array(
                'attributes' => array('href', 'target', 'rel')
            ),
            'hr' => array(),
            'br' => array()
        ),
        'media_elements' => array(
            'img' => array(
                'attributes' => array('src', 'alt', 'width', 'height', 'class', 'title', 'data-mce-src')
            ),
            'div' => array(
                'attributes' => array('!class<mceTemp')
            ),
            'dl' => array(
                'attributes' => array('id', '!class', 'data-mce-style'),
                'styles' => array('width')
            ),
            'dt' => array(
                'attributes' => array('!class<wp-caption-dt')
            ),
            'dd' => array(
                'attributes' => array('!class<wp-caption-dd')
            )
        ),
        'global_styles' => array(
            'text-align'
        )
    );

    // add config: Standard (No Headings)
    $configs['Standard (No Headings)'] = array(
        'toolbars' => array(
            1 => array('bold', 'italic', 'underline', 'strikethrough', 'blockquote', 'bullist', 'numlist', 'link', 'hr', 'undo', 'redo', 'wp_adv'),
            2 => array('alignleft', 'aligncenter', 'alignright', 'removeformat', 'fullscreen')
        ),
        'elements' => array(
            'p' => array(),
            'strong' => array(
                'synonyms' => array('b')
            ),
            'em' => array(
                'synonyms' => array('i')
            ),
            'span' => array(
                'styles' => array('text-decoration')
            ),
            'del' => array(
                'synonyms' => array('s')
            ),
            'blockquote' => array(),
            'ul' => array(),
            'ol' => array(),
            'li' => array(),
            'a' => array(
                'attributes' => array('href', 'target', 'rel')
            ),
            'hr' => array(),
            'br' => array()
        ),
        'media_elements' => array(
            'img' => array(
                'attributes' => array('src', 'alt', 'width', 'height', 'class', 'title', 'data-mce-src')
            ),
            'div' => array(
                'attributes' => array('!class<mceTemp')
            ),
            'dl' => array(
                'attributes' => array('id', '!class', 'data-mce-style'),
                'styles' => array('width')
            ),
            'dt' => array(
                'attributes' => array('!class<wp-caption-dt')
            ),
            'dd' => array(
                'attributes' => array('!class<wp-caption-dd')
            )
        ),
        'global_styles' => array(
            'text-align'
        )
    );

    // add config: Minimal
    $configs['Minimal'] = array(
        'toolbars' => array(
            1 => array('bold' , 'italic', 'link')
        ),
        'elements' => array(
            'p' => array(),
            'strong' => array(
                'synonyms' => array('b')
            ),
            'em' => array(
                'synonyms' => array('i')
            ),
            'a' => array(
                'attributes' => array('href', 'target', 'rel')
            ),
            'br' => array()
        )
    );
    
    // add config: Minimal (No Links)
    $configs['Minimal (No Links)'] = array(
        'toolbars' => array(
            1 => array('bold' , 'italic')
        ),
        'elements' => array(
            'p' => array(),
            'strong' => array(
                'synonyms' => array('b')
            ),
            'em' => array(
                'synonyms' => array('i')
            ),
            'br' => array()
        )
    );


    // process configs
    $processed_configs = array();
    foreach ($configs as $config_name => $config_data) {
        $processed_data = array();

        // add (unsanitzed) name to data
        $processed_data['label'] = $config_name;

        // add toolbars to data
        $processed_data['toolbars'] = $config_data['toolbars'];

        // generate block_formats string
        if ($config_data['formats']) {
            $formats_list = array();
            foreach ($config_data['formats'] as $format_tag => $format_label) {
                $formats_list[] = $format_label . '=' . $format_tag;
            }

            $processed_data['formats'] = implode(';', $formats_list);
        }

        // generate valid_styles array
        if ($config_data['elements'] || $config_data['global_styles']) {
            $styles_list = array();
            
            if ($config_data['global_styles']) {
                $styles_list['*'] = implode(',', $config_data['global_styles']);
            }
            if ($config_data['elements']) {
                foreach ($config_data['elements'] as $element_tag => $element_options) {
                    if ($element_options['styles']) {
                        $styles_list[$element_tag] = implode(',', $element_options['styles']);
                    }
                }
            }

            if ($styles_list) {
                $processed_data['styles'] = $styles_list;
            }
        }

        // generate valid_styles array for when media is allowed
        if ($config_data['media_elements']) {
            $styles_list = array();

            if ($config_data['elements']) {
                $config_data['media_elements'] = array_merge($config_data['elements'], $config_data['media_elements']);
            }

            if ($config_data['global_styles']) {
                $styles_list['*'] = implode(',', $config_data['global_styles']);
            }
            foreach ($config_data['media_elements'] as $element_tag => $element_options) {
                if ($element_options['styles']) {
                    $styles_list[$element_tag] = implode(',', $element_options['styles']);
                }
            }

            if ($styles_list) {
                $processed_data['styles_with_media'] = $styles_list;
            }
        }

        // generate valid_elements string
        if ($config_data['elements']) {
            $elements_list = array();

            if ($processed_data['styles']) {
                $elements_list[] = '@[style]'; // ensure that the style attribute is allowed if there are valid styles specified (this has to be first in the list)
            }
            foreach ($config_data['elements'] as $element_tag => $element_options) {
                $element_attribute_string = '';
                if ($element_options['attributes']) {
                    $element_attribute_string = '[' . implode('|', $element_options['attributes']) . ']';
                }

                if ($element_options['synonyms']) {
                    foreach ($element_options['synonyms'] as $synonym) {
                        $elements_list[] = $element_tag . '/' . $synonym . $element_attribute_string;
                    }
                } else {
                    $elements_list[] = $element_tag . $element_attribute_string;
                }
            }

            $processed_data['elements'] = implode(',', $elements_list);
        }

        // generate valid_elements string for when media is allowed
        if ($config_data['media_elements']) {
            $elements_list = array();

            if ($config_data['elements']) {
                $config_data['media_elements'] = array_merge($config_data['elements'], $config_data['media_elements']);
            }

            if ($processed_data['styles_with_media'] || $processed_data['styles']) {
                $elements_list[] = '@[style]'; // ensure that the style attribute is allowed if there are valid styles specified (this has to be first in the list)
            }
            foreach ($config_data['media_elements'] as $element_tag => $element_options) {
                $element_attribute_string = '';
                if ($element_options['attributes']) {
                    $element_attribute_string = '[' . implode('|', $element_options['attributes']) . ']';
                }

                if ($element_options['synonyms']) {
                    foreach ($element_options['synonyms'] as $synonym) {
                        $elements_list[] = $element_tag . '/' . $synonym . $element_attribute_string;
                    }
                } else {
                    $elements_list[] = $element_tag . $element_attribute_string;
                }
            }

            $processed_data['elements_with_media'] = implode(',', $elements_list);
        }

        // add processed data to array
        $processed_configs[str_replace( '-', '_', sanitize_title($config_name))] = $processed_data;
    }

    return $processed_configs;
}

function ks_configure_tinymce($mce_init, $config_name, $media_allowed) {
    $wysiwyg_configs = ks_wysiwyg_configs();

    $config_name = str_replace( '-', '_', sanitize_title($config_name));

    if ($wysiwyg_configs[$config_name]) {
        $config = $wysiwyg_configs[$config_name];

        // update toolbars
        $mce_init['toolbar1'] = $config['toolbars'][1] ? implode(',', $config['toolbars'][1]) : '';
        $mce_init['toolbar2'] = $config['toolbars'][2] ? implode(',', $config['toolbars'][2]) : '';
        $mce_init['toolbar3'] = $config['toolbars'][3] ? implode(',', $config['toolbars'][3]) : '';
        $mce_init['toolbar4'] = $config['toolbars'][4] ? implode(',', $config['toolbars'][4]) : '';

        // update block_formats setting
        if ($config['formats']) {
            $mce_init['block_formats'] = $config['formats'];
        }

        // update valid_elements setting
        if ($media_allowed && $config['elements_with_media']) {
            $mce_init['valid_elements'] = $config['elements_with_media'];
        } elseif ($config['elements']) {
            $mce_init['valid_elements'] = $config['elements'];
        }

        // update valid_styles setting
        if ($media_allowed && $config['styles_with_media']) {
            $mce_init['valid_styles'] = $config['styles_with_media'];
        } elseif ($config['styles']) {
            $mce_init['valid_styles'] = $config['styles'];
        }
    }

    return $mce_init;
}


/**
 * Add custom WYSIWYG toolbars to ACF
 */
function ks_acf_toolbars($toolbars) {
    $wysiwyg_configs = ks_wysiwyg_configs();

    if ($wysiwyg_configs) {
        $toolbars = array();
        foreach ($wysiwyg_configs as $config) {
            $toolbars[$config['label']] = $config['toolbars'];
        }
    }
    
	return $toolbars;
}
add_filter('acf/fields/wysiwyg/toolbars' , 'ks_acf_toolbars'); // add toolbars


/**
 * Disable autoembed for ACF WYSIWYG fields (and add option to re-enable)
 */
function ks_acf_wysiwyg_disable_auto_embed($value, $post_id, $field) {
    if(!empty($GLOBALS['wp_embed']) && !$field['enable_autoembed']) {
	   remove_filter('acf_the_content', array( $GLOBALS['wp_embed'], 'autoembed' ), 8);
    }
	
	return $value;
}
add_filter('acf/format_value/type=wysiwyg', 'ks_acf_wysiwyg_disable_auto_embed', 10, 3); // disable autoembed

function ks_acf_wysiwyg_disable_auto_embed_after($value, $post_id, $field) {
    if(!empty($GLOBALS['wp_embed']) && !$field['enable_autoembed']) {
	   add_filter('acf_the_content', array( $GLOBALS['wp_embed'], 'autoembed' ), 8);
    }
	
	return $value;
}
add_filter('acf/format_value/type=wysiwyg', 'ks_acf_wysiwyg_disable_auto_embed_after', 20, 3); // re-enable autoembed after value is formatted

function ks_acf_wysiwyg_disable_auto_embed_setting($field) {
	acf_render_field_setting($field, array(
		'label'	=> 'Enable Autoembed',
		'name' => 'enable_autoembed',
		'type' => 'true_false',
        'ui' => 1
	));
}
add_action('acf/render_field_settings/type=wysiwyg', 'ks_acf_wysiwyg_disable_auto_embed_setting'); // add setting to enable/disable

function ks_acf_wysiwyg_disable_auto_embed_class($field) {
    if (!$field['enable_autoembed']) {
        $field['wrapper']['class'] = explode(' ', $field['wrapper']['class']);
        $field['wrapper']['class'][] = 'ks-disable-autoembed';
        $field['wrapper']['class'] = implode(' ', $field['wrapper']['class']);
    }

    return $field;
}
add_filter('acf/prepare_field/type=wysiwyg', 'ks_acf_wysiwyg_disable_auto_embed_class'); // add class to wrapper (so JS knows to disable the wpview TinyMCE plugin)


/**
 * Add option to post object, page link, and relationship fields to allow filtering by page template
 */
function ks_acf_template_filter_setting($field) {
    acf_render_field_setting($field, array(
        'label'	=> 'Filter by Page Template',
        'name' => 'filter_template',
        'type' => 'select',
        'choices' => array_flip(get_page_templates()),
        'multiple' => 1,
        'ui' => 1,
        'allow_null' => 1,
        'placeholder' => 'All page templates'
    ));
}
add_action('acf/render_field_settings/type=post_object', 'ks_acf_template_filter_setting'); // add setting to post object fields
add_action('acf/render_field_settings/type=page_link', 'ks_acf_template_filter_setting'); // add setting to page_link fields
add_action('acf/render_field_settings/type=relationship', 'ks_acf_template_filter_setting'); // add setting to relationship fields

function ks_acf_template_filter_query($args, $field, $post_id) {
    if ($field['filter_template']) {
        $args['meta_query'] = array(
            array(
                'key' => '_wp_page_template',
                'value' => $field['filter_template'],
                'compare' => 'IN'
            )
        );
    }
	
    return $args;
}
add_filter('acf/fields/post_object/query', 'ks_acf_template_filter_query', 10, 3); // update query for post object fields to include template filter
add_filter('acf/fields/page_link/query', 'ks_acf_template_filter_query', 10, 3); // update query for page link fields to include template filter
add_filter('acf/fields/relationship/query', 'ks_acf_template_filter_query', 10, 3); // update query for relationship fields to include template filter


/**
 * Add maximum/minimum selection options to field types with multi-select functionality
 */
function ks_acf_multi_min_max_settings($field) {
    if ($field['type'] == 'checkbox') {
        // render settings for checkbox fields (always show settings)
        acf_render_field_setting($field, array(
            'label'	=> 'Minimum Selection',
            'name' => 'multi_min',
            'type' => 'number'
        ));
        acf_render_field_setting($field, array(
            'label'	=> 'Maximum Selection',
            'name' => 'multi_max',
            'type' => 'number'
        ));
    } elseif ($field['type'] == 'taxonomy') {
        // render settings for taxonomy fields (hide/show settings based on whether selected appearance allows multiple values)
        acf_render_field_setting($field, array(
            'label'	=> 'Minimum Selection',
            'name' => 'multi_min',
            'type' => 'number',
            'conditions' => array(
                array(
                    array(
                        'field' => 'field_type',
                        'operator' => '==',
                        'value' => 'checkbox'
                    )
                ),
                array(
                    array(
                        'field' => 'field_type',
                        'operator' => '==',
                        'value' => 'multi_select'
                    )
                ),
            )
        ));
        acf_render_field_setting($field, array(
            'label'	=> 'Maximum Selection',
            'name' => 'multi_max',
            'type' => 'number',
            'conditions' => array(
                array(
                    array(
                        'field' => 'field_type',
                        'operator' => '==',
                        'value' => 'checkbox'
                    )
                ),
                array(
                    array(
                        'field' => 'field_type',
                        'operator' => '==',
                        'value' => 'multi_select'
                    )
                ),
            )
        ));
    } else {
        // render settings for other field types (hide/show settings based on whether multi-select is enabled)
        acf_render_field_setting($field, array(
            'label'	=> 'Minimum Selection',
            'name' => 'multi_min',
            'type' => 'number',
            'conditions' => array(
                'field' => 'multiple',
                'operator' => '==',
                'value' => 1
            )
        ));
        acf_render_field_setting($field, array(
            'label'	=> 'Maximum Selection',
            'name' => 'multi_max',
            'type' => 'number',
            'conditions' => array(
                'field' => 'multiple',
                'operator' => '==',
                'value' => 1
            )
        ));
    }
}
add_action('acf/render_field_settings/type=checkbox', 'ks_acf_multi_min_max_settings'); // add min/max settings to checkbox fields
add_action('acf/render_field_settings/type=select', 'ks_acf_multi_min_max_settings'); // add min/max settings to select fields
add_action('acf/render_field_settings/type=post_object', 'ks_acf_multi_min_max_settings'); // add min/max settings to post object fields
add_action('acf/render_field_settings/type=page_link', 'ks_acf_multi_min_max_settings'); // add min/max settings to page link fields
add_action('acf/render_field_settings/type=taxonomy', 'ks_acf_multi_min_max_settings'); // add min/max settings to taxonomy fields
add_action('acf/render_field_settings/type=user', 'ks_acf_multi_min_max_settings'); // add min/max settings to user fields
add_action('acf/render_field_settings/type=gf_select', 'ks_acf_multi_min_max_settings'); // add min/max settings to Gravity Form fields

function ks_acf_multi_min_max_validation($valid, $value, $field, $input) {
    if ($valid) {
        if ($field['multi_min']) {
            if (!$value) $value = array(); // if value is empty, set it to an empty array so count() returns 0
            
            // if value doesn't meet minimum, return validation error message
            if (count($value) < $field['multi_min']) {
                $valid = 'Please select a minimum of ' . $field['multi_min'];
                if ($field['multi_min'] == 1) {
                    $valid .= ' value.';
                } else {
                    $valid .= ' values.';
                }
            }
        }
        if ($field['multi_max']) {
            if (!$value) $value = array(); // if value is empty, set it to an empty array so count() returns 0
            
            // if value exceeds maximum, return validation error message
            if (count($value) > $field['multi_max']) {
                $valid = 'Please select a maximum of ' . $field['multi_max'];
                if ($field['multi_max'] == 1) {
                    $valid .= ' value.';
                } else {
                    $valid .= ' values.';
                }
            }
        }
    }
    
    return $valid;
}
add_action('acf/validate_value/type=checkbox', 'ks_acf_multi_min_max_validation', 10, 4); // validate min/max settings for checkbox fields
add_action('acf/validate_value/type=select', 'ks_acf_multi_min_max_validation', 10, 4); // validate min/max settings for select fields
add_action('acf/validate_value/type=post_object', 'ks_acf_multi_min_max_validation', 10, 4); // validate min/max settings for post object fields
add_action('acf/validate_value/type=page_link', 'ks_acf_multi_min_max_validation', 10, 4); // validate min/max settings for page link fields
add_action('acf/validate_value/type=taxonomy', 'ks_acf_multi_min_max_validation', 10, 4); // validate min/max settings for taxonomy fields
add_action('acf/validate_value/type=user', 'ks_acf_multi_min_max_validation', 10, 4); // validate min/max settings for user fields
add_action('acf/validate_value/type=gf_select', 'ks_acf_multi_min_max_validation', 10, 4); // validate min/max settings for Gravity Form fields


/**
 * Add character counter for ACF text and textarea field types
 */
function ks_acf_character_limit_markup($field) {
    $class_list = explode(' ', $field['wrapper']['class']);
    
    if (!in_array('no-char-count', $class_list) && $field['maxlength']) { ?>
        <p class="ks-char-count"><span class="ks-char-count__current"><?php echo strlen(iconv('utf-8', 'utf-16le', str_replace(PHP_EOL, ' ', $field['value']))) / 2; ?></span>/<?php echo $field['maxlength']; ?> characters</p>
    <?php }
}
add_action('acf/render_field/type=text', 'ks_acf_character_limit_markup'); // add counter to text fields
add_action('acf/render_field/type=textarea', 'ks_acf_character_limit_markup'); // add counter to textarea fields


/**
 * Add custom ACF field types
 */
function ks_include_custom_acf_field_types() {
    include_once(get_template_directory() . '/includes/acf-custom/fields/acf-gf-select.php'); // add Gravity Form field type
}
add_action('acf/include_field_types', 'ks_include_custom_acf_field_types');


/**
 * Set Google Maps API key for ACF
 */
function kf_acf_google_map_api($api){
    $f_google_api_key = get_field('config_apis_google', 'option');

    $api['key'] = $f_google_api_key;
    
    return $api;
}
add_filter('acf/fields/google_map/api', 'kf_acf_google_map_api');
