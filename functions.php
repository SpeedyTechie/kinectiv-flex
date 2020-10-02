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
	wp_enqueue_style('kinectiv-flex-style', get_stylesheet_directory_uri() . '/style.min.css', array(), '0.1.0');
	wp_enqueue_style('kinectiv-flex-vendor-style', get_stylesheet_directory_uri() . '/css/vendor.min.css', array(), '1.0.0');
	wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,400;0,700;1,400;1,700&family=Roboto+Slab:wght@700&display=swap', array(), null);
    
    wp_deregister_script('wp-embed');
    wp_deregister_script('jquery');
    wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-1.12.4.min.js', array(), null, false);
	wp_enqueue_script('kinectiv-flex-script', get_template_directory_uri() . '/js/script.min.js', array('jquery'), '0.1.0', true);
    
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
        'colorList' => kf_color_id_list(),
        'themeMaps' => kf_color_theme_maps(),
        'auxThemeMaps' => kf_aux_color_theme_maps()
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
    $year = '2020'; // year site was first published
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
 * Get name of inverse theme
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
add_filter('acf/format_value/type=wysiwyg', 'kf_acf_format_wysiwyg_value', 10, 3); // add classes to ACF WYSIWYG fields

function kf_wysiwyg_text_classes($html) {
    // list of classes to add to various elements
    $class_list = array(
        'h1' => 'title title_lg',
        'h2' => 'title',
        'h3' => 'text text_xl text_normal text_line_1-4',
        'h4' => 'text text_md text_bold',
        'h5' => 'text text_md text_bold',
        'h6' => 'text text_sm text_bold'
    );
    
    return kf_add_wysiwyg_classes($html, $class_list);
}

