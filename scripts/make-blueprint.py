#!/usr/bin/env python3
"""Build blueprint.json: a WordPress Playground blueprint that installs the theme from this repo.

Usage: python3 scripts/make-blueprint.py [owner/repo] [ref]
Re-run after adding or removing theme files. Playground reads the files from raw.githubusercontent.com,
so the repo must be public while the link is being used.
"""
import json
import pathlib
import sys

args = [a for a in sys.argv[1:] if not a.startswith("--")]
repo = args[0] if len(args) > 0 else "we-are-web-services/site-thegarageking"
ref = args[1] if len(args) > 1 else "main"
root = pathlib.Path(__file__).resolve().parent.parent
theme = root / "theme" / "garage-king"
dest = "/wordpress/wp-content/themes/garage-king"
raw = f"https://raw.githubusercontent.com/{repo}/{ref}/theme/garage-king"

# slug: (title, pattern, template, parent slug)
PAGES = {
    "overhead-racks": ("Overhead Storage Solutions", "page-overhead-racks", "", ""),
    "slatwall": ("Slatwall", "page-slatwall", "", ""),
    "steel-cabinets": ("Steel Cabinets", "page-steel-cabinets", "", ""),
    "garage-storage-solutions": ("Garage Storage Solutions", "page-solutions", "", ""),
    "motorized-overhead-racks": ("Motorized Overhead Racks", "page-motorized", "", ""),
    "garage-floor-coatings": ("Garage Floor Coatings", "page-floor-coatings", "", ""),
    "garage-exhaust-fans": ("Garage Ventilation", "page-ventilation", "", ""),
    "lifts": ("Lifts", "page-lifts", "", ""),
    "garage-door-installation-repairs": ("Garage Door Installation and Repairs", "page-garage-doors", "", ""),
    "garage-storage-photos": ("Our Work", "page-gallery", "", ""),
    "project-stories": ("Project Stories", "page-project-stories", "", "garage-storage-photos"),
    "how-it-works": ("How It Works", "page-how-it-works", "", ""),
    "garage-storage-pricing": ("Pricing and Offers", "page-pricing", "", ""),
    "testimonials": ("Reviews", "page-testimonials", "", ""),
    "faq": ("FAQ", "page-faq", "", ""),
    "about": ("About", "page-about", "", ""),
    "contact-us": ("Contact", "page-contact", "", ""),
    "service-areas": ("Service Areas", "page-service-areas", "", ""),
    "schedule-quote": ("Book a Free Design Consultation", "page-schedule-quote", "", ""),
    "privacy-policy": ("Privacy Policy", "page-privacy", "", ""),
    "terms": ("Terms of Use and SMS Terms", "page-terms", "", ""),
    "ga": ("Garage Storage Offer", "page-ga", "page-landing", ""),
    "nextdoor": ("Nextdoor Offer", "page-channel-landing", "page-landing", ""),
    "sael": ("SAEL Offer", "page-channel-landing", "page-landing", ""),
    # the live site's thank-you address, kept as is so the Google Ads conversion tied to it keeps working (David, 2026-10-23)
    "ga-thank-you-page-garage-talk": ("Thank You", "page-thank-you", "page-landing", ""),
}

plugins = root / "plugins"
plugin_files = sorted(p for p in plugins.rglob("*") if p.is_file() and p.name != ".gitkeep")
files = sorted(p for p in theme.rglob("*") if p.is_file())
dirs = sorted({str(p.parent.relative_to(theme)) for p in files} - {"."})

steps = [{"step": "login", "username": "admin", "password": "password"}]
steps.append({"step": "mkdir", "path": dest})
for d in dirs:
    steps.append({"step": "mkdir", "path": f"{dest}/{d}"})
for p in files:
    rel = p.relative_to(theme).as_posix()
    steps.append({"step": "writeFile", "path": f"{dest}/{rel}", "data": {"resource": "url", "url": f"{raw}/{rel}"}})
for p in plugin_files:
    rel = p.relative_to(plugins)
    if rel.parent != pathlib.Path("."):
        steps.append({"step": "mkdir", "path": f"/wordpress/wp-content/plugins/{rel.parent.as_posix()}"})
    steps.append({"step": "writeFile", "path": f"/wordpress/wp-content/plugins/{rel.as_posix()}",
                  "data": {"resource": "url", "url": f"https://raw.githubusercontent.com/{repo}/{ref}/plugins/{rel.as_posix()}"}})
for d in sorted({p.relative_to(plugins).parts[0] for p in plugin_files}):
    steps.append({"step": "activatePlugin", "pluginPath": f"{d}/{d}.php"})
steps.append({"step": "writeFile", "path": "/wordpress/gk-posts.json", "data": {"resource": "url", "url": f"https://raw.githubusercontent.com/{repo}/{ref}/content/posts.json"}})
steps.append({"step": "activateTheme", "themeFolderName": "garage-king"})

php = ["<?php",
       "if (!function_exists('wp_insert_post')) { require_once '/wordpress/wp-load.php'; }",
       "update_option('blogname','The Garage King');",
       "update_option('blogdescription','First draft: text and photos from the current site');",
       "update_option('permalink_structure','/%postname%/');",
       "$h = get_page_by_path('home');",
       "$home = $h ? $h->ID : wp_insert_post(array('post_type'=>'page','post_status'=>'publish','post_title'=>'Home','post_name'=>'home','post_content'=>''));",
       f"$pages = json_decode('{json.dumps(PAGES)}', true);",
       "foreach ($pages as $slug => $row) {",
       "  $path = $row[3] ? $row[3].'/'.$slug : $slug;",
       "  if (get_page_by_path($path)) { continue; }",
       "  $parent = $row[3] ? get_page_by_path($row[3]) : null;",
       "  $content = $row[1] ? '<!-- wp:pattern {\"slug\":\"garage-king/'.$row[1].'\"} /-->' : '';",
       "  $id = wp_insert_post(array('post_type'=>'page','post_status'=>'publish','post_title'=>$row[0],'post_name'=>$slug,'post_parent'=>$parent ? $parent->ID : 0,'post_content'=>$content));",
       "  if ($row[2]) { update_post_meta($id, '_wp_page_template', $row[2]); }",
       "}",
       "update_option('show_on_front','page'); update_option('page_on_front',$home);",
       "$file = defined('GK_POSTS') ? GK_POSTS : '/wordpress/gk-posts.json';",
       "if (file_exists($file) && !get_posts(array('post_type'=>'post','numberposts'=>1))) {",
       "  foreach (json_decode(file_get_contents($file), true) as $p) {",
       "    $pid = wp_insert_post(array('post_type'=>'post','post_status'=>'publish','post_title'=>$p['title'],'post_name'=>$p['slug'],'post_date'=>$p['date'],'post_excerpt'=>$p['excerpt'],'post_content'=>$p['content']));",
       "    if (!empty($p['category']) && $pid && !is_wp_error($pid)) { require_once ABSPATH.'wp-admin/includes/taxonomy.php'; wp_set_post_categories($pid, array(wp_create_category($p['category']))); }",
       "  }",
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
if "--php" in sys.argv:
    (root / "scripts" / "site-setup.php").write_text("\n".join(php) + "\n")
print(f"blueprint.json: {len(files)} theme files, {len(PAGES)} pages")
