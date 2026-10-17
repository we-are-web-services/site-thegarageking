#!/usr/bin/env python3
"""Copy public text from the current thegarageking.com into content/*.json (privacy policy, blog posts).

Reads only public pages through the site's REST API. Online-sales pages are not touched. Usage: python3 scripts/import_content.py
"""
import html
import json
import pathlib
import re
import urllib.request
from html.parser import HTMLParser

ROOT = pathlib.Path(__file__).resolve().parent.parent
BASE = "https://thegarageking.com/wp-json/wp/v2"


class Blocks(HTMLParser):
    def __init__(self):
        super().__init__(convert_charrefs=True)
        self.out, self.cur, self.skip = [], None, 0

    def handle_starttag(self, t, a):
        a = dict(a)
        if t in ("script", "style", "noscript", "form", "svg"):
            self.skip += 1
        elif self.skip:
            return
        elif t in ("h1", "h2", "h3", "h4", "h5", "h6", "p", "li", "blockquote"):
            self.cur = [t, ""]
        elif t == "img":
            src = a.get("src") or ""
            if src.startswith("data:"):
                src = a.get("data-src") or a.get("data-lazy-src") or ""
            if src:
                self.out.append(("img", src.split("?")[0], a.get("alt", "")))

    def handle_endtag(self, t):
        if t in ("script", "style", "noscript", "form", "svg"):
            self.skip = max(0, self.skip - 1)
        elif not self.skip and self.cur and t == self.cur[0]:
            txt = re.sub(r"\s+", " ", self.cur[1]).strip()
            if txt:
                self.out.append((self.cur[0], txt))
            self.cur = None

    def handle_data(self, d):
        if not self.skip and self.cur is not None:
            self.cur[1] += d


def blocks(rendered):
    p = Blocks()
    p.feed(rendered)
    return p.out


def get(path):
    req = urllib.request.Request(f"{BASE}/{path}", headers={"User-Agent": "Mozilla/5.0 (site-thegarageking importer)"})
    with urllib.request.urlopen(req, timeout=60) as r:
        return json.load(r)


def esc(t):
    return html.escape(t, quote=False)


def markup(bl):
    """Turn extracted blocks into WordPress block markup, grouping list items."""
    out, items = [], []

    def flush():
        nonlocal items
        if items:
            li = "".join(f"<!-- wp:list-item -->\n<li>{esc(x)}</li>\n<!-- /wp:list-item -->\n" for x in items)
            out.append(f"<!-- wp:list -->\n<ul>{li}</ul>\n<!-- /wp:list -->\n")
            items = []

    for b in bl:
        if b[0] == "li":
            items.append(b[1])
            continue
        flush()
        if b[0] == "img":
            out.append(f'<!-- wp:image {{"sizeSlug":"large"}} -->\n<figure class="wp-block-image size-large"><img src="{html.escape(b[1])}" alt="{html.escape(b[2])}"/></figure>\n<!-- /wp:image -->\n')
        elif b[0].startswith("h"):
            lv = min(max(int(b[0][1]), 2), 4)
            out.append(f'<!-- wp:heading {{"level":{lv}}} -->\n<h{lv} class="wp-block-heading">{esc(b[1])}</h{lv}>\n<!-- /wp:heading -->\n')
        else:
            out.append(f"<!-- wp:paragraph -->\n<p>{esc(b[1])}</p>\n<!-- /wp:paragraph -->\n")
    flush()
    return "".join(out)


def main():
    pages = get("pages?per_page=100&_fields=slug,content")
    priv = next(p for p in pages if p["slug"] == "privacy-policy")
    (ROOT / "content" / "privacy.json").write_text(json.dumps(blocks(priv["content"]["rendered"]), indent=1))
    posts = get("posts?per_page=100&_fields=slug,date,title,content")
    keep = []
    for p in posts:
        title = html.unescape(p["title"]["rendered"])
        if p["slug"].startswith(("review-by", "google", "2653")) or "<meta" in title:
            continue  # reviews are shown on the Reviews page; the others are verification files
        bl = blocks(p["content"]["rendered"])
        text = [b for b in bl if b[0] == "p"]
        keep.append({"slug": p["slug"], "title": title, "date": p["date"],
                     "excerpt": (text[0][1][:180] + "…") if text else "", "content": markup(bl)})
    (ROOT / "content" / "posts.json").write_text(json.dumps(keep, indent=1))
    print("privacy blocks:", len(blocks(priv["content"]["rendered"])), "posts:", len(keep))


if __name__ == "__main__":
    main()
