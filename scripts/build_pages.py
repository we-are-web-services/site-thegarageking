#!/usr/bin/env python3
"""Generate page patterns for the theme from copy and photos on the current thegarageking.com.

The text below was copied from the live site's public pages (2026-10-17) so the client can see the new design
with real content. Online-sales pages (shop, cart, checkout, my-account, products) are left out on purpose:
the client has no online sales today. Run from the repo root:  python3 scripts/build_pages.py
Pages that do not exist on the current site are built from the client's Website Content Outline & Questionnaire and are marked in docs/brief.md.
"""
import html
import json
import pathlib
import re

ROOT = pathlib.Path(__file__).resolve().parent.parent
PAT = ROOT / "theme" / "garage-king" / "patterns"
UP = "https://thegarageking.com/wp-content/uploads/"

TOLL, TOLL_TEL = "844-MY-GKING", "+18446945464"
AUS, AUS_TEL = "(512) 590-2590", "+15125902590"
SA, SA_TEL = "(210) 714-2590", "+12107142590"
EMAIL = "info@thegarageking.com"
BBB = UP + "2012/03/The-Garage-King-Better-Business-Bureau-Accredited-A-Plus-Rating-191x60.png"
SEAL = UP + "2013/03/seal.png"


def e(t):
    return html.escape(t, quote=False)


def ea(t):
    return html.escape(t, quote=True)


def hd(level, text, color=None, align=None, cls=""):
    a = {"level": level}
    c = ["wp-block-heading"]
    if align:
        a["textAlign"] = align; c.append(f"has-text-align-{align}")
    if color:
        a["textColor"] = color; c += [f"has-{color}-color", "has-text-color"]
    if cls:
        a["className"] = cls; c.append(cls)
    if level == 2 and not color and not align and not cls:
        a = {}
    return f'<!-- wp:heading {json.dumps(a)} -->\n<h{level} class="{" ".join(c)}">{e(text)}</h{level}>\n<!-- /wp:heading -->\n'.replace(" {} -->", " -->")


def pa(text, color=None, align=None, cls="", size=None, raw=False):
    a, c = {}, []
    if align:
        a["align"] = align; c.append(f"has-text-align-{align}")
    if color:
        a["textColor"] = color; c += [f"has-{color}-color", "has-text-color"]
    if size:
        a["fontSize"] = size; c.append(f"has-{size}-font-size")
    if cls:
        a["className"] = cls; c.insert(0, cls)
    attr = (" " + json.dumps(a)) if a else ""
    cl = f' class="{" ".join(c)}"' if c else ""
    return f"<!-- wp:paragraph{attr} -->\n<p{cl}>{text if raw else e(text)}</p>\n<!-- /wp:paragraph -->\n"


def link(label, href):
    return f'<a href="{ea(href)}">{e(label)}</a>'


def btn(label, href, outline=False):
    c = " is-style-outline" if outline else ""
    a = ' {"className":"is-style-outline"}' if outline else ""
    return (f"<!-- wp:button{a} -->\n<div class=\"wp-block-button{c}\"><a class=\"wp-block-button__link wp-element-button\" "
            f"href=\"{ea(href)}\">{e(label)}</a></div>\n<!-- /wp:button -->\n")


def btns(*items, center=False):
    a = ' {"layout":{"type":"flex","justifyContent":"center"}}' if center else ""
    return f"<!-- wp:buttons{a} -->\n<div class=\"wp-block-buttons\">{''.join(items)}</div>\n<!-- /wp:buttons -->\n"


def im(url, alt, cap=None, cls="gk-photo"):
    fc = f'<figcaption class="wp-element-caption">{e(cap)}</figcaption>' if cap else ""
    return (f'<!-- wp:image {{"sizeSlug":"large","className":"{cls}"}} -->\n<figure class="wp-block-image size-large {cls}">'
            f'<img src="{ea(url)}" alt="{ea(alt)}"/>{fc}</figure>\n<!-- /wp:image -->\n')


def ul(items, cls=""):
    a = f' {{"className":"{cls}"}}' if cls else ""
    c = f' class="{cls}"' if cls else ""
    li = "".join(f"<!-- wp:list-item -->\n<li>{x}</li>\n<!-- /wp:list-item -->\n" for x in items)
    return f"<!-- wp:list{a} -->\n<ul{c}>{li}</ul>\n<!-- /wp:list -->\n"


def cols(cells, cls="", gap="2rem"):
    a = {"style": {"spacing": {"blockGap": {"top": gap, "left": gap}}}}
    if cls:
        a["className"] = cls
    cc = f" {cls}" if cls else ""
    inner = "".join(f"<!-- wp:column -->\n<div class=\"wp-block-column\">{c}</div>\n<!-- /wp:column -->\n" for c in cells)
    return f'<!-- wp:columns {json.dumps(a)} -->\n<div class="wp-block-columns{cc}">{inner}</div>\n<!-- /wp:columns -->\n'


def grp(inner, bg=None, color=None, cls="gk-section", pad="60", width=None, full=True):
    a = {"className": cls, "layout": {"type": "constrained"}}
    c = ["wp-block-group", cls]
    if width:
        a["layout"]["contentSize"] = width
    if full:
        a["align"] = "full"; c.insert(1, "alignfull")
    if bg:
        a["backgroundColor"] = bg; c += [f"has-{bg}-background-color", "has-background"]
    if color:
        a["textColor"] = color; c += [f"has-{color}-color", "has-text-color"]
    a["style"] = {"spacing": {"padding": {"top": f"var:preset|spacing|{pad}", "bottom": f"var:preset|spacing|{pad}"}}}
    st = f"padding-top:var(--wp--preset--spacing--{pad});padding-bottom:var(--wp--preset--spacing--{pad})"
    return f'<!-- wp:group {json.dumps(a)} -->\n<div class="{" ".join(c)}" style="{st}">{inner}</div>\n<!-- /wp:group -->\n'


def pattern(slug):
    return f'<!-- wp:pattern {{"slug":"garage-king/{slug}"}} /-->\n'


def page_hero(title, eyebrow, lead, img):
    inner = (pa(eyebrow, "amber", cls="gk-eyebrow") + hd(1, title, "white") + (pa(lead, "muted", size="medium") if lead else ""))
    return (f'<!-- wp:cover {{"url":"{ea(img)}","dimRatio":72,"overlayColor":"black","minHeight":46,"minHeightUnit":"vh",'
            f'"contentPosition":"center left","align":"full","className":"gk-hero gk-hero--page","layout":{{"type":"constrained"}}}} -->\n'
            f'<div class="wp-block-cover alignfull has-custom-content-position is-position-center-left gk-hero gk-hero--page" style="min-height:46vh">'
            f'<span aria-hidden="true" class="wp-block-cover__background has-black-background-color has-background-dim-72 has-background-dim"></span>'
            f'<img class="wp-block-cover__image-background" alt="" src="{ea(img)}" data-object-fit="cover"/>'
            f'<div class="wp-block-cover__inner-container">{inner}</div></div>\n<!-- /wp:cover -->\n')


