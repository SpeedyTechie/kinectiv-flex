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

// build style attribute and class list
$bg_styles = kf_section_bg_styles($section['options']);
$padding_styles = kf_section_padding_styles($section['options'], $section_prev['options'], $section_next['options']);
$section_style = $bg_styles['style'] . $padding_styles['style'];
$section_classes = $bg_styles['classes'] . $padding_styles['classes'];

// build array of social links
$social_links = array();
if ($section['info']['social']['links']['facebook']) {
    $social_links['facebook'] = array(
        'title' => 'Facebook',
        'url' => $section['info']['social']['links']['facebook']
    );
}
if ($section['info']['social']['links']['instagram']) {
    $social_links['instagram'] = array(
        'title' => 'Instagram',
        'url' => $section['info']['social']['links']['instagram']
    );
}
if ($section['info']['social']['links']['twitter']) {
    $social_links['twitter'] = array(
        'title' => 'Twitter',
        'url' => $section['info']['social']['links']['twitter']
    );
}
if ($section['info']['social']['links']['youtube']) {
    $social_links['youtube'] = array(
        'title' => 'YouTube',
        'url' => $section['info']['social']['links']['youtube']
    );
}
if ($section['info']['social']['links']['linkedin']) {
    $social_links['linkedin'] = array(
        'title' => 'LinkedIn',
        'url' => $section['info']['social']['links']['linkedin']
    );
}

// conditional variables
$c_info = $section['info']['addresses'] || $section['info']['hours'] || $section['info']['phones'] || $section['info']['emails'] || $section['info']['social']['title'] || $social_links;
?>

