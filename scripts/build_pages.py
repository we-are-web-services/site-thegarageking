#!/usr/bin/env python3
"""Generate page patterns for the theme from copy and photos on the current thegarageking.com.

The text below was copied from the live site's public pages (2026-10-17) so the client can see the new design
with real content. Online-sales pages (shop, cart, checkout, my-account, products) are left out on purpose:
the client has no online sales today. Run from the repo root:  python3 scripts/build_pages.py
Pages that do not exist on the current site are built from the client's sitemap and are marked in docs/brief.md.
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


SIZES = ["8×4 Enduro Deck", "8×3", "8×2", "6×4", "6×3", "6×2", "4×4", "4×3", "4×2"]
TAG = "Can't find a thing? Call the Garage King!"
OFFER = "Contact us today and receive a $40 discount off each overhead rack when you purchase more than one large rack."

# ---------- Overhead racks (current page copy) ----------
body = page_hero("Overhead Garage Storage Racks", "Overhead storage solutions",
                 "Great for storing large, bulky items up and out of the way.", UP + "2012/03/Overhead-Garage-Storage-Racks-Two-Car-Layout-Image.jpg")
body += grp(
    cols([
        pa("Overhead garage storage racks are great for storing large bulky items up and out of the way. Typically these items are used seasonally and would otherwise take up valuable floor space preventing parking, walking, and cleaning.")
        + pa("Rather than building a cumbersome wood loft our strong yet lightweight steel overhead storage racks are assembled and installed directly into the base of the roof trusses anywhere in the garage, even above garage doors! The open channel platform allows you to see your belongings from below so nothing is lost or forgotten."),
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
    + hd(3, "Sizes available") + chips(SIZES) + pa("Nine sizes, including our exclusive 8′×4′ Enduro Deck rack.", cls="")
    + pa(OFFER, cls="gk-offer") + badges())
body += pattern("cta-band")
write("page-overhead-racks", "Overhead racks page", body)

# ---------- Slatwall ----------
body = page_hero("Slatwall Garage Storage and Organization", "Slatwall", "A fully finished look, with complete flexibility of storage.", UP + "2012/03/Slatwall-Garage-GK-w-Car-Parked-1000x563.jpg")
body += grp(
    cols([
        pa("Slatwall is a heavy duty storage and organization system designed to carry heavy loads of weight while providing a dust free durable surface. It will give your garage a fully finished look, with complete flexibility of storage. It quickly covers up messy drywall work or unsightly concrete block walls.")
        + pa("Contact us to learn how we can help organize your garage.") + btns(btn("Book a Free Design Consultation", "/schedule-quote")),
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
body += pattern("cta-band")
write("page-slatwall", "Slatwall page", body)

# ---------- Steel cabinets (existing URL /steel-cabinets) ----------
body = page_hero("Steel Cabinets", "Garage cabinets", "Coming soon: a new cabinet line built for quality, durability and security.", UP + "2012/03/Slatwall-Garage-King-882x563.jpg")
body += grp(
    pa("Cabinets coming soon! We are in research and development of a new cabinet line that we are confident will continue to lead the industry for quality, durability, and security.")
    + pa("Feel free to contact us for other garage storage options available right now. You can also call us to find out more about our overhead racks and slatwall garage organization solutions, or contact us if you just want to find out when our steel cabinets will be back.")
    + pa(f'Austin: <a href="tel:{AUS_TEL}">{AUS}</a><br>San Antonio: <a href="tel:{SA_TEL}">{SA}</a><br>Email: <a href="mailto:{EMAIL}">{EMAIL}</a>', raw=True)
    + btns(btn("Book a Free Design Consultation", "/schedule-quote")), width="820px")
body += pattern("cta-band")
write("page-steel-cabinets", "Steel cabinets page", body)

# ---------- Pricing: offers, packages, working estimator (prices are sample values in assets/pricing.json) ----------
PKG = [("The Emerson", "A starter combination of overhead racks and slatwall.", "Package contents and price to be confirmed"),
       ("The Duke", "A larger combination for bigger garages.", "Package contents and price to be confirmed"),
       ("The Earl", "Our top combination for the whole garage.", "Package contents and price to be confirmed")]
EST = ('<!-- wp:html -->\n<div id="gk-estimator" class="gk-est" aria-live="polite">Loading the estimator…'
       '<noscript> The estimator needs JavaScript. Call us for a quote.</noscript></div>\n<!-- /wp:html -->\n')
body = page_hero("Garage Storage Pricing", "Pricing and offers", "See an estimate, pick a package, then book a free design consultation.", UP + "2021/04/20190718_125822-scaled.jpg")
body += grp(hd(2, "What affects the price") + ul(["Garage size", "Ceiling height", "The mix of products you choose"], cls="gk-checks")
            + pa("We plan the right combination with you on a free phone consultation, by phone or video, and give you a written price. Professional installation is included.")
            + pa(OFFER, cls="gk-offer"), width="900px", pad="50")
body += grp(hd(2, "Popular packages", color="white") + pa("Three popular combinations. The client will supply what each includes and what it costs.", "muted")
            + card_row([card(n, d, ) .replace("</div>\n<!-- /wp:column -->", pa(x, "white", size="small") + "</div>\n<!-- /wp:column -->") for n, d, x in PKG]),
            bg="ink", color="white")
body += grp(pa("Price estimator", cls="gk-eyebrow") + hd(2, "Build your estimate", cls="gk-anchor") + pa("Choose your area and your solutions to see an estimated investment. It is an estimate only; your written price comes after the planning call.")
            + EST, width="1180px", pad="60", cls="gk-section gk-section--estimator")
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
body += grp(pa("Below are some photos of our team and installation packages. We enjoy installing our garage organizers for you in Greater Austin and San Antonio. If you are interested in finding the right garage storage solution for you, contact us to provide some information about your garage.", align="center")
            + gallery([UP + g for g in GAL], "The Garage King installation photo")
            + btns(btn("Book a Free Design Consultation", "/schedule-quote"), center=True), width="1280px")
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
body = page_hero("Contact Us", "Call the Garage King", "Schedule your free phone consultation, with the option of video.", UP + "2012/03/Overhead-Garage-Storage-Racks-Two-Car-Layout-High-Ceilings-Image.jpg")
body += grp(cols([
    hd(2, "Get in touch")
    + pa("Don't forget to schedule your free phone consultation with the option of video, and to claim your discount, after you provide your information.")
    + pa("The Garage King: the number one garage outfitter in Austin and San Antonio.")
    + ul([f'Toll free: <a href="tel:{TOLL_TEL}">{TOLL} (844-694-5464)</a>', f'Austin: <a href="tel:{AUS_TEL}">{AUS}</a>', f'San Antonio: <a href="tel:{SA_TEL}">{SA}</a>',
          f'Email: <a href="mailto:{EMAIL}">{EMAIL}</a>', "E-fax: (512) 879-9111", "Business hours: Monday to Friday, 8 am to 4 pm",
          "Austin showroom, by appointment only: 12900 Trails End Rd, Ste. 2A, Leander, TX 78641"], cls="gk-checks") + badges(),
    hd(2, "Send us a message")
    + grp(pa("The contact form goes here: name, phone, city, reason for contact (new project, existing customer or service, other) and an optional message. Not built yet.")
          + btns(btn("Book a Free Design Consultation", "/schedule-quote")), bg="grey", cls="gk-estimator gk-estimator--light", pad="40", full=False),
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

# ---------- Pages built from the client's sitemap (not on the current site) ----------
body = page_hero("How It Works", "From first call to finished garage", "Condensed from the sitemap: request, plan, install, enjoy.", UP + "2021/04/20101129-IMG_4456-scaled.jpg")
steps = [("Request a consultation", "Short form or a phone call. Tell us about your garage."),
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
       ("What areas do you serve?", "Austin, San Antonio, Bell County and the surrounding Texas Hill Country communities."),
       ("How do overhead racks attach?", "Our steel racks are assembled and installed directly into the base of the roof trusses, anywhere in the garage, even above garage doors."),
       ("What fits on an overhead rack?", "Seasonal and bulky items: holiday decorations, camping gear, bins, luggage and sports equipment. The open channel platform lets you see your belongings from below."),
       ("What does slatwall hold?", "Standard duty panels hold up to 200 lbs per panel, and heavy duty panels are even stronger. Slatwall works with our accessory system and trim."),
       ("How do I get a price?", "Book a free phone consultation, with the option of video. We plan the right mix for your garage and give you a written price.")]
body = page_hero("Frequently Asked Questions", "FAQ", "", UP + "2012/03/Slatwall-Garage-GK-w-Car-Parked-1000x563.jpg").replace("min-height:46vh", "min-height:30vh").replace('"minHeight":46', '"minHeight":30')
body += grp("".join(details(q, a) for q, a in FAQ) + pa("Answers drafted from the current site and the client's questionnaire; the client approves the final text.", cls=""), width="820px")
body += pattern("cta-band")
write("page-faq", "FAQ page", body)

body = page_hero("About The Garage King", "Family owned and operated since 2004", "", UP + "2021/04/20101129-IMG_4456-scaled.jpg").replace("min-height:46vh", "min-height:36vh").replace('"minHeight":46', '"minHeight":36')
body += grp(cols([
    hd(2, "A specialist, not a franchise")
    + pa("We specialize in professional grade garage storage, including overhead storage racks and slatwall. We strive to offer the highest quality products at an affordable price. As a specialist, we are experts at what we do and we will work with you to come up with the best solution for your garage organizational needs.")
    + pa("The company story, the owner's first name and the team photos go here. The client will supply them.") + badges(),
    im(UP + "2017/07/Garage-Organization-Garage-King-1.png", "The Garage King team under an overhead storage rack"),
]), width="1180px")
body += pattern("cta-band")
write("page-about", "About page", body)

body = page_hero("Service Areas", "Central Texas and the Hill Country", "Central Texas garage storage products and installation, covering greater Austin and San Antonio.", UP + "2012/03/Overhead-Garage-Storage-Racks-Two-Car-Layout-Image.jpg")
body += pattern("service-area") + cta()
write("page-service-areas", "Service areas page", body)

body = page_hero("Book a Free Design Consultation", "Step 1 of 2", "Tell us about your garage. After you submit, you pick a time for your free phone consultation.", UP + "2012/03/Slatwall-Garage-GK-w-Car-Parked-1000x563.jpg").replace("min-height:46vh", "min-height:34vh").replace('"minHeight":46', '"minHeight":34')
body += grp(cols([
    hd(2, "Your details")
    + grp(pa("Form goes here: first and last name, email, phone, city, garage size and what you want to store, plus an optional photo upload. A scheduler for step 2 follows. The form and scheduler are not built yet (GoHighLevel and Jobber details are pending).")
          + btns(btn("Call 844-MY-GKING", f"tel:{TOLL_TEL}")), bg="grey", cls="gk-estimator gk-estimator--light", pad="40", full=False),
    hd(2, "What happens next")
    + ul(["We call at the time you choose, by phone or video.", "We plan the right mix for your ceiling height and space.", "You get a written price and an installation date."], cls="gk-checks")
    + badges(),
]), width="1180px")
write("page-schedule-quote", "Schedule quote page", body)

body = grp(hd(1, "Thank You", align="center") + pa("We received your request and will reply within 24 hours. While you wait, measure your ceiling height and look through our work.", align="center")
           + btns(btn("See our work", "/garage-storage-photos"), center=True), width="720px", pad="60")
write("page-thank-you", "Thank you page", body)

# ---------- Solutions hub and product pages without a page on the current site ----------
body = page_hero("Garage Storage Solutions", "Six product lines", "Shop by problem or by product. Every garage gets a plan.", UP + "2012/03/Slatwall-Garage-GK-w-Car-Parked-1000x563.jpg")
body += pattern("solutions") + cta()
write("page-solutions", "Solutions hub page", body)


def simple_product(slug, title, eyebrow, lead, paras, bullets, img):
    b = page_hero(title, eyebrow, lead, img)
    b += grp(cols([pa(paras[0]) + "".join(pa(x) for x in paras[1:]) + btns(btn("Book a Free Design Consultation", "/schedule-quote")),
                   ul(bullets, cls="gk-checks")]), width="1180px", pad="60")
    b += pattern("cta-band")
    write(slug, title, b)


simple_product("page-motorized", "Motorized Overhead Storage Racks", "Motorized overhead storage", "Press a button, the platform lowers to you.",
               ["Are you in need of garage storage space but want to avoid having to climb up a ladder? Our motorized overhead storage racks allow you to effortlessly lower the platform with the simple press of a button.",
                "This feature makes it easy for you to load or unload the storage rack and safely return it to the ceiling where it will be out of the way."],
               ["No ladder, no lifting heavy bins overhead", "Great for heavy or frequently used items and high ceilings", "A short captioned demo video will go here", "Capacity, safety features, ceiling and power requirements: details from the client"],
               UP + "2012/03/Overhead-Garage-Storage-Racks-Two-Car-Layout-High-Ceilings-Image.jpg")
simple_product("page-floor-coatings", "Garage Floor Coatings", "Floor coatings", "Finish the floor.",
               ["A coated floor finishes the garage. This page is built from the client's sitemap; the current site has no floor coatings page.",
                "The coating system, installation steps, colour and flake options, cure time and warranty come from the client."],
               ["Coating system explained in plain language", "Surface prep, crack repair, base coat, flake and clear top coat", "Colour and flake chip gallery", "Professional coating versus a hardware-store kit"],
               UP + "2012/03/Slatwall-Garage-GK-w-Car-Parked-1000x563.jpg")
simple_product("page-ventilation", "Garage Ventilation", "Garage exhaust fans", "Cool the garage down so you can actually use it.",
               ["A garage that is too hot to use in the Texas summer is wasted space. Our garage exhaust fans move the heat out.",
                "Specifications, power, noise level and thermostat control come from the client. The current site has no page for this product."],
               ["How the fan works and how it is installed", "Pairs with storage for a usable workshop or hobby space"], UP + "2021/04/20190718_125822-scaled.jpg")

# ---------- Ad landing pages (current /ga, /nextdoor, /sael) ----------
body = grp(hd(1, "Transform Your Garage With Custom Storage Solutions", color="white")
           + pa("Our team of experts will help you find the perfect storage solution to transform your space.", "muted", size="medium")
           + ul(["Motorized overhead storage racks", "Overhead storage racks", "Slatwall", "Steel cabinets", "Floor coatings", "Garage exhaust fans"], cls="gk-checks gk-checks--light")
           + pa("Serving Central Texas.", "amber")
           + grp(hd(3, "Step 1: Fill out the form") + pa("Step 2: Book your free phone quote to receive current package details. After submitting the form you will be directed to schedule your call. The form is not built yet.")
                 + btns(btn("Call an expert", f"tel:{TOLL_TEL}")), bg="steel", color="white", cls="gk-estimator", pad="40", full=False),
           bg="black", color="white", width="900px")
body += grp(hd(2, "Transforming garages for over 20 years", align="center") + pa("4.9 Google rating", align="center") + review_rows(GREV[:3]), bg="grey", width="1180px")
body += grp(hd(2, "Affordable, professional grade solutions", align="center")
            + cols([hd(3, "Stationary overhead storage racks") + pa("Store large and bulky items up and out of the way, freeing up valuable floor space that can be used for parking, walking, and cleaning. The open channel platform design allows easy visibility and access to your items."),
                    hd(3, "Motorized overhead storage racks") + pa("Avoid climbing a ladder. Lower the platform with the simple press of a button, load or unload it, and safely return it to the ceiling."),
                    hd(3, "Slatwall") + pa("Designed to carry heavy loads, with a dust-free, durable surface that can withstand wear and tear."),
                    hd(3, "Garage cabinets") + pa("Durable, fully welded steel cabinets designed to fit any garage, to organize your tools, equipment and accessories.")]), width="1180px")
body += grp(hd(2, "Superior storage solutions", align="center")
            + cols([pa("More space: extra room for parking and storage."), pa("Promote safety: less clutter, fewer accidents."), pa("Long-term savings: save on off-site storage fees."),
                    pa("Increased efficiency: quickly locate items."), pa("Protect items: keep them safe from damage.")], cls="gk-problems"), bg="grey", pad="50")
body += grp(hd(2, TAG, color="black", align="center") + pa("Schedule your free, no-obligation consultation today!", align="center")
            + btns(btn("Book a Free Design Consultation", "/schedule-quote"), center=True)
            + pa("Central Texas garage storage products and installation, covering greater Austin and San Antonio, Texas.", align="center", size="small"),
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
    + hd(2, "Central Texas garage storage products and installation")
    + pa("We specialize in garage overhead storage and slatwall. We offer the highest quality garage storage products at an affordable price.")
    + pa("As a specialist, we are experts at what we do and we will work with you to come up with the best garage organization solution. Let us show you how we put our garage storage solutions to the test.")
    + pa(OFFER, cls="gk-offer") + badges(),
]), width="1180px", pad="60")
write("welcome", "Welcome", body)

body = grp(hd(2, "Trusted across Central Texas", align="center") + pa("4.9 Google rating. A few words from our customers.", align="center")
           + review_rows(GREV[:3]) + btns(btn("Read more reviews", "/testimonials"), center=True), bg="grey", width="1180px")
write("reviews", "Reviews", body)
