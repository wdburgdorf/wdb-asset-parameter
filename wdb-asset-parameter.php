<?php
/*
 * Plugin Name:       WDB Asset Parameter
 * Plugin URI:        https://github.com/wdburgdorf/wdb-asset-parameter
 * Description:       Appends a parameter "wap" with the file's modification time to every local CSS and JS URL on frontend pages, so browsers fetch an asset again whenever it changes
 * Version:           1.1.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Ralf Longwitz, Webdesign Burgdorf
 * Author URI:        https://www.webdesign-burgdorf.ch/
 * License:           GPLv2
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wdb-asset-parameter
 */

defined('ABSPATH') || exit;

require_once __DIR__ . '/plugin-update-checker/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

function wdb_ap_init_update_checker() {
	$update_checker = PucFactory::buildUpdateChecker(
		'https://github.com/wdburgdorf/wdb-asset-parameter',
		__FILE__,
		'wdb-asset-parameter'
	);

	//Set the branch that contains the stable release.
	$update_checker->setBranch('main');
}
wdb_ap_init_update_checker();


// Map an asset URL to its file on disk, or return null if it is not a local file
function wdb_ap_local_path($src) {
	$url = strtok(set_url_scheme($src, 'https'), '?#');

	// Check the content directory first, it can live outside ABSPATH
	$roots = array(
		set_url_scheme(content_url('/'), 'https') => trailingslashit(WP_CONTENT_DIR),
		set_url_scheme(site_url('/'), 'https')    => ABSPATH,
	);

	foreach ($roots as $root_url => $root_dir) {
		if (strpos($url, $root_url) === 0) {
			$file = $root_dir . rawurldecode(substr($url, strlen($root_url)));
			return is_file($file) ? $file : null;
		}
	}

	return null;
}

// Function to append the parameter
function wdb_ap_append_param($src, $handle = '') {
	// Only modify URLs on the frontend
	if (is_admin() || !$src) {
		return $src;
	}

	// Substrings of the URL path that should be excluded from having the parameter appended.
	// Only the path is checked, so the site's domain can't accidentally match.
	$exclude_substrings = array(
		'formidableforms',
		'dashicons',
		'admin-bar',
		'frm_fonts',
		'font',
		'media',
		'jquery',
		'wpa'
	);

	/**
	 * Filter the list of excluded substrings.
	 *
	 * @param string[] $exclude_substrings Substrings matched against the URL path.
	 * @param string   $src                The asset URL.
	 * @param string   $handle             The asset's registered handle.
	 */
	$exclude_substrings = (array) apply_filters('wdb_asset_parameter_excludes', $exclude_substrings, $src, $handle);

	$path = (string) wp_parse_url($src, PHP_URL_PATH);

	// Check if the URL path contains any of the excluded substrings.
	foreach ($exclude_substrings as $exclude) {
		if ($exclude !== '' && strpos($path, $exclude) !== false) {
			return $src;
		}
	}

	// External assets have no local modification time, leave them untouched
	$file = wdb_ap_local_path($src);
	if (!$file) {
		return $src;
	}

	return add_query_arg('wap', filemtime($file), $src);
}

// Apply the filter for styles and scripts
add_filter('style_loader_src', 'wdb_ap_append_param', 10, 2);
add_filter('script_loader_src', 'wdb_ap_append_param', 10, 2);
