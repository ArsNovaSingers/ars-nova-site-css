<?php
/**
 * Plugin Name: Ars Nova Site CSS
 * Description: Two things. (1) The SEASON STYLE SHEET — season-scoped typography, opt-in per block, so a heading can be a season heading without changing the site's default headings. (2) A small set of front-end CSS fixes. Site-wide typography is NOT set here — that lives in Appearance > Customize > Typography so the theme's own UI stays truthful.
 * Version: 2.4.0
 * Author: Ars Nova (Jonathan Raabe) + Claude
 * Requires at least: 5.8
 * Requires PHP: 7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Keep this in step with the `Version:` header above and the release tag.
 * See claude/plugins/Ars_Nova_Plugin_Build_Rules.md rule 2.
 *
 * These had DRIFTED: as of 2026-08-08 the header said 2.3.0 while this
 * constant still said 2.1.0, so any report of the constant named a version
 * that was never released. Change both together, every time.
 */
define( 'ANS_SITE_CSS_VERSION', '2.4.0' );
define( 'ANS_SITE_CSS_DIR', plugin_dir_path( __FILE__ ) );

require_once ANS_SITE_CSS_DIR . 'season-stylesheet.php';

/**
 * Site fixes — NOT typography.
 *
 * Printed inline rather than shipped as a static asset: v1.0.0 of this plugin
 * shipped a zero-byte site.css that silently did nothing. Inline PHP output
 * cannot fail that way.
 */
function ans_site_css_output() {
	?>
<style id="ans-site-fixes">
/* --------------------------------------------------------------------------
   Spotify brand colour, header social icons.                Added 2026-07-31

   Kadence's "Use brand colors" only ships colours for Facebook, Instagram and
   YouTube — there is no .social-link-spotify rule anywhere in the theme. On
   hover Spotify fell through to Kadence's generic social rule
   (a.social-button:hover { color: var(--global-palette-btn) }), which sets the
   text white with no brand background behind it, so the icon vanished against
   the white header.
   -------------------------------------------------------------------------- */

body.social-brand-colors .social-show-brand-hover .social-link-spotify:not(.ignore-brand):not(.skip):not(.ignore):hover,
body.social-brand-colors .social-show-brand-until .social-link-spotify:not(:hover):not(.skip):not(.ignore),
body.social-brand-colors .social-show-brand-always .social-link-spotify:not(.ignore-brand):not(.skip):not(.ignore) {
	background: #1DB954;
	background-color: #1DB954;
	color: #ffffff;
}

body.social-brand-colors .social-show-brand-hover.social-style-outline .social-link-spotify:not(.ignore-brand):not(.skip):not(.ignore):hover,
body.social-brand-colors .social-show-brand-until.social-style-outline .social-link-spotify:not(:hover):not(.skip):not(.ignore),
body.social-brand-colors .social-show-brand-always.social-style-outline .social-link-spotify:not(.ignore-brand):not(.skip):not(.ignore) {
	color: #1DB954;
	border-color: currentColor;
}
</style>
	<?php
}
add_action( 'wp_head', 'ans_site_css_output', 999 );
