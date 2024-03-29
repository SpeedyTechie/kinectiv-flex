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
?>

<div <?php if ($section_id) { echo 'id="' . $section_id . '" '; } ?>class="section c_color_<?php color_id($theme, 5); ?><?php echo $section_classes; ?>"<?php if ($section_style) { echo ' style="' . trim($section_style) . '"'; } ?>>
    <div class="section__content">
        <div class="cta cta_<?php echo $section['options']['layout_align']; ?>">
            <?php if ($section['graphic']) { ?><img src="<?php echo $section['graphic']['url']; ?>" alt="<?php echo esc_attr($section['graphic']['alt']); ?>" width="<?php echo $section['graphic']['width']; ?>" height="<?php echo $section['graphic']['height']; ?>" class="cta__graphic cta__graphic_<?php echo $section['options']['layout_graphic-width']; ?> cta__graphic_<?php echo $section['options']['layout_align']; ?>" /><?php } ?>
            <?php if ($section['title']) { ?><h2 class="cta__title title <?php echo component_colors($theme, 'title'); ?>"><?php echo $section['title']; ?></h2><?php } ?>
            <?php if ($section['text']) { ?><div class="cta__text cta__text_<?php echo $section['options']['layout_align']; ?> text text_md text_wrap text_compact"><?php echo kf_wysiwyg_color_classes($section['text'], $theme); ?></div><?php } ?>
            <?php if ($section['buttons']): ?>
            <div class="cta__buttons">
                <div class="button-group button-group_<?php echo $section['options']['layout_align']; ?>">
                    <?php foreach ($section['buttons'] as $button): ?>
                    <div class="button-group__item">
                        <a href="<?php echo esc_url($button['link']['url']); ?>" target="<?php echo $button['link']['target']; ?>" class="button <?php echo $button_color_classes; ?>"><?php echo $button['link']['title']; ?></a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
