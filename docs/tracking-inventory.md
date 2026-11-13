# Forms and tracking on the current site (to match, not change)

Read 2026-10-23 from the live site's public pages and the Google Ads audit (Drive: `Client Work/Second Life Enterprises LLC/The Garage King/Google Ads`). Nothing was submitted. **Rule (David, 2026-10-23): the new build adds no steps, functions, conversion triggers or actions that differ from the current site. If the client wants changes, they come later** (see `docs/later-scope.md`).

## What the current site does

| Where | How it works today | What the new build does |
|---|---|---|
| `/ga` and `/ma` (Google Ads landing pages, LeadPages) | One form (first and last name, email, phone, street address, city, state, postal code, garage size and details, consent, reCAPTCHA), button "GO TO STEP 2". On submit the visitor goes to `/ga-thank-you-page-garage-talk` | Same fields and steps. The form ends on `/ga-thank-you-page-garage-talk/`. `/ma` is not built yet (see open items) |
| `/ga-thank-you-page-garage-talk` (LeadPages) | "Almost there!", the client's Calendly page embedded (`calendly.com/thegarageking/garage-talk`) | Same address, same embedded Calendly page |
| `/contact-us` (Gravity Forms, form 2) | One form, no page change on submit: a confirmation replaces the form | Same fields. Confirmation shown on the same page. The confirmation text is the Gravity Forms default and is not confirmed (nothing was submitted on the live site) |
| `/nextdoor`, `/sael` (LeadPages) | Offer pages; where their buttons lead is not known (the buttons are scripted) | Buttons go to `/schedule-quote`, which ends on the same thank-you address as `/ga` |

## Conversion triggers seen on the live site

- **Lead form thank-you page.** The Google Ads conversion action "Submit lead form: .../ga-thank-you-page-garage-talk" is tied to that page (Ads audit, section 8). The audit says it is currently excluded from the conversion count; whether that is intentional is an open question for the client. **The new thank-you page keeps this exact address so nothing changes.** Check at launch whether the Tag Manager trigger matches the address with or without the final slash (WordPress adds one).
- **Scheduled call.** A script on the contact page listens for Calendly's `calendly.event_scheduled` message and sends a Google Ads conversion (`AW-783955965`, with its conversion label). It was not found in the HTML of `/ga` or the thank-you page, so where scheduling is tracked there is not known.
- **Gravity Forms submit.** Pushes a Tag Manager event `form_lead_submit` with `cov_form_type: "Gravity Form Plugin"`, `cov_form_id` and `cov_form_name`. The new contact form sends the same event (form id 2, name `Form id-2`) when its confirmation shows.
- **Other tags on the page source:** Tag Manager container `GTM-NNRHM92` on all pages (a second container, `GTM-NGTQ2D2P`, on the contact page), Google Ads `AW-783955965`, Meta Pixel `2031676537068080`, three older Universal Analytics IDs, a `begin_datalayer` event with conversion-tracking switches (email, phone, address, scroll, file download) and file download click tracking. Google Analytics 4 access was shared by Justin on 2026-10-19; its measurement ID was not seen on the pages.

## Not built in the prototype (carry over at launch, `docs/launch-checklist.md`)

The Tag Manager container, Meta Pixel, the Calendly listener and the other tags are not in the prototype, because it has no live traffic and must not fire real conversions. At launch, install them as they are on the current site, then confirm in Tag Assistant and Google Ads that every conversion above still records.

## Open items

- `/ma`: a landing page on the live site that is not in the client's Website Content Outline & Questionnaire. Needs the same treatment as `/ga` (build it, or redirect to `/ga`; decide with the client).
- Where the buttons on `/nextdoor` and `/sael` lead, and whether those pages record conversions.
- Whether the Calendly scheduled-call conversion is recorded on the thank-you page (ask Justin, who has the Tag Manager and Google Ads access).

## Added 2026-10-26 (David's notes of the Oct 19 call)
- **A conversion is a form submission; the true conversion is the Calendly booking.** The booking is the event that matters most for the ads. Because the Calendly listener was only found on the contact page, **before launch confirm in Tag Manager (read access needed from the client) that a booking made on the embedded Calendly page of `/ga-thank-you-page-garage-talk` sends a conversion**, and that the new site keeps doing so.
- The ad plan, budget and keywords are private and live in Drive (`Ads/Google Ads/Plans`), not here.

## Added 2026-11-12 (Tag Manager read: what the launch must keep working)
Read in the client's Tag Manager and Analytics (read-only). Nothing private here: no emails or account details.
- **The Calendly booking conversion is a Tag Manager listener that runs on every page.** It hears the Calendly widget's booked message and fires the Google Ads booking conversion. It works only while the Calendly widget is on the page, so the thank-you page keeps the embed. If the scheduler is replaced later (for example a GoHighLevel calendar), a new listener and trigger are needed first. This corrects the note above that it was only found on the contact page.
- **The lead tags depend on exact page paths and wording:** `/ga/` pages, the thank-you path `ga-thank-you-page-garage-talk`, click text "Click Here" and "Schedule a Free Consultation", and the Gravity Forms confirmation class `.gform_confirmation_message`. The new forms use different button labels ("Go to step 2") and no Gravity Forms class, so at launch either match these or update the triggers in the same step. Test each in Tag Assistant before the old site is replaced.
- **The Gravity Forms analytics event goes only to the old Universal Analytics,** which no longer collects data, so form submissions are not measured in Analytics today.
- **Two Analytics 4 properties receive data, and neither is installed through Tag Manager.** The Meta Pixel is also in the site's own code. Find these tags in the Divi theme settings and in LeadPages and carry them over, installing each once. Pick one Analytics 4 property for the new site.
- **A second Tag Manager container (on the contact page) was not visible to David's login;** it may handle the `form_lead_submit` push. Confirm before launch.
