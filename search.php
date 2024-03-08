<?php
/**
 * The template for displaying search results
 */

get_header();
?>

<?php
$f_results_config = get_field('config_search_results', 'option');

$theme = 'main';
$tile_theme = 'main';

$search_bar_args = array(
    'theme' => $theme,
    'value' => get_search_query(),
    'variation' => 'small'
);
if ($f_results_config['bar']['title']) {
    $search_bar_args['title'] = $f_results_config['bar']['title'];
}
if ($f_results_config['bar']['placeholder']) {
    $search_bar_args['placeholder'] = $f_results_config['bar']['placeholder'];
}

$search_query = kf_search_query(get_search_query(false));

$result_count = $search_query->found_posts ? $search_query->found_posts : 'No';
?>
<div class="section c_bg_<?php color_id($theme, 0); ?> c_color_<?php color_id($theme, 5); ?>">
    <div class="section__content">
        <div class="search-results">
            <div class="search-results__top">
                <div class="search-results__top-col search-results__top-col_text">
                    <?php if ($f_results_config['title']) { ?><h1 class="search-results__title title title_md <?php echo component_colors($theme, 'title'); ?>"><?php echo $f_results_config['title']; ?></h1><?php } ?>
                    <p class="search-results__subtitle text text_lg text_line_1-4"><?php echo $result_count; ?> results for "<?php echo get_search_query(); ?>"</p>
                </div>
                <div class="search-results__top-col search-results__top-col_bar">
                    <?php get_template_part('template-parts/component', 'search', $search_bar_args); ?>
                </div>
            </div>
            <div class="search-results__list">
                <?php
                $tile_args = array(
                    'theme' => $tile_theme
                );
                ?>
                <div class="tile-grid">
                    <div class="tile-grid__wrap tile-grid__wrap_no-max-w" data-type="search" data-search="<?php echo get_search_query(); ?>" data-passthrough="<?php echo esc_attr(json_encode($tile_args, JSON_UNESCAPED_SLASHES)); ?>">
                        <div class="tile-grid__grid">
                            <?php if ($search_query->posts): ?>
                            <?php foreach ($search_query->posts as $p_post): ?>
                            <?php
                            global $post;
                            $post = get_post($p_post);
                            setup_postdata($p_post);
                            ?>
                            <div class="tile-grid__item tile-grid__item_full">
                                <?php get_template_part('template-parts/preview', 'search', $tile_args); ?>
                            </div>
                            <?php wp_reset_postdata(); ?>
                            <?php endforeach; ?>
                            <?php endif; ?>
                            <div class="tile-grid__item tile-grid__item_none tile-grid__item_full<?php if ($search_query->posts) { echo ' tile-grid__item_hidden'; } ?>">
                                <p class="tile-grid__none text text_italic text_center"><?php echo $f_results_config['none-text']; ?></p>
                            </div>
                            <div class="tile-grid__item tile-grid__item_more tile-grid__item_full<?php if (!$search_query->posts || $search_query->max_num_pages == 1) { echo ' tile-grid__item_hidden'; } ?>">
                                <div class="tile-grid__more">
                                    <div class="button-group button-group_center">
                                        <div class="button-group__item">
                                            <button type="button" class="tile-grid__more-button button <?php component_colors($theme, 'button'); ?>">Load More</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();
