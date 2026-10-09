=== WDB Asset Parameter ===
Contributors: wdburgdorf
Tags: cache busting, css, js, assets, performance
Requires at least: 7.0
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.0.2
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Appends a unique timestamp parameter to every frontend CSS and JS URL, preventing browsers from serving stale cached assets.

== Description ==

WDB Asset Parameter is a lightweight utility plugin that appends a `wap` query parameter with the current Unix timestamp to all enqueued stylesheet and script URLs on the frontend. This forces browsers to fetch the latest version of every asset on each page load, which is especially useful during active development or after deploying theme/plugin updates.

**Features**

- Automatic cache busting for all frontend CSS and JS assets.
- Excludes admin/dashboard assets — only frontend URLs are modified.
- Configurable exclusion list to skip specific URLs (e.g. Formidable Forms, Dashicons, fonts, media).
- Zero configuration required — activate and it works immediately.
- Supports automatic updates via GitHub releases.

== How It Works ==

The plugin hooks into WordPress's `style_loader_src` and `script_loader_src` filters. For every enqueued asset URL on the frontend it appends `?wap=<timestamp>` (or `&wap=<timestamp>` if the URL already contains query parameters). Because the timestamp changes with every request, browsers treat each load as a new resource and bypass their cache.

== Excluded URLs ==

Certain URLs are excluded from modification to avoid breaking third-party services or admin assets. A URL is skipped if it contains any of the following substrings:

- `formidableforms`
- `dashicons`
- `admin-bar`
- `frm_fonts`
- `font`
- `media`
- `query`
- `wpa`

== Installation ==

1. Upload the `wdb-asset-parameter` folder to `wp-content/plugins/`.
2. Activate the plugin through the **Plugins** menu in WordPress.
3. That's it — no settings page needed. All frontend CSS and JS URLs will now include the cache-busting parameter.

== Frequently Asked Questions ==

= Does this affect admin/dashboard assets? =

No. The plugin checks `is_admin()` and leaves dashboard URLs untouched.

= Will this hurt performance? =

The parameter changes on every page load, so browsers will always fetch fresh assets. This is ideal during development. On production sites with stable assets you may want to deactivate the plugin and rely on standard WordPress versioning instead.

= Can I customise the exclusion list? =

Currently the exclusion list is defined in the plugin source code. You can edit the `$exclude_substrings` array in `wdb-asset-parameter.php` to add or remove entries.

== Changelog ==

= 1.0.1 =
- Initial release - testing update

= 1.0.0 =
- Initial release.
