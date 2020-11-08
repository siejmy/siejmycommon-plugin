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

function siejmycommon_register_thumb_sizes() {
	add_image_size( 'siejmy_blurry', 10, 10 );
	add_image_size( 'siejmy_640', 640 );
	add_image_size( 'siejmy_768', 768 );
	add_image_size( 'siejmy_1024', 1024 );
	add_image_size( 'siejmy_1366', 1366 );
	add_image_size( 'siejmy_1600', 1600 );
	add_image_size( 'siejmy_1920', 1920 );
	add_image_size( 'siejmy_2200', 2200 );
}

function siejmycommon_register_styles() {
  $dir = dirname( __FILE__ );

  $style_css = 'style/style.css';
  wp_register_style(
		'siejmycommon-styles',
		plugins_url( $style_css, __FILE__ ),
		array(),
		filemtime( "$dir/$style_css" )
  );
  wp_enqueue_style('siejmycommon-styles');
}

function siejmycommon_init() {
  siejmycommon_register_thumb_sizes();
  siejmycommon_register_styles();
}
add_action( 'init', 'siejmycommon_init' );
