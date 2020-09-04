<?php
$passthrough = acf_get_store('passthrough_preview-post');
$pt_theme = $passthrough->get('theme');
$pt_tile_options = $passthrough->get('tile_options');


$f_image = get_field('post_preview_image');
$f_description = get_field('post_preview_description');

$theme = ($pt_tile_options['layout_color'] == 'custom') ? $pt_tile_options['color_theme'] : $pt_theme;
$pt_tile_options['color_theme'] = $theme; // set theme for tile options (needed to generate bg styles)

$tile_bg_styles = kf_section_bg_styles($pt_tile_options, 1);
$tile_style = $tile_bg_styles['style'];
$tile_classes = $tile_bg_styles['classes'];
?>

<a href="<?php echo get_permalink(); ?>" class="post-tile">
    <div class="post-tile__tile c_color_<?php color_id($theme, 3); ?><?php echo $tile_classes; ?>"<?php if ($tile_style) { echo ' style="' . trim($tile_style) . '"'; } ?>>
        <?php if ($f_image) { ?><div class="post-tile__image bg-image" role="img" aria-label="<?php echo esc_attr($f_image['alt']); ?>" style="background-image: url('<?php echo $f_image['url']; ?>');"></div><?php } ?>
        <div class="post-tile__main">
            <h3 class="post-tile__title title title_sm c_color_<?php color_id($theme, 5); ?>"><?php echo get_the_title(); ?></h3>
            <div class="post-tile__info c_color_<?php color_id($theme, 5); ?>">
                <p class="post-tile__info-item text text_italic text_xs text_line_1-4"><?php echo get_the_date(); ?></p>
                <p class="post-tile__info-item text text_italic text_xs text_line_1-4">By <?php echo get_the_author(); ?></p>
            </div>
            <?php if ($f_description) { ?><p class="post-tile__text text"><?php echo $f_description; ?></p><?php } ?>
        </div>
    </div>
</a>