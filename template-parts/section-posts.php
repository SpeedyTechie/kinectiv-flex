<?php
// get passthrough data
$passthrough = acf_get_store('passthrough_section');
$pt_sections = $passthrough->get('sections');


$i_section = $pt_sections['current_index'];
$section = $pt_sections['current'];
$section_prev = $pt_sections['prev'];
$section_next = $pt_sections['next'];

$f_none_text = get_field('config_posts_none-text', 'option');

$theme = $section['options']['color_theme'];

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
            <?php if ($section['type'] == 'featured' && $section['featured-posts']): ?>
            <div class="tile-grid__wrap">
                <div class="tile-grid__grid tile-grid__grid_<?php echo $section['options']['layout_align-tiles']; ?>">
                    <?php foreach ($section['featured-posts'] as $p_post): ?>
                    <?php
                    global $post;
                    $post = get_post($p_post);
                    setup_postdata($p_post);
                    
                    acf_register_store('passthrough_preview-post', array(
                        'theme' => $theme,
                        'tile_options' => $section['options']['layout_tile-options']
                    ));
                    ?>
                    <div class="tile-grid__item tile-grid__item_3">
                        <?php get_template_part('template-parts/preview', 'post'); ?>
                    </div>
                    <?php
                    acf_register_store('passthrough_preview-post', array());

                    wp_reset_postdata();
                    ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php elseif ($section['type'] == 'recent'): ?>
            <?php
            // make sure post count is valid (if not, default to 3)
            if (!$section['recent-posts'] || $section['recent-posts'] < 1) {
                $section['recent-posts'] = 3;
            }
            
            $grid_query = kf_custom_query('post', $section['recent-posts']);
            ?>
            <div class="tile-grid__wrap">
                <div class="tile-grid__grid tile-grid__grid_<?php echo $section['options']['layout_align-tiles']; ?>">
                    <?php if ($grid_query->posts): ?>
                    <?php foreach ($grid_query->posts as $p_post): ?>
                    <?php
                    global $post;
                    $post = get_post($p_post);
                    setup_postdata($p_post);
                    
                    acf_register_store('passthrough_preview-post', array(
                        'theme' => $theme,
                        'tile_options' => $section['options']['layout_tile-options']
                    ));
                    ?>
                    <div class="tile-grid__item tile-grid__item_3">
                        <?php get_template_part('template-parts/preview', 'post'); ?>
                    </div>
                    <?php
                    acf_register_store('passthrough_preview-post', array());

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
            <?php elseif ($section['type'] == 'all'): ?>
            <?php
            $grid_query = kf_custom_query('post');
            
            $passthrough_data = array(
                'theme' => $theme,
                'tile_options' => $section['options']['layout_tile-options']
            );
            ?>
            <div class="tile-grid__wrap" data-type="post" data-passthrough="<?php echo esc_attr(json_encode($passthrough_data, JSON_UNESCAPED_SLASHES)); ?>">
                <div class="tile-grid__grid tile-grid__grid_<?php echo $section['options']['layout_align-tiles']; ?>">
                    <?php if ($grid_query->posts): ?>
                    <?php foreach ($grid_query->posts as $p_post): ?>
                    <?php
                    global $post;
                    $post = get_post($p_post);
                    setup_postdata($p_post);
                    
                    acf_register_store('passthrough_preview-post', $passthrough_data);
                    ?>
                    <div class="tile-grid__item tile-grid__item_3">
                        <?php get_template_part('template-parts/preview', 'post'); ?>
                    </div>
                    <?php
                    acf_register_store('passthrough_preview-post', array());

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