def photos(items, per=3):
    """items: (file in assets/images, alt text). Rows of up to `per` photos."""
    out = ""
    for i in range(0, len(items), per):
        row = items[i:i + per]
        out += cols([im("gk-local:" + f, alt) for f, alt in row], cls="gk-gallery", gap="1rem")
    return out


def chips(items, light=True):
    c = "gk-chips gk-chips--light" if light else "gk-chips"
    inner = "".join(pa(x, cls="gk-chip") for x in items)
    return (f'<!-- wp:group {{"className":"{c}","layout":{{"type":"flex","flexWrap":"wrap"}},"style":{{"spacing":{{"blockGap":"0.6rem"}}}}}} -->\n'
            f'<div class="wp-block-group {c}">{inner}</div>\n<!-- /wp:group -->\n')


def badges():
    return cols([im(BBB, "Better Business Bureau Accredited A+ rating", cls="gk-badge"),
                 im(SEAL, "HomeAdvisor Screened and Approved", cls="gk-badge")], cls="gk-badges", gap="1rem")


def gallery(urls, alt):
    inner = "".join(
        f'<!-- wp:image {{"sizeSlug":"large","linkDestination":"none"}} -->\n<figure class="wp-block-image size-large"><img src="{ea(u)}" alt="{ea(alt)}"/></figure>\n<!-- /wp:image -->\n'
        for u in urls)
    return (f'<!-- wp:gallery {{"linkTo":"none","className":"gk-gallery-grid"}} -->\n'
            f'<figure class="wp-block-gallery has-nested-images columns-default is-cropped gk-gallery-grid">{inner}</figure>\n<!-- /wp:gallery -->\n')


def details(q, a):
    return (f"<!-- wp:details -->\n<details class=\"wp-block-details\"><summary>{e(q)}</summary>"
            f"<!-- wp:paragraph -->\n<p>{e(a)}</p>\n<!-- /wp:paragraph --></details>\n<!-- /wp:details -->\n")


def cta():
    return pattern("cta-band")


def write(slug, title, body):
    # "gk-local:name.jpg" points at a photo in theme/garage-king/assets/images (photos from the client's shared folder, resized, location data removed)
    body = re.sub(r"gk-local:([\w.\-]+)", lambda m: "<?php echo esc_url( get_theme_file_uri( 'assets/images/" + m.group(1) + "' ) ); ?>", body)
    # photos that were linked from the client's current site are served from the theme (see gk_placeholder in functions.php)
    body = re.sub(r"https://thegarageking\.com/wp-content/uploads/([A-Za-z0-9_./%()\-]+)", lambda m: "<?php echo gk_placeholder( '" + m.group(1) + "' ); ?>", body)
    head = f"<?php\n/**\n * Title: {title}\n * Slug: garage-king/{slug}\n * Categories: garage-king\n * Inserter: no\n */\n?>\n"
    (PAT / f"{slug}.php").write_text(head + body)


def card(title, text, href=None, num=None):
    t = link(title, href) if href else e(title)
    n = pa(num, "amber", cls="gk-card__num") if num else ""
    return (f'<!-- wp:column {{"className":"gk-card"}} -->\n<div class="wp-block-column gk-card">{n}'
            f'<!-- wp:heading {{"level":3,"textColor":"white"}} -->\n<h3 class="wp-block-heading has-white-color has-text-color">{t}</h3>\n<!-- /wp:heading -->\n'
            f'{pa(text, "muted")}</div>\n<!-- /wp:column -->\n')


def card_row(cards):
    a = {"className": "gk-cards", "style": {"spacing": {"blockGap": {"top": "1.25rem", "left": "1.25rem"}}}}
    return f'<!-- wp:columns {json.dumps(a)} -->\n<div class="wp-block-columns gk-cards">{"".join(cards)}</div>\n<!-- /wp:columns -->\n'


# ---------- Rack size diagram (built from the current site's list of nine sizes, in feet) ----------
def size_diagram():
    """Top-view, to-scale drawing of the nine overhead rack sizes listed on the current site (8x4, 8x3, 8x2, 6x4, 6x3, 6x2, 4x4, 4x3, 4x2).
    Length is across, depth is down. The 8x4 is the exclusive Enduro Deck. Units are feet, as on the current site (8'x4' Enduro Deck)."""
    k = 28                      # pixels per foot
    lengths, depths = (8, 6, 4), (4, 3, 2)
    gap, left, top = 24, 70, 52
    colw = [l * k for l in lengths]
    rowh = [d * k for d in depths]
    xs = [left]
    for w in colw[:-1]:
        xs.append(xs[-1] + w + gap)
    ys = [top]
    for h in rowh[:-1]:
        ys.append(ys[-1] + h + gap)
    width = xs[-1] + colw[-1] + 20
    height = ys[-1] + rowh[-1] + 56
    parts = []
    for ci, l in enumerate(lengths):
        parts.append(f'<text x="{xs[ci] + colw[ci] / 2}" y="30" text-anchor="middle" class="gk-sd-axis">{l}&#8242; long</text>')
    for ri, d in enumerate(depths):
        parts.append(f'<text x="{left - 12}" y="{ys[ri] + rowh[ri] / 2 + 6}" text-anchor="end" class="gk-sd-axis">{d}&#8242; deep</text>')
    names = []
    for ci, l in enumerate(lengths):
        for ri, d in enumerate(depths):
            w, h = l * k, d * k
            enduro = (l, d) == (8, 4)
            cls = "gk-sd-rack gk-sd-rack--enduro" if enduro else "gk-sd-rack"
            parts.append(f'<rect x="{xs[ci]}" y="{ys[ri]}" width="{w}" height="{h}" rx="4" class="{cls}"/>')
            label = f"{l}&#8242; &#215; {d}&#8242;"
            if enduro:
                parts.append(f'<text x="{xs[ci] + w / 2}" y="{ys[ri] + h / 2 - 4}" text-anchor="middle" class="gk-sd-label gk-sd-label--dark">{label}</text>')
                parts.append(f'<text x="{xs[ci] + w / 2}" y="{ys[ri] + h / 2 + 18}" text-anchor="middle" class="gk-sd-note">Enduro Deck</text>')
            else:
                parts.append(f'<text x="{xs[ci] + w / 2}" y="{ys[ri] + h / 2 + 6}" text-anchor="middle" class="gk-sd-label">{label}</text>')
            names.append(f"{l} by {d} feet" + (" (the Enduro Deck)" if enduro else ""))
    sy = height - 26
    parts.append(f'<line x1="{left}" y1="{sy}" x2="{left + 4 * k}" y2="{sy}" class="gk-sd-scale"/>')
    parts.append(f'<line x1="{left}" y1="{sy - 6}" x2="{left}" y2="{sy + 6}" class="gk-sd-scale"/>')
    parts.append(f'<line x1="{left + 4 * k}" y1="{sy - 6}" x2="{left + 4 * k}" y2="{sy + 6}" class="gk-sd-scale"/>')
    parts.append(f'<text x="{left + 4 * k + 12}" y="{sy + 6}" class="gk-sd-axis">scale: 4&#8242;</text>')
    desc = "Top view, drawn to the same scale: " + ", ".join(names) + "."
    svg = (f'<svg class="gk-size-diagram" viewBox="0 0 {width} {height}" xmlns="http://www.w3.org/2000/svg" role="img" aria-labelledby="gk-sd-t gk-sd-d">'
           f'<title id="gk-sd-t">The nine overhead rack sizes, to scale</title><desc id="gk-sd-d">{desc}</desc>' + "".join(parts) + '</svg>')
    return f'<!-- wp:html -->\n<figure class="gk-size-figure">{svg}<figcaption>Rack sizes drawn to the same scale, top view, in feet. See the layout options above for one and two car garages and for ceilings under and over 9.5 feet.</figcaption></figure>\n<!-- /wp:html -->\n'


