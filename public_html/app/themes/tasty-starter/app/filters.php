<?php

/**
 * Theme filters.
 */

namespace App;

/**
 * Add "… Continued" to the excerpt.
 *
 * @return string
 */
//add_filter('excerpt_more', function () {
//    return sprintf(' &hellip; <a href="%s">%s</a>', get_permalink(), __('Continued', 'sage'));
//});

add_filter('excerpt_more', function () {
	return __('&hellip;', 'starter');
});

add_filter( 'excerpt_length', fn($length) => 8, 999 );
// Clean up Head
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wp_shortlink_wp_head');
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'feed_links_extra', 3);


//* Disable any and all mention of emoji's
//* Source code credit: http://ottopress.com/
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_styles', 'print_emoji_styles');
remove_filter('the_content_feed', 'wp_staticize_emoji');
remove_filter('comment_text_rss', 'wp_staticize_emoji');
remove_filter('wp_mail', 'wp_staticize_emoji_for_email');


/**
 * Filter Yoast SEO Metabox Priority
 */
add_filter('wpseo_metabox_prio', fn() => 'low');

//Remove all archive title prefixes
add_filter( 'get_the_archive_title_prefix', '__return_false' );
