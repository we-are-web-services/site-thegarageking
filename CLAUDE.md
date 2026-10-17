# New WordPress site build (from waws-wp-site-template)

Start in `waws-hub` (its `CLAUDE.md` has the shared rules; they apply here). This repo is the **build** of one client site,
then its archive. Last updated: 2026-10-17.

## Rules

1. **Placeholder content only.** This repo may be switched to public so David can open the Playground link. Never commit
   real client copy, logos, contact details, customer data, passwords, keys or exports of a live database. Credentials are
   referred to by name only.
2. Work on a `claude/<short-topic>` branch and open a pull request; never push to `main`. David says "merge."
3. The repo is the source of truth while building. Edits made inside Playground are lost on reload, so every change goes
   into the repo (blueprint, `theme/`, `plugins/`, `content/`).
4. Plugins for the build: `docs/standard-plugins.md` in `waws-hub`. The prototype installs only what matters for the look
   (see `blueprint.json`); the rest is installed at launch.
5. Visibility: private by default. David switches it to public only while viewing (Settings, Danger Zone, Change
   visibility) and back afterward. Everything ever committed is visible while it is public.
6. Playground link: `https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/we-are-web-services/<repo>/<branch>/blueprint.json`
   (works only while the repo is public). The blueprint has not been tested end to end yet; the first build proves it.

## Phases

1. **Build:** theme, plugins and placeholder content in the repo; David reviews the Playground link.
2. **Launch:** follow `docs/launch-checklist.md`. The deploy step to the server is not designed yet.
3. **After launch:** the site is managed over the dashboard login and REST API like David's other sites. This repo becomes an
   **archive**: add the banner from `docs/archive-banner.md` to the top of `README.md`. The live site is then the source of truth.

Keep this file short; detail goes in `docs/`.
