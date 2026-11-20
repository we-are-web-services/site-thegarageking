# Lead capture, conversion tracking and the move to Go High Level

Written 2026-11-18 (Central) for David. Internal; nothing here goes to the client unless David says. No credentials, only names. Built from: the tracking inventory (`docs/tracking-inventory.md`), the Drive documents "Tag Manager and Analytics Inventory (read-only)" (2026-11-12) and "Tracking & Lead Flow (draft)", `waws-ads` `decisions.md` (C-01 to C-08), and David's Nov 13 Zoom notes. **Confirmed** means read from the live site or an account. **Unknown** means nobody on our side has seen it yet. Nothing was submitted or changed to write this.

## 1. How a lead arrives today

| Step | What happens | Status | Counted as a conversion in Google Ads? |
|---|---|---|---|
| 1 | Google Ad (account AW-783955965) sends a click to a landing page: `/ga` (and `/ma`). Meta Ads (managed by a woman named Laura, not us) sends traffic to LeadPages pages (`/nextdoor`, `/sael`, likely `/ma`) | Confirmed pages; which Meta page serves which ad is unknown | **No.** The click itself is not a conversion |
| 2 | LeadPages page shows the 2-step form: step 1 is name, email, phone, address, garage size and details, consent, reCAPTCHA, button "Go to step 2" | Confirmed (public page) | **Partly.** The secondary action "Submit lead form: .../ga-thank-you-page-garage-talk". One tag fires on a click on `/ga`, another on a click of the text "Click Here" on `/ga` pages. It counts clicks, not submissions (C-06). Not used for bidding |
| 3 | On submit the visitor lands on `/ga-thank-you-page-garage-talk` ("Almost there!") with the client's Calendly page (`calendly.com/thegarageking/garage-talk`) embedded | Confirmed | **Partly.** The same secondary lead-form action also fires on a view of this thank-you page. Counts page views, not submissions |
| 4 | The visitor books a consultation call in Calendly. **This booking is the conversion Justin cares about** (C-01) | Confirmed | **Yes, primary.** "Book GA appointment via Calendly". A Tag Manager listener hears the Calendly "booked" message and pushes `calendlyFormSubmitted`. This is the conversion that steers bidding |
| 5 | Where the step-1 lead data goes (LeadPages leads list, email, Zapier, Drip, QuickBooks, the Google spreadsheet Shannon keeps metrics in) | **Unknown.** Justin offered Drip access; the Zap list is not seen | **No.** Nothing is counted here |
| 6 | Separate path: `/contact-us` is a Gravity Forms form (form 2, v3.1.3) on the WordPress site (Divi 4.27.9, WP Engine); form 4 (home page) and form 5 (overhead racks) work the same way. On submit each form emails info@, adds the contact to Drip, feeds a Zapier-driven Google Sheet, and sends the visitor to `/wp-schedule-your-consultation/`, which has the same Calendly booking page embedded, so the visitor can book a consultation call | Confirmed in the transition report (settings read 2026-11-18). An earlier note here said the form shows a confirmation on the same page with no page change; the report supersedes it. Nothing was submitted to test | **Partly.** The form submission itself is not counted anywhere: the tag that should count it (`form-event Tag`, which waits for `.gform_confirmation_message`) sends to retired Universal Analytics, and may never fire if the form redirects (unverified). The booking made on `/wp-schedule-your-consultation/` is counted by the same Calendly booking conversion as step 4 |
| 7 | Third path: phone calls from ads (call assets and click-to-call) | Confirmed in Google Ads | **Yes, primary, two actions.** "Calls From Ads (Google)" (30 seconds) and "Calls from ads" (60 seconds), so a call can be counted twice (C-03). Google call tracking, not Tag Manager |

Other actions Google Ads also lists (not a step in this flow): Google-hosted local actions (clicks to call, directions, website visits, engagements) marked primary but not in the account goals, one misconfigured primary "Submit Lead Page: /ga" action that records 0, and four retired Universal Analytics actions still marked primary (section 2 and the transition report, 3.4). Counts for each action are in section 2.

**Do visitors from outside Google ads get counted? (read-only check, 2026-11-20, Oct 19 to Nov 17).** Mostly no. The Calendly listener runs on every page, so it fires for any visitor who books, but Google Ads credits a booking only when it can tie it to an earlier Google ad click. In Google Analytics, 14 of the 18 views of the contact-form booking page (`/wp-schedule-your-consultation/`) came from Direct, Organic Search or Referral, and only 4 from Paid Search; the ad-page thank-you page had 35 of 38 views from Paid Search. In Google Ads all 15 counted Calendly bookings were same-day after the ad click, so none was credited from an older click. A visitor who clicked an ad earlier the same day and then used the contact form is counted, correctly, and cannot be told apart from the ad-page path. One 30-day window, small numbers. Detail: `waws-ads` `clients/the-garage-king/decisions.md` (C-15).

