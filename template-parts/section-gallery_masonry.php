<?php
$default_args = array(
    'sections' => array(
        'current_index' => 0,
        'current' => array(),
        'prev' => null,
        'next' => null
    )
);
$args = array_merge($default_args, $args);


$i_section = $args['sections']['current_index'];
$section = $args['sections']['current'];
$section_prev = $args['sections']['prev'];
$section_next = $args['sections']['next'];

$theme = $section['options']['color_theme'];
$popup_theme = 'main';

$section_id = $section['options']['advanced_id'] ? 'section-' . $section['options']['advanced_id'] : null;

// build style attribute and class list
$bg_styles = kf_section_bg_styles($section['options']);
$padding_styles = kf_section_padding_styles($section['options'], $section_prev ? $section_prev['options'] : null, $section_next ? $section_next['options'] : null);;
$section_style = $bg_styles['style'] . $padding_styles['style'];
$section_classes = $bg_styles['classes'] . $padding_styles['classes'];

$image_index = 0;
?>

<div <?php if ($section_id) { echo 'id="' . $section_id . '" '; } ?>class="section c_color_<?php color_id($theme, 3); ?><?php echo $section_classes; ?>"<?php if ($section_style) { echo ' style="' . trim($section_style) . '"'; } ?>>
    <div class="section__content">
        <div class="title-caption">
            <?php if ($section['title']) { ?><h2 class="title-caption__title title title_<?php echo $section['options']['layout_align-title']; ?> <?php echo component_colors($theme, 'title'); ?>"><?php echo $section['title']; ?></h2><?php } ?>
            <div class="title-caption__content">
                <div class="image-masonry image-masonry_<?php echo $section['options']['layout_align-images']; ?> image-masonry_h_<?php echo $section['options']['layout_size']; ?><?php if ($section['options']['layout_truncate']) { echo ' image-masonry_truncate'; } ?>" data-gallery="<?php echo $i_section; ?>">
                    <?php foreach ($section['images'] as $image): ?>
                    <div class="image-masonry__item" data-w="<?php echo esc_attr($image['image']['width']); ?>" data-h="<?php echo esc_attr($image['image']['height']); ?>">
                        <div class="image-masonry__block">
                            <img src="<?php echo $image['image']['url']; ?>" alt="<?php echo esc_attr($image['image']['alt']); ?>" width="<?php echo $image['image']['width']; ?>" height="<?php echo $image['image']['height']; ?>" class="image-masonry__image" />
                            <?php if ($section['options']['layout_popups']): ?>
                            <button type="button" class="image-masonry__button" data-gallery-box="<?php echo $i_section; ?>" data-box-theme="<?php echo $popup_theme; ?>" data-start="<?php echo $image_index++; ?>">
                                <span class="screen-reader-text">View Larger</span>
                            </button>
                            <span class="image-masonry__dialog-image" data-gallery-box-image="<?php echo $image['popup-image']['url']; ?>" data-alt="<?php echo esc_attr($image['popup-image']['alt']); ?>"></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php if ($section['caption']) { ?><div class="title-caption__caption text text_<?php echo $section['options']['layout_align-caption']; ?> text_wrap text_compact"><?php echo kf_wysiwyg_color_classes($section['caption'], $theme); ?></div><?php } ?>
        </div>
    </div>
</div>
