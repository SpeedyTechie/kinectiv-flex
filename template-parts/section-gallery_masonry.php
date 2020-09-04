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

$image_index = 0;
?>

<div class="section c_color_<?php color_id($theme, 3); ?><?php echo $section_classes; ?>"<?php if ($section_style) { echo ' style="' . trim($section_style) . '"'; } ?>>
    <div class="section__content">
        <div class="title-caption">
            <?php if ($section['title']) { ?><h2 class="title-caption__title title title_<?php echo $section['options']['layout_align-title']; ?> c_color_<?php color_id($theme, 5); ?>"><?php echo $section['title']; ?></h2><?php } ?>
            <div class="title-caption__content">
                <div class="image-masonry image-masonry_<?php echo $section['options']['layout_align-images']; ?> image-masonry_h_<?php echo $section['options']['layout_size']; ?><?php if ($section['options']['layout_truncate']) { echo ' image-masonry_truncate'; } ?>" data-gallery="<?php echo $i_section; ?>">
                    <?php foreach ($section['images'] as $image): ?>
                    <div class="image-masonry__item" data-w="<?php echo esc_attr($image['image']['width']); ?>" data-h="<?php echo esc_attr($image['image']['height']); ?>">
                        <div class="image-masonry__block">
                            <img src="<?php echo $image['image']['url']; ?>" alt="<?php echo esc_attr($image['image']['alt']); ?>" width="<?php echo $image['image']['width']; ?>" height="<?php echo $image['image']['height']; ?>" class="image-masonry__image" />
                            <?php if ($section['options']['layout_popups']): ?>
                            <button type="button" class="image-masonry__button" data-gallery-box="<?php echo $i_section; ?>" data-start="<?php echo $image_index++; ?>">
                                <span class="screen-reader-text">View Larger</span>
                            </button>
                            <span class="image-masonry__dialog-image" data-gallery-box-image="<?php echo $image['popup-image']['url']; ?>" data-alt="<?php echo esc_attr($image['popup-image']['alt']); ?>"></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php if ($section['caption']) { ?><div class="title-caption__caption text text_<?php echo $section['options']['layout_align-caption']; ?> text_wrap text_compact"><?php echo kf_wysiwyg_color_classes($section['caption'], $theme); ?></div><?php } ?>
        </div>
    </div>
</div>
