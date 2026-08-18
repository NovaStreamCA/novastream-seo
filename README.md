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

The plugin checks the public GitHub repository's `main` branch for updates. Do
not add a GitHub token, deploy key, or other credential to the plugin: every
file shipped with a WordPress plugin must be treated as public.

To publish a release:

1. Update both version declarations in `seo.php`.
2. Merge the release commit into `main`.
3. Create a matching Git tag and GitHub release (for example, `1.1.9`).
4. Verify the update from a WordPress installation before broad deployment.

The repository must remain public for credential-free update checks and
downloads to work on installed sites.

## Security

Never commit credentials, even when the repository is private or the value is
encoded. If a credential is committed, revoke it first and then remove it from
all branches and tags before making the repository public.
