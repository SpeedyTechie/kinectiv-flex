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
        'themeMaps' => kf_color_theme_maps()
    ));
}
add_action('wp_enqueue_scripts', 'kinectiv_flex_scripts');

function ks_admin_scripts() {
    wp_enqueue_style('kf-admin-css', get_stylesheet_directory_uri() . '/css/wp-admin.css', array(), '1.0.0');
    
	wp_enqueue_script('kf-admin-js', get_template_directory_uri() . '/js/wp-admin.js', array(), '1.0.0', true);
    
    wp_localize_script('kf-admin-js', 'wpVars', array(
        'colorList' => kf_color_id_list(),
        'themeMaps' => kf_color_theme_maps()
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
    $color_num = max(0, min(5, $color_num)); // ensure that the color number is between 0 and 5
    
    if (array_key_exists($theme, $theme_maps)) {
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
        'b0' => '#fafdff',
        'b1' => '#c8e0f1',
        'b2' => '#90a6cb',
        'b3' => '#38536f',
        'b4' => '#253143',
        'b5' => '#161a1e'
    );
}


/**
 * Add custom classes to tags in WYSIWYG content
 */
function kf_add_wysiwyg_classes($html, $class_list) {
    $dom = new DOMDocument();
    $dom->loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . $html); // load HTML with proper encoding
    
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
    
    return $final_html; // return final modified HTML
}

function kf_acf_format_wysiwyg_value($value, $post_id, $field) {
    // list of classes to add to various elements
    $class_list = array(
        'h1' => 'title title_lg',
        'h2' => 'title',
        'h3' => 'text text_xl text_normal text_line_1-4',
        'h4' => 'text text_md text_bold',
        'h5' => 'text text_md text_bold',
        'h6' => 'text text_sm text_bold'
    );
    
    return kf_add_wysiwyg_classes($value, $class_list);
}
add_filter('acf/format_value/type=wysiwyg', 'kf_acf_format_wysiwyg_value', 10, 3); // add classes to ACF WYSIWYG fields

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
        /*$f_date_time = get_field('event_details_date');
        $f_description = get_field('event_content_description');

        $start_dt = new DateTime($f_date_time['start'], wp_timezone());
        $end_dt = new DateTime($f_date_time['end'], wp_timezone());

        // create date string
        $date_string = $start_dt->format('F j') . ' @ ' . $start_dt->format('g:ia');

        if ($f_date_time['end']) {
            if ($start_dt->format('Y-m-d') == $end_dt->format('Y-m-d')) {
                $date_string .= ' - ' . $end_dt->format('g:ia');
            } else {
                $date_string .= ' - ' . $end_dt->format('F j') . ' @ ' . $end_dt->format('g:ia');
            }
        }
        
        echo $date_string;*/
        
        
        
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
 * Gravity Forms hide "Add Form" WYSIWYG button
 */
add_filter('gform_display_add_form_button', '__return_false');


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
