<?php
/**
 * SEASON STYLE SHEET
 * ==================
 *
 * Kadence sets heading type globally — `heading_font` targets h1..h6 site-wide
 * and `h1_font` targets every h1. There is no theme-native way to say "this H1
 * is a SEASON H1 and that one is a DEFAULT H1." Putting the season face into
 * Kadence would brand About, Support and every blog post as Confluence, and
 * next August you would be unpicking it by hand.
 *
 * THE SPLIT
 *   SITE typography   -> Appearance > Customize > Typography (Kadence theme
 *                        mods). Permanent Ars Nova house style. This file does
 *                        not touch it.
 *   SEASON typography -> here. Opt-in, per block. Swapped once a year.
 *
 * THE RULE (Jonathan, 2026-07-31)
 *   Season is a property of a BLOCK. Everything inside a season block inherits
 *   it. To style something differently lower down, add a new block and mark it
 *   Default.
 *
 * HOW TO APPLY IT — block editor, Styles panel in the sidebar. No CSS needed.
 *   - Cover / Group / Columns -> "Season"        everything inside is season
 *   - Heading                 -> "Season"        a standalone season heading
 *   - Heading                 -> "Season Title"  the big tracked lockup
 *   - Paragraph               -> "Season"        a standalone season line
 *   - any of the above        -> "Default"       explicit opt-OUT inside a
 *                                                season container
 *
 * ROLLING THE SEASON OVER
 *   Edit ans_season_tokens() below. Nothing else in the codebase changes and
 *   no default heading anywhere on the site moves.
 *
 * Source of truth: SEASON_BRIEF.md 2.4 (Drive). 2026/27 season font is Cinzel,
 * CONFIRMED 2026-07-31.
 *
 * @package ars-nova-site-css
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The season's design tokens. The one place to edit each year.
 *
 * ON `title_size` — READ BEFORE CHANGING THE FLOOR
 * ------------------------------------------------
 * The three clamp values are min, preferred, max. The PREFERRED value (9vw) is
 * what makes it fluid; the MIN is a floor that stops it shrinking.
 *
 * A floor set too high silently breaks small screens. It was 3.25rem (52px)
 * until 2026-08-08. 9vw only equals 52px at a 578px viewport, so every width
 * below that froze at 52px instead of scaling. "CONFLUENCE" at 52px with 0.16em
 * tracking is ~395px wide, which stops fitting around 500px — and the title
 * then wrapped mid-word.
 *
 * Rule of thumb: the floor must be small enough that
 * `season_name` still fits the narrowest viewport you support (320px). At
 * 1.75rem (28px) a ten-character tracked capital lockup measures ~213px, which
 * clears 320px comfortably. A LONGER season name needs a LOWER floor — check it
 * when you roll the season over.
 *
 * @return array
 */
function ans_season_tokens() {
	return array(
		'season_name'  => 'Confluence',
		'season_year'  => '2026/27',
		'display_font' => "'Cinzel', Georgia, 'Times New Roman', serif",
		'google_url'   => 'https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600;700&display=swap',
		'tracking'     => '0.16em',
		'title_size'   => 'clamp(1.75rem, 9vw, 8.5rem)',
	);
}

/**
 * Load the season display font. Only the season face — the site's body and
 * heading fonts are loaded by Kadence from the Customizer, as they should be.
 */
function ans_season_enqueue_font() {
	$t = ans_season_tokens();
	if ( empty( $t['google_url'] ) ) {
		return;
	}
	wp_enqueue_style( 'ans-season-font', $t['google_url'], array(), null );
}
add_action( 'wp_enqueue_scripts', 'ans_season_enqueue_font', 5 );
add_action( 'enqueue_block_editor_assets', 'ans_season_enqueue_font', 5 );

/**
 * Register the Season styles so editors get click-targets in the block
 * sidebar instead of having to remember CSS classes.
 */
function ans_season_register_block_styles() {
	if ( ! function_exists( 'register_block_style' ) ) {
		return;
	}
	$t     = ans_season_tokens();
	$label = sprintf( 'Season (%s)', $t['season_name'] );

	foreach ( array( 'core/cover', 'core/group', 'core/columns' ) as $block ) {
		register_block_style( $block, array( 'name' => 'ans-season', 'label' => $label ) );
		register_block_style( $block, array( 'name' => 'ans-default', 'label' => 'Default (site type)' ) );
	}
	foreach ( array( 'core/heading', 'core/paragraph' ) as $block ) {
		register_block_style( $block, array( 'name' => 'ans-season', 'label' => $label ) );
		register_block_style( $block, array( 'name' => 'ans-default', 'label' => 'Default (site type)' ) );
	}
	register_block_style( 'core/heading', array( 'name' => 'ans-season-title', 'label' => 'Season Title' ) );
}
add_action( 'init', 'ans_season_register_block_styles' );

/**
 * Print the season style sheet. Priority 999 so it lands after Kadence.
 * Scoped throughout — nothing here touches a block that has not opted in.
 */
