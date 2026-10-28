# Follow-ups: The Garage King

Kept in one place; update it when an item is done. Ideas set aside for another scope of work are in `docs/later-scope.md`. Last updated 2026-10-23. Nothing here is live.

## A. Waiting on David
0. **Start with `docs/next-session.md`** (written 2026-10-23).
1. Merge site-thegarageking#4 and waws-hub#28 (first complete draft); then delete the merged branches (tick "Automatically delete head branches" in each repo's settings).
2. Switch this repo to public, open the Playground link, tell Claude what you see, switch it back. If it fails, the blueprint's file-loading steps are the first suspect.
3. Confirm the regular prices may stay in the repo (from the client's publicly linked Latest Deals document; see `CLAUDE.md` rule 1).
4. Confirm: Under Construction on while building, off at launch? Prototype skips server-only plugins (backups, Hide Login, Redis, Postmark, Companion)?
5. Old-site content the Website Content Outline does not mention (section D): blog posts and reviews are **included** (David, 2026-10-23). Still to decide: `/steel-cabinets` (see section D), and confirm the shop, cart and test pages stay out.
6. Decide whether the service area keeps the city chips (removed 2026-10-23; the old list is in git history, `patterns/service-area.php` before that commit).
7. Hub to-do 21: design the deploy step from the repo to the server.

## B. Needs the client (through Rhonda)
Added 2026-10-23: what Lifts means; which garage door services and brands; are cabinets for sale now (Deluxe Cabinet Package) or "coming soon"; slatwall capacity (200 lbs per panel on the current page vs 300 lbs per square foot in Latest Deals); approval of the footer tagline "Can't find a thing? Ask the Garage King!" (replaces "Call the Garage King!"); contents and prices of The Emerson, The Duke and The Earl; when the next limited-time offers start (the last ended 2026-10-19).
- ~~Estimator~~ **Out of scope (David, 2026-10-23):** nothing on the current site to match it, and the client has not confirmed having the information to base estimates on. Revisit only if that changes; the last working version is in git history (commit e1a9cfd).
- Package contents and prices (The Emerson, The Duke, The Earl); garages completed; Google review count; real reviews (or live feed).
- Photos and video: the shared folder arrived ("Pictures"); 32 resized photos are on the site (2026-10-28). Still needed: video, team photos, floor coating, lifts and garage door photos, and customer permission for photos and names.
- Phone, email, hours, address for the footer; warranty and installation details; product specs (motorized racks, slatwall colours and finishes, cabinets, floor coating system, fans), which the client deferred to the designer.
- Financing provider; the primary offer wording and the incentive pop-up offer; the tagline and branding tweaks; a name for "Slatwall" (red).
- Final city-page list; whether /nextdoor and /sael are still used; which ad landing variants at launch; who approves new thank-you URLs in Google Ads.
- GoHighLevel and Jobber details (waiting); Calendly to GoHighLevel move; Drip access; LeadPages decision; Gravity Forms licence; tracking containers (Tag Manager, Meta Pixel, call tracking) to carry over.
- Legal review of the privacy policy and SMS terms.
- Testimonials and the blog setup (David, 2026-10-23): should testimonials be a blog category, or pulled live from Google Reviews? And does the client want a traditional Posts page (for example `/blog`) for the articles? Not changed at launch; see `docs/later-scope.md` ("Post-launch restructuring").

## C. To build
Added 2026-10-23: fill the pages marked "details from the client" (Lifts, Garage Doors, Floor Coatings, How It Works, About, Terms and SMS Terms); keep `/garage-exhaust-fans` as the URL or move to `/garage-ventilation` (David's call); connect the form to GoHighLevel and Jobber and point the scheduler at GoHighLevel's calendar when confirmed (the live site's leads go to LeadPages; where they are used next is unknown).
- Tracking carry-over at launch and the open items about `/ma`, `/nextdoor` and `/sael`: `docs/tracking-inventory.md`. **No new conversion triggers in this scope** (David, 2026-10-23).
- ~~Two-step quote flow~~ **Built 2026-10-23, matched to the live site's steps** (form, then Calendly on the thank-you page). The Contact form now has the live Gravity Forms fields (the discount code is optional, David 2026-10-23, although the live label shows an asterisk).
- Quote form next steps: connect to GoHighLevel and Jobber (set `GK_LEAD_WEBHOOK` or write an adapter once their details arrive); set `GK_LEAD_EMAIL`; build the step 2 scheduler; the Contact page form (its own thank-you URL); decide on invisible spam protection beyond the hidden field; lead photos are saved in the public uploads folder (move them to private storage before launch); data retention for leads; have the client's lawyer review the consent text.
- ~~Estimator~~ **Removed from scope 2026-10-23.** The header nav wraps below about 1100 px (add a menu button on tablets).
- Our Work with before/after sliders; project story template; How It Works (merge steps 2 and 3); FAQ; About; Reviews (Google feed); Contact.
- ~~Quote page and forms~~ **Done 2026-10-23:** quote form in two steps and the Contact form matched to the live site's fields; one thank-you URL per form. Photo upload and estimates are out of scope (David, 2026-10-23). reCAPTCHA: add later if needed (the forms use a hidden field and a minimum fill time for now).
- Lifts and Garage Door Installation and Repairs pages exist as drafts (2026-10-23) and need the client's content; Project Stories is a Coming Soon page. Still to do: Floor Coatings, Cabinets and Terms content; the city page template.
- Ad landing page `/ga` and keyword variants; `/nextdoor` and `/sael` if still active; Privacy, Terms and SMS; 404.
- Real logo; WebP images; accessibility check; tablet check of the header. **Done 2026-10-28:** self-hosted fonts (Inter and Poppins, latin subset, `assets/fonts`), page descriptions and share tags, LocalBusiness structured data on Home and Contact. **Still open:** Review schema (needs the real review count and rating), FAQ schema (needs the FAQ answers approved by the client), street address and hours in the structured data.
- Test the blueprint in real Playground; read pages 15 to 18 of the questionnaire PDF as images for their colours.

## D. Found on the current site (not in the Website Content Outline)
Done 2026-10-17: the prototype now shows the current site's text and photos (see `docs/site-notes.md`), without the shop. Still to decide below.
Checked 2026-10-17 from the site's public sitemaps and pages:
- **A working shop and cart:** `/shop/`, `/cart/`, `/checkout/`, `/my-account/` and about 35 products, many of them "installation appointment" and packs (the client's answer says no online sales today, so confirm what these are for before removing them).
- **`/steel-cabinets/`** already exists; the Website Content Outline calls cabinets new at `/garage-cabinets`. **Decided (David, 2026-10-23):** keep `/steel-cabinets`, and `/garage-cabinets` redirects to it. Restructuring cabinets later is in `docs/later-scope.md` ("Post-launch restructuring").
- **21 blog posts and 5 individual review posts** at root URLs (for example `/fall-garage-prep/`). **Included (David, 2026-10-23):** all 26 are imported at their current addresses; the posts keep the live categories (Blog, Testimonials), so `/category/blog/` and `/category/testimonials/` exist as on the live site. They carry search history.
- `/lumpsumcontract/` and a test page `/test-woocommerce-page/`.
- Pages that do **not** exist today: About, FAQ, How It Works, Service Areas, Blog index. `/ga`, `/nextdoor` and `/sael` do exist.

## E. Launch and the hub
- Design the deploy step from the repo to the server (hub to-do 21).
- Follow `docs/launch-checklist.md`: the server's new-site checklist, the standard access (`claude-waws`, `cdt24`, Simple History), In The Loop, Matomo, WHMCS; Under Construction off; archive banner.
- Plan 301 redirects for every retired URL before launch; keep `/ga` and its conversion tracking intact.
- Hub: `docs/site-todo.md` (plugin items on the live sites) is still open.

## F. From David's notes of the Oct 19 call (added 2026-10-26)
Compared against everything above. Items already built need no action; new requests are sorted by the rule for this scope (nothing new that differs from the current site) and parked in `docs/later-scope.md` until David decides.

**Already built or already decided (no action):** form first and phone number only in contact areas; "boutique, family owned and operated expert in luxury full garage renovations" and no "affordable"; "Overhead Storage Solutions" with "Motorized Overhead Racks" and "Slatwall" kept as headings; "Garage Ventilation"; Lifts and Garage Door Installation and Repairs pages (drafts); Project Stories "Coming soon"; no online sales (shop left out); one service-area statement with "Don't see your town?"; Houston, Waco and Dallas are not mentioned anywhere on the site (checked 2026-10-26); regular prices on the Pricing page.

**New requests that differ from the current site (David to decide; none built):**
1. **Photo upload** on the quote, scheduling and contact forms: the client wants it (Outline step 2 also lists it). Earlier set out of scope on 2026-10-23. Needs a decision, private storage for the photos, and a note on `/schedule-quote`.
2. **Incentive pop-up with a coupon code** (placeholder built 2026-10-28, see `docs/later-scope.md`) the visitor can quote when booking (the Contact form already has an optional discount code field). Not built; the offer wording is not confirmed.
3. **Size chart as a visual diagram** (rack sizes built as a to-scale diagram 2026-10-28) (the Outline's site map says a diagram, not a table). Today the size charts and ceiling-height visuals are untouched, as on the live site.
4. **Price estimator and financing**: client says possibly a new small scope of work. Already parked.
5. **City pages**: starter set Cedar Park, Boerne (the notes say "Bourne"), Horseshoe Bay, Hill Country Village. Not in this scope; the service-area statement stays. The reason to build them later: the ads should never show a city the site does not mention.
6. **Offers placed on the site** (sample offers displayed 2026-10-28) (not in a linked document): the client has the list of ongoing offers from the questionnaire; the last limited-time offers ended 2026-10-19 and are not published. Need the current list and who updates it. David offered to show the client how to update the Pricing page himself.
7. **Testimonials**: how were they collected, and should Google reviews be integrated? (already in section B).
8. **Drip**: David wants to review it to see how it connects to new features.

**Needs the client (added to section B):** tweaks to branding and slogans such as the brand sign-off; GoHighLevel, Jobber and Wisestack information (and a decision on GoHighLevel: the client will probably go with it but has not); details of the three combo packages; details of Lifts and Garage Door Installation and Repairs (**we think Lifts means attic lifts, including Versalift products: confirm**); an estimate of garages completed; wording for guarantees and product warranties; all social media links; the shared Google Drive folder of photos (not received); a full installation description (could be a separate small project); a broad Privacy Policy and Terms are fine, say if specific wording is wanted.

**Other:**
- **Gravity Forms is giving errors on the live site** (raised again on the call). The new build does not use it (it uses the `gk-leads` plugin), so it will not carry this error over. The live site's problem is for the client to decide on.
- **Conversions:** a form submission is a conversion; the true conversion is the Calendly booking. The new site must keep the live site's Calendly booking tracking working: `docs/tracking-inventory.md`.
- **Possible new scope** (not quoted): help implementing GoHighLevel and cleaning up the client's CRM processes.
- **David's deliverable at the end:** build down the site map and the questionnaire as a checklist, with one-to-one answers to the Website Design Template and Questionnaire explaining how each item was met. Plan: `docs/deliverable-checklist.md` (not started).
- **Ads:** the client's ad plan (budget, keywords, geography) is private and lives in Drive, not in this repo. Website points that touch the ads: keep `/ga` and its thank-you address; keep the service-area wording in step with the ads; no "affordable" in any ad or page.