function kf_wysiwyg_color_classes($html, $theme) {
    // list of classes to add to various elements
    $class_list = array(
        'h3' => 'c_color_' . color_id($theme, 3, true),
        'h5' => 'c_color_' . color_id($theme, 3, true),
        'blockquote' => 'c_color_' . color_id($theme, 3, true),
        'hr' => 'c_bg_' . color_id($theme, 2, true)
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
    $front_id = intval(get_option('page_on_front')); // get front page ID
    $ancestors = array(); // create an array to store ancestors (format URL => Page Title)
    
    // if there's a static front page (and it isn't the current page), add it as the first ancestor
    if ($front_id > 0 && $post_id != $front_id) {
        $ancestors[get_permalink($front_id)] = get_the_title($front_id);
    }
    
    // build breadcrumb array based on the post type
    if (is_singular('post')) {
        
    } elseif (is_singular('event')) {
        
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
            
            if ($passthrough_data) acf_register_store('passthrough_preview-' . $type, $passthrough_data);
            ?>
            <div class="tile-grid__item tile-grid__item_3">
                <?php get_template_part('template-parts/preview', $type); ?>
            </div>
            <?php
            if ($passthrough_data) acf_register_store('passthrough_preview-' . $type, array());
            
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
                $valid = 'The end time must be after the start time';
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
 * Add ACF options page
 */
if (function_exists('acf_add_options_page')) {
    $options_page = acf_add_options_page(array(
		'page_title' 	=> 'Site Options',
		'capability'	=> 'edit_theme_options'
	));
    
    acf_add_options_sub_page(array(
        'page_title' => 'General Info',
        'parent_slug' 	=> $options_page['menu_slug']
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
}


/**
 * Remove Site Icon control from Theme Customization
 */
function ks_customize_register($wp_customize) {
    $wp_customize->remove_control('site_icon');
}
add_action('customize_register', 'ks_customize_register', 20);  


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
function kf_show_gform($form_id, $theme) {
    // temporarily store the theme as a field value in order to pass it to the form
    $field_values = array(
        '_kf_temp_color_theme' => $theme
    );
    
    gravity_form($form_id, false, false, false, $field_values, true, 0);
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
 * Gravity Forms - remove unwanted form settings
 */
function kf_gform_form_settings($settings, $form) {
    unset($settings[__('Form Layout', 'gravityforms')]['form_label_placement']); // remove label placement setting
    unset($settings[__('Form Button', 'gravityforms')]['form_button_type']); // remove button type setting
    unset($settings[__('Form Options', 'gravityforms')]['enable_animation']); // remove animated transitions setting
    
    return $settings;
}
add_filter('gform_form_settings', 'kf_gform_form_settings', 10, 2);


/**
 * Gravity Forms - store theme in ACF store for access while modifying form markup
 */
function kf_get_theme_gform_form_args($form_args) {
    if (isset($form_args['field_values']['_kf_temp_color_theme'])) {
        acf_register_store('passthrough_form', array(
            'theme' => $form_args['field_values']['_kf_temp_color_theme']
        ));
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
        if ($passthrough) {
            $pt_theme = $passthrough->get('theme');
        }
        
        $theme = isset($pt_theme) ? $pt_theme : 'main';
        
        // enable user error handling (to prevent warnings caused by HTML5 and SVG tags)
        libxml_clear_errors();
        $libxml_err_prev = libxml_use_internal_errors(true);
        
        $dom = new DOMDocument();
        $dom->loadHTML('<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>' . $field_content); // load HTML with proper encoding
        
        
        // modifications for specific field types
        if ($field->type == 'section') {
            // change section header element from h2 to h3 and add classes
            foreach ($dom->getElementsByTagName('h2') as $h2_element) {
                $classes = explode(' ', $h2_element->getAttribute('class'));
                
                if (in_array('gsection_title', $classes)) {
                    if ($field->label) {
                        // replace h2 with h3
                        $h3_element = $dom->createElement('h3');
                        foreach ($h2_element->childNodes as $child) {
                            $h3_element->appendChild($child);
                        }
                        foreach ($h2_element->attributes as $attribute) {
                            $h3_element->setAttribute($attribute->name, $attribute->value);
                        }
                        $h2_element->parentNode->replaceChild($h3_element, $h2_element);

                        // add classes
                        $classes[] = 'text text_xl text_normal text_line_1-4 c_color_' . color_id($theme, 3, true);
                        $h3_element->setAttribute('class', implode(' ', $classes));
                    } else {
                        $h2_element->parentNode->removeChild($h2_element); // if there's no title text, just remove the element
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
            // add color classes (for ::before and ::after) to copy values checkbox label
            foreach ($dom->getElementsByTagName('label') as $label_element) {
                $classes = explode(' ', $label_element->getAttribute('class'));
                
                if (in_array('copy_values_option_label', $classes)) {
                    $classes[] = 'c_bfr_color_' . color_id($theme, 3, true) . ' c_aft_color_' . color_id($theme, 5, true);
                    $label_element->setAttribute('class', implode(' ', $classes));
                }
            }
        } elseif ($field->type == 'fileupload' || ($field->type == 'post_custom_field' && $field->inputType == 'fileupload')) {
            $drop_area = null;
            
            // add replace button input with button element and add color classes
            foreach ($dom->getElementsByTagName('input') as $input_element) {
                $type = $input_element->getAttribute('type');
                
                if ($type == 'button') {
                    // create new button and duplicate all attributes from existing button
                    $button_element = $dom->createElement('button');
                    $button_element->appendChild($dom->createTextNode('Select Files'));
                    $input_element->removeAttribute('value');
                    foreach($input_element->attributes as $attribute) {
                        $button_element->setAttribute($attribute->name, $attribute->value);
                    }
                    
                    // add classes to new button
                    $classes = 'c_bg_' . color_id($theme, 3, true) . ' c_h_bg_' . color_id($theme, 4, true) . ' c_color_' . color_id($theme, 0, true);
                    $button_element->setAttribute('class', trim($button_element->getAttribute('class') . ' ' . $classes));
                    
                    $input_element->parentNode->replaceChild($button_element, $input_element); // replace existing button with new button
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
                            
                            if (in_array('screen-reader-text', $div_child_classes)) {
                                $remove_key = array_search('screen-reader-text', $div_child_classes);
                                if ($remove_key !== false) {
                                    unset($div_child_classes[$remove_key]);
                                }
                                $div_child_classes[] = 'gform_custom_extension_list text text_xs text_italic text_line_1-4 c_color_' . color_id($theme, 3, true); 
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
            foreach ($dom->getElementsByTagName('a') as $a_element) {
                $classes = explode(' ', $a_element->getAttribute('class'));
                
                if (in_array('add_list_item', $classes) || in_array('delete_list_item', $classes)) {
                    $classes[] = 'c_bg_' . color_id($theme, 3, true) . ' c_h_bg_' . color_id($theme, 5, true) . ' c_color_' . color_id($theme, 1, true) . ' c_h_color_' . color_id($theme, 1, true);
                    $a_element->setAttribute('class', implode(' ', $classes));
                    
                    // remove img tags
                    foreach ($a_element->getElementsByTagName('img') as $img_element) {
                        $a_element->removeChild($img_element);
                    }
                }
            }
        } elseif ($field->type == 'consent') {
            // add color classes to checkbox label (for ::before and ::after)
            foreach ($dom->getElementsByTagName('label') as $label_element) {
                $classes = explode(' ', $label_element->getAttribute('class'));
                
                if (in_array('gfield_consent_label', $classes)) {
                    $classes[] = 'c_bfr_color_' . color_id($theme, 3, true) . ' c_aft_color_' . color_id($theme, 5, true);
                    $label_element->setAttribute('class', implode(' ', $classes));
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
        
        // add color classes to checkbox labels (for ::before and ::after)
        foreach ($dom->getElementsByTagName('ul') as $ul_element) {
            $ul_classes = explode(' ', $ul_element->getAttribute('class'));

            if (in_array('gfield_checkbox', $ul_classes)) {
                foreach ($ul_element->getElementsByTagName('label') as $label_element) {
                    $label_classes = 'c_bfr_color_' . color_id($theme, 3, true) . ' c_aft_color_' . color_id($theme, 5, true);
                    $label_element->setAttribute('class', trim($label_element->getAttribute('class') . ' ' . $label_classes));
                }
            }
        }
        
        // add color classes to radio button labels (for ::before and ::after)
        foreach ($dom->getElementsByTagName('ul') as $ul_element) {
            $ul_classes = explode(' ', $ul_element->getAttribute('class'));

            if (in_array('gfield_radio', $ul_classes)) {
                foreach ($ul_element->getElementsByTagName('label') as $label_element) {
                    $label_classes = 'c_bfr_color_' . color_id($theme, 3, true) . ' c_aft_color_' . color_id($theme, 5, true);
                    $label_element->setAttribute('class', trim($label_element->getAttribute('class') . ' ' . $label_classes));
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
    foreach ($dom->getElementsByTagName('div') as $div_element) {
        $classes = explode(' ', $div_element->getAttribute('class'));
        
        if (in_array('validation_error', $classes)) {
            $classes[] = 'text text_italic text_line_1-4 c_bg_' . aux_color_id($theme, 1, true) . ' c_color_' . color_id($theme, 0, true);
            $div_element->setAttribute('class', implode(' ', $classes));
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
    if ($passthrough) {
        $pt_theme = $passthrough->get('theme');
    }

    $theme = isset($pt_theme) ? $pt_theme : 'main';
    
    
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
    $classes = 'c_bg_' . color_id($theme, 3, true) . ' c_h_bg_' . color_id($theme, 4, true) . ' c_color_' . color_id($theme, 0, true);
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
    if ($passthrough) {
        $pt_theme = $passthrough->get('theme');
    }

    $theme = isset($pt_theme) ? $pt_theme : 'main';
    
    
    // enable user error handling (to prevent warnings caused by HTML5 and SVG tags)
    libxml_clear_errors();
    $libxml_err_prev = libxml_use_internal_errors(true);
    
    $dom = new DOMDocument();
    $dom->loadHTML('<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>' . $link); // load HTML with proper encoding
    
    $a_element = $dom->getElementsByTagName('a')->item(0); // get default button
    
    // add classes to link
    if ($theme == 'main-dark' || $theme == 'alt-dark') {
        $classes = 'light';
        $a_element->setAttribute('class', trim($a_element->getAttribute('class') . ' ' . $classes));
    }
    
    
    $link = $dom->saveHtml($a_element);
    
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
function kf_gform_confirmation_wp_editor_settings($settings, $editor_id) {
    if ($editor_id == 'form_confirmation_message') {
        $settings['quicktags'] = false;
    }
    
    return $settings;
}
add_filter('wp_editor_settings', 'kf_gform_confirmation_wp_editor_settings', 10, 2); // customize wp_editor settings

function kf_gform_confirmation_acf_toolbar_name() {
    return 'Standard'; // set which ACF toolbar to use for the confirmation WYSIWYG editor
}

function kf_gform_confirmation_mce_buttons($mce_buttons, $editor_id) {
    if ($editor_id == 'form_confirmation_message') {
        $mce_buttons = kf_tinymce_toolbar_row_from_acf(kf_gform_confirmation_acf_toolbar_name(), 1);
    }
    
    return $mce_buttons;
}
add_filter('mce_buttons', 'kf_gform_confirmation_mce_buttons', 10, 2); // customize tinyMCE buttons (row 1)

function kf_gform_confirmation_mce_buttons_2($mce_buttons, $editor_id) {
    if ($editor_id == 'form_confirmation_message') {
        $mce_buttons = kf_tinymce_toolbar_row_from_acf(kf_gform_confirmation_acf_toolbar_name(), 2);
    }
    
    return $mce_buttons;
}
add_filter('mce_buttons_2', 'kf_gform_confirmation_mce_buttons_2', 10, 2); // customize tinyMCE buttons (row 2)

function kf_gform_confirmation_mce_buttons_3($mce_buttons, $editor_id) {
    if ($editor_id == 'form_confirmation_message') {
        $mce_buttons = kf_tinymce_toolbar_row_from_acf(kf_gform_confirmation_acf_toolbar_name(), 3);
    }
    
    return $mce_buttons;
}
add_filter('mce_buttons_3', 'kf_gform_confirmation_mce_buttons_3', 10, 2); // customize tinyMCE buttons (row 3)

function kf_gform_confirmation_mce_buttons_4($mce_buttons, $editor_id) {
    if ($editor_id == 'form_confirmation_message') {
        $mce_buttons = kf_tinymce_toolbar_row_from_acf(kf_gform_confirmation_acf_toolbar_name(), 4);
    }
    
    return $mce_buttons;
}
add_filter('mce_buttons_4', 'kf_gform_confirmation_mce_buttons_4', 10, 2); // customize tinyMCE buttons (row 4)


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
                <input type="hidden" name="gform_field_values" value="<?php echo esc_attr('_kf_temp_color_theme=' . $theme); ?>" />
                <input type="hidden" class="gform_hidden" name="is_submit_<?php echo $form_id; ?>" value="1" />
                <input type="hidden" class="gform_hidden" name="gform_submit" value="<?php echo $form_id; ?>" />
                <?php endif; ?>
                <label class="gfield_label text text_bold text_line_1-4<?php if (!is_null($resume_email) && !GFCommon::is_valid_email($resume_email)) { echo ' c_color_' . aux_color_id($theme, 1, true); } ?>">Email Address</label>
                <input type="<?php echo RGFormsModel::is_html5_enabled() ? 'email' : 'text'; ?>" name="gform_resume_email" value="<?php echo esc_attr($resume_email); ?>" class="c_bg_<?php color_id($theme, 1); ?> c_color_<?php color_id($theme, 5); ?> c_placeholder_<?php color_id($theme, 2); ?>" />
                <?php if (!is_null($resume_email) && !GFCommon::is_valid_email($resume_email)) { ?><div class="validation_message text text_line_1-4 text_xs text_italic c_color_<?php aux_color_id($theme, 1); ?>">Please enter a valid email address.</div><?php } ?>
                <input type="hidden" name="gform_resume_token" value="{save_token}" />
                <input type="hidden" name="gform_send_resume_link" value="<?php echo $form_id; ?>" />
                <button type="submit" name="gform_send_resume_link_button" id="gform_send_resume_link_button_<?php echo $form_id; ?>" class="button c_bg_<?php color_id($theme, 3); ?> c_h_bg_<?php color_id($theme, 4); ?> c_color_<?php color_id($theme, 0); ?>"<?php echo $ajax ? ' onclick="jQuery(\'#gform_' . $form_id . '\').trigger(\'submit\',[true]);"' : '' ?>>Send Link</button>
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

function kf_gform_field_appearance_settings_custom_settings($index, $form_id) {
    // field width option
    if ($index == 500) {
        ?>
        <li class="kfgf_width_setting field_setting">
            <label for="field_kfgf_width" class="section_label">Field Width</label>
            <select id="field_kfgf_width" onchange="SetFieldProperty('kfgfWidth', jQuery(this).val());">
                <option value="full">Full</option>
                <option value="two-thirds">Two Thirds</option>
                <option value="half">Half</option>
                <option value="one-third">One Third</option>
            </select>
        </li>
        <?php
    }
}
add_action('gform_field_appearance_settings', 'kf_gform_field_appearance_settings_custom_settings', 10, 2); // add markup for appearance settings

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
    // field width option
    ?>
    <script type='text/javascript'>
        // add setting to (almost) all field types
        jQuery.each(fieldSettings, function(k, v) {
            if (k != 'section' && k != 'html' && k != 'hidden' && k != 'captcha') {
                fieldSettings[k] += ', .kfgf_width_setting'; 
            }
        });
        
        // initialize value on field load
        jQuery(document).on('gform_load_field_settings', function(event, field, form) {
            jQuery('#field_kfgf_width').val(!field.kfgfWidth ? 'full' : field.kfgfWidth);
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
    
    // field width option
    $field_width = $field->kfgfWidth ? $field->kfgfWidth : 'full';
    $classes .= ' field_kfgf_width_' . $field_width;
    
    return $classes;
}
add_filter('gform_field_css_class', 'kf_gform_field_css_class_custom_settings', 10, 3); // add classes to field markup based on settings


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
        'separator2' => array('acf-options-general-info', 'separator-last')
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
 * Enable TinyMCE paste as text by default
 */
function ks_tinymce_paste_as_text($init) {
    $init['paste_as_text'] = true;
    
    return $init;
}
add_filter('tiny_mce_before_init', 'ks_tinymce_paste_as_text');


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
            });
        </script>
    <?php }

    return $field;
}
add_filter('acf/prepare_field/type=wysiwyg', 'ks_acf_wysiwyg_field_height_script'); // add js to adjust field height


/**
 * Customize ACF WYSIWYG toolbars
 */
function ks_acf_toolbars($toolbars) {
    // Add Standard toolbar
    $toolbars['Standard'] = array();
    $toolbars['Standard'][1] = array('formatselect', 'bold', 'italic', 'underline', 'strikethrough', 'blockquote', 'bullist', 'numlist', 'link', 'hr', 'undo', 'redo', 'wp_adv');
    $toolbars['Standard'][2] = array('alignleft', 'aligncenter', 'alignright', 'removeformat', 'fullscreen');
    
    // Add Standard (No Headings) toolbar
    $toolbars['Standard (No Headings)'] = array();
    $toolbars['Standard (No Headings)'][1] = array('bold', 'italic', 'underline', 'strikethrough', 'blockquote', 'bullist', 'numlist', 'link', 'hr', 'undo', 'redo', 'wp_adv');
    $toolbars['Standard (No Headings)'][2] = array('alignleft', 'aligncenter', 'alignright', 'removeformat', 'fullscreen');
    
    // Add Minimal toolbar
	$toolbars['Minimal'] = array();
	$toolbars['Minimal'][1] = array('bold' , 'italic', 'link');
    
    // Add Minimal (No Links) toolbar
	$toolbars['Minimal (No Links)'] = array();
	$toolbars['Minimal (No Links)'][1] = array('bold' , 'italic');
    
	return $toolbars;
}
add_filter('acf/fields/wysiwyg/toolbars' , 'ks_acf_toolbars'); // add toolbars

function ks_acf_wysiwyg_strip_tags($value, $post_id, $field) {
    if ($field['enable_strip_tags']) {
        if ($field['toolbar'] == 'basic') {
            $value = strip_tags($value, '<p><strong><em><span><a><br><blockquote><del><ul><ol><li>');
        } elseif ($field['toolbar'] == 'minimal') {
            $value = strip_tags($value, '<p><strong><em><a><br>');
        } elseif ($field['toolbar'] == 'minimal_no_links') {
            $value = strip_tags($value, '<p><strong><em><br>');
        } elseif ($field['toolbar'] == 'standard') {
            $value = strip_tags($value, '<p><h2><h3><h4><h5><strong><em><span><del><blockquote><ul><ol><li><a><hr><br>');
        } elseif ($field['toolbar'] == 'standard_no_headings') {
            $value = strip_tags($value, '<p><strong><em><span><del><blockquote><ul><ol><li><a><hr><br>');
        }
    }
    
    return $value;
}
add_filter('acf/format_value/type=wysiwyg', 'ks_acf_wysiwyg_strip_tags', 10, 3); // strip tags from WYSIWYG content based on toolbar

function ks_acf_wysiwyg_strip_tags_setting($field) {
	acf_render_field_setting($field, array(
		'label'	=> 'Strip Tags Based on Toolbar',
        'instructions' => 'HTML tags not supported by the selected toolbar will be stripped',
		'name' => 'enable_strip_tags',
		'type' => 'true_false',
        'ui' => 1
	));
}
add_action('acf/render_field_settings/type=wysiwyg', 'ks_acf_wysiwyg_strip_tags_setting'); // add setting to enable/disable

function kf_tinymce_toolbar_row_from_acf($acf_name, $row) {
    // allow pulling the ACF toolbars elsewhere
    if (class_exists('acf_field_wysiwyg')) {
        $acf_toolbars = (new acf_field_wysiwyg)->get_toolbars();
        $toolbar_row = $acf_toolbars[$acf_name][$row];
        
        if (isset($toolbar_row)) {
            return $toolbar_row;
        }
    }
    
    return array();
}


/**
 * Limit WYSIWYG format optons to H2, H3, H4, H5, and Text
 */
function kf_wysiwyg_block_formats($args) {
    $args['block_formats'] = 'Text=p;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5';
    
    return $args;
}
add_filter('tiny_mce_before_init', 'kf_wysiwyg_block_formats');


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
 * Add custom ACF field types
 */
function ks_include_custom_acf_field_types() {
    include_once(get_template_directory() . '/includes/acf-custom/fields/acf-gf-select.php'); // add Gravity Form field type
}
add_action('acf/include_field_types', 'ks_include_custom_acf_field_types');
