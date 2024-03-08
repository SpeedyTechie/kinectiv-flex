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

$f_none_text = get_field('config_posts_none-text', 'option');

$theme = $section['options']['color_theme'];

$section_id = $section['options']['advanced_id'] ? 'section-' . $section['options']['advanced_id'] : null;

// build style attribute and class list
$bg_styles = kf_section_bg_styles($section['options']);
$padding_styles = kf_section_padding_styles($section['options'], $section_prev ? $section_prev['options'] : null, $section_next ? $section_next['options'] : null);;
$section_style = $bg_styles['style'] . $padding_styles['style'];
$section_classes = $bg_styles['classes'] . $padding_styles['classes'];

// get class list for button colors
$button_color_classes = kf_button_color_classes($theme, $section['options']['color_buttons']);
?>

<div <?php if ($section_id) { echo 'id="' . $section_id . '" '; } ?>class="section c_color_<?php color_id($theme, 5); ?><?php echo $section_classes; ?>"<?php if ($section_style) { echo ' style="' . trim($section_style) . '"'; } ?>>
    <div class="section__content">
        <div class="tile-grid">
            <?php if ($section['title']) { ?><h2 class="tile-grid__title title title_<?php echo $section['options']['layout_align-title']; ?> <?php echo component_colors($theme, 'title'); ?>"><?php echo $section['title']; ?></h2><?php } ?>
            <?php if ($section['type'] == 'featured' && $section['featured-posts']): ?>
            <div class="tile-grid__wrap">
                <div class="tile-grid__grid tile-grid__grid_<?php echo $section['options']['layout_align-tiles']; ?>">
                    <?php foreach ($section['featured-posts'] as $p_post): ?>
                    <?php
                    global $post;
                    $post = get_post($p_post);
                    setup_postdata($p_post);
                    
                    $tile_args = array(
                        'theme' => $theme,
                        'tile_options' => $section['options']['layout_tile-options']
                    );
                    ?>
                    <div class="tile-grid__item tile-grid__item_3">
                        <?php get_template_part('template-parts/preview', 'post', $tile_args); ?>
                    </div>
                    <?php wp_reset_postdata(); ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php elseif ($section['type'] == 'recent'): ?>
            <?php
            // make sure post count is valid (if not, default to 3)
            if (!$section['recent-posts'] || $section['recent-posts'] < 1) {
                $section['recent-posts'] = 3;
            }
            
            $grid_query = kf_custom_query('post', $section['tags'], $section['recent-posts']);
            ?>
            <div class="tile-grid__wrap">
                <div class="tile-grid__grid tile-grid__grid_<?php echo $section['options']['layout_align-tiles']; ?>">
                    <?php if ($grid_query->posts): ?>
                    <?php foreach ($grid_query->posts as $p_post): ?>
                    <?php
                    global $post;
                    $post = get_post($p_post);
                    setup_postdata($p_post);
                    
                    $tile_args = array(
                        'theme' => $theme,
                        'tile_options' => $section['options']['layout_tile-options']
                    );
                    ?>
                    <div class="tile-grid__item tile-grid__item_3">
                        <?php get_template_part('template-parts/preview', 'post', $tile_args); ?>
                    </div>
                    <?php wp_reset_postdata(); ?>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <div class="tile-grid__item tile-grid__item_full">
                        <p class="tile-grid__none text text_italic text_<?php echo $section['options']['layout_align-title']; ?>"><?php echo $f_none_text; ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php elseif ($section['type'] == 'all'): ?>
            <?php
            $grid_query = kf_custom_query('post', $section['tags']);
            
            $tile_args = array(
                'theme' => $theme,
                'tile_options' => $section['options']['layout_tile-options']
            );
            ?>
            <div class="tile-grid__wrap" data-type="post" data-tags="<?php echo $section['tags'] ? implode(',', $section['tags']) : ''; ?>" data-passthrough="<?php echo esc_attr(json_encode($tile_args, JSON_UNESCAPED_SLASHES)); ?>">
                <div class="tile-grid__grid tile-grid__grid_<?php echo $section['options']['layout_align-tiles']; ?>">
                    <?php if ($grid_query->posts): ?>
                    <?php foreach ($grid_query->posts as $p_post): ?>
                    <?php
                    global $post;
                    $post = get_post($p_post);
                    setup_postdata($p_post);
                    ?>
                    <div class="tile-grid__item tile-grid__item_3">
                        <?php get_template_part('template-parts/preview', 'post', $tile_args); ?>
                    </div>
                    <?php wp_reset_postdata(); ?>
                    <?php endforeach; ?>
                    <?php endif; ?>
                    <div class="tile-grid__item tile-grid__item_none tile-grid__item_full<?php if ($grid_query->posts) { echo ' tile-grid__item_hidden'; } ?>">
                        <p class="tile-grid__none text text_italic text_<?php echo $section['options']['layout_align-title']; ?>"><?php echo $f_none_text; ?></p>
                    </div>
                    <div class="tile-grid__item tile-grid__item_more tile-grid__item_full<?php if (!$grid_query->posts || $grid_query->max_num_pages == 1) { echo ' tile-grid__item_hidden'; } ?>">
                        <div class="tile-grid__more">
                            <div class="button-group button-group_<?php echo $section['options']['layout_align-title']; ?>">
                                <div class="button-group__item">
                                    <button type="button" class="tile-grid__more-button button <?php echo $button_color_classes; ?>">Load More</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <?php if ($section['type'] != 'all'): ?>
            <?php if ($section['cta']['text'] || $section['cta']['link']): ?>
            <p class="tile-grid__cta tile-grid__cta_<?php echo $section['options']['layout_align-cta']; ?> text text_xs">
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
                        <a href="<?php echo esc_url($button['link']['url']); ?>" target="<?php echo $button['link']['target']; ?>" class="button <?php echo $button_color_classes; ?>"><?php echo $button['link']['title']; ?></a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