Open question that matters for the site plan: **how `/ga`, `/ma`, `/nextdoor` and `/sael` are served on thegarageking.com** (the LeadPages WordPress plugin, or a DNS or proxy setup). It decides whether switching the WordPress theme touches them at all.

## 2. How Google Ads records conversions today

Everything runs through Tag Manager container `GTM-NNRHM92` (all pages; 10 tags; live version 14, published May 2024; nothing since). A second container, `GTM-NGTQ2D2P`, is on the contact page and **David's login cannot see it**.

| Conversion | What fires it | In Google Ads (60 days to Oct 25) |
|---|---|---|
| **Booking** "Book GA appointment via Calendly" | A Custom HTML tag on every page listens for the Calendly widget's "booked" message and pushes the event `calendlyFormSubmitted`; that event fires the Google Ads conversion tag | **Primary**, 39. Works only while the Calendly widget is on the page. Status shows "Needs attention" (known website-code suggestion) |
| **Calls from ads** | Google's own call tracking | Primary, 11 ("Calls From Ads (Google)", 30 s). A second action, "Calls from ads" (60 s), is also Primary, so it can double count (C-03; it recorded 1 call in the Nov 9 to 15 week) |
| **Lead form** "Submit lead form: .../ga-thank-you-page-garage-talk" | Two tags share one label. One fires on a view of the thank-you page or a click on /ga; the other on a click of the text "Click Here" on /ga pages. **They count clicks, not submissions** (C-06) | Secondary, 65. Not used for bidding |
| Gravity Forms submit | `form-event Tag` fires when `.gform_confirmation_message` appears and sends a Universal Analytics event | **Dead**: Universal Analytics no longer collects data, so contact-form submissions are not measured anywhere |
| Microsoft Ads | Bing UET tag on all pages; "Bing Calendly Tracking" really fires on the "Click Here" click, not a booking | Microsoft has not started |
| Meta | Meta Pixel `2031676537068080` is in the site's own code, not in Tag Manager | Events unknown |
| Analytics | Two GA4 properties collect data, **neither installed through Tag Manager** (A: G-63MR0WMT4D, linked to Google Ads, no useful key events; B: G-05NP7FZ4D0, gets most paid traffic, not linked). Neither records bookings or form submissions | Confirmed |

The Google Ads booking conversion is the one that steers bidding. **Anything that stops the `calendlyFormSubmitted` event stops the account learning from bookings**, with no visible error.

## 3. Question 1: can we apply our design to the current site through the dashboard?

**Yes, with limits.** Our build is a custom theme plus a plugin (`gk-leads`), not something that layers onto Divi page by page.

