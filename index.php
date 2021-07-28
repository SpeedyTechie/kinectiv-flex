<?php
/**
 * The main template file
 */

get_header();
?>

<?php if (post_password_required()): ?>

<?php
$theme = 'main';
?>
<div class="section c_color_<?php color_id($theme, 5); ?>">
    <div class="section__content">
        <?php echo get_the_password_form(); ?>
    </div>
</div>

<?php else: ?>

<?php get_template_part('template-parts/content', 'sections'); ?>

<?php endif; ?>

<?php
get_footer();
