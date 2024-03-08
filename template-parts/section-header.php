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
$padding_styles = kf_section_padding_styles($section['options'], $section_prev ? $section_prev['options'] : null, $section_next ? $section_next['options'] : null);
$section_style = $bg_styles['style'] . $padding_styles['style'];
$section_classes = $bg_styles['classes'] . $padding_styles['classes'];

// get class list for button colors
$button_color_classes = kf_button_color_classes($theme, $section['options']['color_buttons']);

// calculate padding-top size for text blocks
$bottom_padding = $section['options']['padding_vertical'];
if ($bottom_padding == 'separate') {
    $bottom_padding = $section['options']['padding_bottom'];
    
    if ($bottom_padding == 'none') {
        $bottom_padding = $section['options']['padding_top'];
    }
}
if ($bottom_padding == 'xs' || $bottom_padding == 'none') {
    $bottom_padding = 'sm';
}
?>

<div <?php if ($section_id) { echo 'id="' . $section_id . '" '; } ?>class="section c_color_<?php color_id($theme, 5); ?><?php echo $section_classes; ?>"<?php if ($section_style) { echo ' style="' . trim($section_style) . '"'; } ?>>
    <?php if ($section['options']['bg_type'] == 'video'): ?>
    <?php
    $video_args = array(
        'image' => $section['options']['bg_image'],
        'video' => $section['options']['bg_video'],
        'advanced' => $section['options']['bg_advanced']
    );
    ?>
    <div class="section__bg">
        <?php get_template_part('template-parts/component', 'autoplay_video', $video_args); ?>
    </div>
    <?php endif; ?>
    <div class="section__content section__content_w_full">
        <div class="page-intro">
            <?php if ($section['graphic'] || $section['title'] || $section['text'] || $section['buttons'] || (($section['options']['bg_type'] == 'image' || $section['options']['bg_type'] == 'video') && $section['options']['layout_height'] != 'min')): ?>
            <div class="page-intro__main page-intro__main_<?php echo $section['options']['layout_align']; ?><?php if ($section['options']['bg_type'] == 'image' || $section['options']['bg_type'] == 'video') { echo ' page-intro__main_h_' . $section['options']['layout_height']; } ?>">
                <div class="page-intro__main-content">
                    <?php if ($section['graphic']) { ?><img src="<?php echo $section['graphic']['url']; ?>" alt="<?php echo esc_attr($section['graphic']['alt']); ?>" width="<?php echo $section['graphic']['width']; ?>" height="<?php echo $section['graphic']['height']; ?>" class="page-intro__graphic page-intro__graphic_<?php echo $section['options']['layout_graphic-width']; ?> page-intro__graphic_<?php echo $section['options']['layout_align']; ?>" /><?php } ?>
                    <?php if ($section['title']) { ?><h2 class="page-intro__title title title_<?php echo $section['options']['layout_title-size']; ?>"><?php echo $section['title']; ?></h2><?php } ?>
                    <?php if ($section['text']) { ?><div class="page-intro__text page-intro__text_<?php echo $section['options']['layout_align']; ?> text text_wrap text_compact"><?php echo kf_wysiwyg_color_classes($section['text'], $theme); ?></div><?php } ?>
                    <?php if ($section['buttons']): ?>
                    <div class="page-intro__buttons">
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
            <?php endif; ?>
            <?php if ($section['text-blocks']): ?>
            <?php
            // determine whether blocks should be displayed at half instead of one-third width
            $block_count = count($section['text-blocks']);
            $half = ($block_count < 3) || ($block_count % 3 == 1);
            ?>
            <div class="page-intro__bottom page-intro__bottom_top_<?php echo $bottom_padding; ?>">
                <div class="text-blocks text-blocks_<?php echo $section['options']['layout_align']; ?>">
                    <?php foreach ($section['text-blocks'] as $block): ?>
                    <div class="text-blocks__item<?php if ($half) { echo ' text-blocks__item_half'; } ?>">
                        <?php if ($block['graphic']) { ?><img src="<?php echo $block['graphic']['url']; ?>" alt="<?php echo esc_attr($block['graphic']['alt']); ?>" width="<?php echo $block['graphic']['width']; ?>" height="<?php echo $block['graphic']['height']; ?>" class="text-blocks__graphic" /><?php } ?>
                        <?php if ($block['title']) { ?><h2 class="text-blocks__title text text_md text_bold"><?php echo $block['title']; ?></h2><?php } ?>
                        <?php if ($block['text']) { ?><div class="text-blocks__text text text_wrap text_compact"><?php echo kf_wysiwyg_color_classes($block['text'], $theme); ?></div><?php } ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
