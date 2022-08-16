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

// build style attribute and class list
$bg_styles = kf_section_bg_styles($section['options']);
$padding_styles = kf_section_padding_styles($section['options'], $section_prev['options'], $section_next['options']);
$section_style = $bg_styles['style'] . $padding_styles['style'];
$section_classes = $bg_styles['classes'] . $padding_styles['classes'];
?>

<div class="section c_color_<?php color_id($theme, 5); ?><?php echo $section_classes; ?>"<?php if ($section_style) { echo ' style="' . trim($section_style) . '"'; } ?>>
    <div class="section__content">
        <div class="basic-form basic-form_<?php echo $section['options']['layout_align']; ?>">
            <?php if ($section['title']) { ?><h2 class="basic-form__title title"><?php echo $section['title']; ?></h2><?php } ?>
            <?php if ($section['text']) { ?><div class="basic-form__text text text_wrap text_compact c_color_<?php color_id($theme, 3); ?>"><?php echo kf_wysiwyg_color_classes($section['text'], $theme); ?></div><?php } ?>
            <?php if ($section['form']) { ?><div class="basic-form__form"><?php kf_show_gform($section['form'], $theme, $section['options']['color_buttons']); ?></div><?php } ?>
        </div>
    </div>
</div>
