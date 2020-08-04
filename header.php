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
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
    
    <?php
    $f_breadcrumbs = get_field('hf_header_breadcrumbs', 'option');
    $f_nav = get_field('hf_header_nav', 'option');
    $f_nav_2 = get_field('hf_header_nav-2', 'option');
    $f_options = get_field('hf_header_options', 'option');
    
    $theme = 'main-dark';
    ?>
    
	<header class="site-header c_bg_<?php color_id($theme, 1); ?> c_color_<?php color_id($theme, 5); ?>">
        <?php if ($f_options['nav-location'] == 'top' && $f_nav_2['links']): ?>
        <div class="site-header__secondary c_bg_<?php color_id($theme, 0); ?>">
            <div class="site-header__content">
                <nav class="site-nav site-nav_right site-nav_xs site-nav_lines c_color_<?php color_id($theme, 3); ?>">
                    <?php foreach ($f_nav_2['links'] as $item): ?>
                    <div class="site-nav__item site-nav__item_xs site-nav__item_line">
                        <a href="<?php echo esc_url($item['link']['url']); ?>" target="<?php echo $item['link']['target']; ?>" class="site-nav__link c_color_<?php color_id($theme, 3); ?> c_h_color_<?php color_id($theme, 5); ?>"><?php echo $item['link']['title']; ?></a>
                    </div>
                    <?php endforeach; ?>
                </nav>
            </div>
        </div>
        <?php endif; ?>
        <div class="site-header__main<?php if ($f_breadcrumbs) { echo ' site-header__main_breadcrumbs'; } ?>">
            <div class="site-header__content">
                <div class="header-1">
                    <div class="header-1__main">
                        <div class="header-1__col header-1__col_logo">
                            <a href="<?php echo esc_url(home_url()); ?>" class="site-header__home">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/logo.svg" alt="<?php bloginfo('name'); ?>" class="site-header__logo" />
                            </a>
                        </div>
                        <?php if ($f_nav['links']): ?>
                        <div class="header-1__col header-1__col_nav">
                            <nav class="site-nav site-nav_right c_color_<?php color_id($theme, 5); ?>">
                                <?php foreach ($f_nav['links'] as $item): ?>
                                <div class="site-nav__item">
                                    <a href="<?php echo esc_url($item['link']['url']); ?>" target="<?php echo $item['link']['target']; ?>" class="site-nav__link c_color_<?php color_id($theme, 5); ?> c_h_color_<?php color_id($theme, 3); ?>"><?php echo $item['link']['title']; ?></a>
                                </div>
                                <?php endforeach; ?>
                            </nav>
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
                                <span class="breadcrumbs__title"><?php echo get_the_title(); ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php if ($f_options['nav-location'] == 'bottom' && $f_nav_2['links']): ?>
        <div class="site-header__secondary c_bg_<?php color_id($theme, 0); ?>">
            <div class="site-header__content">
                <nav class="site-nav site-nav_right site-nav_xs site-nav_lines c_color_<?php color_id($theme, 3); ?>">
                    <?php foreach ($f_nav_2['links'] as $item): ?>
                    <div class="site-nav__item site-nav__item_xs site-nav__item_line">
                        <a href="<?php echo esc_url($item['link']['url']); ?>" target="<?php echo $item['link']['target']; ?>" class="site-nav__link c_color_<?php color_id($theme, 3); ?> c_h_color_<?php color_id($theme, 5); ?>"><?php echo $item['link']['title']; ?></a>
                    </div>
                    <?php endforeach; ?>
                </nav>
            </div>
        </div>
        <?php endif; ?>
    </header>

	<div id="content" class="site-content">
