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
$section['options']['layout_block-options']['color_theme'] = $theme; // set theme for block options (needed to generate bg styles)

$section_id = $section['options']['advanced_id'] ? 'section-' . $section['options']['advanced_id'] : null;

// build style attribute and class list
$bg_styles = kf_section_bg_styles($section['options']);
$padding_styles = kf_section_padding_styles($section['options'], $section_prev['options'], $section_next['options']);
$section_style = $bg_styles['style'] . $padding_styles['style'];
$section_classes = $bg_styles['classes'] . $padding_styles['classes'];

// get class list for button colors
$button_color_classes = kf_button_color_classes($theme, $section['options']['color_buttons']);

$blocks = ($section['options']['layout_image-position'] == 'left') ? array('image', 'text') : array('text', 'image'); // set order of blocks
?>

<div <?php if ($section_id) { echo 'id="' . $section_id . '" '; } ?>class="section c_color_<?php color_id($theme, 3); ?><?php echo $section_classes; ?>"<?php if ($section_style) { echo ' style="' . trim($section_style) . '"'; } ?>>
    <?php if ($section['options']['layout_size'] == 'boxed') { ?><div class="section__content"><?php } ?>
        <div class="text-image text-image_<?php echo $section['options']['layout_size']; ?>">
            <?php foreach ($blocks as $i_block => $block_type): ?>
            <div class="text-image__item text-image__item_<?php echo $section['options']['layout_size']; ?>">
                <?php if ($block_type == 'text'): ?>
                <?php
                $block_bg_styles = kf_section_bg_styles($section['options']['layout_block-options'], 1);
                $block_style = $block_bg_styles['style'];
                $block_classes = $block_bg_styles['classes'];
                ?>
                <div class="text-image__block text-image__block_text text-image__block_<?php echo $section['options']['layout_align']; ?><?php echo $block_classes; ?>"<?php if ($block_style) { echo ' style="' . trim($block_style) . '"'; } ?>>
                    <div class="text-image__content text-image__content_<?php echo $section['options']['layout_size']; ?><?php if ($i_block == 1) { echo ' text-image__content_left'; } ?><?php if ($section['options']['layout_size'] == 'full') { echo ' text-image__content_y_' . $section['options']['layout_block-options']['padding_vertical']; } ?>">
                        <?php if ($section['graphic']) { ?><img src="<?php echo $section['graphic']['url']; ?>" alt="<?php echo esc_attr($section['graphic']['alt']); ?>" width="<?php echo $section['graphic']['width']; ?>" height="<?php echo $section['graphic']['height']; ?>" class="text-image__graphic" /><?php } ?>
                        <?php if ($section['title']) { ?><h2 class="text-image__title title title_line_1-1 c_color_<?php color_id($theme, 5); ?>"><?php echo $section['title']; ?></h2><?php } ?>
                        <?php if ($section['text']) { ?><div class="text-image__text text text_wrap text_compact"><?php echo kf_wysiwyg_color_classes($section['text'], $theme); ?></div><?php } ?>
                        <?php if ($section['buttons']): ?>
                        <div class="text-image__buttons">
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
                <?php elseif ($block_type == 'image'): ?>
                <?php
                $bg_image_styles = kf_advanced_bg_image_styles($section['options']['layout_advanced-image']);
                ?>
                <div class="text-image__block text-image__block_image">
                    <div class="text-image__image text-image__image_<?php echo $section['options']['layout_image-height']; ?><?php echo $bg_image_styles['classes']; ?>" role="img" aria-label="<?php echo esc_attr($section['image']['alt']); ?>" style="background-image: url('<?php echo $section['image']['url']; ?>');<?php echo $bg_image_styles['style']; ?>"></div>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    <?php if ($section['options']['layout_size'] == 'boxed') { ?></div><?php } ?>
</div>
