# The Garage King: build brief (draft 2)

Drafted 2026-10-17 from the client folder in Drive (including the colour-coded comments on the questionnaire PDF), the email threads with the designer, the UX Pilot homepage mockup, and the live site.
**Source order:** the questionnaire and the current site decide content and facts; the UX Pilot mockup is a design reference only; later client answers fill the placeholders.
Written to be safe in a repo that may go public: no prices, contact details, logins or personal data. Those stay in Drive.
Everything here is a starting point; items marked **CONFIRM** are not settled.

## 1. Project snapshot

- **Client:** The Garage King, a family-owned Central Texas garage storage business (overhead racks, slatwall, cabinets, floor coatings, exhaust fans). In business since 2004.
- **Who sends work:** Second Life Enterprises (designer and marketing partner) relays client material. The client's decision-maker is the owner; a second contact handles day-to-day copy and the questionnaire.
- **Goal:** replace the current brochure-style site with a conversion-focused, photo- and video-led site that feels premium. The client wants to be seen as the boutique, family-owned expert in luxury garage renovation, **not** the cheapest option.
- **Approach (decided with the designer, 2026-10-13):** build in a sandbox, then replace the old site at launch. Keep existing URLs wherever a page is rebuilt, so search rankings and ad tracking survive.
- **Primary conversion:** 1. book a consultation (button), 2. tappable phone number. The client wants customers to book before they call. **Primary offer chosen: a free design consultation** (marked green). The button reads "Book a Free Design Consultation".
- **No online sales today.** The shopping cart on the current site is not used.

## 2. Design direction (UX Pilot mockup = reference)

First fold and section list were read from the shared mockup. The mockup is a rough draft by the client, not a final design.

- **Look:** dark, high-contrast, bold italic uppercase headlines, thin vertical side navigation, large photography. Palette: near-black, white, light grey, and one amber accent (about `#FFB117`). Fonts in the mockup: Inter and Poppins. The logo is a gold crown "G" on black.
- **Homepage sections in the mockup:** hero ("Own your garage space", family-owned message, estimate button, rating and years badges) -> offer strip -> Our Solutions (overhead storage, slatwall, full renovations, garage fans) -> "The King Difference" (not a franchise, local, direct pricing, guarantee, fast install) -> pricing section with a **project cost calculator** (service area, solution, quantity) -> before/after slider -> offer banner -> reviews -> service-area map and city list -> FAQ -> final call to action.
- **Sitewide (from the sitemap):** sticky header with phone and one primary button; sticky mobile bar (Call Now / Book Consultation); trust bar; an incentive pop-up that appears **after scrolling** (client choice; not on quote, thank-you, contact, or ad pages; not built yet); pre-footer call to action band; footer with the tagline "Can't find a thing? Call the Garage King!" (yellow: branding and slogans to be tweaked).
- **Readability:** comfortable text size, good contrast, pinch-to-zoom left on (the current site blocks it), captioned video.

## 3. Pages (client's sitemap, 25 templates)

Keep the existing URL where marked.

| # | Page | URL | Status |
|---|---|---|---|
| 1 | Home | `/` | redesign |
| 2 | Garage Storage Solutions (hub) | `/garage-storage-solutions` | new |
| 2a | Overhead Storage Racks | `/overhead-racks` | redesign, keep URL |
| 2b | Motorized Overhead Racks | `/motorized-overhead-racks` | new |
| 2c | Slatwall | `/slatwall` | redesign, keep URL |
| 2d | Steel Garage Cabinets | `/garage-cabinets` | new |
| 2e | Garage Floor Coatings | `/garage-floor-coatings` | new |
| 2f | Garage Exhaust Fans | `/garage-exhaust-fans` | new |
| 3 | Our Work (gallery) | `/garage-storage-photos` | redesign, keep URL |
| 3a | Project story (one per project) | `/garage-storage-photos/[project]` | new, template |
| 4 | How It Works | `/how-it-works` | new (client: merge steps 2 and 3) |
| 5 | Pricing and Offers | `/garage-storage-pricing` | redesign, keep URL |
| 6 | Reviews | `/testimonials` | redesign, keep URL |
| 7 | FAQ | `/faq` | new |
| 8 | About | `/about` | new |
| 9 | Contact | `/contact-us` | redesign, keep URL |
| 10 | Service Areas | `/service-areas` | new |
| 10a | City page (optional) | `/service-areas/[city]` | new, template |
| 11 | Blog | `/blog` | new, **phase 2** |
| 12 | Privacy Policy | `/privacy-policy` | keep URL |
| 13 | Terms and SMS Terms | `/terms` | new |
| 14 | Schedule Your Phone Quote | `/schedule-quote` | new, hidden |
| 15 | Thank-you pages (one per form) | `/thank-you/quote`, `/contact`, `/ads` | new |
| 16 | Google Ads landing page and variants | `/ga` (+ `/ga/overhead-storage`, `/ga/garage-cabinets`, `/ga/floor-coatings`) | redesign, **keep `/ga`** (live ads and conversion tracking depend on it) |
| 16b | Channel pages | `/nextdoor`, `/sael` | **CONFIRM** still in use |
| 17 | 404 | | new |

Main navigation order: Solutions (dropdown), Our Work, How It Works, Pricing, Reviews, FAQ, About. The current site's nav is Overhead Racks, Slatwall, Pricing, Gallery, Testimonials, Contact Us.

## 4. Client decisions and notes (from the answered questionnaire)

