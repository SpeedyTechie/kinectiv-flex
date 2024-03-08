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

$search_bar_args = array(
    'theme' => $theme,
    'width' => $section['options']['layout_width'],
    'alignment' => $section['options']['layout_align']
);
if ($section['title']) {
    $search_bar_args['title'] = $section['title'];
}
if ($section['placeholder']) {
    $search_bar_args['placeholder'] = $section['placeholder'];
}
?>

<div <?php if ($section_id) { echo 'id="' . $section_id . '" '; } ?>class="section c_color_<?php color_id($theme, 5); ?><?php echo $section_classes; ?>"<?php if ($section_style) { echo ' style="' . trim($section_style) . '"'; } ?>>
    <div class="section__content">
        <?php get_template_part('template-parts/component', 'search', $search_bar_args); ?>
    </div>
</div>
