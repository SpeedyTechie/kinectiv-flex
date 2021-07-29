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

$theme = 'main';

// build style attribute and class list
$bg_styles = kf_section_bg_styles($section['options']);
$section_style = $bg_styles['style'];
$section_classes = $bg_styles['classes'];
?>

<div class="divider divider_<?php echo $section['options']['layout_size']; ?> c_bg_<?php color_id($theme, 0); ?><?php echo $section_classes; ?>"<?php if ($section_style) { echo ' style="' . trim($section_style) . '"'; } ?>></div>
