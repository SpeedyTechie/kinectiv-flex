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
?>

<div <?php if ($section_id) { echo 'id="' . $section_id . '" '; } ?>class="section c_color_<?php color_id($theme, 3); ?><?php echo $section_classes; ?>"<?php if ($section_style) { echo ' style="' . trim($section_style) . '"'; } ?>>
    <div class="section__content">
        <div class="title-caption title-caption_w_<?php echo $section['options']['layout_size']; ?>">
            <?php if ($section['title']) { ?><h2 class="title-caption__title title title_<?php echo $section['options']['layout_align-title']; ?> c_color_<?php color_id($theme, 5); ?>"><?php echo $section['title']; ?></h2><?php } ?>
            <div class="title-caption__content"><?php echo $section['embed']; ?></div>
            <?php if ($section['caption']) { ?><div class="title-caption__caption text text_<?php echo $section['options']['layout_align-caption']; ?> text_wrap text_compact"><?php echo kf_wysiwyg_color_classes($section['caption'], $theme); ?></div><?php } ?>
        </div>
    </div>
</div>
