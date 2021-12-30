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
$tile_theme = ($section['options']['layout_tile-options']['layout_color'] == 'custom') ? $section['options']['layout_tile-options']['color_theme'] : $theme;
$popup_theme = 'main';
$section['options']['layout_tile-options']['color_theme'] = $tile_theme; // set theme for tile options (needed to generate bg styles)
$section['options']['layout_popup-options']['color_theme'] = $popup_theme; // set theme for popup options (needed to generate bg styles)

// build style attribute and class list
$bg_styles = kf_section_bg_styles($section['options']);
$padding_styles = kf_section_padding_styles($section['options'], $section_prev['options'], $section_next['options']);
$section_style = $bg_styles['style'] . $padding_styles['style'];
$section_classes = $bg_styles['classes'] . $padding_styles['classes'];
?>

<div class="section c_color_<?php color_id($theme, 5); ?><?php echo $section_classes; ?>"<?php if ($section_style) { echo ' style="' . trim($section_style) . '"'; } ?>>
    <div class="section__content">
        <div class="tile-grid">
            <?php if ($section['title']) { ?><h2 class="tile-grid__title<?php if ($section['options']['layout_per-row'] == 4) { echo ' tile-grid__title_wide'; } elseif ($section['options']['layout_per-row'] == 2) { echo ' tile-grid__title_no-max-w'; } ?> title title_<?php echo $section['options']['layout_align-title']; ?>"><?php echo $section['title']; ?></h2><?php } ?>
            <?php if ($section['tiles']): ?>
            <?php
            $tile_style = '';
            $tile_classes = '';
            if ($section['options']['layout_tile-options']['layout_variation'] == 'standard') {
                $tile_bg_styles = kf_section_bg_styles($section['options']['layout_tile-options'], 1);
                $tile_style = $tile_bg_styles['style'];
                $tile_classes = $tile_bg_styles['classes'];
            } elseif ($section['options']['layout_tile-options']['layout_variation'] == 'image') {
                $bg_image_styles = kf_advanced_bg_image_styles($section['options']['layout_tile-options']['layout_advanced-image']);
                $tile_style = $bg_image_styles['style'];
                $tile_classes = $bg_image_styles['classes'];
            }
            
            $popup_bg_styles = kf_section_bg_styles($section['options']['layout_popup-options'], 0);
            $popup_style = $popup_bg_styles['style'];
            $popup_classes = $popup_bg_styles['classes'];
            ?>
            <div class="tile-grid__wrap<?php if ($section['options']['layout_per-row'] == 4) { echo ' tile-grid__wrap_wide'; } elseif ($section['options']['layout_per-row'] == 2) { echo ' tile-grid__wrap_no-max-w'; } ?>">
                <div class="tile-grid__grid tile-grid__grid_<?php echo $section['options']['layout_align-tiles']; ?>">
                    <?php foreach ($section['tiles'] as $i_tile => $tile): ?>
                    <?php
                    $action_text = null;
                    if ($tile['action'] == 'link') {
                        $action_text = $tile['link']['title'];
                    } elseif ($tile['action'] == 'popup') {
                        $action_text = $tile['action-text'];
                    }
                    
                    $base_class = '';
                    if ($section['options']['layout_tile-options']['layout_variation'] == 'standard') {
                        $base_class = 'text-tile';
                    } elseif ($section['options']['layout_tile-options']['layout_variation'] == 'image') {
                        $base_class = 'image-tile';
                    }
                    ?>
                    <div class="tile-grid__item tile-grid__item_<?php echo $section['options']['layout_per-row']; ?>">
                        <?php if ($tile['action'] == 'none'): ?>
                        <div class="<?php echo $base_class; ?>">
                        <?php elseif ($tile['action'] == 'link'): ?>
                        <a href="<?php echo esc_url($tile['link']['url']); ?>" target="<?php echo $tile['link']['target']; ?>" class="<?php echo $base_class . ' ' . $base_class . '_action'; ?>">
                        <?php elseif ($tile['action'] == 'popup'): ?>
                        <button type="button" class="<?php echo $base_class . ' ' . $base_class . '_action'; ?>" data-dialog-box="<?php echo 'section-' . $i_section . '_tile-' . $i_tile; ?>">
                        <?php endif; ?>
                            <?php if ($base_class == 'text-tile'): ?>
                            <div class="text-tile__tile text-tile__tile_<?php echo $section['options']['layout_tile-options']['layout_align']; ?> c_color_<?php color_id($tile_theme, 3); ?><?php echo $tile_classes; ?>"<?php if ($tile_style) { echo ' style="' . trim($tile_style) . '"'; } ?>>
                                <?php if ($tile['image']) { ?><img src="<?php echo $tile['image']['url']; ?>" alt="<?php echo esc_attr($tile['image']['alt']); ?>" width="<?php echo $tile['image']['width']; ?>" height="<?php echo $tile['image']['height']; ?>" class="text-tile__image" /><?php } ?>
                                <?php if ($tile['title'] || $tile['text'] || $action_text): ?>
                                <div class="text-tile__main">
                                    <?php if ($tile['title'] || $tile['text']): ?>
                                    <div class="text-tile__content<?php if ($action_text) { echo ' text-tile__content_bottom_none'; } ?>">
                                        <?php if ($tile['title']) { ?><h3 class="text-tile__title title title_sm c_color_<?php color_id($tile_theme, 5); ?>"><?php echo $tile['title']; ?></h3><?php } ?>
                                        <?php if ($tile['text']) { ?><p class="text-tile__text text"><?php echo $tile['text']; ?></p><?php } ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <?php if ($action_text): ?>
                                <div class="text-tile__bottom">
                                    <div class="text-tile__content<?php if ($tile['title'] || $tile['text']) { echo ' text-tile__content_top_xs text-tile__content_bottom_sm'; } else { echo ' text-tile__content_y_sm'; } ?>">
                                        <p class="text-tile__action-text text c_color_<?php color_id($tile_theme, 5); ?>"><?php echo $action_text; ?></p>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            <?php elseif ($base_class == 'image-tile'): ?>
                            <div class="image-tile__tile image-tile__tile_<?php echo $section['options']['layout_tile-options']['layout_align']; ?> c_color_<?php color_id($tile_theme, 5); ?> c_bg_<?php color_id($tile_theme, 1); ?><?php echo $tile_classes; ?>" style="background-image: url(<?php echo $tile['image']['url']; ?>);<?php echo $tile_style; ?>">
                                <div class="image-tile__main">
                                    <?php if ($tile['title']) { ?><h3 class="image-tile__title title title_sm"><?php echo $tile['title']; ?></h3><?php } ?>
                                    <?php if ($tile['subtitle']) { ?><p class="image-tile__subtitle text"><?php echo $tile['subtitle']; ?></p><?php } ?>
                                </div>
                                <?php if ($action_text): ?>
                                <div class="image-tile__bottom">
                                    <p class="image-tile__action-text text"><?php echo $action_text; ?></p>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        <?php if ($tile['action'] == 'popup'): ?>
                        </button>
                        <?php elseif ($tile['action'] == 'link'): ?>
                        </a>
                        <?php elseif ($tile['action'] == 'none'): ?>
                        </div>
                        <?php endif; ?>
                        <?php if ($tile['action'] == 'popup'): ?>
                        <div class="tile-grid__box">
                            <div class="info-box c_color_<?php color_id($popup_theme, 5); ?> info-box_<?php echo $section['options']['layout_popup-options']['layout_align']; ?><?php echo $popup_classes; ?>" data-dialog-box-content="<?php echo 'section-' . $i_section . '_tile-' . $i_tile; ?>"<?php if ($popup_style) { echo ' style="' . trim($popup_style) . '"'; } ?>>
                                <?php if ($tile['popup']['image']): ?>
                                <div class="info-box__col info-box__col_<?php echo $section['options']['layout_popup-options']['layout_image-size']; ?>-image">
                                    <?php if ($section['options']['layout_popup-options']['layout_image-size'] == 'boxed'): ?>
                                    <img src="<?php echo $tile['popup']['image']['url']; ?>" alt="<?php echo esc_attr($tile['popup']['image']['alt']); ?>" width="<?php echo $tile['popup']['image']['width']; ?>" height="<?php echo $tile['popup']['image']['height']; ?>" class="info-box__boxed-image" />
                                    <?php elseif ($section['options']['layout_popup-options']['layout_image-size'] == 'full'): ?>
                                    <?php
                                    $popup_image_styles = kf_advanced_bg_image_styles($section['options']['layout_popup-options']['layout_advanced-image']);
                                    ?>
                                    <div class="info-box__full-image<?php echo $popup_image_styles['classes']; ?>" role="img" aria-label="<?php echo esc_attr($tile['popup']['image']['alt']); ?>" style="background-image: url('<?php echo $tile['popup']['image']['url']; ?>');<?php echo $popup_image_styles['style']; ?>"></div>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                                <div class="info-box__col info-box__col_text">
                                    <?php if ($tile['popup']['title']) { ?><h3 class="info-box__title title title_sm"><?php echo $tile['popup']['title']; ?></h3><?php } ?>
                                    <?php if ($tile['popup']['text']) { ?><div class="info-box__text text text_wrap text_compact"><?php echo kf_wysiwyg_color_classes($tile['popup']['text'], $popup_theme); ?></div><?php } ?>
                                    <?php if ($tile['popup']['buttons']): ?>
                                    <div class="info-box__buttons">
                                        <div class="button-group button-group_<?php echo $tile['options']['layout_popup-options']['layout_align']; ?>">
                                            <?php foreach ($tile['popup']['buttons'] as $button): ?>
                                            <div class="button-group__item">
                                                <a href="<?php echo esc_url($button['link']['url']); ?>" target="<?php echo $button['link']['target']; ?>" class="button <?php component_colors($popup_theme, 'button'); ?>"><?php echo $button['link']['title']; ?></a>
                                            </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            <?php if ($section['cta']['text'] || $section['cta']['link']): ?>
            <p class="tile-grid__cta<?php if ($section['options']['layout_per-row'] == 4) { echo ' tile-grid__cta_wide'; } elseif ($section['options']['layout_per-row'] == 2) { echo ' tile-grid__cta_no-max-w'; } ?> tile-grid__cta_<?php echo $section['options']['layout_align-cta']; ?> text text_xs">
                <?php echo $section['cta']['text']; ?>
                <?php if ($section['cta']['link']): ?>
                <a href="<?php echo esc_url($section['cta']['link']['url']); ?>" target="<?php echo $section['cta']['link']['target']; ?>" class="arrow-link <?php component_colors($theme, 'arrow-link'); ?>">
                    <span class="arrow-link__text"><?php echo $section['cta']['link']['title']; ?></span>
                    <svg viewBox="0 0 17.45 27.04" class="arrow-link__arrow">
                        <polygon points="3.94 0 0 3.94 9.58 13.52 0 23.1 3.94 27.04 17.45 13.52 3.94 0"/>
                    </svg>
                </a>
                <?php endif; ?>
            </p>
            <?php endif; ?>
            <?php if ($section['buttons']): ?>
            <div class="tile-grid__buttons">
                <div class="button-group button-group_<?php echo $section['options']['layout_align-title']; ?>">
                    <?php foreach ($section['buttons'] as $button): ?>
                    <div class="button-group__item">
                        <a href="<?php echo esc_url($button['link']['url']); ?>" target="<?php echo $button['link']['target']; ?>" class="button <?php component_colors($theme, 'button'); ?>"><?php echo $button['link']['title']; ?></a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