- **Switching the theme is all or nothing.** Divi stores layout in each page's content and settings in the database, so the day the theme changes, every Divi page changes. We replace every page with our own content in the same step. Pages are not restyled one at a time.
- **Do it on a staging copy first.** WP Engine can copy the live site to staging from a backup point. Rhonda asked for a staging copy on 2026-10-23 and it is unanswered. Who owns the WP Engine account is not recorded here; confirm. Search results say WP Engine advises, for database-stored theme settings, to test on staging or with its preview and rely on its backups ([source](https://wpengine.com/resources/staging-sites/)). Not verified for Divi specifically.
- **Forms stay as they are.** Contact stays the Gravity Forms form 2, so `.gform_confirmation_message` and the Tag Manager event keep working. The ad pages stay on LeadPages unchanged. So **our own quote, ads and contact forms (`gk-leads`) are not activated in this phase**, and the pages built around them (`/schedule-quote`, our `/ga` and thank-you pages) are held back, or point at the existing pages. Needs David's decision per page. Gravity Forms must stay licensed (open item).
- **Tracking stays as it is:** same Tag Manager container, same Meta Pixel and GA4 tags (found in the Divi theme settings or LeadPages and **installed again in the new theme exactly once each**), same thank-you path, same Calendly embed.
- **Access.** A dedicated WordPress Administrator user with an Application Password stored as a named Network secret (the way the other 15 sites were done, `waws-hub` to-do 18), over the REST API, plus a headless browser for wp-admin screens the API does not reach. **The REST API cannot install a custom theme or plugin from a zip**, so either David uploads the zips in wp-admin, or Claude uploads them through wp-admin in a headless browser. WP Engine's own portal (backup point, staging, copy to live) stays with David. Rules apply: say what changes, back up first, wait for the go-ahead, check afterward, record.
- **Timing (my suggestion):** the 30-day check of the ad changes is 2026-11-26. A theme switch and tag reinstall in the middle of that measurement would muddy it, so schedule the live switch after it, and never in the middle of a week's Digest read. **Start (David, 2026-11-20):** as soon as the client accepts the proposal, and not before Nov 26. Target launch window, depending on access: roughly late January to mid-February if the work is approved in early December.

## 4. Question 2: moving to Go High Level (GHL)

**Recommendation (unchanged): website first with today's tools, then GHL as its own phase.** Each step below can be undone before the next begins.

**Phase 0, read-only facts (needed before any price or date)**
1. LeadPages: how the pages are served, where each form's leads go, any integrations.
2. Zapier list, Drip sequences, Calendly integrations and webhooks, QuickBooks: what each receives.
3. Second Tag Manager container `GTM-NGTQ2D2P` (needs access from Justin).
4. Who owns the GHL account and whether we get access; which GHL plan (white-label or not).
5. Where the Meta Pixel and the two GA4 tags sit.

**Phase A, new look on the current site** (section 3). Tracking verified in Tag Assistant before and after.

**Phase B, GHL built beside the old system (no cutover).** Pipeline, calendar, forms, texting and email follow-ups, workflows that mirror what Zapier and Drip do today, the reports Shannon keeps in the spreadsheet. Test with internal leads only.

**Phase C, landing pages on the website, forms and calendar feeding GHL.**
- Landing pages are built in WordPress at the **same addresses** (`/ga`, `/ma`, thank-you path), so ad URLs and tag triggers hold.
- Form: our own form posts each lead to GHL by webhook (the plugin has a webhook setting) rather than an embedded GHL form, to keep design control and the Tag Manager event. **Add hidden fields for the Google click ID (gclid) and UTM source data.** Search results say the click ID is often lost when a GHL form sits in an iframe on another site, so this must be designed in, not assumed ([source](https://www.customerlabs.com/blog/gohighlevel-google-ads-conversion-tracking-missing-gclid-fix); a vendor page, no official GHL document found).
- Calendar: GHL's calendar is embedded on the thank-you page at the same address, replacing the Calendly embed. GHL offers one-on-one, round-robin, group and combined calendars, a connection to each person's Google or Outlook calendar, email and text reminders, reschedule and cancel links, and a booking that creates the contact (from vendor and community pages, not GHL's official documentation). **Still to confirm in the client's GHL account:** a redirect after booking to a "you're booked" page on our own site (set in the calendar's confirmation settings); which calendar features come with their plan; and that the Google click ID is kept at the form step, because a click ID cannot be relied on to pass through a redirect. Whether Calendly is retired is the client's choice after the parallel test.
- **Booking conversion, the main risk.** The current listener hears Calendly only. GHL's calendar widget also sits in an iframe, which Tag Manager cannot see into. Two routes: (1) a listener for the widget's completion message that pushes a data-layer event ([one practitioner's snippet](https://krmdigital.uk/?p=3206), unverified); (2) send the visitor to a dedicated "booked" page after booking and fire the conversion on that page view ([discussed here](https://consultevo.com/?p=6971)). I prefer (2) if GHL allows the redirect, because it is easy to test; **not yet confirmed against GHL's own documents.** Either route should **fire the same Google Ads booking conversion action** so its history and bidding data continue.
- **Run old and new in parallel before cutover**: Calendly and its listener stay live until a test booking in GHL records exactly one conversion in Google Ads, then Calendly and its tag are retired together.
- Later, optional: GHL can send closed-won stages back to Google Ads as offline conversions (needs the gclid saved on the contact). That would let bidding learn from jobs sold, not only calls booked. A separate decision.

**Phase D, phones.** Moving off Vonage is the riskiest part (a missed call is a lost lead; texting needs consent wording and carrier registration). Last, only after A to C are stable, and the call tracking in Google Ads is re-checked.

**Phase E, clean-up.** One GA4 property linked to Google Ads with real key events (form submission, booking); retire the Universal Analytics tags; fix the double-counted lead and call actions (C-03, C-06); remove Calendly and LeadPages when unused.

## 5. Questions that decide the plan (one at a time with David)
1. Is the live site's LeadPages content served by a plugin or by DNS? (Phase 0.)
2. Does the client own the WP Engine account, and will they open a staging copy?
3. For Phase A, do the ad pages (`/ga`, `/ma`, `/nextdoor`, `/sael`) stay on LeadPages untouched, or does our look reach them?
4. Does the client pay for GHL and give us access, and is scope or price a separate conversation with Rhonda? (None exists yet.)
