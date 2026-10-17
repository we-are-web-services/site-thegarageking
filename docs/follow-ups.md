# Follow-ups: The Garage King

Kept in one place; update it when an item is done. Last updated 2026-10-17. Nothing here is live.

## A. Waiting on David
1. Merge site-thegarageking#1, waws-hub#22, waws-wp-site-template#2; delete the merged branches (tick "Automatically delete head branches" in each repo's settings).
2. Switch this repo to public, open the Playground link, switch it back. If the link fails, the blueprint's file-loading steps are the first suspect.
3. Confirm: Under Construction on while building, off at launch? Prototype skips server-only plugins (backups, Hide Login, Redis, Postmark, Companion)?
4. Decide what to do with the old site's content that the sitemap does not mention (section D).

## B. Needs the client (through Rhonda)
- Estimator: the real prices (now sample values in `assets/pricing.json`), which products and units to offer, what the multi-rack offer covers, whether the BOGO offers belong in the estimator, floor coating price per square foot, and whether to show an exact figure or a range.
- Real prices, package contents and what the estimator multiplies; garages completed; Google review count; real reviews (or live feed).
- Photos and video (shared Drive folder, not received); team photos; customer permission for photos and names.
- Phone, email, hours, address for the footer; warranty and installation details; product specs (motorized racks, slatwall colours and finishes, cabinets, floor coating system, fans), which the client deferred to the designer.
- Financing provider; the primary offer wording and the incentive pop-up offer; the tagline and branding tweaks; a name for "Slatwall" (red).
- Final city-page list; whether /nextdoor and /sael are still used; which ad landing variants at launch; who approves new thank-you URLs in Google Ads.
- GoHighLevel and Jobber details (waiting); Calendly to GoHighLevel move; Drip access; LeadPages decision; Gravity Forms licence; tracking containers (Tag Manager, Meta Pixel, call tracking) to carry over.
- Legal review of the privacy policy and SMS terms.

## C. To build
- Quote form next steps: connect to GoHighLevel and Jobber (set `GK_LEAD_WEBHOOK` or write an adapter once their details arrive); set `GK_LEAD_EMAIL`; build the step 2 scheduler; the Contact page form (its own thank-you URL); decide on invisible spam protection beyond the hidden field; lead photos are saved in the public uploads folder (move them to private storage before launch); data retention for leads; have the client's lawyer review the consent text.
- Estimator: drop the $40 rule and add the buy-one-get-one rules once the client confirms (docs/follow-ups section B); the header nav wraps below about 1100 px (add a menu button on tablets).
- Our Work with before/after sliders; project story template; How It Works (merge steps 2 and 3); FAQ; About; Reviews (Google feed); Contact.
- Quote page and forms: photo upload, "reason for contact" including existing customer, one thank-you URL per form (no "take photos" tip on the thank-you page, ask on the form).
- Five more product pages from the overhead page; Lifts; a bookmarked Garage Door Installation and Repairs page; Service Areas and the city page template.
- Ad landing page `/ga` and keyword variants; `/nextdoor` and `/sael` if still active; Privacy, Terms and SMS; 404.
- Scroll-triggered incentive pop-up; real logo; self-hosted fonts instead of Google Fonts; SEO (titles, descriptions, LocalBusiness, Review and FAQ schema); WebP images; accessibility check; tablet check of the header.
- Test the blueprint in real Playground; read pages 15 to 18 of the questionnaire PDF as images for their colours.

## D. Found on the current site (not in the sitemap)
Done 2026-10-17: the prototype now shows the current site's text and photos (see `docs/site-notes.md`), without the shop. Still to decide below.
Checked 2026-10-17 from the site's public sitemaps and pages:
- **A working shop and cart:** `/shop/`, `/cart/`, `/checkout/`, `/my-account/` and about 35 products, many of them "installation appointment" and packs (the client's answer says no online sales today, so confirm what these are for before removing them).
- **`/steel-cabinets/`** already exists; the sitemap calls cabinets new at `/garage-cabinets`. Keep the old URL or redirect it.
- **21 blog posts and 5 individual review posts** at root URLs (for example `/fall-garage-prep/`). The sitemap treats the blog as new and phase 2. Decide: bring them over, redirect them, or drop them. They carry search history.
- `/lumpsumcontract/` and a test page `/test-woocommerce-page/`.
- Pages that do **not** exist today: About, FAQ, How It Works, Service Areas, Blog index. `/ga`, `/nextdoor` and `/sael` do exist.

## E. Launch and the hub
- Design the deploy step from the repo to the server (hub to-do 21).
- Follow `docs/launch-checklist.md`: the server's new-site checklist, the standard access (`claude-waws`, `cdt24`, Simple History), In The Loop, Matomo, WHMCS; Under Construction off; archive banner.
- Plan 301 redirects for every retired URL before launch; keep `/ga` and its conversion tracking intact.
- Hub: `docs/site-todo.md` (plugin items on the live sites) is still open.
