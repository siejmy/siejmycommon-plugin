<?php
/**
 * Plugin Name:     siejmycommon
 * Description:     Common files for other siejmy plugins
 * Version:         1.0.0
 * Author:          Jędrzej Lewandowski
 * License:         GPL-2.0-or-later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:     siejmycommon
 *
 * @package         siejmy
 */

require_once(dirname(__FILE__) . '/classes/TimeAgoRenderer.php');

function siejmycommon_register_thumb_sizes() {
	add_image_size( 'siejmy_blurry', 10, 10 );
	add_image_size( 'siejmy_100', 100 );
	add_image_size( 'siejmy_230', 230 );
	add_image_size( 'siejmy_640', 640 );
	add_image_size( 'siejmy_768', 768 );
	add_image_size( 'siejmy_1024', 1024 );
	add_image_size( 'siejmy_1366', 1366 );
	add_image_size( 'siejmy_1600', 1600 );
	add_image_size( 'siejmy_1920', 1920 );
	add_image_size( 'siejmy_2200', 2200 );
}

function siejmycommon_init() {
  siejmycommon_register_thumb_sizes();
}
add_action( 'init', 'siejmycommon_init' );

add_action( 'wp_head', 'time_ago_head_hook' );
add_action( 'wp_footer', 'time_ago_footer_hook' );
