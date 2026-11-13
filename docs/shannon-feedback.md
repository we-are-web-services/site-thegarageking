# Shannon Earl's feedback (2026-11-03, 21 notes): what the build does

Branch `claude/shannon-feedback`. Wording marked **[Proposed]** is ours for the client to approve. Draft boxes remain on pages that still need Justin's content.

| # | Note | What was done |
|---|---|---|
| 1 | Garage Door first in Solutions | Menu, Solutions cards (also used on the hub page) and footer now list Garage Door Installation and Repairs first. |
| 2 | Motorized Overhead Storage Solutions | Renamed in menu, cards, footer, page title and H1. URL unchanged (`/motorized-overhead-racks`). |
| 3 | Garage Climate Control | Renamed in menu, cards, footer, page title and H1. URL unchanged (`/garage-exhaust-fans`). **Overrides the earlier "Garage Ventilation" wording (2026-10-23); confirm.** |
| 4 | Our Work order | Project Stories first, then Photo Gallery. The "Our Work" label links to Project Stories. |
| 5 | "Our Process" | Menu, footer, page title and H1. URL unchanged (`/how-it-works`). Treated as a yes; confirm. |
| 6 | Steps as moving icons | Four steps in a swipeable carousel with icons, buttons and slow autoplay (pauses on hover or focus, off for reduced-motion). Works without JavaScript. `assets/carousel.js`. |
| 7 | Hero photo | Replaced with a finished garage (slatwall, bikes, white racks). |
| 8 | Copy | Steps rewritten around what the customer gets **[Proposed]**. |
| 9 | Less text on Pricing | "Starting prices" blocks; product details, accessories, conventional-garage rules and the price list are collapsible. |
| 10 | Price calculator ("a pricing calculator if the user wants to calculate pricing for mixed combinations") | Built as asked: combine products, estimate at regular prices (`assets/calc.js`). Floor coating by square foot. Offers and packages are applied at booking. Open: whether it should also apply the packages (prices not final) or pass the estimate to the form (wait for the tracking and form decisions). |
| 11 | Financing links | Above and below the calculator, jumping to the Financing section (still a draft box: no provider chosen). |
| 12 | (none) | |
| 13 | Reviews out of the menu | Removed from the main menu. `/testimonials` stays and is reached from the carousel. |
| 14 | Reviews on every page | `garage-king/reviews-carousel` is added to `templates/page.html`. Hidden on quote, contact, thank-you, ad landing pages and Testimonials. The home page keeps its own reviews section. |
| 15 | Garage door page | Three services: repair, replacement, new installation. Brands, warranty and photos stay in a draft box. |
| 16 | One Services page or two | One hub page now lists garage doors first, then storage. |
| 17 | Different from competitors FAQ | Added as the first FAQ **[Proposed]**. |
| 18 | Garage door FAQs | Three added, generic **[Proposed]**; client approves. |
| 19 | Blog | Not built: posts are content. Plan: storage and garage-door tips posts after launch (`docs/later-scope.md`). |
| 20 | Tone ("evolve") | Ask Shannon what "evolve" means before a site-wide rewrite; touched pages use plainer, outcome-led wording. |
| 21 | Landscape hero video | Parked in `docs/later-scope.md`. |

Not tested in a live WordPress this session: patterns lint with `php -l`, and the carousel and calculator were run in Chromium in a static harness (calculator total checked, carousel scrolls). Check the Playground link.
