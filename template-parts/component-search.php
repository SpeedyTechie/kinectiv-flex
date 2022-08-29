<?php
$f_search_bar_defaults = get_field('config_search_bar', 'option');

$default_args = array(
    'theme' => 'main',
    'title' => $f_search_bar_defaults['title'],
    'placeholder' => $f_search_bar_defaults['placeholder'],
    'value' => '',
    'width' => 'lg',
    'alignment' => 'left',
    'variation' => 'default'
);
$args = array_merge($default_args, $args);

$theme = $args['theme'];

$field_id = wp_unique_id('search-field-');
?>

<form method="GET" action="<?php echo esc_url(home_url()); ?>" class="search-bar search-bar_w_<?php echo $args['width']; ?> search-bar_<?php echo $args['alignment']; ?>" role="search">
    <?php if ($args['title'] || $args['variation'] == 'popup'): ?>
    <div class="search-bar__top<?php if ($args['variation'] == 'small') { echo ' search-bar__top_bottom_sm'; } ?>">
        <?php if ($args['title']): ?>
        <label for="<?php echo $field_id; ?>" class="search-bar__title text text_sm text_line_1-4 c_color_<?php color_id($theme, 5); ?>"><?php echo $args['title']; ?></label>
        <?php endif; ?>
        <?php if ($args['variation'] == 'popup'): ?>
        <div class="search-bar__close-spacer"></div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    <?php if (!$args['title']): ?>
    <label for="<?php echo $field_id; ?>" class="screen-reader-text">Search</label>
    <?php endif; ?>
    <div class="search-bar__main<?php echo ($args['variation'] == 'small') ? ' text text_md' : ' title title_sm'; ?>">
        <input type="search" name="s" id="<?php echo $field_id; ?>" class="search-bar__field c_bg_<?php color_id($theme, ($args['variation'] == 'popup' ? 0 : 1)); ?> c_color_<?php color_id($theme, 5); ?> c_placeholder_<?php color_id($theme, 2); ?>" style="background-image: url('<?php echo get_stylesheet_directory_uri() . '/images/dynamic/icon-search.php?color=' . color_id($theme, 5, true); ?>');" autocomplete="off" placeholder="<?php echo $args['placeholder']; ?>" value="<?php echo esc_attr($args['value']); ?>" />
        <button type="submit" class="search-bar__button c_bg_<?php color_id($theme, 5); ?> c_h_bg_<?php color_id($theme, 4); ?> c_color_<?php color_id($theme, 0); ?>">
            <span class="screen-reader-text">Search</span>
            <svg viewBox="0 0 17.45 27.04" class="search-bar__button-arrow">
                <polygon points="3.94 0 0 3.94 9.58 13.52 0 23.1 3.94 27.04 17.45 13.52 3.94 0"/>
            </svg>
        </button>
    </div>
</form>
