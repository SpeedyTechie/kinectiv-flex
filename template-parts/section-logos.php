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
$padding_styles = kf_section_padding_styles($section['options'], $section_prev['options'], $section_next['options']);
$section_style = $bg_styles['style'] . $padding_styles['style'];
$section_classes = $bg_styles['classes'] . $padding_styles['classes'];

// get class list for button colors
$button_color_classes = kf_button_color_classes($theme, $section['options']['color_buttons']);
?>

<div <?php if ($section_id) { echo 'id="' . $section_id . '" '; } ?>class="section c_color_<?php color_id($theme, 5); ?><?php echo $section_classes; ?>"<?php if ($section_style) { echo ' style="' . trim($section_style) . '"'; } ?>>
    <div class="section__content">
        <div class="logos logos_<?php echo $section['options']['layout_align']; ?>">
            <?php if ($section['title']) { ?><h2 class="logos__title title"><?php echo $section['title']; ?></h2><?php } ?>
            <?php if ($section['text']) { ?><div class="logos__text logos__text_<?php echo $section['options']['layout_align']; ?> text text_md text_wrap text_compact"><?php echo kf_wysiwyg_color_classes($section['text'], $theme); ?></div><?php } ?>
            <div class="logos__logos">
                <?php if ($section['logos']): ?>
                <div class="logos__grid logos__grid_<?php echo $section['options']['layout_align']; ?> logos__grid_<?php echo $section['options']['layout_logo-size']; ?>">
                    <?php foreach ($section['logos'] as $logo): ?>
                    <div class="logos__item logos__item_<?php echo $section['options']['layout_logo-size']; ?>">
                        <?php if ($logo['link']) { ?><a href="<?php echo esc_url($logo['link']); ?>" target="_blank" rel="nofollow" class="logos__link"><?php } ?>
                            <img src="<?php echo $logo['logo']['url']; ?>" alt="<?php echo esc_attr($section['logo']['alt']); ?>" width="<?php echo $logo['logo']['width']; ?>" height="<?php echo $logo['logo']['height']; ?>" class="logos__logo logos__logo_<?php echo $section['options']['layout_logo-size']; ?>" />
                        <?php if ($logo['link']) { ?></a><?php } ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            <?php if ($section['buttons']): ?>
            <div class="logos__buttons">
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
