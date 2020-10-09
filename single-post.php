<?php
/**
 * The template for displaying single posts
 */

get_header();
?>

<?php
$f_image = get_field('post_content_image');
$f_content = get_field('post_content_sections');

$f_posts_page = get_field('config_posts_page', 'option');

$theme = 'main';


// set the bg color for the intro
$bg_color = 1;

// determine if intro bottom padding should be removed
$no_bottom = false;
if ($f_content) {
    // check if bg color of intro and first content section match
    if (kf_get_bg_color($f_content[0]['options']) == kf_hex_3_to_6(kf_color_id_list()[color_id($theme, $bg_color, true)])) {
        $first_padding = ($f_content[0]['options']['padding_vertical'] == 'separate') ? $f_content[0]['options']['padding_top'] : $f_content[0]['options']['padding_vertical']; // get top padding of first content section
        
        // check if there is first section padding and padding collapse is enabled
        if ($first_padding != 'none' && $f_content[0]['options']['padding_collapse-top']) {
            $no_bottom = true; // remove bottom padding
        }
    }
}
?>
<div class="section section_y_sm<?php if ($no_bottom) { echo ' section_bottom_none'; } ?> c_bg_<?php color_id($theme, $bg_color); ?> c_color_<?php color_id($theme, 5); ?>">
    <div class="section__content section__content_x_sm">
        <div class="post-intro">
            <?php if ($f_image) { ?><img src="<?php echo $f_image['url']; ?>" alt="<?php echo esc_attr($f_image['alt']); ?>" width="<?php echo $f_image['width']; ?>" height="<?php echo $f_image['height']; ?>" class="post-intro__image" /><?php } ?>
            <div class="post-intro__main">
                <div class="post-intro__col post-intro__col_main">
                    <h1 class="post-intro__title title title_md"><?php echo get_the_title(); ?></h1>
                    <div class="post-intro__info c_color_<?php color_id($theme, 3); ?>">
                        <p class="post-intro__info-item text text_lg text_line_1-4"><time datetime="<?php echo get_the_date('Y-m-d'); ?>"><?php echo get_the_date(); ?></time></p>
                        <p class="post-intro__info-item text text_lg text_line_1-4">By <?php echo get_the_author_meta('display_name', get_post()->post_author); ?></p>
                    </div>
                </div>
                <?php if ($f_posts_page): ?>
                <div class="post-intro__col post-intro__col_side">
                    <a href="<?php echo get_permalink($f_posts_page->ID); ?>" class="button c_bg_<?php color_id($theme, 3); ?> c_h_bg_<?php color_id($theme, 4); ?> c_color_<?php color_id($theme, 0); ?> c_h_color_<?php color_id($theme, 0); ?>">Back to Blog</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php get_template_part('template-parts/content', 'sections'); ?>

<?php
get_footer();
