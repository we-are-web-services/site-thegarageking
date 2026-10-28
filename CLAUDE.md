# New WordPress site build (from waws-wp-site-template)

Start in `waws-hub` (its `CLAUDE.md` has the shared rules; they apply here). This repo is the **build** of one client site,
then its archive. Last updated: 2026-10-17 (rule 1 changed for this site).

## Rules

1. **Only what is already public.** This repo may be switched to public so David can open the Playground link. For this site
   (David, 2026-10-17) text, photos, reviews and business contact details copied from the client's current public site
   (thegarageking.com) are fine. Never commit anything private: client questionnaire answers, customer data, prices or
   package terms not yet public (exception 2026-10-23: the regular prices on the Pricing page come from the client's Latest Deals document, which the current site links to publicly; David to confirm; exception 2026-10-28: the expired October promotion offers in the same document appear as clearly labelled sample offers, David's instruction; the current site's pricing page links to the document; exception 2026-10-28: resized photos from the client's shared Drive folder "Pictures" are in `theme/garage-king/assets/images`, chosen with no people, license plates or house numbers and with location data removed, David's instruction), internal notes, passwords, keys, or exports of a live database. Raw client material stays
   in Drive. Credentials are referred to by name only.
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
