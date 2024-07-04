<?php
/**
 * Plugin Name: Sowebio 3D Viewer
 * Description: Visualisateur de modèles 3D
 * Plugin URI:  https://elementor.com/
 * Version:     2.0.0
 * Author:      Maximilien empereur de jade
 * Author URI:  https://developers.elementor.com/
 * Text Domain: sow3d_widget
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Will not work if the script is not executed in Wordpress
}

/**
 * Register Sow3d Widget.
 *
 * Include widget file and register widget class.
 *
 * @since 1.0.0
 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
 * @return void
 */
function register_sow3d_widget( $widgets_manager ) {

	require_once( __DIR__ . '/widgets/sow3d_widget.php' );

	$widgets_manager->register( new \sow3d_widget() );

}
add_action( 'elementor/widgets/register', 'register_sow3d_widget' );