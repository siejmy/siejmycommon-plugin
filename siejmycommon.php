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
	$ratio = 2/3;
	$widths = [100, 230, 640, 768, 1024, 1366, 1600, 1920, 2200 ];
	foreach($widths as $width) {
		$name = 'siejmy_' . $width;
		$height = floor($width * $ratio);
		add_image_size( $name, $width, $height );
	}
}

function siejmycommon_init() {
  siejmycommon_register_thumb_sizes();
}
add_action( 'init', 'siejmycommon_init' );

add_action( 'wp_head', 'time_ago_head_hook' );
add_action( 'wp_footer', 'time_ago_footer_hook' );
