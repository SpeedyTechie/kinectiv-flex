<?php
/**
 * The main template file
 */

get_header();
?>

<?php if (!is_404() && post_password_required()): ?>

<?php
$theme = 'main';
?>
<div class="section c_color_<?php color_id($theme, 5); ?>">
    <div class="section__content">
        <?php echo get_the_password_form(); ?>
    </div>
</div>

<?php else: ?>

<?php
$f_sections = get_field('page_content_sections');
if (is_404()) {
    $f_sections = get_field('404_content_sections', 'option'); // get sections for 404 page
}

$content_args = array(
    'sections' => $f_sections
);

get_template_part('template-parts/content', 'sections', $content_args);
?>

<?php endif; ?>

<?php
get_footer();
