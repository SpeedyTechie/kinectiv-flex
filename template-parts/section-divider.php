<?php
// get passthrough data
$passthrough = acf_get_store('passthrough_section');
$pt_sections = $passthrough->get('sections');


$i_section = $pt_sections['current_index'];
$section = $pt_sections['current'];
$section_prev = $pt_sections['prev'];
$section_next = $pt_sections['next'];

$theme = 'main';

// build style attribute and class list
$bg_styles = kf_section_bg_styles($section['options']);
$section_style = $bg_styles['style'];
$section_classes = $bg_styles['classes'];
?>

<div class="divider divider_<?php echo $section['options']['layout_size']; ?> c_bg_<?php color_id($theme, 0); ?><?php echo $section_classes; ?>"<?php if ($section_style) { echo ' style="' . trim($section_style) . '"'; } ?>></div>
