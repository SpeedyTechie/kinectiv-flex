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

<div class="section c_color_<?php color_id($theme, 5); ?><?php echo $section_classes; ?>"<?php if ($section_style) { echo ' style="' . trim($section_style) . '"'; } ?>>
    <div class="section__content">
        <div class="info-cols">
            <?php if ($section['title']) { ?><h2 class="info-cols__title<?php if ($section['options']['layout_columns'] == 1) { echo ' info-cols__title_w_sm'; } ?> title title_<?php echo $section['options']['layout_align-title']; ?>"><?php echo $section['title']; ?></h2><?php } ?>
            <?php if ($section['blocks']): ?>
            <div class="info-cols__columns info-cols__columns_<?php echo $section['options']['layout_columns']; ?> info-cols__columns_<?php echo $section['options']['layout_align-columns']; ?>">
                <?php foreach ($section['blocks'] as $block): ?>
                <div class="info-cols__item">
                    <?php if ($block['title']) { ?><h3 class="info-cols__subtitle title title_sm"><?php echo $block['title']; ?></h3><?php } ?>
                    <?php if ($block['text']) { ?><div class="info-cols__text text text_md text_wrap text_compact"><?php echo kf_wysiwyg_color_classes($block['text'], $theme); ?></div><?php } ?>
                    <?php if ($block['list']): ?>
                    <ul class="info-cols__list info-cols__list_<?php echo $section['options']['layout_align-columns']; ?>">
                        <?php foreach ($block['list'] as $list_item) { ?><li class="info-cols__list-item title title_xs"><?php echo $list_item['text']; ?></li><?php } ?>
                    </ul>
                    <?php endif; ?>
                    <?php if ($block['buttons']): ?>
                    <div class="info-cols__buttons">
                        <div class="button-group button-group_<?php echo $section['options']['layout_align-columns']; ?>">
                            <?php foreach ($block['buttons'] as $button): ?>
                            <div class="button-group__item">
                                <a href="<?php echo esc_url($button['link']['url']); ?>" target="<?php echo $button['link']['target']; ?>" class="button c_bg_<?php color_id($theme, 3); ?> c_h_bg_<?php color_id($theme, 4); ?> c_color_<?php color_id($theme, 0); ?>"><?php echo $button['link']['title']; ?></a>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
