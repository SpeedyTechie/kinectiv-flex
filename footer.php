<?php
/**
 * Theme footer
 */

?>

	</main><!-- #content -->

    <?php
    $f_nav = get_field('hf_footer_nav', 'option');
    $f_nav_2 = get_field('hf_footer_nav-2', 'option');
    $f_contact = get_field('hf_footer_contact', 'option');
    $f_social = get_field('hf_footer_social', 'option');
    $f_details = get_field('hf_footer_details', 'option');
    $f_options = get_field('hf_footer_options', 'option');
    
    $f_header_nav = get_field('hf_header_nav', 'option');
    $f_header_nav_2 = get_field('hf_header_nav-2', 'option');
    
    $nav_links = $f_nav['use-header'] ? $f_header_nav['links'] : $f_nav['links'];
    $nav_links_2 = $f_nav_2['use-header'] ? $f_header_nav_2['links'] : $f_nav_2['links'];
    
    
    $social_links = array();
    if ($f_social['facebook']) {
        $social_links['facebook'] = array(
            'title' => 'Facebook',
            'url' => $f_social['facebook']
        );
    }
    if ($f_social['instagram']) {
        $social_links['instagram'] = array(
            'title' => 'Instagram',
            'url' => $f_social['instagram']
        );
    }
    if ($f_social['twitter']) {
        $social_links['twitter'] = array(
            'title' => 'Twitter',
            'url' => $f_social['twitter']
        );
    }
    if ($f_social['youtube']) {
        $social_links['youtube'] = array(
            'title' => 'YouTube',
            'url' => $f_social['youtube']
        );
    }
    if ($f_social['linkedin']) {
        $social_links['linkedin'] = array(
            'title' => 'LinkedIn',
            'url' => $f_social['linkedin']
        );
    }

    $theme = 'main-dark';
    ?>
	<footer class="site-footer c_bg_<?php color_id($theme, 0); ?> c_color_<?php color_id($theme, 3); ?>">
        <div class="site-footer__main<?php if ($f_options['layout_variation'] == 'simple') { echo ' site-footer__main_y_sm'; } ?>">
            <div class="site-footer__content">
                <?php if ($f_options['layout_variation'] == 'standard'): ?>
                <div class="footer-1">
                    <div class="footer-1__col footer-1__col_details">
                        <div class="footer-1__section footer-1__section_logo">
                            <a href="<?php echo esc_url(home_url()); ?>" class="site-footer__home">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/logo.svg" width="158" height="31" alt="<?php bloginfo('name'); ?>" class="site-footer__logo" />
                            </a>
                        </div>
                        <?php if ($f_contact['address']['line-1'] || $f_contact['address']['line-2']): ?>
                        <div class="footer-1__section">
                            <?php if ($f_contact['address']['link']): ?>
                            <a href="<?php echo esc_url($f_contact['address']['link']); ?>" target="_blank" class="footer-1__link address c_color_<?php color_id($theme, 3); ?> c_h_color_<?php color_id($theme, 5); ?>">
                                <?php if ($f_contact['address']['line-1']) { ?><span class="address__line"><?php echo $f_contact['address']['line-1']; ?></span><?php } ?>
                                <?php if ($f_contact['address']['line-2']) { ?><span class="address__line"><?php echo $f_contact['address']['line-2']; ?></span><?php } ?>
                            </a>
                            <?php else: ?>
                            <p class="footer-1__text address">
                                <?php if ($f_contact['address']['line-1']) { ?><span class="address__line"><?php echo $f_contact['address']['line-1']; ?></span><?php } ?>
                                <?php if ($f_contact['address']['line-2']) { ?><span class="address__line"><?php echo $f_contact['address']['line-2']; ?></span><?php } ?>
                            </p>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                        <?php if ($f_contact['phone']): ?>
                        <div class="footer-1__section">
                            <a href="<?php echo esc_url('tel:' . $f_contact['phone']); ?>" class="footer-1__link c_color_<?php color_id($theme, 3); ?> c_h_color_<?php color_id($theme, 5); ?>"><?php echo $f_contact['phone']; ?></a>
                        </div>
                        <?php endif; ?>
                        <?php if ($f_contact['email']): ?>
                        <div class="footer-1__section">
                            <a href="<?php echo esc_url('mailto:' . $f_contact['email']); ?>" class="footer-1__link c_color_<?php color_id($theme, 3); ?> c_h_color_<?php color_id($theme, 5); ?>"><?php echo $f_contact['email']; ?></a>
                        </div>
                        <?php endif; ?>
                        <?php if ($social_links): ?>
                        <div class="footer-1__section footer-1__section_social">
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
                    <?php if ($nav_links): ?>
                    <div class="footer-1__col">
                        <nav class="site-nav site-nav_full site-nav_left c_color_<?php color_id($theme, 5); ?>">
                            <?php foreach ($nav_links as $item): ?>
                            <div class="site-nav__item site-nav__item_full">
                                <a href="<?php echo esc_url($item['link']['url']); ?>" target="<?php echo $item['link']['target']; ?>" class="site-nav__link site-nav__link_full-head c_color_<?php color_id($theme, 5); ?> c_h_color_<?php color_id($theme, 3); ?>"><?php echo $item['link']['title']; ?></a>
                                <?php if ($item['submenu']): ?>
                                <div class="site-nav__sub">
                                    <?php foreach ($item['submenu'] as $sub_item): ?>
                                    <div class="site-nav__sub-item">
                                        <a href="<?php echo esc_url($sub_item['link']['url']); ?>" target="<?php echo $sub_item['link']['target']; ?>" class="site-nav__link c_color_<?php color_id($theme, 3); ?> c_h_color_<?php color_id($theme, 5); ?>"><?php echo $sub_item['link']['title']; ?></a>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        </nav>
                    </div>
                    <?php endif; ?>
                </div>
                <?php elseif ($f_options['layout_variation'] == 'simple'): ?>
                <div class="footer-2">
                    <div class="footer-2__section footer-2__section_logo">
                        <a href="<?php echo esc_url(home_url()); ?>" class="site-footer__home site-footer__home_lg">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/logo.svg" width="158" height="31" alt="<?php bloginfo('name'); ?>" class="site-footer__logo" />
                        </a>
                    </div>
                    <?php if ($nav_links): ?>
                    <div class="footer-2__section">
                        <nav class="site-nav c_color_<?php color_id($theme, 5); ?>">
                            <?php foreach ($nav_links as $item): ?>
                            <div class="site-nav__item">
                                <a href="<?php echo esc_url($item['link']['url']); ?>" target="<?php echo $item['link']['target']; ?>" class="site-nav__link c_color_<?php color_id($theme, 5); ?> c_h_color_<?php color_id($theme, 3); ?>"><?php echo $item['link']['title']; ?></a>
                            </div>
                            <?php endforeach; ?>
                        </nav>
                    </div>
                    <?php endif; ?>
                    <?php if ($nav_links_2): ?>
                    <div class="footer-2__section">
                        <nav class="site-nav site-nav_sm site-nav_lines c_color_<?php color_id($theme, 3); ?>">
                            <?php foreach ($nav_links_2 as $item): ?>
                            <div class="site-nav__item site-nav__item_sm site-nav__item_line">
                                <a href="<?php echo esc_url($item['link']['url']); ?>" target="<?php echo $item['link']['target']; ?>" class="site-nav__link c_color_<?php color_id($theme, 3); ?> c_h_color_<?php color_id($theme, 5); ?>"><?php echo $item['link']['title']; ?></a>
                            </div>
                            <?php endforeach; ?>
                        </nav>
                    </div>
                    <?php endif; ?>
                    <?php if ($social_links): ?>
                    <div class="footer-2__section footer-2__section_social">
                        <div class="social social_center">
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
                <?php endif; ?>
            </div>
        </div>
        <div class="site-footer__bottom c_bg_<?php color_id($theme, 1); ?>">
            <div class="site-footer__content">
                <?php if ($f_options['layout_variation'] == 'standard'): ?>
                <div class="sub-footer-1">
                    <div class="sub-footer-1__item">
                        <p class="sub-footer-1__text">&copy; Copyright <?php echo kf_get_copyright_year(); if ($f_details['copyright']) { echo ' ' . $f_details['copyright']; } ?></p>
                    </div>
                    <?php if ($nav_links_2): ?>
                    <div class="sub-footer-1__item">
                        <nav class="site-nav site-nav_left site-nav_xs site-nav_lines c_color_<?php color_id($theme, 3); ?>">
                            <?php foreach ($nav_links_2 as $item): ?>
                            <div class="site-nav__item site-nav__item_xs site-nav__item_line">
                                <a href="<?php echo esc_url($item['link']['url']); ?>" target="<?php echo $item['link']['target']; ?>" class="site-nav__link c_color_<?php color_id($theme, 3); ?> c_h_color_<?php color_id($theme, 5); ?>"><?php echo $item['link']['title']; ?></a>
                            </div>
                            <?php endforeach; ?>
                        </nav>
                    </div>
                    <?php endif; ?>
                </div>
                <?php elseif ($f_options['layout_variation'] == 'simple'): ?>
                <div class="sub-footer-2">
                    <div class="sub-footer-2__item">
                        <p class="sub-footer-2__text">&copy; Copyright <?php echo kf_get_copyright_year(); if ($f_details['copyright']) { echo ' ' . $f_details['copyright']; } ?></p>
                    </div>
                    <?php if ($f_contact['phone']): ?>
                    <div class="sub-footer-2__item">
                        <a href="<?php echo esc_url('tel:' . $f_contact['phone']); ?>" class="sub-footer-2__link c_color_<?php color_id($theme, 3); ?> c_h_color_<?php color_id($theme, 5); ?>"><?php echo $f_contact['phone']; ?></a>
                    </div>
                    <?php endif; ?>
                    <?php if ($f_contact['email']): ?>
                    <div class="sub-footer-2__item">
                        <a href="<?php echo esc_url('mailto:' . $f_contact['email']); ?>" class="sub-footer-2__link c_color_<?php color_id($theme, 3); ?> c_h_color_<?php color_id($theme, 5); ?>"><?php echo $f_contact['email']; ?></a>
                    </div>
                    <?php endif; ?>
                    <?php if ($f_contact['address']['line-1'] || $f_contact['address']['line-2']): ?>
                    <div class="sub-footer-2__item">
                        <?php if ($f_contact['address']['link']): ?>
                        <a href="<?php echo esc_url($f_contact['address']['link']); ?>" target="_blank" class="sub-footer-2__link address c_color_<?php color_id($theme, 3); ?> c_h_color_<?php color_id($theme, 5); ?>">
                            <?php if ($f_contact['address']['line-1']) { ?><span class="address__line"><?php echo $f_contact['address']['line-1']; ?></span><?php } ?>
                            <?php if ($f_contact['address']['line-2']) { ?><span class="address__line"><?php echo $f_contact['address']['line-2']; ?></span><?php } ?>
                        </a>
                        <?php else: ?>
                        <p class="sub-footer-2__text address">
                            <?php if ($f_contact['address']['line-1']) { ?><span class="address__line"><?php echo $f_contact['address']['line-1']; ?></span><?php } ?>
                            <?php if ($f_contact['address']['line-2']) { ?><span class="address__line"><?php echo $f_contact['address']['line-2']; ?></span><?php } ?>
                        </p>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </footer>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
