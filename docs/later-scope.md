# Later scope: The Garage King

Ideas and features that are **not** in the current scope of work but may be built later, in another scope. Add to this list whenever something is set aside; move an item out when it is picked up. Last updated 2026-10-23. Not the same as `docs/follow-ups.md` (what is still open inside the current scope).

| Item | Why it is parked | What it needs before it can be built | Where to find earlier work or notes |
|---|---|---|---|
| **Price estimator** (service area, products, quantities, estimated total, estimate attached to the quote request) | Nothing on the current site to match; the client has not confirmed having the information to base estimates on (David, 2026-10-23) | The client's confirmation of what can be estimated, which products and units, and whether to show a figure or a range | Last working version in git history, commit `e1a9cfd` (`theme/garage-king/assets/estimator.js`, `pricing.json`, the plugin's estimate box). Notes: `docs/brief.md` section 2 |
| **Photo upload on the forms** | Not on the current site's forms (David, 2026-10-23) | Client decision; storage for photos that is not the public uploads folder | Last version in commit `e1a9cfd` (the plugin's step 2) |
| **reCAPTCHA** on the forms | "Add later if needed" (David, 2026-10-23); the live site uses it. Today: a hidden field and a minimum fill time | Google reCAPTCHA keys (referred to by name only) if spam becomes a problem | `plugins/gk-leads/gk-leads.php` |
| **GoHighLevel and Jobber connection** (leads, contacts, scheduler) | Waiting for the client's details; today leads are stored on the site and can go to a webhook or email | Answers on whether step 1 creates the GoHighLevel contact, whether GoHighLevel's calendar replaces Calendly, when Jobber is involved; set `GK_LEAD_WEBHOOK`, `GK_LEAD_EMAIL`, `GK_SCHEDULER_URL` | `docs/follow-ups.md` sections B and C; plugin header comment |
| **Before and after sliders** on the home page and Our Work | Needs the client's before and after photos | Photos from the shared Drive folder | `docs/follow-ups.md` section C |
| **Project Stories** (one page per project: problem, plan, install, result) | Placeholder "Coming soon" page for now | Project photos, customer permission, stories | `/garage-storage-photos/project-stories`; `docs/brief.md` section 3 (3a) |
| **Live Google reviews feed** | Reviews are copied text for now | Google review access; the real review count | `docs/follow-ups.md` section B |
| **Scroll-triggered incentive pop-up** (not on the quote, thank-you, contact or ad pages) | Not built; the offer wording is not confirmed | The client's incentive offer | `docs/brief.md` sections 2 and 4 |
| **City pages** (`/service-areas/[city]`) and a map | The service area is one statement for now (David, 2026-10-23); the city chips were removed | Final list of cities; local content for each | `docs/brief.md` section 4; old chips in git history (`patterns/service-area.php` on `main`) |
| **Blog** (`/blog`) | Phase 2 in the client's sitemap; the 21 current posts are copied over | Who writes new posts; decision on redirecting or keeping old posts | `docs/follow-ups.md` section D |
| **Video** (walkthroughs, motorized rack demo, install time-lapse, video testimonials) | Shoot not planned | Video files and captions | `docs/brief.md` section 4 |
| **Financing section** | The client plans to offer financing; details unknown | Financing provider and terms | Pricing page |
| **Offer display after booking** (limited-time offers shown to the visitor once they book) | Offers are given at booking by phone or video for now | Client's offer rules and dates (the last ones ended 2026-10-19) | Pricing page "Current offers" |
| **Self-hosted fonts, SEO and schema** (LocalBusiness, Review, FAQ), WebP images, accessibility check | Polish work after content is final | Final content; launch plan | `docs/follow-ups.md` section C |
| **One thank-you page per form** (`/thank-you/quote`, `/contact`, `/ads`, from the client's sitemap) | The new build matches the current site's conversion setup exactly (David, 2026-10-23); changing conversion triggers is for later | Client decision and a matching change to the Google Ads conversion actions and Tag Manager | `docs/tracking-inventory.md` |
| **Tablet menu button** (the header wraps below about 1100 px) | Small fix, not yet scheduled | None | `docs/follow-ups.md` section C |
| **Ad landing variants** (`/ga/overhead-storage`, `/ga/garage-cabinets`, `/ga/floor-coatings`) | Which variants are live at launch is not decided | Client's ad plan | `docs/brief.md` section 3 (16) |

Add new rows at the bottom. Keep each row to one line of "why" and "what it needs".