<div class="section c_color_<?php color_id($theme, 5); ?><?php echo $section_classes; ?>"<?php if ($section_style) { echo ' style="' . trim($section_style) . '"'; } ?>>
    <div class="section__content">
        <div class="contact-info">
            <?php if ($section['title']) { ?><h2 class="contact-info__title title title_<?php echo $section['options']['layout_align-title']; ?>"><?php echo $section['title']; ?></h2><?php } ?>
            <?php if ($section['text']) { ?><div class="contact-info__text contact-info__text_<?php echo $section['options']['layout_align-title']; ?> text text_md text_wrap text_compact"><?php echo kf_wysiwyg_color_classes($section['text'], $theme); ?></div><?php } ?>
            <?php if ($section['form']['title'] || $section['form']['form'] || $section['info']['title'] || $section['info']['markers'] || $c_info): ?>
            <div class="contact-info__columns">
                <?php if ($section['form']['title'] || $section['form']['form']): ?>
                <div class="contact-info__col contact-info__col_form">
                    <?php if ($section['form']['title']) { ?><h2 class="contact-info__subtitle title title_sm title_<?php echo $section['options']['layout_align-column']; ?>"><?php echo $section['form']['title']; ?></h2><?php } ?>
                    <?php if ($section['form']['form']) { ?><div class="contact-info__form"><?php kf_show_gform($section['form']['form'], $theme); ?></div><?php } ?>
                </div>
                <?php endif; ?>
                <?php if ($section['info']['title'] || $section['info']['markers'] || $c_info): ?>
                <div class="contact-info__col contact-info__col_info<?php if ($section['info']['markers'] && $c_info) { echo ' contact-info__col_w_full'; } ?>">
                    <?php if ($section['info']['title']) { ?><h2 class="contact-info__subtitle title title_sm title_<?php echo $section['options']['layout_align-column']; ?>"><?php echo $section['info']['title']; ?></h2><?php } ?>
                    <?php if ($section['info']['markers'] || $c_info): ?>
                    <div class="contact-info__boxes<?php if (!$section['form']['title'] && !$section['form']['form']) { echo ' contact-info__boxes_full'; } ?>">
                        <?php if ($section['info']['markers']): ?>
                        <div class="contact-info__box">
                            <div class="contact-info__map g-map">
                                <div class="g-map__canvas"></div>
                                <div class="g-map__data" data-zoom="<?php echo $section['options']['layout_map']['map_zoom']; ?>" data-fit="<?php echo ($section['options']['layout_map']['map_fit']) ? 'true' : 'false'; ?>">
                                    <?php foreach ($section['info']['markers'] as $marker): ?>
                                    <div class="g-map__marker" data-loc="<?php echo $marker['location']['lat'] . ',' . $marker['location']['lng']; ?>" <?php if ($marker['link']) { echo ' data-link="' . $marker['link'] . '"'; } ?>><?php echo $marker['label']; ?></div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if ($c_info): ?>
                        <div class="contact-info__box">
                            <div class="contact-info__info c_color_<?php color_id($theme, 3); ?> c_bg_<?php color_id($theme, 1); ?>">
                                <?php if ($section['info']['addresses']): ?>
                                <div class="contact-info__info-section">
                                    <svg viewBox="0 0 200 200" class="contact-info__info-icon">
                                        <title>Location</title>
                                        <path class="c_fill_<?php color_id($theme, 5); ?>" d="M98.68,5.84c-47.36,0-71,35.52-71,71s71,118.38,71,118.38,71-82.87,71-118.38S146,5.84,98.68,5.84Zm0,94.71a23.68,23.68,0,1,1,23.67-23.67A23.68,23.68,0,0,1,98.68,100.55Z"/>
                                    </svg>
                                    <?php foreach ($section['info']['addresses'] as $address_row): ?>
                                    <p class="contact-info__info-row text text_line_1-4">
                                        <?php if ($address_row['label']) { ?><strong class="contact-info__info-label"><?php echo $address_row['label']; ?></strong><br /><?php } ?>
                                        <?php if ($address_row['link']): ?>
                                        <a href="<?php echo esc_url($address_row['link']); ?>" class="contact-info__info-detail c_color_<?php color_id($theme, 3); ?> c_h_color_<?php color_id($theme, 5); ?>">
                                            <span><?php echo $address_row['line-1']; ?></span><br />
                                            <span><?php echo $address_row['line-2']; ?></span>
                                        </a>
                                        <?php else: ?>
                                        <span class="contact-info__info-detail">
                                            <span><?php echo $address_row['line-1']; ?></span><br />
                                            <span><?php echo $address_row['line-2']; ?></span>
                                        </span>
                                        <?php endif; ?>
                                    </p>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>
                                <?php if ($section['info']['hours']): ?>
                                <div class="contact-info__info-section">
                                    <svg viewBox="0 0 200 200" class="contact-info__info-icon">
                                        <title>Hours</title>
                                        <path class="c_fill_<?php color_id($theme, 5); ?>" d="M98.68,5.84a94.71,94.71,0,1,0,94.71,94.71A94.7,94.7,0,0,0,98.68,5.84Zm0,165.75a71,71,0,1,1,71-71A71.12,71.12,0,0,1,98.68,171.59Z"/>
                                        <path class="c_fill_<?php color_id($theme, 5); ?>" d="M98.68,45.8a7.37,7.37,0,0,0-7.36,6.66l-4.3,43L50.58,99.11a7.4,7.4,0,0,0,0,14.73l56.84,5.94h.13a4.47,4.47,0,0,0,4.44-4.58l-6-62.75A7.37,7.37,0,0,0,98.68,45.8Z"/>
                                    </svg>
                                    <?php foreach ($section['info']['hours'] as $hours_row): ?>
                                    <p class="contact-info__info-row text text_line_1-4">
                                        <strong class="contact-info__info-label"><?php echo $hours_row['days']; ?></strong>
                                        <span class="contact-info__info-detail"><?php echo $hours_row['hours']; ?></span>
                                    </p>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>
                                <?php if ($section['info']['phones']): ?>
                                <div class="contact-info__info-section">
                                    <svg viewBox="0 0 200 200" class="contact-info__info-icon">
                                        <title>Phone</title>
                                        <path class="c_fill_<?php color_id($theme, 5); ?>" d="M178.46,181.79a47.75,47.75,0,0,1-15.26,9.72,49.79,49.79,0,0,1-12.09,3.15,70.33,70.33,0,0,1-8.46.6c-29.05.32-62.2-16.54-92.29-46.4C20.51,118.79,3.63,85.62,4,56.58a69.89,69.89,0,0,1,.59-8.45A51.19,51.19,0,0,1,7.7,36a48.14,48.14,0,0,1,9.74-15.26l9.86-9.93a16.74,16.74,0,0,1,11.25-5A14.87,14.87,0,0,1,49.81,10.2l28.05,28c5.85,5.85,5.52,16.31-.65,22.5L58.63,79.31c.34.64.71,1.29,1.06,2,4.89,8.93,11.55,21.15,24.34,33.93s25,19.45,33.92,24.34l2,1.07L138.48,122c6.2-6.18,16.65-6.5,22.51-.66l28,28a15,15,0,0,1,4.35,11.26,16.7,16.7,0,0,1-5,11.23Z"/>
                                    </svg>
                                    <?php foreach ($section['info']['phones'] as $phone_row): ?>
                                    <p class="contact-info__info-row text text_line_1-4">
                                        <?php if ($phone_row['label']) { ?><strong class="contact-info__info-label"><?php echo $phone_row['label']; ?></strong><?php } ?>
                                        <a href="<?php echo esc_url('tel:' . $phone_row['phone']); ?>" class="contact-info__info-detail c_color_<?php color_id($theme, 3); ?> c_h_color_<?php color_id($theme, 5); ?>"><?php echo $phone_row['phone']; ?></a>
                                    </p>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>
                                <?php if ($section['info']['emails']): ?>
                                <div class="contact-info__info-section">
                                    <svg viewBox="0 0 200 200" class="contact-info__info-icon">
                                        <title>Email</title>
                                        <path class="c_fill_<?php color_id($theme, 5); ?>" d="M185.12,7.19,8.53,101.79c-6,3.22-6.1,8.65-.19,12.06l31.59,18.24,88.34-55.21L71.19,139c-4.42,4.78-8,14-8,20.53v29.6c0,6.51,3.77,8.07,8.37,3.47l26.81-26.81,37.36,21.58c5.91,3.41,12.14.79,13.84-5.81L193,13.34C194.66,6.74,191.14,4,185.12,7.19Z"/>
                                    </svg>
                                    <?php foreach ($section['info']['emails'] as $email_row): ?>
                                    <p class="contact-info__info-row text text_line_1-4">
                                        <?php if ($email_row['label']) { ?><strong class="contact-info__info-label"><?php echo $email_row['label']; ?></strong><?php } ?>
                                        <a href="<?php echo esc_url('mailto:' . $email_row['email']); ?>" class="contact-info__info-detail c_color_<?php color_id($theme, 3); ?> c_h_color_<?php color_id($theme, 5); ?>"><?php echo $email_row['email']; ?></a>
                                    </p>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>
                                <?php if ($section['info']['social']['title'] || $social_links): ?>
                                <div class="contact-info__info-section">
                                    <svg viewBox="0 0 200 200" class="contact-info__info-icon">
                                        <title>Social</title>
                                        <path class="c_fill_<?php color_id($theme, 5); ?>" d="M98.68,5.84a94.71,94.71,0,1,0,94.71,94.71A94.71,94.71,0,0,0,98.68,5.84Zm70.73,88.8H145.85c-.95-23.6-7.52-44.47-17.42-58.49A71.12,71.12,0,0,1,169.41,94.64ZM92.76,30.73V94.64H63.32C64.78,60.72,78.58,36.22,92.76,30.73Zm0,75.74v63.91c-14.18-5.49-28-30-29.44-63.91Zm11.84,63.91V106.47H134C132.55,140.39,118.77,164.89,104.6,170.38Zm0-75.74V30.73c14.17,5.49,28,30,29.43,63.91ZM68.89,36.15C59,50.17,52.43,71,51.48,94.64H27.94A71.05,71.05,0,0,1,68.89,36.15ZM27.94,106.47H51.48c1,23.6,7.52,44.47,17.41,58.49A71.07,71.07,0,0,1,27.94,106.47ZM128.43,165c9.9-14,16.47-34.89,17.42-58.49h23.56A71.14,71.14,0,0,1,128.43,165Z"/>
                                    </svg>
                                    <div class="contact-info__info-row">
                                        <?php if ($section['info']['social']['title']) { ?><p class="contact-info__info-label contact-info__info-label_social text text_line_1-4"><?php echo $section['info']['social']['title']; ?></p><?php } ?>
                                        <?php if ($social_links): ?>
                                        <div class="contact-info__info-social">
                                            <div class="social">
                                                <?php foreach ($social_links as $site => $link): ?>
                                                <div class="social__item">
                                                    <a href="<?php echo esc_url($link['url']); ?>" target="_blank" class="social__link c_h-parent">
                                                        <span class="screen-reader-text"><?php echo $link['title']; ?></span>
                                                        <?php if ($site == 'facebook'): ?>
                                                        <svg viewBox="0 0 16.23 16.23" class="social__icon social__icon_facebook">
                                                            <path class="social__icon-fill c_fill_<?php color_id($theme, 3); ?> c_h-child_fill_<?php color_id($theme, 5); ?>" d="M15.33,0H.9A.9.9,0,0,0,0,.9H0V15.33a.9.9,0,0,0,.9.9H8.67V9.94H6.56V7.49H8.67V5.69a3,3,0,0,1,2.64-3.23,2.9,2.9,0,0,1,.51,0,17.09,17.09,0,0,1,1.89.1V4.74h-1.3c-1,0-1.22.48-1.22,1.19V7.5h2.43L13.3,9.94H11.19v6.29h4.14a.91.91,0,0,0,.9-.9h0V.9A.9.9,0,0,0,15.33,0Z"/>
                                                        </svg>
                                                        <?php elseif ($site == 'instagram'): ?>
                                                        <svg viewBox="0 0 17.34 17.34" class="social__icon social__icon_instagram">
                                                            <path class="social__icon-fill c_fill_<?php color_id($theme, 3); ?> c_h-child_fill_<?php color_id($theme, 5); ?>" d="M8.67,1.56c2.32,0,2.59,0,3.51.05a4.93,4.93,0,0,1,1.61.3,2.9,2.9,0,0,1,1.64,1.65,4.46,4.46,0,0,1,.3,1.61c0,.92.06,1.19.06,3.51s0,2.59-.06,3.5a4.42,4.42,0,0,1-.3,1.61,2.87,2.87,0,0,1-1.64,1.65,4.93,4.93,0,0,1-1.61.3c-.92,0-1.19,0-3.51,0s-2.59,0-3.5,0a4.89,4.89,0,0,1-1.61-.3,2.86,2.86,0,0,1-1.65-1.65,4.89,4.89,0,0,1-.3-1.61c0-.91-.05-1.19-.05-3.5s0-2.59.05-3.51a4.93,4.93,0,0,1,.3-1.61A2.87,2.87,0,0,1,3.56,1.92a4.42,4.42,0,0,1,1.61-.3c.91-.05,1.19-.06,3.5-.06M8.67,0C6.32,0,6,0,5.1.05A6.58,6.58,0,0,0,3,.45,4.44,4.44,0,0,0,.45,3,6.63,6.63,0,0,0,.05,5.1C0,6,0,6.32,0,8.67s0,2.66.05,3.58a6.63,6.63,0,0,0,.4,2.11A4.43,4.43,0,0,0,3,16.89a6.08,6.08,0,0,0,2.11.4c.92.05,1.22.06,3.57.06s2.65,0,3.58-.06a6,6,0,0,0,2.1-.4,4.43,4.43,0,0,0,2.54-2.53,6.37,6.37,0,0,0,.4-2.11c0-.92.05-1.22.05-3.58s0-2.65-.05-3.57A6.37,6.37,0,0,0,16.89,3,4.47,4.47,0,0,0,14.35.45a6.52,6.52,0,0,0-2.1-.4C11.33,0,11,0,8.67,0Z"/>
                                                            <path class="social__icon-fill c_fill_<?php color_id($theme, 3); ?> c_h-child_fill_<?php color_id($theme, 5); ?>" d="M8.67,4.22a4.46,4.46,0,1,0,4.45,4.45A4.45,4.45,0,0,0,8.67,4.22Zm0,7.35a2.9,2.9,0,1,1,2.89-2.9h0A2.89,2.89,0,0,1,8.67,11.57Z"/>
                                                            <circle class="social__icon-fill c_fill_<?php color_id($theme, 3); ?> c_h-child_fill_<?php color_id($theme, 5); ?>" cx="13.3" cy="4.04" r="1.04"/>
                                                        </svg>
                                                        <?php elseif ($site == 'twitter'): ?>
                                                        <svg viewBox="0 0 21.7 17.63" class="social__icon social__icon_twitter">
                                                            <path class="social__icon-fill c_fill_<?php color_id($theme, 3); ?> c_h-child_fill_<?php color_id($theme, 5); ?>" d="M6.82,17.63A12.59,12.59,0,0,0,19.49,5.13V5c0-.19,0-.39,0-.58a9,9,0,0,0,2.22-2.3,8.77,8.77,0,0,1-2.56.7,4.44,4.44,0,0,0,2-2.46A8.92,8.92,0,0,1,18.27,1.4a4.45,4.45,0,0,0-7.58,4.06A12.65,12.65,0,0,1,1.51.81,4.45,4.45,0,0,0,2.89,6.75a4.46,4.46,0,0,1-2-.55v0a4.46,4.46,0,0,0,3.57,4.37,4.4,4.4,0,0,1-2,.07,4.47,4.47,0,0,0,4.16,3.1,9,9,0,0,1-5.53,1.9A9.42,9.42,0,0,1,0,15.63a12.6,12.6,0,0,0,6.82,2"/>
                                                        </svg>
                                                        <?php elseif ($site == 'youtube'): ?>
                                                        <svg viewBox="0 0 18.48 13" class="social__icon social__icon_youtube">
                                                            <path class="social__icon-fill c_fill_<?php color_id($theme, 3); ?> c_h-child_fill_<?php color_id($theme, 5); ?>" d="M18.3,2.8A4,4,0,0,0,17.56,1,2.6,2.6,0,0,0,15.71.19C13.13,0,9.25,0,9.25,0h0S5.36,0,2.77.19A2.6,2.6,0,0,0,.92,1,4,4,0,0,0,.18,2.8,28.86,28.86,0,0,0,0,5.8V7.2a28.79,28.79,0,0,0,.18,3A4.07,4.07,0,0,0,.92,12a3.15,3.15,0,0,0,2,.8C4.44,13,9.24,13,9.24,13s3.89,0,6.47-.19A2.67,2.67,0,0,0,17.56,12a4,4,0,0,0,.74-1.83,28.79,28.79,0,0,0,.18-3V5.8a28.86,28.86,0,0,0-.18-3Zm-11,6.1V3.7l5,2.61Z"/>
                                                        </svg>
                                                        <?php elseif ($site == 'linkedin'): ?>
                                                        <svg viewBox="0 0 16.21 16.4" class="social__icon social__icon_linkedin">
                                                            <path class="social__icon-fill c_fill_<?php color_id($theme, 3); ?> c_h-child_fill_<?php color_id($theme, 5); ?>" d="M14.57,0H1.63A1.64,1.64,0,0,0,0,1.64V14.76A1.64,1.64,0,0,0,1.63,16.4H14.57a1.64,1.64,0,0,0,1.64-1.64h0V1.64A1.64,1.64,0,0,0,14.57,0ZM5.2,13H3V6H5.2ZM4.1,5.1A1.09,1.09,0,1,1,5.28,4,1.08,1.08,0,0,1,4.2,5.1ZM13.17,13H11V9.09c0-.9-.32-1.52-1.1-1.52a1.18,1.18,0,0,0-1.12.81,1.49,1.49,0,0,0-.07.55V13H6.52V8.2c0-.88,0-1.61-.06-2.24H8.35l.1,1h0A2.53,2.53,0,0,1,10.66,5.8c1.43,0,2.51,1,2.51,3Z"/>
                                                        </svg>
                                                        <?php endif; ?>
                                                    </a>
                                                </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