# ---------- Offers (sample, from the client's public Latest Deals document) ----------
# Read 2026-10-28 from the document the current site's pricing page links to (the "Your Special Deals" block). Promotional pricing in it ended 2026-10-19,
# so the site shows these as SAMPLE offers to preview the layout. The client edits this section in the page editor.
OFFERS = [("Buy one motor rack, get 50% off the second", "Two motor racks", "$6,900"),
          ("Buy two fixed racks, get one free", "Three fixed racks", "$2,960"),
          ("Buy two cases of slatwall, get the third free", "Three cases of slatwall", "$2,960"),
          ("The Deluxe Cabinet Package, 7-piece set", "Two tall, two upper and two lower cabinets with a bamboo counter top", "$7,200 (normally $8,380)")]
OFFERS_NOTE = "Sample offers: the October promotion, which ended on October 19, 2026, shown here to preview how current offers will look. Offer pricing is for conventional garages."


def offer_card(title, what, price):
    return (f'<!-- wp:column {{"className":"gk-card gk-offer-card"}} -->\n<div class="wp-block-column gk-card gk-offer-card">'
            + pa("Sample offer", "amber", cls="gk-offer-tag")
            + f'<!-- wp:heading {{"level":3,"textColor":"white"}} -->\n<h3 class="wp-block-heading has-white-color has-text-color">{e(title)}</h3>\n<!-- /wp:heading -->\n'
            + pa(what, "muted") + pa(price, "amber", cls="gk-offer-price")
            + '</div>\n<!-- /wp:column -->\n')


def offers_block(heading_color="white"):
    return (card_row([offer_card(*o) for o in OFFERS[:2]]) + card_row([offer_card(*o) for o in OFFERS[2:]])
            + pa(OFFERS_NOTE, "muted", size="small"))


SIZES = ["8×4 Enduro Deck", "8×3", "8×2", "6×4", "6×3", "6×2", "4×4", "4×3", "4×2"]
TAG = "Can't find a thing? Ask the Garage King!"
POSITION = "The boutique, family owned and operated expert in luxury full garage renovations, including floors, doors and storage solution systems."
OFFER = "Complete our contact form to book your free design consultation and hear our current offers."
SERVICE_AREA = "We serve all Greater Austin, Greater San Antonio and surrounding areas."
SERVICE_CTA = "Don't see your town? Complete our contact form for a consultation to see if we can provide for your needs and service area."

# ---------- Overhead racks (current page copy) ----------
body = page_hero("Overhead Storage Solutions", "Overhead storage racks",
                 "Storage solution systems for large, bulky items, up and out of the way.", UP + "2012/03/Overhead-Garage-Storage-Racks-Two-Car-Layout-Image.jpg")
body += grp(
    cols([
        pa("Our overhead storage solutions are great for storing large bulky items up and out of the way. Typically these items are used seasonally and would otherwise take up valuable floor space preventing parking, walking, and cleaning.")
        + pa("Rather than building a cumbersome wood loft our strong yet lightweight steel overhead storage racks are assembled and installed directly into the base of the roof trusses anywhere in the garage, even above garage doors! The open channel platform allows you to see your belongings from below so nothing is lost or forgotten.")
        + pa("Nine sizes are available. We mount our stationary racks across multiple ceiling joists and bolt them into your wall framing, or flush to the wall, and we accommodate all ceiling heights.")
        + btns(btn("Book a Free Design Consultation", "/schedule-quote"), btn("See pricing", "/garage-storage-pricing", outline=True)),
        im(UP + "2012/03/Overhead-Garage-Storage-Racks-Two-Car-Layout-High-Ceilings-Image.jpg", "Overhead storage racks above a garage door"),
    ]) + hd(2, "Layout options for our steel overhead racks")
    + hd(3, "One car layout")
    + cols([im(UP + "2012/03/Overhead-Racks-One-Car-Garage-Layout.jpg", "One car garage rack layout"),
            im(UP + "2012/03/Overhead-Racks-One-Car-Garage-Layout-High-Ceiling.jpg", "One car garage rack layout, high ceiling"),
            im(UP + "2012/03/Overhead-Garage-Storage-Racks-One-Care-Layout-351x247.jpg", "Overhead racks in a one car garage"),
            im(UP + "2012/03/Overhead-Garage-Storage-Racks.jpg", "Overhead garage storage racks")], cls="gk-gallery", gap="1rem")
    + hd(3, "Two car layout")
    + cols([im(UP + "2012/03/Overhead-Racks-Two-Car-Garage-Layout.jpg", "Two car garage rack layout"),
            im(UP + "2012/03/Overhead-Racks-Two-Car-Garage-Layout-High-Ceilings.jpg", "Two car garage rack layout, high ceilings"),
            im(UP + "2012/03/Overhead-Garage-Storage-Racks-Two-Car-Layout-Image.jpg", "Overhead racks in a two car garage"),
            im(UP + "2012/03/Overhead-Garage-Storage-Racks-Two-Car-Layout-High-Ceilings-Image.jpg", "Overhead racks, two car, high ceilings")], cls="gk-gallery", gap="1rem")
    + hd(3, "Tall and vaulted ceilings") + pa("We accommodate all ceiling heights, including vaulted and high ceilings.")
    + photos([("overhead-rack-vaulted-ceiling.jpg", "Overhead racks installed under a vaulted ceiling above a garage door"),
              ("overhead-rack-vaulted-ceiling-2.jpg", "An overhead rack under a sloped ceiling"),
              ("overhead-rack-high-ceiling.jpg", "Overhead racks in a garage with a high ceiling")])
    + hd(3, "Recent installs")
    + photos([("overhead-rack-slatwall-door.jpg", "An overhead rack and slatwall beside the garage door"),
              ("overhead-racks-long-run.jpg", "A long run of overhead racks along a garage wall")], per=2)
    + hd(3, "Sizes available") + chips(SIZES) + pa("Nine sizes, including our exclusive 8′×4′ Enduro Deck rack.", cls="") + size_diagram()
    + pa(OFFER, cls="gk-offer") + badges())
body += pattern("cta-band")
write("page-overhead-racks", "Overhead racks page", body)

