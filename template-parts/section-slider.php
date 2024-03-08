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

$section_id = $section['options']['advanced_id'] ? 'section-' . $section['options']['advanced_id'] : null;

// build style attribute and class list
$bg_styles = kf_section_bg_styles($section['options']);
$padding_styles = kf_section_padding_styles($section['options'], $section_prev ? $section_prev['options'] : null, $section_next ? $section_next['options'] : null);;
$section_style = $bg_styles['style'] . $padding_styles['style'];
$section_classes = $bg_styles['classes'] . $padding_styles['classes'];

// get class list for button colors
$button_color_classes = kf_button_color_classes($theme, $section['options']['color_buttons']);

// get location of border (added when there's no padding)
$border_location = null;
if ($section['options']['padding_vertical'] == 'none') {
    $border_location = 'y';
} elseif ($section['options']['padding_vertical'] == 'separate') {
    if ($section['options']['padding_top'] == 'none') {
        $border_location = 'top';
    }
    if ($section['options']['padding_bottom'] == 'none') {
        $border_location = ($border_location == 'top') ? 'y' : 'bottom';
    }
}

$slide_count = $section['slides'] ? count($section['slides']) : 0;

$bg_image_styles = kf_advanced_bg_image_styles($section['options']['layout_advanced-image']);
?>

<div <?php if ($section_id) { echo 'id="' . $section_id . '" '; } ?>class="section<?php echo $section_classes; ?>"<?php if ($section_style) { echo ' style="' . trim($section_style) . '"'; } ?>>
    <div class="image-slider<?php if ($border_location) { echo ' image-slider_border_' . $border_location; } ?><?php if ($slide_count > 1) { echo ' image-slider_slick'; } ?>">
        <?php if ($slide_count > 1): ?>
        <button type="button" class="image-slider__nav image-slider__nav_prev<?php if ($border_location) { echo ' image-slider__nav_border_' . $border_location; } ?>">
            <span class="screen-reader-text">Previous Slide</span>
            <div class="image-slider__nav-box image-slider__nav-box_prev c_bg_<?php color_id($theme, 5); ?> c_h_bg_<?php color_id($theme, 4); ?>">
                <svg viewBox="0 0 17.45 27.04" class="image-slider__nav-arrow image-slider__nav-arrow_reverse">
                    <polygon class="c_fill_<?php color_id($theme, 0); ?>" points="3.94 0 0 3.94 9.58 13.52 0 23.1 3.94 27.04 17.45 13.52 3.94 0"/>
                </svg>
            </div>
        </button>
        <?php endif; ?>
        <?php if ($section['slides']): ?>
        <div class="image-slider__slider<?php if ($slide_count > 1) { echo ' image-slider__slider_slick'; } ?>">
            <?php foreach ($section['slides'] as $i_slide => $slide): ?>
            <div class="image-slider__item">
                <div class="image-slider__slide image-slider__slide_<?php echo $section['options']['layout_align']; ?> image-slider__slide_<?php echo $section['options']['layout_height']; ?><?php if ($i_slide == 0) { echo ' image-slider__slide_active'; } ?> c_color_<?php color_id($theme, 5); ?> c_bg_<?php color_id($theme, 1); ?>">
                    <div class="image-slider__image<?php echo $bg_image_styles['classes']; ?>" role="img" aria-label="<?php echo esc_attr($slide['image']['alt']); ?>" style="background-image: url('<?php echo $slide['image']['url']; ?>');<?php echo $bg_image_styles['style']; ?>"></div>
                    <?php if ($section['options']['layout_content'] && ($slide['title'] || $slide['text'] || $slide['buttons'])): ?>
                    <div class="image-slider__content">
                        <?php if ($slide['title']) { ?><h2 class="image-slider__title title"><?php echo $slide['title']; ?></h2><?php } ?>
                        <?php if ($slide['text']) { ?><div class="image-slider__text image-slider__text_<?php echo $section['options']['layout_align']; ?> text text_wrap text_compact"><?php echo kf_wysiwyg_color_classes($slide['text'], $theme); ?></div><?php } ?>
                        <?php if ($slide['buttons']): ?>
                        <div class="image-slider__buttons">
                            <div class="button-group button-group_<?php echo $section['options']['layout_align']; ?>">
                                <?php foreach ($slide['buttons'] as $button): ?>
                                <div class="button-group__item">
                                    <a href="<?php echo esc_url($button['link']['url']); ?>" target="<?php echo $button['link']['target']; ?>" class="button <?php echo $button_color_classes; ?>"><?php echo $button['link']['title']; ?></a>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <?php if ($slide_count > 1): ?>
        <button type="button" class="image-slider__nav image-slider__nav_next<?php if ($border_location) { echo ' image-slider__nav_border_' . $border_location; } ?>">
            <span class="screen-reader-text">Next Slide</span>
            <div class="image-slider__nav-box image-slider__nav-box_next c_bg_<?php color_id($theme, 5); ?> c_h_bg_<?php color_id($theme, 4); ?>">
                <svg viewBox="0 0 17.45 27.04" class="image-slider__nav-arrow">
                    <polygon class="c_fill_<?php color_id($theme, 0); ?>" points="3.94 0 0 3.94 9.58 13.52 0 23.1 3.94 27.04 17.45 13.52 3.94 0"/>
                </svg>
            </div>
        </button>
        <?php endif; ?>
    </div>
</div>
