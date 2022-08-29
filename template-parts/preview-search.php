<?php
$default_args = array(
    'theme' => 'main'
);
$args = array_merge($default_args, $args);


$post_type = get_post_type();
$post_type_labels = array(
    'page' => 'Page',
    'post' => 'Post',
    'event' => 'Event'
);

$theme = $args['theme'];

$excerpt = strip_tags(get_the_excerpt(), '<strong>');
if ($post_type == 'post' && !$excerpt) {
    $excerpt = get_field('post_preview_description');
}
if (post_password_required()) {
    $excerpt = 'A password is required to view this content.';
}

$detail = '';
if ($post_type == 'post') {
    $detail = '<time datetime="' . get_the_date('Y-m-d') . '">' . get_the_date() . '</time>';
}

if ($post_type == 'event') {
    $f_date = get_field('event_details_date');
    $f_location = get_field('event_details_location');

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
}
?>

<a href="<?php echo get_permalink(); ?>" class="result-tile">
    <div class="result-tile__tile c_color_<?php color_id($theme, 5); ?> c_bg_<?php color_id($theme, 1); ?>">
        <div class="result-tile__top">
            <p class="result-tile__type text text_xs text_line_1-4 c_color_<?php color_id($theme, 0); ?> c_bg_<?php color_id($theme, 5); ?>">
                <?php if ($post_type == 'page'): ?>
                <svg viewBox="0 0 200 200" class="result-tile__type-icon">
                    <path class="c_fill_<?php color_id($theme, 0); ?>" d="M98.68,5.84a94.71,94.71,0,1,0,94.71,94.71A94.71,94.71,0,0,0,98.68,5.84Zm70.73,88.8H145.85c-.95-23.6-7.52-44.47-17.42-58.49A71.12,71.12,0,0,1,169.41,94.64ZM92.76,30.73V94.64H63.32C64.78,60.72,78.58,36.22,92.76,30.73Zm0,75.74v63.91c-14.18-5.49-28-30-29.44-63.91Zm11.84,63.91V106.47H134C132.55,140.39,118.77,164.89,104.6,170.38Zm0-75.74V30.73c14.17,5.49,28,30,29.43,63.91ZM68.89,36.15C59,50.17,52.43,71,51.48,94.64H27.94A71.05,71.05,0,0,1,68.89,36.15ZM27.94,106.47H51.48c1,23.6,7.52,44.47,17.41,58.49A71.07,71.07,0,0,1,27.94,106.47ZM128.43,165c9.9-14,16.47-34.89,17.42-58.49h23.56A71.14,71.14,0,0,1,128.43,165Z"/>
                </svg>
                <?php elseif ($post_type == 'post'): ?>
                <svg viewBox="0 0 19.83 19.83" class="result-tile__type-icon">
                    <path class="c_fill_<?php color_id($theme, 0); ?>" d="M19.83,0H3.72V15.49a.62.62,0,0,1-.62.62h0a.62.62,0,0,1-.62-.62h0V3.72A1.24,1.24,0,1,0,0,3.72v12.4a3.72,3.72,0,0,0,3.72,3.71H16.11a3.72,3.72,0,0,0,3.72-3.72Zm-3.1,16.11H6.82a.62.62,0,0,1-.62-.62h0a.61.61,0,0,1,.61-.62h9.92a.62.62,0,0,1,.62.62h0a.62.62,0,0,1-.62.62Zm0-2.48H6.82A.61.61,0,0,1,6.2,13h0a.61.61,0,0,1,.61-.62h9.92a.62.62,0,0,1,.62.62h0a.62.62,0,0,1-.62.62Zm.62-5H6.2v-5H17.36Z"/>
                </svg>
                <?php elseif ($post_type == 'event'): ?>
                <svg viewBox="0 0 16.76 18.06" class="result-tile__type-icon">
                    <path class="c_fill_<?php color_id($theme, 0); ?>" d="M16.75,4.91V3.71a1.2,1.2,0,0,0-1.2-1.2h-1.2V1.31A1.2,1.2,0,0,0,12,1.09a.81.81,0,0,0,0,.22v1.2H4.79V1.31A1.2,1.2,0,0,0,3.7,0,1.18,1.18,0,0,0,2.4,1.09a.81.81,0,0,0,0,.22v1.2H1.2A1.2,1.2,0,0,0,0,3.71v1.2Z"/>
                    <path class="c_fill_<?php color_id($theme, 0); ?>" d="M0,6.1V16.86a1.2,1.2,0,0,0,1.2,1.2H15.56a1.2,1.2,0,0,0,1.2-1.2V6.1ZM4.8,15.67H2.41V13.28H4.8Zm0-4.78H2.41V8.5H4.8Zm4.78,4.78H7.19V13.28H9.58Zm0-4.78H7.19V8.5H9.58Zm4.78,4.78H12V13.28h2.39Zm0-4.78H12V8.5h2.39Z"/>
                </svg>
                <?php endif; ?>
                <span class="result-tile__type-text"><?php echo $post_type_labels[$post_type]; ?></span>
            </p>
        </div>
        <div class="result-tile__content">
            <?php if ($post_type == 'event'): ?>
            <p class="result-tile__event-date text text_lg text_italic text_line_1-4">
                <time datetime="<?php echo $date_dt->format('Y-m-d'); if ($time_dt) { echo ' ' . $time_dt->format('H:i'); } ?>" class="result-tile__event-date-part"><?php echo $date_string; ?></time>
                <?php if ($date_end_string) { ?><time datetime="<?php echo $date_end_dt->format('Y-m-d'); ?>" class="result-tile__event-date-part"><?php echo $date_end_string; ?></time><?php } ?>
            </p>
            <?php endif; ?>
            <h3 class="result-tile__title title title_sm"><?php echo get_the_title(); ?></h3>
            <?php if ($detail) { ?><p class="result-tile__detail text text_xs text_italic text_line_1-4"><?php echo $detail; ?></p><?php } ?>
            <?php if ($post_type == 'event' && ($time_string || $f_location['name'])): ?>
            <div class="result-tile__event-info">
                <div class="event-info">
                    <?php if ($time_string): ?>
                    <div class="event-info__item">
                        <p class="event-info__line text text_xs text_line_1-4">
                            <svg viewBox="0 0 17 17" class="event-info__icon">
                                <title>Time</title>
                                <path class="c_fill_<?php color_id($theme, 5); ?>" d="M8.5,0A8.5,8.5,0,1,0,17,8.5,8.5,8.5,0,0,0,8.5,0m0,14.875A6.375,6.375,0,1,1,14.875,8.5,6.382,6.382,0,0,1,8.5,14.875"/>
                                <path class="c_fill_<?php color_id($theme, 5); ?>" d="M8.5,3.586a.66.66,0,00-.66.6l-.386,3.86-3.27.327a.664.664,0,000,1.322l5.1.533h.012a.4.4,0,00.4-.411l-.535-5.632a.66.66,0,00-.66-.6"/>
                            </svg>
                            <?php echo $time_string; ?>
                        </p>
                    </div>
                    <?php endif; ?>
                    <?php if ($f_location['name']): ?>
                    <div class="event-info__item">
                        <p class="event-info__line text text_xs text_line_1-4">
                            <svg viewBox="0 0 17 17" class="event-info__icon">
                                <title>Location</title>
                                <path class="c_fill_<?php color_id($theme, 5); ?>" d="M8.501,0a6.124,6.124,0,00-6.376,6.375C2.125,9.563,8.501,17,8.501,17s6.374-7.437,6.374-10.625A6.124,6.124,0,008.501,0m0,8.5a2.125,2.125,0,112.124-2.125A2.125,2.125,0,018.5,8.5"/>
                            </svg>
                            <?php echo $f_location['name']; ?>
                        </p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
            <?php if ($excerpt) { ?><p class="result-tile__text text"><?php echo $excerpt; ?></p><?php } ?>
        </div>
    </div>
</a>
