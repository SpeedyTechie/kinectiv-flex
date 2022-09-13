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

$section_id = $section['options']['advanced_id'] ? 'section-' . $section['options']['advanced_id'] : null;

// build style attribute and class list
$bg_styles = kf_section_bg_styles($section['options']);
$padding_styles = kf_section_padding_styles($section['options'], $section_prev['options'], $section_next['options']);
$section_style = $bg_styles['style'] . $padding_styles['style'];
$section_classes = $bg_styles['classes'] . $padding_styles['classes'];
?>

<div <?php if ($section_id) { echo 'id="' . $section_id . '" '; } ?>class="section<?php echo $section_classes; ?>"<?php if ($section_style) { echo ' style="' . trim($section_style) . '"'; } ?>>
    <div class="full-image">
        <?php if ($section['options']['layout_height'] == 'auto'): ?>
        <img src="<?php echo $section['image']['url']; ?>" alt="<?php echo esc_attr($section['image']['alt']); ?>" class="full-image__image full-image__image_auto" />
        <?php else: ?>
        <?php
        $bg_image_styles = kf_advanced_bg_image_styles($section['options']['layout_advanced-image']);
        ?>
        <div class="full-image__image full-image__image_<?php echo $section['options']['layout_height']; ?><?php echo $bg_image_styles['classes']; ?>" role="img" aria-label="<?php echo esc_attr($section['image']['alt']); ?>" style="background-image: url('<?php echo $section['image']['url']; ?>');<?php echo $bg_image_styles['style']; ?>"></div>
        <?php endif; ?>
    </div>
</div>
