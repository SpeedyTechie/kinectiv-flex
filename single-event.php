<?php
/**
 * The template for displaying single events
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

<?php
$f_description = get_field('event_content_description');
$f_buttons = get_field('event_content_buttons');
$f_image = get_field('event_content_image');
$f_sections = get_field('event_content_sections');

$f_date = get_field('event_details_date');
$f_location = get_field('event_details_location');

$f_events_page = get_field('config_events_page', 'option');

$theme = 'main';


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

// set the bg color for the intro
$bg_color = 0;
$bg_color_2 = 1;

// determine if intro bottom padding should be removed
$no_bottom = false;
if ($f_sections) {
    // check if bg color of intro and first content section match
    if (kf_get_bg_color($f_sections[0]['options']) == kf_hex_3_to_6(kf_color_id_list()[color_id($theme, $bg_color, true)])) {
        $first_padding = ($f_sections[0]['options']['padding_vertical'] == 'separate') ? $f_sections[0]['options']['padding_top'] : $f_sections[0]['options']['padding_vertical']; // get top padding of first content section
        
        // check if there is first section padding and padding collapse is enabled
        if ($first_padding != 'none' && $f_sections[0]['options']['padding_collapse-top']) {
            $no_bottom = true; // remove bottom padding
        }
    }
}

// determine if secondary date should be displayed
$date_2_display = true;
if (!$time_string && !$f_description) $date_2_display = false;
?>
<div class="section section_y_sm<?php if ($no_bottom) { echo ' section_bottom_none'; } ?> c_bg_<?php color_id($theme, $bg_color); ?> c_color_<?php color_id($theme, 5); ?>">
    <div class="section__content section__content_x_sm">
        <div class="event-intro">
            <div class="event-intro__item">
                <div class="event-intro__block event-intro__block_text c_bg_<?php color_id($theme, $bg_color_2); ?>">
                    <div class="event-intro__content">
                        <h1 class="event-intro__title title title_md"><?php echo get_the_title(); ?></h1>
                        <p class="event-intro__date text text_xl text_italic text_line_1-4">
                            <time datetime="<?php echo $date_dt->format('Y-m-d'); if ($time_dt) { echo ' ' . $time_dt->format('H:i'); } ?>" class="event-intro__date-part"><?php echo $date_string; ?></time>
                            <?php if ($date_end_string) { ?><time datetime="<?php echo $date_end_dt->format('Y-m-d'); ?>" class="event-intro__date-part"><?php echo $date_end_string; ?></time><?php } ?>
                        </p>
                        <?php if ($f_description) { ?><div class="event-intro__text text text_wrap text_compact"><?php echo kf_wysiwyg_color_classes($f_description, $theme); ?></div><?php } ?>
                        <?php if ($date_2_display || $f_location['name'] || $f_location['address']['line-1'] || $f_location['address']['line-2']): ?>
                        <div class="event-intro__info">
                            <div class="event-intro__info-cols">
                                <?php if ($date_2_display): ?>
                                <div class="event-intro__info-item">
                                    <p class="event-intro__info-group text text_xs text_line_1-4">
                                        <svg viewBox="0 0 17 17" class="event-intro__info-icon">
                                            <path class="c_fill_<?php color_id($theme, 5); ?>" d="M8.5,0A8.5,8.5,0,1,0,17,8.5,8.5,8.5,0,0,0,8.5,0m0,14.875A6.375,6.375,0,1,1,14.875,8.5,6.382,6.382,0,0,1,8.5,14.875"/>
                                            <path class="c_fill_<?php color_id($theme, 5); ?>" d="M8.5,3.586a.66.66,0,00-.66.6l-.386,3.86-3.27.327a.664.664,0,000,1.322l5.1.533h.012a.4.4,0,00.4-.411l-.535-5.632a.66.66,0,00-.66-.6"/>
                                        </svg>
                                        <?php if ($f_description): // only show date here if there's a description (otherwise it's redundant) ?>
                                        <span class="event-intro__info-line">
                                            <time datetime="<?php echo $date_dt->format('Y-m-d'); if ($time_dt) { echo ' ' . $time_dt->format('H:i'); } ?>" class="event-intro__info-part"><?php echo $date_string; ?></time>
                                            <?php if ($date_end_string) { ?><time datetime="<?php echo $date_end_dt->format('Y-m-d'); ?>" class="event-intro__info-part"><?php echo $date_end_string; ?></time><?php } ?>
                                        </span>
                                        <?php endif; ?>
                                        <?php if ($time_string) { ?><span class="event-intro__info-line"><?php echo $time_string; ?></span><?php } ?>
                                    </p>
                                </div>
                                <?php endif; ?>
                                <?php if ($f_location['name'] || $f_location['address']['line-1'] || $f_location['address']['line-2']): ?>
                                <div class="event-intro__info-item">
                                    <p class="event-intro__info-group text text_xs text_line_1-4">
                                        <svg viewBox="0 0 17 17" class="event-intro__info-icon">
                                            <path class="c_fill_<?php color_id($theme, 5); ?>" d="M8.501,0a6.124,6.124,0,00-6.376,6.375C2.125,9.563,8.501,17,8.501,17s6.374-7.437,6.374-10.625A6.124,6.124,0,008.501,0m0,8.5a2.125,2.125,0,112.124-2.125A2.125,2.125,0,018.5,8.5"/>
                                        </svg>
                                        <?php if ($f_location['address']['link']) { ?><a href="<?php echo esc_url($f_location['address']['link']); ?>" target="_blank" class="event-intro__info-link c_color_<?php echo color_id($theme, 5); ?> c_h_color_<?php echo color_id($theme, 4); ?>"><?php } ?>
                                            <?php if ($f_location['name']) { ?><span class="event-intro__info-line"><?php echo $f_location['name']; ?></span><?php } ?>
                                            <?php if ($f_location['address']['line-1']) { ?><span class="event-intro__info-line"><?php echo $f_location['address']['line-1']; ?></span><?php } ?>
                                            <?php if ($f_location['address']['line-2']) { ?><span class="event-intro__info-line"><?php echo $f_location['address']['line-2']; ?></span><?php } ?>
                                        <?php if ($f_location['address']['link']) { ?></a><?php } ?>
                                    </p>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if ($f_buttons): ?>
                        <div class="event-intro__buttons">
                            <div class="button-group button-group_left">
                                <?php foreach ($f_buttons as $button): ?>
                                <div class="button-group__item">
                                    <a href="<?php echo esc_url($button['link']['url']); ?>" target="<?php echo $button['link']['target']; ?>" class="button <?php component_colors($theme, 'button'); ?>"><?php echo $button['link']['title']; ?></a>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php if ($f_image): ?>
            <div class="event-intro__item">
                <div class="event-intro__block event-intro__block_image">
                    <div class="event-intro__image bg-image" role="img" aria-label="<?php echo esc_attr($f_image['alt']); ?>" style="background-image: url('<?php echo $f_image['url']; ?>');"></div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$f_sections = get_field('event_content_sections');

$content_args = array(
    'sections' => $f_sections
);

get_template_part('template-parts/content', 'sections', $content_args);
?>

<?php endif; ?>

<?php
get_footer();