# ---------- Slatwall ----------
body = page_hero("Slatwall Garage Storage and Organization", "Slatwall", "A fully finished look, with complete flexibility of storage. A storage solution system built around your garage.", UP + "2012/03/Slatwall-Garage-GK-w-Car-Parked-1000x563.jpg")
body += grp(
    cols([
        pa("Slatwall is a heavy duty storage and organization system designed to carry heavy loads of weight while providing a dust free durable surface. It will give your garage a fully finished look, with complete flexibility of storage. It quickly covers up messy drywall work or unsightly concrete block walls.")
        + pa("Slatwall is sold by the case of four 12″ × 96″ panels, with a hidden fastening system, and it accepts heavy duty shelves and accessories. Complete our contact form to learn how we can help organize your garage.") + btns(btn("Book a Free Design Consultation", "/schedule-quote"), btn("See pricing", "/garage-storage-pricing", outline=True)),
        im(UP + "2013/01/slatwall.jpg", "Slatwall panels with storage accessories"),
    ]) + hd(2, "Current options")
    + cols([
        hd(3, "Standard duty wall panel, 8 feet")
        + pa("Standard duty wall panels are ideal for most conventional slatwall garage organization applications. Each panel is 8 feet in length by 12 inches in height by 0.66 inches thick. All panels are sturdy and waterproof.")
        + pa("Key features: stackable storage, comes in white, available in 8 feet length, and can hold up to 200 lbs per panel.")
        + pa("Specs: 8 feet wide, 12 inches high, 0.66 inches thick. Panel weight 48″: 4.5 lbs; 96″: 9.0 lbs. Panel area 48″: 4 sq ft; 96″: 8 sq ft. Slat spacing 3″ on center."),
        im(UP + "2013/01/12-slatwall.jpg", "Standard duty slatwall panel"),
    ])
    + cols([
        hd(3, "Heavy duty wall panel, 8 feet")
        + pa("Heavy duty wall panels are an extremely strong and versatile garage organization solution. Each panel is 8 feet in length by 15 inches in height by 0.7 inches thick.")
        + pa("Specs: 8 feet wide, 15 inches high, 0.7 inches thick."),
        im(UP + "2012/03/Slatwall-Garage-Panels-433x149.jpg", "Heavy duty slatwall panels"),
    ])
    + hd(2, "Recent slatwall work")
    + cols([im(UP + "2012/03/Slatwall-Garage-GK-w-Car-Parked-1000x563.jpg", "Slatwall garage with car parked"),
            im(UP + "2012/03/Slatwall-Garage-King-882x563.jpg", "Slatwall garage"),
            im(UP + "2012/03/Slatwall-Garage-King-621x1000.jpg", "Slatwall garage wall")], cls="gk-gallery", gap="1rem"))
body += grp(hd(2, "Finishes") + pa("Slatwall comes in four finishes. Photos are from recent installs.")
            + hd(3, "Barnwood") + photos([("slatwall-barnwood-panel.jpg", "A barnwood finish slatwall panel on a garage wall"), ("slatwall-barnwood-hooks.jpg", "Barnwood slatwall with hooks beside an overhead rack")], per=2)
            + hd(3, "Graphite") + photos([("slatwall-graphite-panel.jpg", "A graphite slatwall panel below an overhead rack"), ("slatwall-graphite-shelves.jpg", "Graphite slatwall in a corner with shelves")], per=2)
            + hd(3, "Gray") + photos([("slatwall-gray-panel.jpg", "A gray slatwall panel on a garage wall"), ("slatwall-gray-bike-hooks.jpg", "Gray slatwall with bike hooks")], per=2)
            + hd(3, "Whitewood") + photos([("slatwall-whitewood-hooks.jpg", "Whitewood slatwall with hooks under an overhead rack"), ("slatwall-whitewood-shelves.jpg", "Whitewood slatwall with shelves")], per=2),
            width="1180px", pad="50")
body += pattern("cta-band")
write("page-slatwall", "Slatwall page", body)

# ---------- Steel cabinets (existing URL /steel-cabinets) ----------
body = page_hero("Steel Cabinets", "Garage cabinets", "Coming soon: a new cabinet line built for quality, durability and security.", UP + "2012/03/Slatwall-Garage-King-882x563.jpg")
body += grp(
    pa("Cabinets coming soon! We are in research and development of a new cabinet line that we are confident will continue to lead the industry for quality, durability, and security.")
    + pa("Complete our contact form to hear about the storage solution systems available right now, including overhead storage and slatwall, or to find out when our steel cabinets will be back.")
    + btns(btn("Book a Free Design Consultation", "/schedule-quote")), width="820px")
body += grp(hd(2, "Recent installs") + photos([("steel-cabinets-wall.jpg", "A wall of gray steel cabinets"),
                                                 ("steel-cabinets-open.jpg", "Gray steel cabinets with the doors open and shelves showing"),
                                                 ("steel-cabinets-workbench.jpg", "Gray steel cabinets with a bamboo workbench and slatwall"),
                                                 ("steel-cabinets-bamboo-counter.jpg", "Upper and lower steel cabinets with a bamboo counter top")], per=2),
            width="1180px", pad="50")
body += pattern("cta-band")
write("page-steel-cabinets", "Steel cabinets page", body)

# ---------- Pricing: regular prices from the client's "Latest Deals" document, offers shown at booking ----------
# Regular prices come from the client's public pricing document (linked from the current site's Pricing tab, read 2026-10-23).
# The limited-time promotions in that document had an end date of 2026-10-19 and are not published here; offers are given when the visitor books.
PRICES = [("Motorized overhead storage racks", "$4,600 each"), ("Overhead storage racks (fixed)", "$1,480 each"),
          ("Slatwall, per case of four 12″ × 96″ panels", "$1,480 per case"), ("Floor coatings", "$7 per square foot"),
          ("Garage ventilation, two car garage", "$3,380"), ("Steel cabinets", "Custom quote; the Deluxe Cabinet Package is $8,380")]
EXTRAS = [("Deluxe hook bundle", "$650"), ("Basic hook bundle", "$350"), ("Three 30″ metal shelves", "$720"),
          ("Reflective garage door insulation, standard 16′ two car door (sold with ventilation only)", "$570"),
          ("Reflective garage door insulation, 16′ two car door and 8′ one car door (sold with ventilation only)", "$750")]


def price_table(rows, head=("Product", "Regular price")):
    body = "".join(f"<tr><td>{e(a)}</td><td>{e(b)}</td></tr>" for a, b in rows)
    return (f'<!-- wp:table {{"className":"gk-prices"}} -->\n<figure class="wp-block-table gk-prices"><table><thead><tr><th>{e(head[0])}</th><th>{e(head[1])}</th></tr></thead>'
            f'<tbody>{body}</tbody></table></figure>\n<!-- /wp:table -->\n')


PKG = [("The Emerson", "A starter combination of overhead storage and slatwall.", "Package contents and price to be confirmed"),
       ("The Duke", "A larger combination for bigger garages.", "Package contents and price to be confirmed"),
       ("The Earl", "Our top combination for the whole garage.", "Package contents and price to be confirmed")]
