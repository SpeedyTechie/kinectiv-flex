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

// build style attribute and class list for custom columns
$column_styles = '';
$column_classes = ' long-text__text_columns_' . $section['options']['layout_columns'];
if ($section['options']['layout_columns'] == 'custom') {
    $column_styles .= ' column-count: ' . $section['options']['layout_custom-columns']['count'] . ';';
    $column_styles .= ' column-width: ' . $section['options']['layout_custom-columns']['width'] . 'px;';
    $column_styles .= ' column-gap: ' . $section['options']['layout_custom-columns']['gap'] . '%;';

    foreach ($section['options']['layout_custom-columns']['avoid-breaks'] as $element_type) {
        $column_classes .= ' long-text__text_avoid-br_' . $element_type;
    }
}
?>

<div <?php if ($section_id) { echo 'id="' . $section_id . '" '; } ?>class="section c_color_<?php color_id($theme, 5); ?><?php echo $section_classes; ?>"<?php if ($section_style) { echo ' style="' . trim($section_style) . '"'; } ?>>
    <div class="section__content">
        <div class="long-text">
            <?php if ($section['text']): ?>
            <div class="long-text__text text text_wrap text_<?php echo $section['options']['layout_align']; echo $column_classes; ?>"<?php if ($column_styles) { echo ' style="' . trim($column_styles) . '"'; } ?>>
                <?php echo kf_wysiwyg_color_classes($section['text'], $theme); ?>
            </div>
            <?php endif; ?>
            <?php if ($section['buttons']): ?>
            <div class="long-text__buttons">
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
