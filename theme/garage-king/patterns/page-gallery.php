<?php
/**
 * Title: Gallery page
 * Slug: garage-king/page-gallery
 * Categories: garage-king
 * Inserter: no
 */
?>
<!-- wp:cover {"url":"<?php echo gk_placeholder( '2021/04/20101129-IMG_4456-scaled.jpg' ); ?>","dimRatio":72,"overlayColor":"black","minHeight":46,"minHeightUnit":"vh","contentPosition":"center left","align":"full","className":"gk-hero gk-hero--page","layout":{"type":"constrained"}} -->
<div class="wp-block-cover alignfull has-custom-content-position is-position-center-left gk-hero gk-hero--page" style="min-height:46vh"><span aria-hidden="true" class="wp-block-cover__background has-black-background-color has-background-dim-72 has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="<?php echo gk_placeholder( '2021/04/20101129-IMG_4456-scaled.jpg' ); ?>" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:paragraph {"textColor": "amber", "className": "gk-eyebrow"} -->
<p class="gk-eyebrow has-amber-color has-text-color">Photo gallery</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level": 1, "textColor": "white"} -->
<h1 class="wp-block-heading has-white-color has-text-color">Our Work</h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor": "muted", "fontSize": "medium"} -->
<p class="has-muted-color has-text-color has-medium-font-size">Photos of our team and installations in Greater Austin and San Antonio.</p>
<!-- /wp:paragraph -->
</div></div>
<!-- /wp:cover -->
<!-- wp:group {"className": "gk-section", "layout": {"type": "constrained", "contentSize": "1280px"}, "align": "full", "style": {"spacing": {"padding": {"top": "var:preset|spacing|50", "bottom": "var:preset|spacing|50"}}}} -->
<div class="wp-block-group alignfull gk-section" style="padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50)"><!-- wp:heading -->
<h2 class="wp-block-heading">Before and after</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Drag the slider to see a wall before and after. This is a sample pair of photos from a slatwall install, to preview how the sliders will work. Real before and after pairs come from the client.</p>
<!-- /wp:paragraph -->
<!-- wp:html -->
<?php echo gk_before_after( 'sample-before-slatwall.jpg', 'sample-after-slatwall.jpg', 'A wall before and after slatwall was installed', 'Sample: slatwall install' ); ?>
<!-- /wp:html -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className": "gk-section", "layout": {"type": "constrained", "contentSize": "1280px"}, "align": "full", "style": {"spacing": {"padding": {"top": "var:preset|spacing|50", "bottom": "var:preset|spacing|50"}}}} -->
<div class="wp-block-group alignfull gk-section" style="padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50)"><!-- wp:heading -->
<h2 class="wp-block-heading">Recent installs</h2>
<!-- /wp:heading -->
<!-- wp:gallery {"linkTo":"none","className":"gk-gallery-grid"} -->
<figure class="wp-block-gallery has-nested-images columns-default is-cropped gk-gallery-grid"><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/garage-complete-1.jpg' ) ); ?>" alt="A finished garage installation by The Garage King"/></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/garage-complete-2.jpg' ) ); ?>" alt="A finished garage installation by The Garage King"/></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/garage-complete-3.jpg' ) ); ?>" alt="A finished garage installation by The Garage King"/></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/overhead-racks-two-car.jpg' ) ); ?>" alt="A finished garage installation by The Garage King"/></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/garage-complete-4.jpg' ) ); ?>" alt="A finished garage installation by The Garage King"/></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/motorized-two-racks-dark.jpg' ) ); ?>" alt="A finished garage installation by The Garage King"/></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/overhead-rack-slatwall-door.jpg' ) ); ?>" alt="A finished garage installation by The Garage King"/></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/steel-cabinets-wall.jpg' ) ); ?>" alt="A finished garage installation by The Garage King"/></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/steel-cabinets-workbench.jpg' ) ); ?>" alt="A finished garage installation by The Garage King"/></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/motorized-rack-net-closeup.jpg' ) ); ?>" alt="A finished garage installation by The Garage King"/></figure>
<!-- /wp:image -->
</figure>
<!-- /wp:gallery -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className": "gk-section", "layout": {"type": "constrained", "contentSize": "1280px"}, "align": "full", "style": {"spacing": {"padding": {"top": "var:preset|spacing|60", "bottom": "var:preset|spacing|60"}}}} -->
<div class="wp-block-group alignfull gk-section" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)"><!-- wp:paragraph {"align": "center"} -->
<p class="has-text-align-center">Below are some photos of our team and installation packages. We enjoy installing our garage organizers for you in Greater Austin and San Antonio. If you are interested in finding the right garage storage solution for you, contact us to provide some information about your garage.</p>
<!-- /wp:paragraph -->
<!-- wp:gallery {"linkTo":"none","className":"gk-gallery-grid"} -->
<figure class="wp-block-gallery has-nested-images columns-default is-cropped gk-gallery-grid"><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo gk_placeholder( '2021/04/20170303_180905-scaled.jpg' ); ?>" alt="The Garage King installation photo"/></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo gk_placeholder( '2021/04/20210212_101134-scaled.jpg' ); ?>" alt="The Garage King installation photo"/></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo gk_placeholder( '2021/04/20101129-IMG_4462-1-scaled.jpg' ); ?>" alt="The Garage King installation photo"/></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo gk_placeholder( '2021/04/20101129-IMG_4456-scaled.jpg' ); ?>" alt="The Garage King installation photo"/></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo gk_placeholder( '2021/04/20101129-IMG_4491-scaled.jpg' ); ?>" alt="The Garage King installation photo"/></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo gk_placeholder( '2021/04/20190225_135201-scaled.jpg' ); ?>" alt="The Garage King installation photo"/></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo gk_placeholder( '2021/04/20210329_134101-scaled.jpg' ); ?>" alt="The Garage King installation photo"/></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo gk_placeholder( '2021/04/Copy-of-20170623_122354-scaled.jpg' ); ?>" alt="The Garage King installation photo"/></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo gk_placeholder( '2021/04/20190227_103359-scaled.jpg' ); ?>" alt="The Garage King installation photo"/></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo gk_placeholder( '2021/04/Copy-of-20170522_132812-scaled.jpg' ); ?>" alt="The Garage King installation photo"/></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo gk_placeholder( '2021/04/20190227_141838-scaled.jpg' ); ?>" alt="The Garage King installation photo"/></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo gk_placeholder( '2021/04/20190227_141618-scaled.jpg' ); ?>" alt="The Garage King installation photo"/></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo gk_placeholder( '2023/01/20220715_174055-scaled.jpg' ); ?>" alt="The Garage King installation photo"/></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo gk_placeholder( '2023/01/20220712_175832-scaled.jpg' ); ?>" alt="The Garage King installation photo"/></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo gk_placeholder( '2021/04/20190718_125822-scaled.jpg' ); ?>" alt="The Garage King installation photo"/></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo gk_placeholder( '2021/04/20190828_134644-scaled.jpg' ); ?>" alt="The Garage King installation photo"/></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo gk_placeholder( '2021/04/20190225_134757-scaled.jpg' ); ?>" alt="The Garage King installation photo"/></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo gk_placeholder( '2021/04/20190225_134936-scaled.jpg' ); ?>" alt="The Garage King installation photo"/></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo gk_placeholder( '2021/04/20190225_135004-scaled.jpg' ); ?>" alt="The Garage King installation photo"/></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo gk_placeholder( '2012/03/PA010394.jpg' ); ?>" alt="The Garage King installation photo"/></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo gk_placeholder( '2012/03/PA020395.jpg' ); ?>" alt="The Garage King installation photo"/></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo gk_placeholder( '2012/03/PA020396.jpg' ); ?>" alt="The Garage King installation photo"/></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo gk_placeholder( '2012/03/PA020397.jpg' ); ?>" alt="The Garage King installation photo"/></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo gk_placeholder( '2012/03/PA020398.jpg' ); ?>" alt="The Garage King installation photo"/></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo gk_placeholder( '2012/03/PA020399.jpg' ); ?>" alt="The Garage King installation photo"/></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo gk_placeholder( '2012/03/PA020401.jpg' ); ?>" alt="The Garage King installation photo"/></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo gk_placeholder( '2012/03/PA020402.jpg' ); ?>" alt="The Garage King installation photo"/></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo gk_placeholder( '2012/03/PA020403.jpg' ); ?>" alt="The Garage King installation photo"/></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo gk_placeholder( '2012/03/PA020404.jpg' ); ?>" alt="The Garage King installation photo"/></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo gk_placeholder( '2012/03/PA020405.jpg' ); ?>" alt="The Garage King installation photo"/></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo gk_placeholder( '2012/03/PA020406.jpg' ); ?>" alt="The Garage King installation photo"/></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo gk_placeholder( '2012/03/PA020408.jpg' ); ?>" alt="The Garage King installation photo"/></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo gk_placeholder( '2012/03/PA020409.jpg' ); ?>" alt="The Garage King installation photo"/></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo gk_placeholder( '2012/03/PA020410.jpg' ); ?>" alt="The Garage King installation photo"/></figure>
<!-- /wp:image -->
</figure>
<!-- /wp:gallery -->
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="/schedule-quote">Book a Free Design Consultation</a></div>
<!-- /wp:button -->
<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="/garage-storage-photos/project-stories">Project stories</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
<!-- wp:pattern {"slug":"garage-king/cta-band"} /-->
