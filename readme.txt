=== WDB Asset Parameter ===
Contributors: wdburgdorf
Tags: cache busting, css, js, assets, performance
Requires at least: 6.0
Tested up to: 7.1.3
Requires PHP: 7.4
Stable tag: 1.1.0
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Appends the file's modification time to every local frontend CSS and JS URL, so browsers fetch an asset again whenever it changes.

== Description ==

WDB Asset Parameter is a lightweight utility plugin that appends a `wap` query parameter with the file's modification time to all local enqueued stylesheet and script URLs on the frontend. Browsers keep caching assets normally, but fetch a fresh copy as soon as a file changes, which is especially useful after deploying theme/plugin updates.

**Features**

- Automatic cache busting for all local frontend CSS and JS assets.
- Excludes admin/dashboard assets — only frontend URLs are modified.
- Exclusion list to skip specific URLs (e.g. Formidable Forms, Dashicons, fonts, media), adjustable via the `wdb_asset_parameter_excludes` filter.
- Zero configuration required — activate and it works immediately.
- Supports automatic updates via GitHub releases.

== How It Works ==

The plugin hooks into WordPress's `style_loader_src` and `script_loader_src` filters. For every enqueued asset URL on the frontend that points to a file on the site, it appends `wap=<modification time>` using `add_query_arg()`. The value only changes when the file changes, so browsers cache the asset until then. External URLs (e.g. CDNs) are left untouched.

== Excluded URLs ==

Certain URLs are excluded from modification to avoid breaking third-party services or admin assets. A URL is skipped if its path (not its domain) contains any of the following substrings:

- `formidableforms`
- `dashicons`
- `admin-bar`
- `frm_fonts`
- `font`
- `media`
- `jquery`
- `wpa`

== Installation ==

1. Upload the `wdb-asset-parameter` folder to `wp-content/plugins/`.
2. Activate the plugin through the **Plugins** menu in WordPress.
3. That's it — no settings page needed. All local frontend CSS and JS URLs will now include the cache-busting parameter.

== Frequently Asked Questions ==

= Does this affect admin/dashboard assets? =

No. The plugin checks `is_admin()` and leaves dashboard URLs untouched.

= Will this hurt performance? =

No. The parameter only changes when a file is modified, so browsers and CDNs still cache assets normally. The plugin is safe to leave active on production sites.

= Can I customise the exclusion list? =

Yes, with the `wdb_asset_parameter_excludes` filter, e.g. in your theme's `functions.php`:

    add_filter( 'wdb_asset_parameter_excludes', function ( $excludes, $src, $handle ) {
        $excludes[] = 'my-plugin';
        return $excludes;
    }, 10, 3 );

== Upgrade Notice ==

= 1.1.0 =
Assets are now versioned by file modification time instead of the current time, so browsers cache them until they change.

== Changelog ==

= 1.1.0 =
- Use the file's modification time instead of the current time, so assets stay cached until they change.
- External assets are no longer modified.
- Exclusions are matched against the URL path only; `query` replaced by `jquery`.
- New filter `wdb_asset_parameter_excludes` to change the exclusion list.
- Fixed relative require path, added direct access check, prefixed function names.

= 1.0.1 – 1.0.6 =
- Test releases for the update mechanism.

= 1.0.0 =
- Initial release.
