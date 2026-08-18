# NovaStream SEO

NovaStream's WordPress SEO plugin.

## Requirements

- WordPress 6.5 or newer is recommended for native plugin-dependency handling.
- Advanced Custom Fields Pro must be installed and active separately.

ACF Pro is intentionally not bundled with this plugin. WordPress can enforce
the dependency when ACF Pro uses its standard `advanced-custom-fields-pro`
plugin directory. A runtime check also prevents fatal errors and displays an
administrator notice on older or manually managed installations.

## Updates and releases

The plugin uses Plugin Update Checker 5.7 to follow stable, non-prerelease
GitHub releases and tags. Commits on `main` are not a production release by
themselves. Do not add a GitHub token, deploy key, or other credential to the
plugin: every file shipped with a WordPress plugin must be treated as public.

To publish a release:

1. Update both version declarations in `seo.php`.
2. Merge the release commit into `main`.
3. Create a matching Git tag and GitHub release (for example, `1.1.9`).
4. Verify the update from a WordPress installation before broad deployment.

The repository must remain public for credential-free update checks and
downloads to work on installed sites.

The updater is vendored at `vendor/plugin-update-checker`. When upgrading it,
copy the official runtime package and retain its MIT `license.txt`; do not
copy examples, build scripts, or development configuration into this plugin.

## Security

Never commit credentials, even when the repository is private or the value is
encoded. If a credential is committed, revoke it first and then remove it from
all branches and tags before making the repository public.
