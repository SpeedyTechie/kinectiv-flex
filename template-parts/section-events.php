<?php
// get passthrough data
$passthrough = acf_get_store('passthrough_section');
$pt_sections = $passthrough->get('sections');


$i_section = $pt_sections['current_index'];
$section = $pt_sections['current'];
$section_prev = $pt_sections['prev'];
$section_next = $pt_sections['next'];

$f_none_text = get_field('config_events_none-text', 'option');

$theme = $section['options']['color_theme'];
$theme_inverse = kf_color_theme_inverses()[$theme];

// build style attribute and class list
$bg_styles = kf_section_bg_styles($section['options']);
$padding_styles = kf_section_padding_styles($section['options'], $section_prev['options'], $section_next['options']);
$section_style = $bg_styles['style'] . $padding_styles['style'];
$section_classes = $bg_styles['classes'] . $padding_styles['classes'];
?>

<div class="section c_color_<?php color_id($theme, 5); ?><?php echo $section_classes; ?>"<?php if ($section_style) { echo ' style="' . trim($section_style) . '"'; } ?>>
    <div class="section__content">
        <div class="tile-grid">
            <?php if ($section['title']) { ?><h2 class="tile-grid__title title title_<?php echo $section['options']['layout_align-title']; ?>"><?php echo $section['title']; ?></h2><?php } ?>
            <?php if ($section['type'] == 'featured'): ?>
            <?php
            $featured_events = null;
            if ($section['remove-past']) {
                foreach ($section['featured-events'] as $p_event) {
                    if (!kf_is_past_event($p_event->ID)) {
                        $featured_events[] = $p_event;
                    }
                }
            } else {
                $featured_events = $section['featured-events'];
            }
            ?>
            <?php if ($featured_events): ?>
            <div class="tile-grid__wrap">
                <div class="tile-grid__grid tile-grid__grid_<?php echo $section['options']['layout_align-tiles']; ?>">
                    <?php foreach ($featured_events as $p_event): ?>
                    <?php
                    global $post;
                    $post = get_post($p_event);
                    setup_postdata($p_event);
                    
                    acf_register_store('passthrough_preview-event', array(
                        'theme' => $theme_inverse,
                        'tile_options' => $section['options']['layout_tile-options']
                    ));
                    ?>
                    <div class="tile-grid__item tile-grid__item_3">
                        <?php get_template_part('template-parts/preview', 'event'); ?>
                    </div>
                    <?php
                    acf_register_store('passthrough_preview-event', array());

                    wp_reset_postdata();
                    ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php else: ?>
            <div class="tile-grid__item tile-grid__item_full">
                <p class="tile-grid__none text text_italic text_<?php echo $section['options']['layout_align-title']; ?>"><?php echo $f_none_text; ?></p>
            </div>
            <?php endif; ?>
            <?php elseif ($section['type'] == 'upcoming'): ?>
            <?php
            // make sure post count is valid (if not, default to 3)
            if (!$section['upcoming-events'] || $section['upcoming-events'] < 1) {
                $section['upcoming-events'] = 3;
            }
            
            $grid_query = kf_custom_query('event', $section['upcoming-events']);
            ?>
            <div class="tile-grid__wrap">
                <div class="tile-grid__grid tile-grid__grid_<?php echo $section['options']['layout_align-tiles']; ?>">
                    <?php if ($grid_query->posts): ?>
                    <?php foreach ($grid_query->posts as $p_event): ?>
                    <?php
                    global $post;
                    $post = get_post($p_event);
                    setup_postdata($p_event);
                    
                    acf_register_store('passthrough_preview-event', array(
                        'theme' => $theme_inverse,
                        'tile_options' => $section['options']['layout_tile-options']
                    ));
                    ?>
                    <div class="tile-grid__item tile-grid__item_3">
                        <?php get_template_part('template-parts/preview', 'event'); ?>
                    </div>
                    <?php
                    acf_register_store('passthrough_preview-event', array());

                    wp_reset_postdata();
                    ?>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <div class="tile-grid__item tile-grid__item_full">
                        <p class="tile-grid__none text text_italic text_<?php echo $section['options']['layout_align-title']; ?>"><?php echo $f_none_text; ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php elseif ($section['type'] == 'all-future'): ?>
            <?php
            $grid_query = kf_custom_query('event');
            
            $passthrough_data = array(
                'theme' => $theme_inverse,
                'tile_options' => $section['options']['layout_tile-options']
            );
            ?>
            <div class="tile-grid__wrap" data-type="event" data-passthrough="<?php echo esc_attr(json_encode($passthrough_data, JSON_UNESCAPED_SLASHES)); ?>">
                <div class="tile-grid__grid tile-grid__grid_<?php echo $section['options']['layout_align-tiles']; ?>">
                    <?php if ($grid_query->posts): ?>
                    <?php foreach ($grid_query->posts as $p_post): ?>
                    <?php
                    global $post;
                    $post = get_post($p_post);
                    setup_postdata($p_post);
                    
                    acf_register_store('passthrough_preview-event', $passthrough_data);
                    ?>
                    <div class="tile-grid__item tile-grid__item_3">
                        <?php get_template_part('template-parts/preview', 'event'); ?>
                    </div>
                    <?php
                    acf_register_store('passthrough_preview-event', array());

                    wp_reset_postdata();
                    ?>
                    <?php endforeach; ?>
                    <?php endif; ?>
                    <div class="tile-grid__item tile-grid__item_none tile-grid__item_full<?php if ($grid_query->posts) { echo ' tile-grid__item_hidden'; } ?>">
                        <p class="tile-grid__none text text_italic text_<?php echo $section['options']['layout_align-title']; ?>"><?php echo $f_none_text; ?></p>
                    </div>
                    <div class="tile-grid__item tile-grid__item_more tile-grid__item_full<?php if (!$grid_query->posts || $grid_query->max_num_pages == 1) { echo ' tile-grid__item_hidden'; } ?>">
                        <div class="tile-grid__more">
                            <div class="button-group button-group_<?php echo $section['options']['layout_align-title']; ?>">
                                <div class="button-group__item">
                                    <button type="button" class="tile-grid__more-button button c_bg_<?php color_id($theme, 3); ?> c_h_bg_<?php color_id($theme, 4); ?> c_color_<?php color_id($theme, 0); ?> c_h_color_<?php color_id($theme, 0); ?>">Load More</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php elseif ($section['type'] == 'all-past'): ?>
            <?php
            $grid_query = kf_custom_query('event', false, 1, 'past');
            
            $passthrough_data = array(
                'theme' => $theme_inverse,
                'tile_options' => $section['options']['layout_tile-options']
            );
            ?>
            <div class="tile-grid__wrap" data-type="event" data-special="past" data-passthrough="<?php echo esc_attr(json_encode($passthrough_data, JSON_UNESCAPED_SLASHES)); ?>">
                <div class="tile-grid__grid tile-grid__grid_<?php echo $section['options']['layout_align-tiles']; ?>">
                    <?php if ($grid_query->posts): ?>
                    <?php foreach ($grid_query->posts as $p_post): ?>
                    <?php
                    global $post;
                    $post = get_post($p_post);
                    setup_postdata($p_post);
                    
                    acf_register_store('passthrough_preview-event', $passthrough_data);
                    ?>
                    <div class="tile-grid__item tile-grid__item_3">
                        <?php get_template_part('template-parts/preview', 'event'); ?>
                    </div>
                    <?php
                    acf_register_store('passthrough_preview-event', array());

                    wp_reset_postdata();
                    ?>
                    <?php endforeach; ?>
                    <?php endif; ?>
                    <div class="tile-grid__item tile-grid__item_none tile-grid__item_full<?php if ($grid_query->posts) { echo ' tile-grid__item_hidden'; } ?>">
                        <p class="tile-grid__none text text_italic text_<?php echo $section['options']['layout_align-title']; ?>"><?php echo $f_none_text; ?></p>
                    </div>
                    <div class="tile-grid__item tile-grid__item_more tile-grid__item_full<?php if (!$grid_query->posts || $grid_query->max_num_pages == 1) { echo ' tile-grid__item_hidden'; } ?>">
                        <div class="tile-grid__more">
                            <div class="button-group button-group_<?php echo $section['options']['layout_align-title']; ?>">
                                <div class="button-group__item">
                                    <button type="button" class="tile-grid__more-button button c_bg_<?php color_id($theme, 3); ?> c_h_bg_<?php color_id($theme, 4); ?> c_color_<?php color_id($theme, 0); ?> c_h_color_<?php color_id($theme, 0); ?>">Load More</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <?php if ($section['type'] != 'all'): ?>
            <?php if ($section['cta']['text'] || $section['cta']['link']): ?>
            <p class="tile-grid__cta<?php if ($section['options']['layout_per-row'] == 4) { echo ' tile-grid__cta_wide'; } ?> text text_xs">
                <?php echo $section['cta']['text']; ?>
                <?php if ($section['cta']['link']): ?>
                <a href="<?php echo esc_url($section['cta']['link']['url']); ?>" target="<?php echo $section['cta']['link']['target']; ?>" class="arrow-link c_color_<?php color_id($theme, 5); ?> c_h_color_<?php color_id($theme, 4); ?>">
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
                        <a href="<?php echo esc_url($button['link']['url']); ?>" target="<?php echo $button['link']['target']; ?>" class="button c_bg_<?php color_id($theme, 3); ?> c_h_bg_<?php color_id($theme, 4); ?> c_color_<?php color_id($theme, 0); ?> c_h_color_<?php color_id($theme, 0); ?>"><?php echo $button['link']['title']; ?></a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