body = page_hero("Garage Storage Pricing", "Pricing and offers", "Regular pricing and what each product includes, then a free design consultation to plan your garage.", UP + "2021/04/20190718_125822-scaled.jpg")
body += grp(hd(2, "Regular pricing") + price_table(PRICES)
            + pa("Pricing is for conventional garages. Professional installation is included.", size="small")
            + hd(3, "Accessories and add-ons") + price_table(EXTRAS, ("Item", "Price"))
            + hd(3, "What counts as a conventional garage") + ul(["A 9′ ceiling height (typically no additional charge for ceilings under 11′)", "A flat ceiling, rather than cathedral, vaulted, gable or sloped", "A garage attached to a home built after 2004"], cls="gk-checks")
            + pa("Have a taller ceiling? We specialize in tall ceilings. Complete our contact form for a customized quote.")
            + btns(btn("Book a Free Design Consultation", "/schedule-quote")), width="900px", pad="50")
body += grp(hd(2, "What each product includes", color="white")
            + card_row([card("Motorized overhead storage racks", "A wireless remote with keyed lockout, super duty seat belt webbing with 1,500 lb. break strength per corner, and a four-point outside corner lift system for platform stability.", "/motorized-overhead-racks"),
                        card("Overhead storage racks", "Nine sizes. Mounted across multiple ceiling joists and bolted into your wall framing, or flush to the wall, for all ceiling heights.", "/overhead-racks"),
                        card("Slatwall", "Sold by the case of four 12″ × 96″ panels. Hidden fastening system, 300 lbs. per square foot at the surface, and it accepts heavy duty shelves and accessories.", "/slatwall")])
            + card_row([card("Steel cabinets", "Powder coated steel, soft self-closing drawers and doors, 18 and 24 gauge available, 1,000 lb. capacity, 80″ tall lockers, mounted off the floor. The Deluxe Cabinet Package has two tall, two upper and two lower cabinets with a bamboo counter top.", "/steel-cabinets"),
                        card("Floor coatings", "Priced by the square foot. A one car garage is about 250 square feet.", "/garage-floor-coatings"),
                        card("Garage ventilation", "Cools your garage up to 20 degrees and the attic up to 40 degrees. Includes a thermostat and two premium aluminum intake vents with insect screens.", "/garage-exhaust-fans")]),
            bg="ink", color="white")
body += grp(hd(2, "Current offers", color="white", align="center")
            + pa("Limited-time offers on racks, slatwall and cabinet packages. Book your free design consultation to claim them.", "muted", align="center")
            + offers_block() + btns(btn("Book a call to claim current offers", "/schedule-quote"), center=True), bg="black", color="white", width="1000px", pad="50")
body += grp(hd(2, "Popular packages", color="white") + pa("Three popular combinations. The client will supply what each includes and what it costs.", "muted")
            + card_row([card(n, d, ).replace("</div>\n<!-- /wp:column -->", pa(x, "white", size="small") + "</div>\n<!-- /wp:column -->") for n, d, x in PKG]),
            bg="ink", color="white")
body += grp(hd(2, "Financing") + pa("The client plans to offer financing. Details to come.")
            + hd(2, "Compare to off-site storage") + pa("A garage that stores your things costs less than renting a storage unit month after month."), bg="grey", width="820px", pad="50")
body += grp(badges(), width="900px", pad="40") + pattern("cta-band")
write("page-pricing", "Pricing page", body)

# ---------- Photo gallery (34 photos on the current page) ----------
GAL = """2021/04/20170303_180905-scaled.jpg 2021/04/20210212_101134-scaled.jpg 2021/04/20101129-IMG_4462-1-scaled.jpg 2021/04/20101129-IMG_4456-scaled.jpg
2021/04/20101129-IMG_4491-scaled.jpg 2021/04/20190225_135201-scaled.jpg 2021/04/20210329_134101-scaled.jpg 2021/04/Copy-of-20170623_122354-scaled.jpg
2021/04/20190227_103359-scaled.jpg 2021/04/Copy-of-20170522_132812-scaled.jpg 2021/04/20190227_141838-scaled.jpg 2021/04/20190227_141618-scaled.jpg
2023/01/20220715_174055-scaled.jpg 2023/01/20220712_175832-scaled.jpg 2021/04/20190718_125822-scaled.jpg 2021/04/20190828_134644-scaled.jpg
2021/04/20190225_134757-scaled.jpg 2021/04/20190225_134936-scaled.jpg 2021/04/20190225_135004-scaled.jpg 2012/03/PA010394.jpg 2012/03/PA020395.jpg
2012/03/PA020396.jpg 2012/03/PA020397.jpg 2012/03/PA020398.jpg 2012/03/PA020399.jpg 2012/03/PA020401.jpg 2012/03/PA020402.jpg 2012/03/PA020403.jpg
2012/03/PA020404.jpg 2012/03/PA020405.jpg 2012/03/PA020406.jpg 2012/03/PA020408.jpg 2012/03/PA020409.jpg 2012/03/PA020410.jpg""".split()
body = page_hero("Our Work", "Photo gallery", "Photos of our team and installations in Greater Austin and San Antonio.", UP + "2021/04/20101129-IMG_4456-scaled.jpg")
body += grp(hd(2, "Recent installs")
            + gallery(["gk-local:" + f for f in ["garage-complete-1.jpg", "garage-complete-2.jpg", "garage-complete-3.jpg", "overhead-racks-two-car.jpg", "garage-complete-4.jpg",
                                                  "motorized-two-racks-dark.jpg", "overhead-rack-slatwall-door.jpg", "steel-cabinets-wall.jpg", "steel-cabinets-workbench.jpg", "motorized-rack-net-closeup.jpg"]],
                       "A finished garage installation by The Garage King"), width="1280px", pad="50")
body += grp(pa("Below are some photos of our team and installation packages. We enjoy installing our garage organizers for you in Greater Austin and San Antonio. If you are interested in finding the right garage storage solution for you, contact us to provide some information about your garage.", align="center")
            + gallery([UP + g for g in GAL], "The Garage King installation photo")
            + btns(btn("Book a Free Design Consultation", "/schedule-quote"), btn("Project stories", "/garage-storage-photos/project-stories", outline=True), center=True), width="1280px")
body += pattern("cta-band")
write("page-gallery", "Gallery page", body)

