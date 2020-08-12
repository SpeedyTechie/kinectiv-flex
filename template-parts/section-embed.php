<?php
// get passthrough data
$passthrough = acf_get_store('passthrough_section');
$pt_sections = $passthrough->get('sections');


$i_section = $pt_sections['current_index'];
$section = $pt_sections['current'];
$section_prev = $pt_sections['prev'];
$section_next = $pt_sections['next'];

$theme = $section['options']['color_theme'];

// build style attribute and class list
$bg_styles = kf_section_bg_styles($section['options']);
$padding_styles = kf_section_padding_styles($section['options'], $section_prev['options'], $section_next['options']);
$section_style = $bg_styles['style'] . $padding_styles['style'];
$section_classes = $bg_styles['classes'] . $padding_styles['classes'];
?>

<div class="section c_color_<?php color_id($theme, 3); ?><?php echo $section_classes; ?>"<?php if ($section_style) { echo ' style="' . trim($section_style) . '"'; } ?>>
    <div class="section__content">
        <div class="title-caption">
            <?php if ($section['title']) { ?><h2 class="title-caption__title title title_<?php echo $section['options']['layout_align-title']; ?> c_color_<?php color_id($theme, 5); ?>"><?php echo $section['title']; ?></h2><?php } ?>
            <div class="title-caption__content"><?php echo $section['embed']; ?></div>
            <?php if ($section['caption']) { ?><div class="title-caption__caption text text_<?php echo $section['options']['layout_align-caption']; ?> text_wrap text_compact"><?php echo kf_wysiwyg_color_classes($section['caption'], $theme); ?></div><?php } ?>
        </div>
    </div>
</div>
