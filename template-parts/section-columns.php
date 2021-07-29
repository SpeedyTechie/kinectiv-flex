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
                        <?php foreach ($block['list'] as $list_item): ?>
                        <li class="info-cols__list-item title title_xs">
                            <svg viewBox="0 0 21.69 21.69" class="info-cols__list-bullet">
                                <path class="c_fill_<?php color_id($theme, 5); ?>" d="M10.85,0A10.85,10.85,0,1021.7,10.85,10.85,10.85,0,0010.85,0zm6.24,7.17l-6.77,8.79-.05.06a1.48,1.48,0,01-.3.26,1.38,1.38,0,01-.51.16h-.11a1.09,1.09,0,01-.43-.08,1.17,1.17,0,01-.34-.2h-.06l-3.79-3.6a1.2,1.2,0,011.64-1.71l2.84,2.65,6-7.75a1.21,1.21,0,011.91,1.47z"/>
                            </svg>
                            <?php echo $list_item['text']; ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                    <?php if ($block['buttons']): ?>
                    <div class="info-cols__buttons">
                        <div class="button-group button-group_<?php echo $section['options']['layout_align-columns']; ?>">
                            <?php foreach ($block['buttons'] as $button): ?>
                            <div class="button-group__item">
                                <a href="<?php echo esc_url($button['link']['url']); ?>" target="<?php echo $button['link']['target']; ?>" class="button c_bg_<?php color_id($theme, 3); ?> c_h_bg_<?php color_id($theme, 4); ?> c_color_<?php color_id($theme, 0); ?> c_h_color_<?php color_id($theme, 0); ?>"><?php echo $button['link']['title']; ?></a>
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