# ---------- Testimonials (current page + review posts + /ga reviews) ----------
REV = [
    ("Friendly. The boss came out first and sketched up where the best places for the organizers would be. Explained it thoroughly so I could understand my options. When the installer came a few days later, he had a schematic to work off of and installed with great care.", "Georgetown, TX"),
    ("Easy to work with, nice product, arrived on time, did a good job on the installation, and cleaned up well before they left.", "Austin, TX"),
    ("They did a fantastic job. They went above and beyond the call of duty. I would highly recommend them to anyone.", "Cedar Park, TX"),
    ("Very professional, and provided what I contracted for. 5 Stars!", "San Antonio, TX"),
    ("Easy to work with, great product. Clean looking with tons of easy to access storage. Highest recommendations.", "San Antonio, TX"),
    ("They are good, efficient, well organized and communicate well. I am fully satisfied with the end result. I was looking for a turn-key solution, this is what they committed to and delivered.", "San Antonio, TX"),
    ("Returned call promptly. Visited for an estimate. And all carpentry work was done quickly and professionally. Also, they hooked me up with a painter to complete the project as well. I highly recommend them!!", "Austin, TX"),
]
GREV = [
    ("The pricing, product quality and installation were all excellent. I would highly recommend Garage King for your next garage organization project.", "Britt D."),
    ("Great experience end to end! Quick and thorough estimate and they were able to schedule install right away. Installation was professionally completed and as promised. Beautiful workmanship & great product.", "Sidney L."),
    ("Highly recommend the Garage Kings. I wanted hanging storage in my garage. Justin was very thorough and detail-oriented, explaining everything he was thinking and listening to what I wanted. I couldn't be more pleased.", "Terry B."),
    ("Positive, Professionalism, Punctuality, Quality. I had a couple of great video calls to decide what I wanted, and then on install day the installer was thoughtful and made sure I got everything installed exactly where I wanted it. Great experience from start to finish!", "Joshua M."),
    ("The entire team was superb. It was easy to talk to them. They showed up quick and did a good job. Completed in a timely manner. We love our new racks. Will definitely use them again and refer to anyone who needs additional storage space in their garage.", "Gigi N."),
    ("One of the nicest groups I have worked with! On time. Professional. And very very helpful with the details leading up to the installation. I recommended them to my friends.", "Anna C."),
]


def review_cards(items, stars=True):
    out = ""
    for t, who in items:
        out += (f'<!-- wp:column {{"className":"gk-review"}} -->\n<div class="wp-block-column gk-review">' + (pa("★★★★★") if stars else "")
                + pa(t) + pa(who, size="small", cls="gk-review__who") + "</div>\n<!-- /wp:column -->\n")
    return out


def review_rows(items, per=3):
    out = ""
    for i in range(0, len(items), per):
        chunk = items[i:i + per]
        out += f'<!-- wp:columns {{"className":"gk-reviews"}} -->\n<div class="wp-block-columns gk-reviews">{review_cards(chunk)}</div>\n<!-- /wp:columns -->\n'
    return out


body = page_hero("Garage Storage Testimonials", "Reviews", "A few of the many testimonials from our happy clients.", UP + "2021/04/20190225_134757-scaled.jpg")
body += grp(pa("The following are just a few of the many garage storage testimonials from our happy clients. We strive to provide best-in-class service to each and every one of our clients.", align="center")
            + review_rows(GREV) + hd(2, "From HomeAdvisor", align="center") + review_rows(REV) + badges(), bg="grey", width="1180px")
body += pattern("cta-band")
write("page-testimonials", "Testimonials page", body)

# ---------- Contact ----------
body = page_hero("Contact Us", "Get in touch", "Complete the form to schedule your free consultation, by phone or video.", UP + "2012/03/Overhead-Garage-Storage-Racks-Two-Car-Layout-High-Ceilings-Image.jpg")
body += grp(cols([
    hd(2, "Get in touch")
    + pa("Complete the form and we will reach out within 24 hours. You can then schedule your free consultation, by phone or video.")
    + pa(POSITION)
    + ul([f'Toll free: <a href="tel:{TOLL_TEL}">{TOLL} (844-694-5464)</a>', f'Austin: <a href="tel:{AUS_TEL}">{AUS}</a>', f'San Antonio: <a href="tel:{SA_TEL}">{SA}</a>',
          f'Email: <a href="mailto:{EMAIL}">{EMAIL}</a>', "E-fax: (512) 879-9111", "Business hours: Monday to Friday, 8 am to 4 pm",
          "Austin showroom, by appointment only: 12900 Trails End Rd, Ste. 2A, Leander, TX 78641"], cls="gk-checks") + badges(),
    hd(2, "Send us a message") + "<!-- wp:shortcode -->\n[gk_quote_form source=\"contact\"]\n<!-- /wp:shortcode -->\n",
]), width="1180px")
write("page-contact", "Contact page", body)

# ---------- Privacy policy (current text) ----------
priv = json.load(open(ROOT / "content" / "privacy.json"))
def privacy_blocks(blocks):
    out = ""
    for b in blocks:
        if b[0].startswith("h"):
            out += hd(3, b[1])
        elif b[0] == "li":
            out += ul([e(b[1])])
        else:
            out += pa(b[1])
    return out


body = (page_hero("Privacy Policy", "Legal", "", UP + "2012/03/Slatwall-Garage-King-882x563.jpg")
        .replace("min-height:46vh", "min-height:28vh").replace('"minHeight":46', '"minHeight":28')
        + grp(privacy_blocks(priv), width="820px"))
write("page-privacy", "Privacy policy page", body)

# ---------- Pages built from the client's Website Content Outline & Questionnaire (not on the current site) ----------
body = page_hero("How It Works", "From first call to finished garage", "Condensed from the Website Content Outline: request, plan, install, enjoy.", UP + "2021/04/20101129-IMG_4456-scaled.jpg")
steps = [("Complete the contact form", "Tell us about your garage and pick a time for your free consultation."),
         ("Planning call and written price", "By phone or video: ceiling height, garage size, what you store, photos. You get a recommendation and a written price."),
         ("Installation day", "Arrival window, how the crew protects your home, how long it takes, and cleanup. Most storage installations are completed within one day."),
         ("After the install", "Care tips and support.")]
body += grp(card_row([card(t, x, num=f"0{i + 1}") for i, (t, x) in enumerate(steps[:2])]) + card_row([card(t, x, num=f"0{i + 3}") for i, (t, x) in enumerate(steps[2:])])
            + pa("Workmanship guarantee and product warranty, stated plainly, and a meet-the-team section with photos go here. Details from the client.", color="muted"), bg="black", color="white")
body += pattern("cta-band")
write("page-how-it-works", "How it works page", body)

FAQ = [("Are you a franchise?", "No. The Garage King is an independent, family-owned and operated Central Texas business, and every installation is done by our own crew."),
       ("How long does an installation take?", "Most storage solution installations are completed within one day."),
       ("Do you offer a warranty?", "Yes: a workmanship guarantee and a product warranty. Details to be confirmed with the client."),
       ("What areas do you serve?", "We serve all Greater Austin, Greater San Antonio and surrounding areas. Don't see your town? Complete our contact form and we will let you know if we can provide for your needs and service area."),
       ("How do overhead racks attach?", "Our steel racks are assembled and installed directly into the base of the roof trusses, anywhere in the garage, even above garage doors."),
       ("What fits on an overhead rack?", "Seasonal and bulky items: holiday decorations, camping gear, bins, luggage and sports equipment. The open channel platform lets you see your belongings from below."),
       ("What does slatwall hold?", "Standard duty panels hold up to 200 lbs per panel, and heavy duty panels are even stronger. Slatwall works with our accessory system and trim."),
       ("How do I get a price?", "Our regular pricing is on the Pricing page. For a plan and written price for your garage, complete our contact form and book a free consultation, by phone or video.")]
