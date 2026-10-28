# Pick up here (written 2026-10-23)

Open a session in `waws-hub`, say "continue The Garage King", and attach `site-thegarageking`.

## Where we are
- **First complete draft built** on branch `claude/first-full-draft` (pull request open; David says "merge"). Checked in a local WordPress with Chromium screenshots and a scripted run of both forms. **Not checked:** the Playground link (never opened; the repo is private, so David switches it to public to test, then back).
- **Done this session (David's notes of 2026-10-23):**
  - Form-first: every call to action goes to the form. The phone number stays only in the footer "Get in touch" block and on the Contact page. Removed from the header, the mobile bar, the call-to-action band, the Google Ads page and the schedule page. Footer tagline now reads "Can't find a thing? Ask the Garage King!" ([Proposed], client to approve).
  - Positioning line used on Home, footer, About, king-difference and Contact: "the boutique, family owned and operated expert in luxury full garage renovations, including floors, doors and storage solution systems." "Affordable" removed everywhere.
  - "Overhead Storage Solutions" (the URL stays `/overhead-racks`); "Garage Ventilation" (URL stays `/garage-exhaust-fans`); "Motorized Overhead Racks" and "Slatwall" kept as headings for search, with "automated or remote storage solution systems" in the descriptions.
  - New pages: Lifts (`/lifts`), Garage Door Installation and Repairs (`/garage-door-installation-repairs`), Project Stories "Coming soon" (`/garage-storage-photos/project-stories`). All in the menus; Our Work now has a dropdown.
  - Service area: one statement and one call to action everywhere. City chips and the Bell County wording removed.
  - Ceiling height visuals and size charts untouched.
  - **Pricing on the site:** the Pricing page has the regular prices, add-ons, what each product includes and the "conventional garage" rules from the client's Latest Deals document, and a "Book a call to claim current offers" button. The limited-time offers in that document ended 2026-10-19 and are not published; offers are given when the visitor books. **The price estimator is out of scope** (David, 2026-10-23; nothing on the current site to match it, and the client has not confirmed having the information to base estimates on). It is removed; the last version is in git history (commit e1a9cfd).
  - **Forms match the current site** (plugin `gk-leads` 0.4.0; checked against the live `/ga` page, a LeadPages form, and the live Contact page, a Gravity Form, on 2026-10-23). **Quote and Ads forms, two steps:** step 1 is the form (first and last name, email, phone, street address, city, state, postal code, garage size and details, consent, "Go to step 2"); the lead is saved at once and sent to the webhook and email if set. Step 2 is the scheduler on the thank-you page ("Almost there!"), the client's Calendly page embedded, as on the live site. **Contact form, one step, same fields as live:** first and last name, email, phone, street address, city, zip, how did you hear about us, discount code, garage size, ceiling height, message, consent. Quote and Ads forms end on `/ga-thank-you-page-garage-talk/`, the live site's own address (the Google Ads conversion is tied to it); the Contact form confirms on its page and sends the live form's tracking event. No new conversion triggers: see `docs/tracking-inventory.md`. Set `GK_SCHEDULER_URL` to change the scheduler (for example GoHighLevel's calendar). Not included: reCAPTCHA (add later if needed; a hidden field and a minimum fill time are used for now), photo upload and estimates (out of scope, David 2026-10-23).

## Rule for this scope (David, 2026-10-23)
The new build adds no steps, functions, conversion triggers or actions that differ from the current site. Changes the client wants come later; park them in `docs/later-scope.md`.

## Do first next session
1. David tests the Playground preview (see above) and tells Claude what he sees.
2. Confirm with David: real regular prices are now in the repo (on the Pricing page; they come from a document the current site links to). If he does not want that, revert the Pricing page tables.
3. Fill the pages marked "details from the client": Lifts, Garage Door Installation and Repairs, Floor Coatings, Steel Cabinets (the Latest Deals document sells a Deluxe Cabinet Package, but the current site says cabinets are "coming soon"), How It Works, About, Terms and SMS Terms (still a placeholder).
4. Then: before/after sliders, real reviews feed, tablet menu button (header wraps below about 1100 px), pop-up, SEO and schema, self-hosted fonts.

