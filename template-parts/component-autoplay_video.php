<?php
$default_args = array(
    'image' => null,
    'video' => null,
    'advanced' => array()
);
$args = array_merge($default_args, $args);

$image_styles = kf_advanced_bg_image_styles($args['advanced']);
?>
<div class="autoplay-video" style="<?php if ($args['advanced']['video_color']) { echo 'background-color: ' . $args['advanced']['video_color'] . ';'; } ?>">
    <?php if ($args['image']) { ?><div class="autoplay-video__image autoplay-video__image_disabled<?php if (!$args['video']) { echo ' autoplay-video__image_no-video'; } echo $image_styles['classes']; ?>" style="background-image: url('<?php echo $args['image']['url']; ?>');<?php echo $image_styles['style']; ?>" role="img" aria-label="<?php echo esc_attr($args['image']['alt']); ?>"></div><?php } ?>
    <?php if ($args['video']) { ?><video src="<?php echo $args['video']['url']; ?>" autoplay playsinline loop muted disableRemotePlayback<?php if ($args['advanced']['video_poster']) { ?> poster="<?php echo $args['advanced']['video_poster']['url'] ?>"<?php } ?> class="autoplay-video__video"></video><?php } ?>
</div>