body = page_hero("Frequently Asked Questions", "FAQ", "", UP + "2012/03/Slatwall-Garage-GK-w-Car-Parked-1000x563.jpg").replace("min-height:46vh", "min-height:30vh").replace('"minHeight":46', '"minHeight":30')
body += grp("".join(details(q, a) for q, a in FAQ) + pa("Answers drafted from the current site and the client's questionnaire; the client approves the final text.", cls=""), width="820px")
body += pattern("cta-band")
write("page-faq", "FAQ page", body)

body = page_hero("About The Garage King", "Family owned and operated since 2004", "", UP + "2021/04/20101129-IMG_4456-scaled.jpg").replace("min-height:46vh", "min-height:36vh").replace('"minHeight":46', '"minHeight":36')
body += grp(cols([
    hd(2, "A specialist, not a franchise") + pa(POSITION)
    + pa("We specialize in professional grade garage storage, including overhead storage racks and slatwall. We strive to offer the highest quality products and a luxury finish. As a specialist, we are experts at what we do and we will work with you to come up with the best solution for your garage organizational needs.")
    + pa("The company story, the owner's first name and the team photos go here. The client will supply them.") + badges(),
    im(UP + "2017/07/Garage-Organization-Garage-King-1.png", "The Garage King team under an overhead storage rack"),
]), width="1180px")
body += pattern("cta-band")
write("page-about", "About page", body)

body = page_hero("Service Areas", "Greater Austin and Greater San Antonio", SERVICE_AREA, UP + "2012/03/Overhead-Garage-Storage-Racks-Two-Car-Layout-Image.jpg")
body += pattern("service-area") + cta()
write("page-service-areas", "Service areas page", body)

body = page_hero("Book a Free Design Consultation", "Step 1 of 2", "Tell us about your garage. After you submit, you pick a time for your free consultation, by phone or video.", UP + "2012/03/Slatwall-Garage-GK-w-Car-Parked-1000x563.jpg").replace("min-height:46vh", "min-height:34vh").replace('"minHeight":46', '"minHeight":34')
body += grp(cols([
    hd(2, "Your details") + "<!-- wp:shortcode -->\n[gk_quote_form source=\"quote\"]\n<!-- /wp:shortcode -->\n",
    hd(2, "What happens next")
    + ul(["We meet at the time you choose, by phone or video.", "We plan the right mix for your ceiling height and space.", "You get a written price and an installation date."], cls="gk-checks")
    + badges(),
]), width="1180px")
write("page-schedule-quote", "Schedule quote page", body)

body = grp(hd(1, "Almost there!", align="center")
           + pa("We received your details and will reply within 24 hours.", align="center")
           + hd(2, "Step 2 of 2: schedule your free consultation", align="center")
           + pa("Choose a time now to hear your current offers. If you join from a computer we can review garage layouts and pricing with you, and if you would like, we can even take a look at your garage on video.", align="center")
           + "<!-- wp:shortcode -->\n[gk_scheduler]\n<!-- /wp:shortcode -->\n"
           + pa("For quality assurance, we are unable to install materials that were purchased independently.", align="center", size="small")
           + btns(btn("See our work", "/garage-storage-photos"), center=True), width="900px", pad="60")
write("page-thank-you", "Thank you page", body)

# ---------- Solutions hub and product pages without a page on the current site ----------
body = page_hero("Garage Storage Solutions", "Eight product lines", "Shop by problem or by product. Every garage gets a plan.", UP + "2012/03/Slatwall-Garage-GK-w-Car-Parked-1000x563.jpg")
body += pattern("solutions") + cta()
write("page-solutions", "Solutions hub page", body)


def simple_product(slug, title, eyebrow, lead, paras, bullets, img, pics=None, pics_title=None, per=3):
    b = page_hero(title, eyebrow, lead, img)
    b += grp(cols([pa(paras[0]) + "".join(pa(x) for x in paras[1:]) + btns(btn("Book a Free Design Consultation", "/schedule-quote")),
                   ul(bullets, cls="gk-checks")]), width="1180px", pad="60")
    if pics:
        b += grp(hd(2, pics_title) + photos(pics, per=per), width="1180px", pad="50")
    b += pattern("cta-band")
    write(slug, title, b)


simple_product("page-motorized", "Motorized Overhead Storage Racks", "Automated overhead storage solution", "Press a button, the platform lowers to you.",
               ["Are you in need of garage storage space but want to avoid having to climb up a ladder? Our automated, remote-controlled storage solution systems allow you to effortlessly lower the platform with the simple press of a button.",
                "This feature makes it easy for you to load or unload the storage rack and safely return it to the ceiling where it will be out of the way."],
               ["Wireless remote with keyed lockout", "Super duty seat belt webbing with 1,500 lb. break strength per corner", "Four-point outside corner lift system for platform stability", "No ladder, no lifting heavy bins overhead", "A short captioned demo video will go here"],
               UP + "2012/03/Overhead-Garage-Storage-Racks-Two-Car-Layout-High-Ceilings-Image.jpg",
               pics=[("motorized-rack-net-long.jpg", "A motorized overhead rack with a safety net, lowered in a garage"),
                     ("motorized-racks-pair-white.jpg", "Two white motorized overhead racks above a garage"),
                     ("motorized-racks-pair-dark.jpg", "Two dark motorized overhead racks on a garage ceiling"),
                     ("motorized-rack-lowered.jpg", "A motorized overhead rack lowered for loading"),
                     ("motorized-rack-net-closeup.jpg", "A motorized overhead rack with a safety net, seen from below")],
               pics_title="Motorized racks in real garages")
simple_product("page-floor-coatings", "Garage Floor Coatings", "Floor coatings", "Finish the floor.",
               ["A coated floor finishes the garage. This page is built from the client's Website Content Outline & Questionnaire; the current site has no floor coatings page.",
                "The coating system, installation steps, colour and flake options, cure time and warranty come from the client."],
               ["Coating system explained in plain language", "Surface prep, crack repair, base coat, flake and clear top coat", "Colour and flake chip gallery", "Professional coating versus a hardware-store kit"],
               UP + "2012/03/Slatwall-Garage-GK-w-Car-Parked-1000x563.jpg")
simple_product("page-ventilation", "Garage Ventilation", "Garage ventilation", "Cool the garage down so you can actually use it.",
               ["A garage that is too hot to use in the Texas summer is wasted space. Our garage ventilation system cools your garage up to 20 degrees and your attic up to 40 degrees.",
                "Each system includes a thermostat and two premium aluminum intake vents with insect screens and an auxiliary frame for a double skin insulated door (insulation not included). Reflective garage door insulation is available as an add-on."],
               ["Cools the garage up to 20 degrees, the attic up to 40", "Thermostat controlled", "Pairs with storage for a usable workshop or hobby space", "How the system is installed: details from the client"], UP + "2021/04/20190718_125822-scaled.jpg",
               pics=[("garage-door-vents-stone.jpg", "A garage door with louvered intake vents at the bottom"),
                     ("garage-door-vent-louver.jpg", "A louvered intake vent on a garage door"),
                     ("garage-door-vent-closeup.jpg", "A close-up of a louvered garage door vent"),
                     ("garage-door-vent-inside.jpg", "An intake vent seen from inside the garage door")],
               pics_title="Garage door vents we install", per=2)
