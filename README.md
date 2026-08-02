# ars-nova-site-css

**Ars Nova Singers - custom WordPress plugin.** Canonical source (private use; not distributed).

- Current version: 2.3.0 (matches DEV arsnovasingers.kinsta.cloud)
- Environment: DEV only; not on LIVE.
- Two things. (1) The **Season Style Sheet** - season-scoped typography, opt-in per block, so a heading can be a season heading without changing the site's default headings. (2) A small set of front-end CSS fixes. Site-wide typography is NOT set here - that lives in Appearance > Customize > Typography so the theme's own UI stays truthful.

## Installed folder name

The plugin directory on the site is **`ans-site-css/`**, while the main file is
`ars-nova-site-css.php`. That mismatch predates this repo and is preserved deliberately -
renaming the directory would deactivate the plugin on DEV.

Release zips therefore use the prefix `ans-site-css/`, not the repo name:

```
git archive --format=zip --prefix=ans-site-css/ -o ans-site-css.zip vX.Y.Z
```

## Releases

Each tag `vX.Y.Z` attaches the installable zip (top folder `ans-site-css/`), built with
`git archive` **from the tag** so the asset is provably the tagged commit. The asset is named
`ans-site-css.zip` without a version - the WordPress installer derives the destination folder
from the zip filename, so a versioned filename installs into the wrong directory.

Install/update via the Ars Nova Ops installer (`wp_install_plugin` with the release asset URL)
or WP-admin upload.
