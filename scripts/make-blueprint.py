#!/usr/bin/env python3
"""Build blueprint.json: a WordPress Playground blueprint that installs the theme from this repo.

Usage: python3 scripts/make-blueprint.py [owner/repo] [ref]
Re-run after adding or removing theme files. Playground reads the files from raw.githubusercontent.com,
so the repo must be public while the link is being used.
"""
import json
import pathlib
import sys

repo = sys.argv[1] if len(sys.argv) > 1 else "we-are-web-services/site-thegarageking"
ref = sys.argv[2] if len(sys.argv) > 2 else "main"
root = pathlib.Path(__file__).resolve().parent.parent
theme = root / "theme" / "garage-king"
dest = "/wordpress/wp-content/themes/garage-king"
raw = f"https://raw.githubusercontent.com/{repo}/{ref}/theme/garage-king"

PAGES = {
    "overhead-racks": "Overhead Storage Solutions", "garage-storage-solutions": "Garage Storage Solutions",
    "motorized-overhead-racks": "Motorized Overhead Storage", "slatwall": "Slatwall",
    "garage-cabinets": "Garage Cabinets", "garage-floor-coatings": "Garage Floor Coatings",
    "garage-exhaust-fans": "Garage Ventilation", "garage-storage-photos": "Our Work",
    "how-it-works": "How It Works", "garage-storage-pricing": "Pricing and Offers",
    "testimonials": "Reviews", "faq": "FAQ", "about": "About", "contact-us": "Contact",
    "service-areas": "Service Areas", "schedule-quote": "Book a Free Design Consultation",
    "privacy-policy": "Privacy Policy", "terms": "Terms of Use and SMS Terms",
    "thank-you": "Thank You", "ga": "Garage Storage Offer",
}

files = sorted(p for p in theme.rglob("*") if p.is_file())
dirs = sorted({str(p.parent.relative_to(theme)) for p in files} - {"."})

steps = [{"step": "login", "username": "admin", "password": "password"}]
steps.append({"step": "mkdir", "path": dest})
for d in dirs:
    steps.append({"step": "mkdir", "path": f"{dest}/{d}"})
for p in files:
    rel = p.relative_to(theme).as_posix()
    steps.append({"step": "writeFile", "path": f"{dest}/{rel}", "data": {"resource": "url", "url": f"{raw}/{rel}"}})
steps.append({"step": "activateTheme", "themeFolderName": "garage-king"})

php = ["<?php", "require_once '/wordpress/wp-load.php';",
       "update_option('blogname','The Garage King');",
       "update_option('blogdescription','Prototype: placeholder content only');",
       "update_option('permalink_structure','/%postname%/');",
       f"$pages = json_decode('{json.dumps(PAGES)}', true);",
       "foreach ($pages as $slug => $title) {",
       "  if (get_page_by_path($slug)) { continue; }",
       "  $pattern = ($slug === 'overhead-racks') ? 'product-page' : 'page-stub';",
       "  wp_insert_post(array('post_type'=>'page','post_status'=>'publish','post_title'=>$title,'post_name'=>$slug,",
       "    'post_content'=>'<!-- wp:pattern {\"slug\":\"garage-king/'.$pattern.'\"} /-->'));",
       "}",
       "flush_rewrite_rules();"]
steps.append({"step": "runPHP", "code": "\n".join(php)})

blueprint = {
    "$schema": "https://playground.wordpress.net/blueprint-schema.json",
    "landingPage": "/",
    "preferredVersions": {"php": "8.3", "wp": "latest"},
    "features": {"networking": True},
    "steps": steps,
}
(root / "blueprint.json").write_text(json.dumps(blueprint, indent=2) + "\n")
print(f"blueprint.json: {len(files)} theme files, {len(PAGES)} pages")
