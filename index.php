<?php
/**
 * Plugin Name:       Body Class Site Info
 * Plugin URI:        https://github.com/ginsterbusch/body-class-site-info
 * Description:       Adds the current home or site URL as well as the site ID (if multisite) to the body class. A very simple way to distinguish between eg. your development and your live site.
 * Version:           0.2
 * Author:            Fabian Wolf
 * Author URI:        http://usability-idealist.de/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * GitHub Plugin URI: https://github.com/ginsterbusch/body-class-site-info
 */

class __bodyClassSiteURL {
	function __construct() {
		add_filter('body_class', array( $this, 'filter_body_class' ) );
	}
	
	function filter_body_class( $classes = array() ) {
		$return = $classes;
	
		$site_id = 0;
		$site_url = network_site_url();
		$home_url = network_home_url();

		if( is_multisite() ) {
			$site_id = get_current_blog_id();
		}
		
		if( !empty( $site_id ) ) {
			$return[] = 'site-id-' . $site_id;
		}
		
		if( $site_url != $home_url ) {
			$return[] = 'site-url-' . $this->sanitize_url_class( $site_url );
			$return[] = 'home-url-' . $this->sanitize_url_class( $home_url );
		} else {
			$return[] = $this->sanitize_url_class( $site_url );
		}
		
		
		return $return;
	}
	
	function sanitize_url_class( $class = '', $remove_protocol = false ) {
		$return = $class;
		
		if( !empty( $class ) ) {
			if( !empty( $remove_protocol ) && ( stripos( $class, 'http://' ) !== false || stripos( $class, 'https://' ) !== false ) ) {
				$return = str_replace( array('http://', 'https://' ), '', $return );
			}
			$return = str_replace( array(' ', '/', '.', '--' ), '-', $return );
			$return = sanitize_key( $return );
		}
		
		return $return;
	}
	
	/**
	 * No fancy factory pattern this time ;)
	 */
	
	function init() {
		new self();
	}
}

add_action( 'plugins_loaded', array( '__bodyClassSiteURL', 'init' ) );