## Questions for the client (through Rhonda)
- Lifts: what exactly? Garage Door Installation and Repairs: which services and brands?
- Are the cabinets for sale now (Deluxe Cabinet Package) or still "coming soon"?
- Slatwall specs differ between pages: the current page says 200 lbs per panel; the Latest Deals document says 300 lbs per square foot. Which is right?
- GoHighLevel and Jobber: does step 1 create the contact, and does the GoHighLevel calendar replace Calendly? Drip, Wisestack and LeadPages decisions.
- The three named packages (Emerson, Duke, Earl): contents and prices.
- Do they approve the footer tagline "Can't find a thing? Ask the Garage King!" instead of "Call the Garage King!"?
- Photos and video, team photos, hours, warranty and install details, financing; items in `docs/follow-ups.md` section B.

## Added 2026-10-26
Read `docs/follow-ups.md` section F first (David's notes of the Oct 19 call, sorted into built, new requests and client to-dos). New requests (photo upload, coupon pop-up, size chart diagram, city pages) are parked in `docs/later-scope.md` until David decides.

## Things to remember
- Unread: pages 15 to 18 of the questionnaire PDF as images (their colours).
- Photos from the current site are now in the theme (`assets/images/old-site`, 2026-10-28), so they will not break at launch.
- Branches: delete merged branches (Delete branch button); tick "Automatically delete head branches" in each repo.
- Items parked for another scope of work: `docs/later-scope.md`. Full to-do list: `docs/follow-ups.md`. Decisions: `docs/site-notes.md`. What the site should be: `docs/brief.md`.

## Added 2026-10-28
Built on branch `claude/offers-popup-size-diagram` (David's instruction, as previews): sample current offers (Pricing page and Home band), a placeholder coupon pop-up, and a to-scale rack size diagram. Details and what each still needs: `docs/later-scope.md`, "Built 2026-10-28". **Checked** in a static page in Chromium (desktop and phone widths: the diagram, the offer cards, the pop-up opens, closes with Escape and remembers). **Not checked in WordPress** (no local WordPress this session); open the Playground link from the branch and look at the Home, Pricing and Overhead pages. Add `?gk_popup=1` to any address to see the pop-up.

## Client photos added 2026-10-28
32 resized photos (about 1,600 px wide, location data removed, 5.6 MB in total) from Justin's shared Drive folder "Pictures" are in `theme/garage-king/assets/images`, used on: Overhead Storage (tall and vaulted ceilings, recent installs), Motorized Overhead Racks, Slatwall (four finishes), Steel Cabinets, Garage Ventilation and Our Work. Chosen by Claude's judgment (David: "use your best judgement"), no people, plates or house numbers. The full-size originals are in Drive, `The Garage King / Website Build / New Website Build Materials / Photos from Justin`. Not used: customer-named folders (Service Calls, Jobs In Progress, Damages), videos, team photos (people; About page later, with permission), and the supplier-looking cabinet rendering. The 51 photos the draft used to link from the old site (heroes, Welcome, Our Work, About and others) are now downloaded, resized and stored in `theme/garage-king/assets/images/old-site` (7 MB, already-public photos), and served from the theme through `gk_placeholder()`, so nothing breaks when the old site is replaced. 14 newer Google Meet photos over 6 MB could not be downloaded through the Drive tool; David can download them by hand if wanted. Floor Coatings, Lifts and Garage Doors have no client photos yet.

## Added 2026-10-28 (later): fonts and search basics
Inter and Poppins are now served from the theme (`assets/fonts`), so the site makes no Google Fonts calls. `functions.php` adds a short description and share tags for the main pages and LocalBusiness structured data on Home and Contact (phone, email, area served only). Left out until the client confirms: rating and review count, founding year (2004 on the site, 2000 in the ads), street address and hours, and FAQ structured data. Checked by running the head hook in PHP with stand-ins for WordPress and by loading the fonts in Chromium; not checked in WordPress or Playground.