function ans_season_stylesheet_output() {
	$t = ans_season_tokens();
	?>
<style id="ans-season-stylesheet">
/* ==========================================================================
   SEASON STYLE SHEET — <?php echo esc_html( $t['season_name'] . ' ' . $t['season_year'] ); ?>

   A block only becomes season if it opts in. Default site headings and body
   text are untouched and keep whatever Customize > Typography says.
   ========================================================================== */

:root {
	--ans-season-display: <?php echo $t['display_font']; ?>;
	--ans-season-tracking: <?php echo esc_html( $t['tracking'] ); ?>;
}

/* 1. Season block — everything inside inherits the season face. */
.is-style-ans-season,
.is-style-ans-season h1,
.is-style-ans-season h2,
.is-style-ans-season h3,
.is-style-ans-season h4,
.is-style-ans-season h5,
.is-style-ans-season h6,
.is-style-ans-season p,
.is-style-ans-season li,
.is-style-ans-season blockquote,
.is-style-ans-season-title {
	font-family: var(--ans-season-display);
}

/* 1a. Explicit opt-OUT. A Default block inside a Season block wins for its
   own contents. Buttons and form controls are carved out by default: Cinzel
   is an inscriptional capital face and reads poorly at interface sizes. */
.is-style-ans-default,
.is-style-ans-default h1,
.is-style-ans-default h2,
.is-style-ans-default h3,
.is-style-ans-default h4,
.is-style-ans-default h5,
.is-style-ans-default h6,
.is-style-ans-default p,
.is-style-ans-default li,
.is-style-ans-season .wp-element-button,
.is-style-ans-season button,
.is-style-ans-season input,
.is-style-ans-season select,
.is-style-ans-season textarea {
	font-family: var(--global-body-font-family);
}

/* 1b. Season lockup spacing.

   Kadence's h1 margin is em-based, so when .ans-season-title jumps to ~136px
   the inherited top margin balloons to ~192px. That stranded the eyebrow line
   21px from the top of the hero with ~192px of void beneath it. These two
   rules split that space, so the eyebrow sits centred between the top of the
   image and the top of the season name.

   The eyebrow is selected structurally — "the paragraph immediately before a
   season title" — so it works on any hero without editing page content. The
   two values differ by the cover block's own top padding (~21px), which is
   why they are not identical. */

p:has(+ .ans-season-title),
p:has(+ .is-style-ans-season-title) {
	margin-top: 5.3rem;
}

.wp-block-cover .wp-block-cover__inner-container h1.ans-season-title,
.wp-block-cover .wp-block-cover__inner-container h1.is-style-ans-season-title,
h1.ans-season-title,
h1.is-style-ans-season-title {
	margin-top: 6.3rem;
	margin-bottom: 0;
}

/* The tagline sits BELOW the title and is deliberately half the eyebrow's
   distance from it — the tagline belongs to the season name, the eyebrow is a
   separate label above it. Same colour, size and face as the eyebrow so the
   two read as one type system bracketing the title. */

.wp-block-cover .wp-block-cover__inner-container p.ans-season-tagline,
p.ans-season-tagline {
	margin-top: 3.15rem;
}

/* 2. Season title lockup — the big tracked season name.

   Cinzel is a Roman inscriptional face built for large, widely tracked
   capitals. clamp() scales it with the viewport instead of needing
   breakpoints. The negative margin-right cancels the trailing letter-space
   CSS adds after the final letter, which otherwise nudges centred text
   fractionally off centre. */
.ans-season-title,
.is-style-ans-season-title {
	font-family: var(--ans-season-display);
	font-size: <?php echo esc_html( $t['title_size'] ); ?>;
	letter-spacing: var(--ans-season-tracking);
	margin-right: calc(var(--ans-season-tracking) * -1);
	line-height: 1.05;
	text-transform: uppercase;
}

/* 2a. Never split the season name mid-word.       Added 2026-08-08 (WEB-24)

   Kadence's headings inherit `word-break: break-word`, which is fine for prose
   but catastrophic for a one-word lockup: at 443px the title rendered as
   "CONFLUEN / CE". The clamp floor above now guarantees the word fits down to
   ~311px, so this should never trigger — it is here so that if a future season
   name IS too long, it overflows visibly (obvious, gets fixed) rather than
   silently breaking mid-word (looks deliberate, ships).

   Selectors are h1-qualified as well as bare so this outranks the theme rule
   regardless of which one Kadence emits. Wrapping BETWEEN words is still
   allowed — a two-word season name will break at the space, as it should. */
.ans-season-title,
.is-style-ans-season-title,
h1.ans-season-title,
h1.is-style-ans-season-title,
h2.ans-season-title,
h2.is-style-ans-season-title {
	word-break: normal;
	overflow-wrap: normal;
	hyphens: none;
}
</style>
	<?php
}
add_action( 'wp_head', 'ans_season_stylesheet_output', 999 );