- **Call to action order:** book a call first, tappable phone second.
- **Wording:** use "overhead storage solutions" / "storage solution systems" and "automated or remote storage solutions" to convey luxury. Use "Garage Ventilation" instead of exhaust fans.
- **Pricing page:** show regular pricing, a **cost estimator tool**, and three combo packages named The Emerson, The Duke and The Earl. Financing: planning to offer. Prices and package contents are in Drive, not here.
- **Offers:** recurring offers exist (rack, slatwall, cabinet packages). The pop-up should give an incentive **before** the visitor loses interest, for example "book now" and receive the current offer.
- **Add:** Lifts as a product or service, and a page for Garage Door Installation and Repairs (bookmarked for later).
- **Service area:** Austin, San Antonio and Bell County are ticked; mainly all of Central Texas, San Antonio and the Texas Hill Country, plus a list of suburbs (full list in Drive). Houston and Dallas not served; Houston not added to ad targeting.
- **Company story:** feature the owner by **first name only**. **Social:** Facebook, Instagram, YouTube. **Physical address:** show it. **Response time:** within 24 hours.
- **Pop-up timing:** after scrolling.
- **City pages:** the client picked a starter set (Cedar Park, Bourne, Horseshoe Bay, Hill Country Village); the designer's notes suggest graduating from 2 to 4 or 5 over time. **CONFIRM** final list.
- **Install claim:** "most installs finished in one day" is accurate. Workmanship guarantee and product warranty: yes. Planning calls: phone or video; in-home visits sometimes.
- **Blog:** phase 2, written by the designer or web team. **Video:** wants walkthroughs, motorized rack demo, install time-lapse, video testimonials (shoot to be planned). **Reviews:** live Google feed. **Team:** photos to come. **FAQ:** designer drafts, client approves.
- **Scheduling:** currently Calendly, to migrate to GoHighLevel. Forms must feed GoHighLevel and Jobber so all lead details are captured. Submission email goes to the shared business inbox.
- **Leadpages:** keep for now (needs further discussion). **Drip:** existing email sequences, client will give access.
- **Photos:** a shared Drive folder will replace the spreadsheet in the questionnaire (agreed 2026-10-13). Not received yet.

## 5. Current site (read 2026-10-17, read-only)

Built on **Divi** (theme) with WooCommerce and add-ons for deposits and a QuickBooks payment gateway, Jetpack, Gravity Forms and a Divi form-styling add-on, a gallery plugin, enhanced e-commerce tracking, and a Google site verification plugin. Only seven public nav links. Tracking (Tag Manager containers, Meta Pixel, call tracking) must carry over.

## 6. Plugins for this build

- Standard list: `waws-hub/docs/standard-plugins.md`. Gravity Forms is paid and not on that list; whether the client's licence carries over is **CONFIRM**.
- Extra for this site: a form tool that can send to GoHighLevel and Jobber, a cost-estimator block, a before/after slider, a review feed.
- No online store, so the store add-ons (WooCommerce, Redis, Independent Analytics) are **not** included unless the client changes course.

## 7. Conflicts and open questions

1. **Mockup vs. positioning.** The mockup says "No franchise markups", "Direct pricing" and "Honest Texas pricing", while the client's notes say they are the boutique luxury option, not the most affordable. Pick one voice.
2. **The mockup's numbers are placeholders**, not facts: "3,800+ garages", "4.9 / 412 reviews", the three named reviews, and every price in the calculator. The client still has to supply real counts, reviews and prices (questionnaire item 9 says the garage count is still an estimate to gather).
3. **Calculator rules.** Built with sample prices (`assets/pricing.json`): quantities per product, a service-area label, and the multi-rack offer. Still to decide: real prices, whether it shows exact figures or ranges, and whether the BOGO offers belong in it.
4. **Primary offer.** The client has not decided whether to change it (current: phone quote plus a multi-rack discount).
5. **Photos and video** have not arrived, so the prototype uses placeholders.
6. **GoHighLevel and Jobber** integration details are not specified.
7. **Colour-coded comments** (red = no, yellow = tweak, green = go) were read from the PDF pages and are summarised in section 9.
8. **Terminology.** The sitemap says "Pricing", the mockup uses "Pricing", "Gallery"; the client wants "Our Work". Follow the sitemap.

## 8. What is built so far (first slice)

Block theme `theme/garage-king` with the palette, fonts, sticky header, footer and mobile bar; the Home page (all sections from the mockup, with placeholders); one product page (Overhead Storage) to use as the template for the other five; and a placeholder page for every other page in the sitemap so every link works.
**Next:** Pricing page with the estimator, Our Work, How It Works, Service Areas, the quote page and form.
Placeholder photos are linked from the current site, not copied. Phone, email and hours are placeholders (`000-000-0000`); real details are in Drive.

## 9. Client colour-coded comments (read from the PDF pages)

- **Green (go):** free design consultation as the primary offer; the project story structure (problem, plan, install, result: "GOOD! LOVE!"); the "Don't see your town?" callout; the core (4 to 5) and expanded city sets; photo upload on the quote form; "existing customer" as a contact reason; keyword-matched ad landing pages.
- **Yellow (tweak):** sticky header button label, mobile bar "Get a Quote", the footer tagline and branding/slogans, click-to-call (book a consultation first), the "How it works" preview, "Service Areas" and the number of city pages, "reason for contact" on the form, and the blog rationale.
- **Red (not the direction):** comparing against "luxury full-remodel companies" and the word "affordable" (the client wants the boutique, luxury, not-cheapest position); the product names "Overhead Storage Racks", "Slatwall" and "Garage Exhaust Fans" (use "storage solutions" wording and "Garage Ventilation"; a replacement for "Slatwall" was not given, **CONFIRM**); and "take a few photos of your garage" on the thank-you page (ask for photos on the form instead).
- Pages 15 to 18 of the PDF (the rest of the answered form) were read as text only; their colours are not reviewed.
