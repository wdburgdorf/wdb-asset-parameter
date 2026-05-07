<?php
/*
 * Plugin Name: 	WDB Asset Parameter
 * Plugin URI:		https://www.webdesign-burgdorf.ch/
 * Description:	Appends a parameter "wap" to the end of every CSS and JS URL on frontend pages, thus preventing caching
 * Version: 			1.0.0
 * Author: 			Ralf Longwitz, Webdesign Burgdorf
 * Author URI: 	https://www.webdesign-burgdorf.ch/
 * Plugin URI: https://github.com/YOUR-GITHUB-USER/wdb-my-plugin
 * License: 			GPL2
 * Last Update:	2025-06-24
 */


require 'plugin-update-checker/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$myUpdateChecker = PucFactory::buildUpdateChecker(
	'wdb-asset-parameter.json',
	__FILE__, //Full path to the main plugin file or functions.php.
	'wdb-asset-parameter'
);


// Function to append the parameter
function append_x_parameter($src) {
	// Only modify URLs on the frontend
	if (is_admin()) {
		return $src;
	}

	// List of substrings for URLs that should be excluded from having the parameter appended.
	// For example, any URL that contains "formidableforms" will be excluded.
	$exclude_substrings = array(
		'formidableforms',         // Exclude any URL that contains "formidableforms".
		'dashicons',
		'admin-bar',
		'frm_fonts',
		'font',
		'media',
		'query',
		'wpa'
	);

	// Check if the URL contains any of the excluded substrings.
	foreach ($exclude_substrings as $exclude) {
		if (strpos($src, $exclude) !== false) {
			return $src;
		}
	}

	// Determine the appropriate separator
	$separator = (strpos($src, '?') === false) ? '?' : '&';

	// Append the parameter (change the value as needed)
	return $src . $separator . 'wap=' . time();
}

// Apply the filter for styles and scripts
add_filter('style_loader_src', 'append_x_parameter');
add_filter('script_loader_src', 'append_x_parameter');
