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
  - **Pricing on the site:** the Pricing page now has the regular prices, add-ons, what each product includes and the "conventional garage" rules from the client's Latest Deals document, and the estimator uses those prices. The $40 rule is gone; the button reads "Book a call to claim current offers". The limited-time offers in that document ended 2026-10-19 and are not published; offers are given when the visitor books.
  - **Two-step quote flow** (plugin `gk-leads` 0.2.0): step 1 saves the lead at once (and notifies the webhook and email if set); step 2 adds optional details and photos to the same lead and has the "choose a time" button; then `/thank-you/quote/`. The Contact page and `/ga` have their own forms and thank-you pages. The scheduler link defaults to the client's current Calendly page (set `GK_SCHEDULER_URL` to change it, for example to GoHighLevel's calendar).

## Do first next session
1. David tests the Playground preview (see above) and tells Claude what he sees.
2. Confirm with David: real regular prices are now in the repo (they come from a document the current site links to). If he does not want that, revert `pricing.json` and the Pricing page table.
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

## Things to remember
- Unread: pages 15 to 18 of the questionnaire PDF as images (their colours).
- Photos are linked from the current site; download them into the new site before launch. Lead photos go in the public uploads folder; move to private storage before launch.
- Branches: delete merged branches (Delete branch button); tick "Automatically delete head branches" in each repo.
- Full to-do list: `docs/follow-ups.md`. Decisions: `docs/site-notes.md`. What the site should be: `docs/brief.md`.
