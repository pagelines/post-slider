<?php
/*
	Plugin Name: Post Slider
	Demo: http://postslider.ahansson.com
	Description: A nice way to feature your posts in a great looking slider.
	Version: 1.1
	Author: Aleksander Hansson
	Author URI: http://ahansson.com
	v3: true
*/

class ah_PostSlider_Plugin {

	function __construct() {
		add_action( 'init', array( &$this, 'ah_updater_init' ) );
	}

	/**
	 * Load and Activate Plugin Updater Class.
	 * @since 0.1.0
	 */
	function ah_updater_init() {

		/* Load Plugin Updater */
		require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'includes/plugin-updater.php' );

		/* Updater Config */
		$config = array(
			'base'      => plugin_basename( __FILE__ ), //required
			'repo_uri'  => 'http://shop.ahansson.com',  //required
			'repo_slug' => 'post-slider',  //required
		);

		/* Load Updater Class */
		new AH_PostSlider_Plugin_Updater( $config );
	}

}

new ah_PostSlider_Plugin;