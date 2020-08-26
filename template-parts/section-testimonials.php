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

$testimonial_count = $section['testimonials'] ? count($section['testimonials']) : 0;
?>

<div class="section c_color_<?php color_id($theme, 3); ?><?php echo $section_classes; ?>"<?php if ($section_style) { echo ' style="' . trim($section_style) . '"'; } ?>>
    <div class="section__content">
        <div class="testimonials testimonials_<?php echo $section['options']['layout_align']; ?><?php if ($testimonial_count > 1) { echo ' testimonials_slick'; } ?>">
            <?php if ($testimonial_count > 1): ?>
            <button type="button" class="testimonials__nav testimonials__nav_prev c_bg_<?php color_id($theme, 5); ?> c_h_bg_<?php color_id($theme, 4); ?> c_color_<?php color_id($theme, 0); ?>">
                <span class="screen-reader-text">Previous Slide</span>
            </button>
            <?php endif; ?>
            <?php if ($section['testimonials']): ?>
            <div class="testimonials__slider<?php if ($testimonial_count > 1) { echo ' testimonials__slider_slick'; } ?>">
                <?php foreach ($section['testimonials'] as $testimonial): ?>
                <div class="testimonials__item<?php if ($testimonial_count > 1) { echo ' testimonials__item_slick'; } ?>">
                    <div class="testimonials__icon"></div>
                    <?php if ($testimonial['quote']) { ?><div class="testimonials__text text text_wrap text_compact"><?php echo $testimonial['quote']; ?></div><?php } ?>
                    <?php if ($testimonial['name']) { ?><p class="testmonials__name text text_md text_bold"><?php echo $testimonial['name']; ?></p><?php } ?>
                    <?php if ($testimonial['details']) { ?><p class="testmonials__details text text_md"><?php echo $testimonial['details']; ?></p><?php } ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <?php if ($testimonial_count > 1): ?>
            <button type="button" class="testimonials__nav testimonials__nav_next c_bg_<?php color_id($theme, 5); ?> c_h_bg_<?php color_id($theme, 4); ?> c_color_<?php color_id($theme, 0); ?>">
                <span class="screen-reader-text">Next Slide</span>
            </button>
            <?php endif; ?>
        </div>
    </div>
</div>
