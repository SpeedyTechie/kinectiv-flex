<?php
$passthrough = acf_get_store('passthrough_preview-event');
$pt_theme = $passthrough->get('theme');
$pt_tile_options = $passthrough->get('tile_options');


$f_image = get_field('event_preview_image');

$f_date = get_field('event_details_date');
$f_location = get_field('event_details_location');

$theme = ($pt_tile_options['layout_color'] == 'custom') ? $pt_tile_options['color_theme'] : $pt_theme;
$pt_tile_options['color_theme'] = $theme; // set theme for tile options (needed to generate bg styles)

$tile_bg_styles = kf_section_bg_styles($pt_tile_options, 1);
$tile_style = $tile_bg_styles['style'];
$tile_classes = $tile_bg_styles['classes'];

// create DateTime objects for event date(s) and time(s)
$date_dt = new DateTime($f_date['date'], wp_timezone());
$date_end_dt = $f_date['end-date'] ? new DateTime($f_date['end-date'], wp_timezone()) : null;
$time_dt = $f_date['time'] ? new DateTime($f_date['time'], wp_timezone()) : null;
$time_end_dt = $f_date['end-time'] ? new DateTime($f_date['end-time'], wp_timezone()) : null;

// create date string(s)
$date_string = null;
$date_end_string = null;
if ($date_end_dt) {
    $date_string = $date_dt->format(($date_dt->format('Y') == $date_end_dt->format('Y')) ? 'F j' : 'F j, Y') . ' - '; // include the year in the start date only if it differs from the end date's year
    $date_end_string = $date_end_dt->format('F j, Y');
} else {
    $date_string = $date_dt->format('F j, Y');
}

// create time string
$time_string = null;
if ($time_dt) {
    $time_string = $time_dt->format('g:ia');

    // if this is a single day event and there's an end time, add it to the string
    if (!$date_end_dt && $time_end_dt) {
        $time_string .= ' - ' . $time_end_dt->format('g:ia');
    }
}
?>

<a href="<?php echo get_permalink(); ?>" class="event-tile">
    <div class="event-tile__tile c_color_<?php color_id($theme, 5); ?><?php echo $tile_classes; ?>"<?php if ($tile_style) { echo ' style="' . trim($tile_style) . '"'; } ?>>
        <?php if ($f_image): ?>
        <div class="event-tile__image bg-image" role="img" aria-label="<?php echo esc_attr($f_image['alt']); ?>" style="background-image: url('<?php echo $f_image['url']; ?>');"></div>
        <div class="event-tile__overlay c_color_<?php color_id($theme, 1); ?>"></div>
        <?php endif; ?>
        <div class="event-tile__main<?php if ($f_image) { echo ' event-tile__main_image'; } ?>">
            <p class="event-tile__date text text_lg text_italic text_line_1-4">
                <time datetime="<?php echo $date_dt->format('Y-m-d'); if ($time_dt) { echo ' ' . $time_dt->format('H:i'); } ?>" class="event-tile__date-part"><?php echo $date_string; ?></time>
                <?php if ($date_end_string) { ?><time datetime="<?php echo $date_end_dt->format('Y-m-d'); ?>" class="event-tile__date-part"><?php echo $date_end_string; ?></time><?php } ?>
            </p>
            <h3 class="event-tile__title title title_sm"><?php echo get_the_title(); ?></h3>
            <?php if ($time_string || $f_location['name']): ?>
            <div class="event-tile__info">
                <?php if ($time_string): ?>
                <div class="event-tile__info-item">
                    <p class="event-tile__info-line text text_xs text_line_1-4">
                        <svg viewBox="0 0 17 17" class="event-tile__info-icon">
                            <title>Time</title>
                            <path class="c_fill_<?php color_id($theme, 5); ?>" d="M8.5,0A8.5,8.5,0,1,0,17,8.5,8.5,8.5,0,0,0,8.5,0m0,14.875A6.375,6.375,0,1,1,14.875,8.5,6.382,6.382,0,0,1,8.5,14.875"/>
                            <path class="c_fill_<?php color_id($theme, 5); ?>" d="M8.5,3.586a.66.66,0,00-.66.6l-.386,3.86-3.27.327a.664.664,0,000,1.322l5.1.533h.012a.4.4,0,00.4-.411l-.535-5.632a.66.66,0,00-.66-.6"/>
                        </svg>
                        <?php echo $time_string; ?>
                    </p>
                </div>
                <?php endif; ?>
                <?php if ($f_location['name']): ?>
                <div class="event-tile__info-item">
                    <p class="event-tile__info-line text text_xs text_line_1-4">
                        <svg viewBox="0 0 17 17" class="event-tile__info-icon">
                            <title>Location</title>
                            <path class="c_fill_<?php color_id($theme, 5); ?>" d="M8.501,0a6.124,6.124,0,00-6.376,6.375C2.125,9.563,8.501,17,8.501,17s6.374-7.437,6.374-10.625A6.124,6.124,0,008.501,0m0,8.5a2.125,2.125,0,112.124-2.125A2.125,2.125,0,018.5,8.5"/>
                        </svg>
                        <?php echo $f_location['name']; ?>
                    </p>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</a>