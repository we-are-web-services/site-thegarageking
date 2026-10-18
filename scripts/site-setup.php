<?php
if (!function_exists('wp_insert_post')) { require_once '/wordpress/wp-load.php'; }
update_option('blogname','The Garage King');
update_option('blogdescription','Prototype: text and photos copied from the current site');
update_option('permalink_structure','/%postname%/');
$h = get_page_by_path('home');
$home = $h ? $h->ID : wp_insert_post(array('post_type'=>'page','post_status'=>'publish','post_title'=>'Home','post_name'=>'home','post_content'=>''));
$pages = json_decode('{"overhead-racks": ["Overhead Garage Storage Racks", "page-overhead-racks", "", ""], "slatwall": ["Slatwall", "page-slatwall", "", ""], "steel-cabinets": ["Steel Cabinets", "page-steel-cabinets", "", ""], "garage-storage-solutions": ["Garage Storage Solutions", "page-solutions", "", ""], "motorized-overhead-racks": ["Motorized Overhead Storage", "page-motorized", "", ""], "garage-floor-coatings": ["Garage Floor Coatings", "page-floor-coatings", "", ""], "garage-exhaust-fans": ["Garage Ventilation", "page-ventilation", "", ""], "garage-storage-photos": ["Our Work", "page-gallery", "", ""], "how-it-works": ["How It Works", "page-how-it-works", "", ""], "garage-storage-pricing": ["Pricing and Offers", "page-pricing", "", ""], "testimonials": ["Reviews", "page-testimonials", "", ""], "faq": ["FAQ", "page-faq", "", ""], "about": ["About", "page-about", "", ""], "contact-us": ["Contact", "page-contact", "", ""], "service-areas": ["Service Areas", "page-service-areas", "", ""], "schedule-quote": ["Book a Free Design Consultation", "page-schedule-quote", "", ""], "privacy-policy": ["Privacy Policy", "page-privacy", "", ""], "terms": ["Terms of Use and SMS Terms", "page-stub", "", ""], "thank-you": ["Thank You", "page-thank-you", "", ""], "ga": ["Garage Storage Offer", "page-ga", "page-landing", ""], "nextdoor": ["Nextdoor Offer", "page-channel-landing", "page-landing", ""], "sael": ["SAEL Offer", "page-channel-landing", "page-landing", ""], "blog": ["Garage Tips and Ideas", "", "", ""], "quote": ["Thank You", "page-thank-you", "", "thank-you"], "contact": ["Thank You", "page-thank-you", "", "thank-you"], "ads": ["Thank You", "page-thank-you", "", "thank-you"]}', true);
foreach ($pages as $slug => $row) {
  $path = $row[3] ? $row[3].'/'.$slug : $slug;
  if (get_page_by_path($path)) { continue; }
  $parent = $row[3] ? get_page_by_path($row[3]) : null;
  $content = $row[1] ? '<!-- wp:pattern {"slug":"garage-king/'.$row[1].'"} /-->' : '';
  $id = wp_insert_post(array('post_type'=>'page','post_status'=>'publish','post_title'=>$row[0],'post_name'=>$slug,'post_parent'=>$parent ? $parent->ID : 0,'post_content'=>$content));
  if ($row[2]) { update_post_meta($id, '_wp_page_template', $row[2]); }
}
$blog = get_page_by_path('blog');
update_option('show_on_front','page'); update_option('page_on_front',$home); if ($blog) { update_option('page_for_posts',$blog->ID); }
$file = defined('GK_POSTS') ? GK_POSTS : '/wordpress/gk-posts.json';
if (file_exists($file) && !get_posts(array('post_type'=>'post','numberposts'=>1))) {
  foreach (json_decode(file_get_contents($file), true) as $p) {
    wp_insert_post(array('post_type'=>'post','post_status'=>'publish','post_title'=>$p['title'],'post_name'=>$p['slug'],'post_date'=>$p['date'],'post_excerpt'=>$p['excerpt'],'post_content'=>$p['content']));
  }
}
flush_rewrite_rules();