simple_product("page-lifts", "Lifts", "Lifts", "Part of a complete garage renovation.",
               ["Lifts are part of the full garage renovations The Garage King offers, alongside storage solution systems, floors and doors.",
                "The products, specifications and photos for this page come from the client. Complete our contact form to ask about lifts for your garage."],
               ["Product types and specifications: details from the client", "Garage and ceiling requirements: details from the client", "Installation and warranty: details from the client"],
               UP + "2021/04/20190227_103359-scaled.jpg")
simple_product("page-garage-doors", "Garage Door Installation and Repairs", "Garage doors", "Part of a complete garage renovation.",
               ["A full garage renovation includes the door. The Garage King offers garage door installation and repairs alongside storage solution systems and floors.",
                "The services, brands, photos and service details for this page come from the client. Complete our contact form to ask about your garage door."],
               ["Installation and repair services: details from the client", "Door styles and brands: details from the client", "Warranty and service area: details from the client"],
               UP + "2021/04/20190718_125822-scaled.jpg")

# ---------- Project Stories (placeholder) ----------
body = page_hero("Project Stories", "Coming soon", "Before and after stories from real Greater Austin and Greater San Antonio garages.", UP + "2021/04/20101129-IMG_4456-scaled.jpg")
body += grp(hd(2, "Coming soon", align="center")
            + pa("We are putting together project stories: the garage we started with, the plan, and the finished result. In the meantime, see photos of our work or complete our contact form to start planning yours.", align="center")
            + btns(btn("See our photo gallery", "/garage-storage-photos"), btn("Book a Free Design Consultation", "/schedule-quote", outline=True), center=True), width="720px")
body += pattern("cta-band")
write("page-project-stories", "Project stories page", body)

# ---------- Ad landing pages (current /ga, /nextdoor, /sael) ----------
body = grp(hd(1, "Transform Your Garage With Custom Storage Solutions", color="white")
           + pa("Our team of experts will help you find the perfect storage solution to transform your space.", "muted", size="medium")
           + ul(["Motorized overhead storage racks", "Overhead storage solutions", "Slatwall", "Steel cabinets", "Floor coatings", "Garage ventilation"], cls="gk-checks gk-checks--light")
           + pa(SERVICE_AREA, "amber")
           + grp(hd(3, "Step 1: Fill out the form") + pa("Step 2: Book your free consultation to receive current offers and package details.")
                 + "<!-- wp:shortcode -->\n[gk_quote_form source=\"ads\"]\n<!-- /wp:shortcode -->\n", bg="steel", color="white", cls="gk-estimator", pad="40", full=False),
           bg="black", color="white", width="900px")
body += grp(hd(2, "Transforming garages for over 20 years", align="center") + pa("4.9 Google rating", align="center") + review_rows(GREV[:3]), bg="grey", width="1180px")
body += grp(hd(2, "Professional grade storage solution systems", align="center")
            + cols([hd(3, "Stationary overhead storage racks") + pa("Store large and bulky items up and out of the way, freeing up valuable floor space that can be used for parking, walking, and cleaning. The open channel platform design allows easy visibility and access to your items."),
                    hd(3, "Motorized overhead storage racks") + pa("Avoid climbing a ladder. Lower the platform with the simple press of a button, load or unload it, and safely return it to the ceiling."),
                    hd(3, "Slatwall") + pa("Designed to carry heavy loads, with a dust-free, durable surface that can withstand wear and tear."),
                    hd(3, "Garage cabinets") + pa("Durable, fully welded steel cabinets designed to fit any garage, to organize your tools, equipment and accessories.")]), width="1180px")
body += grp(hd(2, "Superior storage solutions", align="center")
            + cols([pa("More space: extra room for parking and storage."), pa("Promote safety: less clutter, fewer accidents."), pa("Long-term savings: save on off-site storage fees."),
                    pa("Increased efficiency: quickly locate items."), pa("Protect items: keep them safe from damage.")], cls="gk-problems"), bg="grey", pad="50")
body += grp(hd(2, TAG, color="black", align="center") + pa("Schedule your free, no-obligation consultation today!", align="center")
            + btns(btn("Book a Free Design Consultation", "/schedule-quote"), center=True)
            + pa(SERVICE_AREA, align="center", size="small"),
            bg="amber", color="black", cls="gk-cta", pad="50")
write("page-ga", "Google Ads landing page", body)

body = grp(hd(1, "Organize Your Garage", color="white") + pa("Overhead storage, slatwall, motor racks, cabinets, garage fans.", "muted", size="medium")
           + btns(btn("Claim your offer and schedule your phone call", "/schedule-quote")), bg="black", color="white", width="900px")
body += grp(hd(2, "We want to hang with you!", align="center")
            + cols([pa("Our famous 8′×4′ exclusive Enduro Deck rack. Nine overhead rack sizes available.") + btns(btn("Claim your offer, view pricing and schedule your phone call", "/schedule-quote")),
                    pa("Our motor racks. Motor racks, slatwall with steel shelves, tall steel cabinet.") + btns(btn("Claim your offer, view pricing and schedule your phone call", "/schedule-quote"))])
            + cols([pa("Steel cabinets: heavy-duty, fully welded and powder coated."),
                    pa("Stationary and motorized overhead storage, slatwall with metal shelves, accessories and swivel bike mounts.")])
            + btns(btn("Our popular slatwall: claim your offer", "/schedule-quote"), center=True), width="1180px")
write("page-channel-landing", "Channel landing page (Nextdoor and SAEL)", body)

print("pages written:", len(list(PAT.glob("page-*.php"))))

# ---------- Home page sections that use real copy ----------
body = grp(cols([
    im(UP + "2017/07/Garage-Organization-Garage-King-1.png", "The Garage King team under an overhead storage rack"),
    pa("Organize your garage!", cls="gk-eyebrow")
    + hd(2, "Luxury garage renovations, from floors to storage")
    + pa(POSITION)
    + pa("We specialize in garage overhead storage and slatwall. We offer the highest quality garage storage products, installed by our own crew.")
    + pa("As a specialist, we are experts at what we do and we will work with you to come up with the best garage organization solution. Let us show you how we put our garage storage solutions to the test.")
    + pa(OFFER, cls="gk-offer") + badges(),
]), width="1180px", pad="60")
write("welcome", "Welcome", body)

body = grp(hd(2, "Trusted across Central Texas", align="center") + pa("4.9 Google rating. A few words from our customers.", align="center")
           + review_rows(GREV[:3]) + btns(btn("Read more reviews", "/testimonials"), center=True), bg="grey", width="1180px")
write("reviews", "Reviews", body)

# ---------- Home page: offers band ----------
write("offers-band", "Offers band", grp(hd(2, "Current offers", color="white", align="center") + offers_block()
      + btns(btn("Book a Free Design Consultation", "/schedule-quote"), btn("See pricing", "/garage-storage-pricing", outline=True), center=True),
      bg="black", color="white", pad="50"))
