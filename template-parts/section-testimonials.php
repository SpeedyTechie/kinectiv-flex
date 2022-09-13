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

$testimonial_count = $section['testimonials'] ? count($section['testimonials']) : 0;
?>

<div <?php if ($section_id) { echo 'id="' . $section_id . '" '; } ?>class="section c_color_<?php color_id($theme, 3); ?><?php echo $section_classes; ?>"<?php if ($section_style) { echo ' style="' . trim($section_style) . '"'; } ?>>
    <div class="section__content">
        <div class="testimonials testimonials_<?php echo $section['options']['layout_align']; ?><?php if ($testimonial_count > 1) { echo ' testimonials_slick'; } ?>">
            <?php if ($testimonial_count > 1): ?>
            <button type="button" class="testimonials__nav testimonials__nav_prev c_bg_<?php color_id($theme, 5); ?> c_h_bg_<?php color_id($theme, 4); ?>">
                <span class="screen-reader-text">Previous Slide</span>
                <svg viewBox="0 0 17.45 27.04" class="testimonials__nav-arrow testimonials__nav-arrow_reverse">
                    <polygon class="c_fill_<?php color_id($theme, 0); ?>" points="3.94 0 0 3.94 9.58 13.52 0 23.1 3.94 27.04 17.45 13.52 3.94 0"/>
                </svg>
            </button>
            <?php endif; ?>
            <?php if ($section['testimonials']): ?>
            <div class="testimonials__slider<?php if ($testimonial_count > 1) { echo ' testimonials__slider_slick'; } ?>">
                <?php foreach ($section['testimonials'] as $testimonial): ?>
                <div class="testimonials__item<?php if ($testimonial_count > 1) { echo ' testimonials__item_slick'; } ?>">
                    <svg viewBox="0 0 78.08 62.221" class="testimonials__icon">
                        <path class="c_fill_<?php color_id($theme, 2); ?>" d="M-36.295-199.47a14.275,14.275,0,0,1,4.27-10.217A14.227,14.227,0,0,1-21.35-214.11a13.155,13.155,0,0,1,10.065,4.727,17.289,17.289,0,0,1,4.27,12.048q0,13.42-8.235,26.535t-19.825,18.91l-5.49-8.845a48.083,48.083,0,0,0,12.2-10.827,24.761,24.761,0,0,0,4.88-13.268,13.655,13.655,0,0,1-9.15-4.88A14.573,14.573,0,0,1-36.295-199.47Zm44.835,0a14.275,14.275,0,0,1,4.27-10.217,13.876,13.876,0,0,1,10.37-4.423,13.155,13.155,0,0,1,10.065,4.727,17.289,17.289,0,0,1,4.27,12.048q0,13.42-8.235,26.535T9.76-151.89l-5.49-8.845a50.872,50.872,0,0,0,12.353-10.827,24.116,24.116,0,0,0,5.033-13.268,13.9,13.9,0,0,1-9.455-4.88A14.573,14.573,0,0,1,8.54-199.47Z" transform="translate(40.565 214.11)"/>
                    </svg>
                    <?php if ($testimonial['quote']) { ?><div class="testimonials__text text text_wrap text_compact"><?php echo $testimonial['quote']; ?></div><?php } ?>
                    <?php if ($testimonial['name']) { ?><p class="testimonials__name text text_md text_bold text_line_1-4"><?php echo $testimonial['name']; ?></p><?php } ?>
                    <?php if ($testimonial['details']) { ?><p class="testimonials__details text text_md text_line_1-4"><?php echo $testimonial['details']; ?></p><?php } ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <?php if ($testimonial_count > 1): ?>
            <button type="button" class="testimonials__nav testimonials__nav_next c_bg_<?php color_id($theme, 5); ?> c_h_bg_<?php color_id($theme, 4); ?>">
                <span class="screen-reader-text">Next Slide</span>
                <svg viewBox="0 0 17.45 27.04" class="testimonials__nav-arrow">
                    <polygon class="c_fill_<?php color_id($theme, 0); ?>" points="3.94 0 0 3.94 9.58 13.52 0 23.1 3.94 27.04 17.45 13.52 3.94 0"/>
                </svg>
            </button>
            <?php endif; ?>
        </div>
    </div>
</div>
