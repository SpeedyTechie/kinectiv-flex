<?php
/**
 * Theme header
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">

	<?php wp_head(); ?>

    <?php
    $f_image_default = get_field('config_sharing_image', 'option');
    $f_sharing_twitter = get_field('config_sharing_twitter', 'option');
    
    $f_image = get_field('sharing_custom_image');
    $f_description = get_field('sharing_custom_description');
    
    // get sharing image
    $meta_image = null;
    if ($f_image) {
        $meta_image = $f_image;
    } else {
        if (is_page()) {
            if (get_page_template_slug() == '') {
                $mf_content_sections = get_field('page_content_sections');

                $meta_image = kf_flex_sharing_image($mf_content_sections);
            }
        } elseif (is_singular('post')) {
            $mf_content_image = get_field('post_content_image');
            $mf_content_sections = get_field('post_content_sections');

            if ($mf_content_image) {
                $meta_image = $mf_content_image;
            } else {
                $meta_image = kf_flex_sharing_image($mf_content_sections);
            }
        } elseif (is_singular('event')) {
            $mf_content_image = get_field('event_content_image');
            $mf_content_sections = get_field('event_content_sections');

            if ($mf_content_image) {
                $meta_image = $mf_content_image;
            } else {
                $meta_image = kf_flex_sharing_image($mf_content_sections);
            }
        } elseif (is_404()) {
            $mf_content_sections = get_field('404_content_sections', 'option');

            $meta_image = kf_flex_sharing_image($mf_content_sections);
        }
        
        if (!$meta_image) {
            $meta_image = $f_image_default;
        }
    }
    
    // get sharing description
    $meta_description = '';
    if ($f_description) {
        $meta_description = $f_description;
    } else {
        if (is_page()) {
            if (get_page_template_slug() == '') {
                $mf_content_sections = get_field('page_content_sections');

                $meta_description = kf_flex_sharing_desc($mf_content_sections);
            }
        } elseif (is_singular('post')) {
            $mf_preview_description = get_field('post_preview_description');
            $mf_content_sections = get_field('post_content_sections');

            if ($mf_preview_description) {
                $meta_description = $mf_preview_description;
            } else {
                $meta_description = kf_flex_sharing_desc($mf_content_sections);
            }
        } elseif (is_singular('event')) {
            $mf_content_description = get_field('event_content_description');
            $mf_content_sections = get_field('event_content_sections');

            if ($mf_content_description) {
                $meta_description = $mf_content_description;
            } else {
                $meta_description = kf_flex_sharing_desc($mf_content_sections);
            }
        } elseif (is_404()) {
            $mf_content_sections = get_field('404_content_sections', 'option');

            $meta_description = kf_flex_sharing_desc($mf_content_sections);
        }
    }
    $meta_description = trim(strip_tags($meta_description));
    if (mb_strlen($meta_description) > 500) {
        $meta_description = mb_substr($meta_description, 0, 500) . '...';
    }

    // clear meta image/description if password protected
    if (!is_404() && post_password_required()) {
        $meta_image = $f_image_default;
        $meta_description = 'A password is required to view this content.';
    }
    
    $canonical_url = get_permalink();
    if (is_404()) {
        $canonical_url = '';
    }
    ?>
    
    <?php if ($meta_description) { ?><meta name="description" content="<?php echo esc_attr($meta_description); ?>" /><?php } ?>
    
    <meta property="og:title" content="<?php echo esc_attr(wp_get_document_title()); ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="<?php echo $canonical_url; ?>" />
    <?php if ($meta_image): ?>
    <meta property="og:image" content="<?php echo $meta_image['url']; ?>" />
    <meta property="og:image:alt" content="<?php echo esc_attr($meta_image['alt']); ?>" />
    <meta property="og:image:type" content="<?php echo $meta_image['mime_type']; ?>" />
    <meta property="og:image:width" content="<?php echo $meta_image['width']; ?>" />
    <meta property="og:image:height" content="<?php echo $meta_image['height']; ?>" />
    <?php endif; ?>
    <meta property="og:description" content="<?php echo esc_attr($meta_description); ?>" />
    <meta property="og:site_name" content="<?php esc_attr(bloginfo('name')); ?>" />
    
    <?php if ($f_sharing_twitter): ?>
    <meta name="twitter:card" content="<?php if ($meta_image) { echo 'summary_large_image'; } else { echo 'summary'; } ?>" />
    <meta name="twitter:site" content="@<?php echo esc_attr($f_sharing_twitter); ?>" />
    <meta name="twitter:title" content="<?php echo esc_attr(wp_get_document_title()); ?>" />
    <meta name="twitter:description" content="<?php echo esc_attr($meta_description); ?>" />
    <?php if ($meta_image): ?>
    <meta name="twitter:image" content="<?php echo $meta_image['url']; ?>" />
    <meta name="twitter:image:alt" content="<?php echo esc_attr($meta_image['alt']); ?>" />
    <?php endif; ?>
    <?php endif; ?>
    
    <script> </script><!-- to fix Chrome bug https://bugs.chromium.org/p/chromium/issues/detail?id=332189 -->
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
    
    <?php
    $f_breadcrumbs = get_field('hf_header_breadcrumbs', 'option');
    $f_nav = get_field('hf_header_nav', 'option');
    $f_nav_2 = get_field('hf_header_nav-2', 'option');
    $f_social = get_field('hf_header_social', 'option');
    $f_options = get_field('hf_header_options', 'option');

    $f_footer_contact = get_field('hf_footer_contact', 'option');
    $f_footer_social = get_field('hf_footer_social', 'option');
    $f_footer_details = get_field('hf_footer_details', 'option');

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

    $footer_social_links = array();
    if ($f_footer_social['facebook']) {
        $footer_social_links['facebook'] = array(
            'title' => 'Facebook',
            'url' => $f_footer_social['facebook']
        );
    }
    if ($f_footer_social['instagram']) {
        $footer_social_links['instagram'] = array(
            'title' => 'Instagram',
            'url' => $f_footer_social['instagram']
        );
    }
    if ($f_footer_social['twitter']) {
        $footer_social_links['twitter'] = array(
            'title' => 'Twitter',
            'url' => $f_footer_social['twitter']
        );
    }
    if ($f_footer_social['youtube']) {
        $footer_social_links['youtube'] = array(
            'title' => 'YouTube',
            'url' => $f_footer_social['youtube']
        );
    }
    if ($f_footer_social['linkedin']) {
        $footer_social_links['linkedin'] = array(
            'title' => 'LinkedIn',
            'url' => $f_footer_social['linkedin']
        );
    }

    $c_mobile_menu = $f_nav['links'] || $f_nav['button'] || $f_nav_2['links'];
    
    $theme = 'main-dark';
    $menu_theme = 'main';

    // get class list for button colors
    $button_color_classes = kf_button_color_classes($theme, $f_options['color_buttons']);
    $menu_button_color_classes = kf_button_color_classes($menu_theme, $f_options['color_buttons']);
    ?>
	<header class="site-header c_bg_<?php color_id($theme, 1); ?> c_color_<?php color_id($theme, 5); ?>">
        <?php if ($f_options['layout_nav-location'] == 'top' && ($f_nav_2['links'] || $social_links)): ?>
        <div class="site-header__secondary c_bg_<?php color_id($theme, 0); ?>">
            <div class="site-header__content">
                <div class="sub-header-1">
                    <?php if ($f_nav_2['links']): ?>
                    <div class="sub-header-1__item">
                        <nav class="site-nav site-nav_right site-nav_xs site-nav_lines c_color_<?php color_id($theme, 3); ?>">
                            <?php foreach ($f_nav_2['links'] as $item): ?>
                            <div class="site-nav__item site-nav__item_xs site-nav__item_line">
                                <a href="<?php echo esc_url($item['link']['url']); ?>" target="<?php echo $item['link']['target']; ?>" class="site-nav__link c_color_<?php color_id($theme, 3); ?> c_h_color_<?php color_id($theme, 5); ?>"><?php echo $item['link']['title']; ?></a>
                            </div>
                            <?php endforeach; ?>
                        </nav>
                    </div>
                    <?php endif; ?>
                    <?php if ($social_links): ?>
                    <div class="sub-header-1__item">
                        <div class="social social_right">
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
        </div>
        <?php endif; ?>
        <div class="site-header__main<?php if ($f_breadcrumbs) { echo ' site-header__main_breadcrumbs'; } ?>">
            <div class="site-header__content">
                <div class="header-1">
                    <div class="header-1__main">
                        <div class="header-1__col header-1__col_logo">
                            <a href="<?php echo esc_url(home_url()); ?>" class="site-header__home">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/logo.svg" width="158" height="31" alt="<?php bloginfo('name'); ?>" class="site-header__logo" />
                            </a>
                        </div>
                        <?php if ($f_nav['links'] || $f_nav['button']): ?>
                        <div class="header-1__col header-1__col_nav">
                            <nav class="site-nav site-nav_right c_color_<?php color_id($theme, 5); ?>">
                                <?php foreach ($f_nav['links'] as $item): ?>
                                <div class="site-nav__item">
                                    <a href="<?php echo esc_url($item['link']['url']); ?>" target="<?php echo $item['link']['target']; ?>" class="site-nav__link c_color_<?php color_id($theme, 5); ?> c_h_color_<?php color_id($theme, 3); ?>"><?php echo $item['link']['title']; ?></a>
                                </div>
                                <?php endforeach; ?>
                                <?php if ($f_nav['button']): ?>
                                <div class="site-nav__item">
                                    <a href="<?php echo esc_url($f_nav['button']['url']); ?>" target="<?php echo $f_nav['button']['target']; ?>" class="button <?php echo $button_color_classes; ?>"><?php echo $f_nav['button']['title']; ?></a>
                                </div>
                                <?php endif; ?>
                            </nav>
                        </div>
                        <?php endif; ?>
                        <?php if ($c_mobile_menu): ?>
                        <div class="header-1__col header-1__col_button">
                            <button type="button" class="hamburger-button" aria-controls="mobile-menu" aria-expanded="false">
                                <span class="screen-reader-text">Menu</span>
                                <div class="hamburger-button__line c_bg_<?php color_id($theme, 5); ?>"></div>
                                <div class="hamburger-button__line c_bg_<?php color_id($theme, 5); ?>"></div>
                                <div class="hamburger-button__line c_bg_<?php color_id($theme, 5); ?>"></div>
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php if ($f_breadcrumbs): ?>
                    <?php
                    $ancestors = kf_get_ancestors(get_the_ID());
                    ?>
                    <div class="header-1__breadcrumbs">
                        <div class="breadcrumbs">
                            <?php foreach ($ancestors as $anc_url => $anc_title): ?>
                            <div class="breadcrumbs__item">
                                <a href="<?php echo esc_url($anc_url); ?>" class="breadcrumbs__link c_color_<?php color_id($theme, 5); ?> c_h_color_<?php color_id($theme, 3); ?>"><?php echo $anc_title; ?></a>
                            </div>
                            <?php endforeach; ?>
                            <div class="breadcrumbs__item breadcrumbs__item_title">
                                <span class="breadcrumbs__title"><?php echo is_404() ? 'Page not found' : get_the_title(); ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php if ($f_options['layout_nav-location'] == 'bottom' && ($f_nav_2['links'] || $social_links)): ?>
        <div class="site-header__secondary c_bg_<?php color_id($theme, 0); ?>">
            <div class="site-header__content">
                <div class="sub-header-1">
                    <?php if ($f_nav_2['links']): ?>
                    <div class="sub-header-1__item">
                        <nav class="site-nav site-nav_right site-nav_xs site-nav_lines c_color_<?php color_id($theme, 3); ?>">
                            <?php foreach ($f_nav_2['links'] as $item): ?>
                            <div class="site-nav__item site-nav__item_xs site-nav__item_line">
                                <a href="<?php echo esc_url($item['link']['url']); ?>" target="<?php echo $item['link']['target']; ?>" class="site-nav__link c_color_<?php color_id($theme, 3); ?> c_h_color_<?php color_id($theme, 5); ?>"><?php echo $item['link']['title']; ?></a>
                            </div>
                            <?php endforeach; ?>
                        </nav>
                    </div>
                    <?php endif; ?>
                    <?php if ($social_links): ?>
                    <div class="sub-header-1__item">
                        <div class="social social_right">
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
        </div>
        <?php endif; ?>
    </header>
    <?php if ($c_mobile_menu): ?>
    <div id="mobile-menu" class="mobile-menu c_bg_<?php color_id($menu_theme, 0); ?> c_color_<?php color_id($menu_theme, 3); ?>">
        <div class="mobile-menu__top">
            <div class="header-1">
                <div class="header-1__main">
                    <div class="header-1__col header-1__col_logo">
                        <a href="<?php echo esc_url(home_url()); ?>" class="site-header__home">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/logo-dark.svg" width="158" height="31" alt="<?php bloginfo('name'); ?>" class="site-header__logo" />
                        </a>
                    </div>
                    <div class="header-1__col header-1__col_button">
                    <button type="button" class="mobile-menu__close" aria-controls="mobile-menu" aria-expanded="false">
                        <span class="screen-reader-text">Menu</span>
                        <div class="mobile-menu__close-line c_bg_<?php color_id($menu_theme, 5); ?>"></div>
                        <div class="mobile-menu__close-line mobile-menu__close-line_alt c_bg_<?php color_id($menu_theme, 5); ?>"></div>
                    </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="mobile-menu__main">
            <div class="mobile-menu__content">
                <div class="section section_top_sm">
                    <div class="section__content">
                        <div class="footer-2">
                            <?php if ($f_nav['links'] || $f_nav['button']): ?>
                            <div class="footer-2__section">
                                <nav class="site-nav c_color_<?php color_id($menu_theme, 5); ?>">
                                    <?php foreach ($f_nav['links'] as $item): ?>
                                    <div class="site-nav__item">
                                        <a href="<?php echo esc_url($item['link']['url']); ?>" target="<?php echo $item['link']['target']; ?>" class="site-nav__link c_color_<?php color_id($menu_theme, 5); ?> c_h_color_<?php color_id($menu_theme, 3); ?>"><?php echo $item['link']['title']; ?></a>
                                    </div>
                                    <?php endforeach; ?>
                                    <?php if ($f_nav['button']): ?>
                                    <div class="site-nav__item">
                                        <a href="<?php echo esc_url($f_nav['button']['url']); ?>" target="<?php echo $f_nav['button']['target']; ?>" class="button <?php echo $menu_button_color_classes; ?>"><?php echo $f_nav['button']['title']; ?></a>
                                    </div>
                                    <?php endif; ?>
                                </nav>
                            </div>
                            <?php endif; ?>
                            <?php if ($f_nav_2['links']): ?>
                            <div class="footer-2__section">
                                <nav class="site-nav site-nav_sm site-nav_lines c_color_<?php color_id($menu_theme, 3); ?>">
                                    <?php foreach ($f_nav_2['links'] as $item): ?>
                                    <div class="site-nav__item site-nav__item_sm site-nav__item_line">
                                        <a href="<?php echo esc_url($item['link']['url']); ?>" target="<?php echo $item['link']['target']; ?>" class="site-nav__link c_color_<?php color_id($menu_theme, 3); ?> c_h_color_<?php color_id($menu_theme, 5); ?>"><?php echo $item['link']['title']; ?></a>
                                    </div>
                                    <?php endforeach; ?>
                                </nav>
                            </div>
                            <?php endif; ?>
                            <?php if ($footer_social_links): ?>
                            <div class="footer-2__section footer-2__section_social">
                                <div class="social social_center">
                                    <?php foreach ($footer_social_links as $site => $link): ?>
                                    <div class="social__item">
                                        <a href="<?php echo esc_url($link['url']); ?>" target="_blank" class="social__link c_h-parent">
                                            <span class="screen-reader-text"><?php echo $link['title']; ?></span>
                                            <?php if ($site == 'facebook'): ?>
                                            <svg viewBox="0 0 16.23 16.23" class="social__icon social__icon_facebook">
                                                <path class="social__icon-fill c_fill_<?php color_id($menu_theme, 3); ?> c_h-child_fill_<?php color_id($menu_theme, 5); ?>" d="M15.33,0H.9A.9.9,0,0,0,0,.9H0V15.33a.9.9,0,0,0,.9.9H8.67V9.94H6.56V7.49H8.67V5.69a3,3,0,0,1,2.64-3.23,2.9,2.9,0,0,1,.51,0,17.09,17.09,0,0,1,1.89.1V4.74h-1.3c-1,0-1.22.48-1.22,1.19V7.5h2.43L13.3,9.94H11.19v6.29h4.14a.91.91,0,0,0,.9-.9h0V.9A.9.9,0,0,0,15.33,0Z"/>
                                            </svg>
                                            <?php elseif ($site == 'instagram'): ?>
                                            <svg viewBox="0 0 17.34 17.34" class="social__icon social__icon_instagram">
                                                <path class="social__icon-fill c_fill_<?php color_id($menu_theme, 3); ?> c_h-child_fill_<?php color_id($menu_theme, 5); ?>" d="M8.67,1.56c2.32,0,2.59,0,3.51.05a4.93,4.93,0,0,1,1.61.3,2.9,2.9,0,0,1,1.64,1.65,4.46,4.46,0,0,1,.3,1.61c0,.92.06,1.19.06,3.51s0,2.59-.06,3.5a4.42,4.42,0,0,1-.3,1.61,2.87,2.87,0,0,1-1.64,1.65,4.93,4.93,0,0,1-1.61.3c-.92,0-1.19,0-3.51,0s-2.59,0-3.5,0a4.89,4.89,0,0,1-1.61-.3,2.86,2.86,0,0,1-1.65-1.65,4.89,4.89,0,0,1-.3-1.61c0-.91-.05-1.19-.05-3.5s0-2.59.05-3.51a4.93,4.93,0,0,1,.3-1.61A2.87,2.87,0,0,1,3.56,1.92a4.42,4.42,0,0,1,1.61-.3c.91-.05,1.19-.06,3.5-.06M8.67,0C6.32,0,6,0,5.1.05A6.58,6.58,0,0,0,3,.45,4.44,4.44,0,0,0,.45,3,6.63,6.63,0,0,0,.05,5.1C0,6,0,6.32,0,8.67s0,2.66.05,3.58a6.63,6.63,0,0,0,.4,2.11A4.43,4.43,0,0,0,3,16.89a6.08,6.08,0,0,0,2.11.4c.92.05,1.22.06,3.57.06s2.65,0,3.58-.06a6,6,0,0,0,2.1-.4,4.43,4.43,0,0,0,2.54-2.53,6.37,6.37,0,0,0,.4-2.11c0-.92.05-1.22.05-3.58s0-2.65-.05-3.57A6.37,6.37,0,0,0,16.89,3,4.47,4.47,0,0,0,14.35.45a6.52,6.52,0,0,0-2.1-.4C11.33,0,11,0,8.67,0Z"/>
                                                <path class="social__icon-fill c_fill_<?php color_id($menu_theme, 3); ?> c_h-child_fill_<?php color_id($menu_theme, 5); ?>" d="M8.67,4.22a4.46,4.46,0,1,0,4.45,4.45A4.45,4.45,0,0,0,8.67,4.22Zm0,7.35a2.9,2.9,0,1,1,2.89-2.9h0A2.89,2.89,0,0,1,8.67,11.57Z"/>
                                                <circle class="social__icon-fill c_fill_<?php color_id($menu_theme, 3); ?> c_h-child_fill_<?php color_id($menu_theme, 5); ?>" cx="13.3" cy="4.04" r="1.04"/>
                                            </svg>
                                            <?php elseif ($site == 'twitter'): ?>
                                            <svg viewBox="0 0 21.7 17.63" class="social__icon social__icon_twitter">
                                                <path class="social__icon-fill c_fill_<?php color_id($menu_theme, 3); ?> c_h-child_fill_<?php color_id($menu_theme, 5); ?>" d="M6.82,17.63A12.59,12.59,0,0,0,19.49,5.13V5c0-.19,0-.39,0-.58a9,9,0,0,0,2.22-2.3,8.77,8.77,0,0,1-2.56.7,4.44,4.44,0,0,0,2-2.46A8.92,8.92,0,0,1,18.27,1.4a4.45,4.45,0,0,0-7.58,4.06A12.65,12.65,0,0,1,1.51.81,4.45,4.45,0,0,0,2.89,6.75a4.46,4.46,0,0,1-2-.55v0a4.46,4.46,0,0,0,3.57,4.37,4.4,4.4,0,0,1-2,.07,4.47,4.47,0,0,0,4.16,3.1,9,9,0,0,1-5.53,1.9A9.42,9.42,0,0,1,0,15.63a12.6,12.6,0,0,0,6.82,2"/>
                                            </svg>
                                            <?php elseif ($site == 'youtube'): ?>
                                            <svg viewBox="0 0 18.48 13" class="social__icon social__icon_youtube">
                                                <path class="social__icon-fill c_fill_<?php color_id($menu_theme, 3); ?> c_h-child_fill_<?php color_id($menu_theme, 5); ?>" d="M18.3,2.8A4,4,0,0,0,17.56,1,2.6,2.6,0,0,0,15.71.19C13.13,0,9.25,0,9.25,0h0S5.36,0,2.77.19A2.6,2.6,0,0,0,.92,1,4,4,0,0,0,.18,2.8,28.86,28.86,0,0,0,0,5.8V7.2a28.79,28.79,0,0,0,.18,3A4.07,4.07,0,0,0,.92,12a3.15,3.15,0,0,0,2,.8C4.44,13,9.24,13,9.24,13s3.89,0,6.47-.19A2.67,2.67,0,0,0,17.56,12a4,4,0,0,0,.74-1.83,28.79,28.79,0,0,0,.18-3V5.8a28.86,28.86,0,0,0-.18-3Zm-11,6.1V3.7l5,2.61Z"/>
                                            </svg>
                                            <?php elseif ($site == 'linkedin'): ?>
                                            <svg viewBox="0 0 16.21 16.4" class="social__icon social__icon_linkedin">
                                                <path class="social__icon-fill c_fill_<?php color_id($menu_theme, 3); ?> c_h-child_fill_<?php color_id($menu_theme, 5); ?>" d="M14.57,0H1.63A1.64,1.64,0,0,0,0,1.64V14.76A1.64,1.64,0,0,0,1.63,16.4H14.57a1.64,1.64,0,0,0,1.64-1.64h0V1.64A1.64,1.64,0,0,0,14.57,0ZM5.2,13H3V6H5.2ZM4.1,5.1A1.09,1.09,0,1,1,5.28,4,1.08,1.08,0,0,1,4.2,5.1ZM13.17,13H11V9.09c0-.9-.32-1.52-1.1-1.52a1.18,1.18,0,0,0-1.12.81,1.49,1.49,0,0,0-.07.55V13H6.52V8.2c0-.88,0-1.61-.06-2.24H8.35l.1,1h0A2.53,2.53,0,0,1,10.66,5.8c1.43,0,2.51,1,2.51,3Z"/>
                                            </svg>
                                            <?php endif; ?>
                                        </a>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            <div class="footer-2__section footer-2__section_sub">
                                <div class="sub-footer-2">
                                    <div class="sub-footer-2__item">
                                        <p class="sub-footer-2__text">&copy; Copyright <?php echo kf_get_copyright_year(); if ($f_footer_details['copyright']) { echo ' ' . $f_footer_details['copyright']; } ?></p>
                                    </div>
                                    <?php if ($f_footer_contact['phone']): ?>
                                    <div class="sub-footer-2__item">
                                        <a href="<?php echo esc_url('tel:' . $f_footer_contact['phone']); ?>" class="sub-footer-2__link c_color_<?php color_id($menu_theme, 3); ?> c_h_color_<?php color_id($menu_theme, 5); ?>"><?php echo $f_footer_contact['phone']; ?></a>
                                    </div>
                                    <?php endif; ?>
                                    <?php if ($f_footer_contact['email']): ?>
                                    <div class="sub-footer-2__item">
                                        <a href="<?php echo esc_url('mailto:' . $f_footer_contact['email']); ?>" class="sub-footer-2__link c_color_<?php color_id($menu_theme, 3); ?> c_h_color_<?php color_id($menu_theme, 5); ?>"><?php echo $f_footer_contact['email']; ?></a>
                                    </div>
                                    <?php endif; ?>
                                    <?php if ($f_footer_contact['address']['line-1'] || $f_footer_contact['address']['line-2']): ?>
                                    <div class="sub-footer-2__item">
                                        <?php if ($f_footer_contact['address']['link']): ?>
                                        <a href="<?php echo esc_url($f_footer_contact['address']['link']); ?>" target="_blank" class="sub-footer-2__link address c_color_<?php color_id($menu_theme, 3); ?> c_h_color_<?php color_id($menu_theme, 5); ?>">
                                            <?php if ($f_footer_contact['address']['line-1']) { ?><span class="address__line"><?php echo $f_footer_contact['address']['line-1']; ?></span><?php } ?>
                                            <?php if ($f_footer_contact['address']['line-2']) { ?><span class="address__line"><?php echo $f_footer_contact['address']['line-2']; ?></span><?php } ?>
                                        </a>
                                        <?php else: ?>
                                        <p class="sub-footer-2__text address">
                                            <?php if ($f_footer_contact['address']['line-1']) { ?><span class="address__line"><?php echo $f_footer_contact['address']['line-1']; ?></span><?php } ?>
                                            <?php if ($f_footer_contact['address']['line-2']) { ?><span class="address__line"><?php echo $f_footer_contact['address']['line-2']; ?></span><?php } ?>
                                        </p>
                                        <?php endif; ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

	<main id="content" class="site-content">
