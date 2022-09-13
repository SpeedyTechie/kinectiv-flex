<?php
$default_args = array(
    'theme' => 'main',
    'tile_options' => array()
);
$args = array_merge($default_args, $args);


$f_image = get_field('post_preview_image');
$f_description = get_field('post_preview_description');

$excerpt = $f_description;
if (post_password_required()) {
    $excerpt = kf_password_message(true);
}

$theme = ($args['tile_options']['layout_color'] == 'custom') ? $args['tile_options']['color_theme'] : $args['theme'];
$args['tile_options']['color_theme'] = $theme; // set theme for tile options (needed to generate bg styles)

$tile_bg_styles = kf_section_bg_styles($args['tile_options'], 1);
$tile_style = $tile_bg_styles['style'];
$tile_classes = $tile_bg_styles['classes'];
?>

<a href="<?php echo get_permalink(); ?>" class="post-tile">
    <div class="post-tile__tile c_color_<?php color_id($theme, 3); ?><?php echo $tile_classes; ?>"<?php if ($tile_style) { echo ' style="' . trim($tile_style) . '"'; } ?>>
        <?php if ($f_image) { ?><div class="post-tile__image bg-image" role="img" aria-label="<?php echo esc_attr($f_image['alt']); ?>" style="background-image: url('<?php echo $f_image['url']; ?>');"></div><?php } ?>
        <div class="post-tile__main">
            <h3 class="post-tile__title title title_sm c_color_<?php color_id($theme, 5); ?>"><?php echo get_the_title(); ?></h3>
            <div class="post-tile__info c_color_<?php color_id($theme, 5); ?>">
                <p class="post-tile__info-item text text_italic text_xs text_line_1-4"><time datetime="<?php echo get_the_date('Y-m-d'); ?>"><?php echo get_the_date(); ?></time></p>
                <p class="post-tile__info-item text text_italic text_xs text_line_1-4">By <?php echo get_the_author(); ?></p>
            </div>
            <?php if ($excerpt) { ?><p class="post-tile__text text"><?php echo $excerpt; ?></p><?php } ?>
        </div>
    </div>
</a>
