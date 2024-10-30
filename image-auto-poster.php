<?php
/*
Plugin Name: Image Auto Poster
Plugin URI: https://highfiveplugins.com/iap/iap-documentation/
Description: Automatically Pin Your Images to Your Pinterest Account
Text Domain: iaposter
Domain Path: /languages
Author: Marcin Skrzypiec
Version:0.7.0
Author URI: https://highfiveplugins.com/
*/

if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Image_Auto_Poster' ) ) {

	function iaposter_autoloader( $class_name ) {
		$includes = false;
		$includes_replace = '';
		if ( false !== strpos( $class_name, 'IAPoster_' ) ) {
			$includes = realpath( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'src';
			$includes_replace = 'iaposter';
		} else if ( false !== strpos( $class_name, 'IAPosterPinLib_' ) ) {
			$includes = realpath( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'pinterest' . DIRECTORY_SEPARATOR . 'Pinterest';
			$includes_replace = 'iaposterpinlib';
		}

		if ( false === $includes ) {
			return;
		}
		$file_directory       = $class_name;
		$file_directory       = str_replace( '_', DIRECTORY_SEPARATOR, $file_directory );
		$last_separator_index = strrpos( $file_directory, DIRECTORY_SEPARATOR );
		$file_directory       = substr( $file_directory, 0, $last_separator_index + 1 );
		$file_directory       = strtolower( $file_directory );
		$file_directory       = str_replace( $includes_replace, $includes, $file_directory );
		require_once $file_directory . $class_name . '.php';
	}

	spl_autoload_register( 'iaposter_autoloader' );

	register_activation_hook( __FILE__, array( 'IAPoster_Setup', 'on_activation' ) );
	register_deactivation_hook( __FILE__, array( 'IAPoster_Setup', 'on_deactivation' ) );

	final class Image_Auto_Poster {

		function __construct() {
			$version = '0.7.0';
			new IAPoster_Main( __FILE__, $version, 'iaposter' );
		}
	}

	class IAPoster_Setup {

		public static function on_activation() {
			// deactivate the plugin if curl not available
			if ( !function_exists( 'curl_version' ) ) {
				deactivate_plugins( plugin_basename( __FILE__ ) );
				wp_die( __('This plugin requires the cURL module.', 'iaposter') );
			}
			/* Checks */
			if ( ! current_user_can( 'activate_plugins' ) ) {
				return;
			}
			$plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
			check_admin_referer( "activate-plugin_{$plugin}" );

			/* Transients */
			set_transient( 'iaposter_schedule_jobs', '1' );
			set_transient( 'iaposter_welcome_screen', '1' );
		}

		public static function on_deactivation() {
			/* Checks */
			if ( ! current_user_can( 'activate_plugins' ) ) {
				return;
			}
			$plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
			check_admin_referer( "deactivate-plugin_{$plugin}" );

			/* Actual logic */
			$images_timestamp = wp_next_scheduled( 'iaposter_pin_images_cron_hook' );
			wp_unschedule_event( $images_timestamp, 'iaposter_pin_images_cron_hook' );
			$logs_timestamp = wp_next_scheduled( 'iaposter_clear_logs_hook' );
			wp_unschedule_event( $logs_timestamp, 'iaposter_clear_logs_hook' );
		}
	}

	$iaposter = new Image_Auto_Poster();
}
