<?php

remove_action( 'wp_enqueue_scripts', 'twentyseventeen_scripts' );

function commbible_enqueue_styles() {
	// get parent style
    wp_enqueue_style( 'twentyseventeen-style', get_template_directory_uri() . '/style.css' );
    // get child style
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( 'twentyseventeen-style' ),
        wp_get_theme()->get('Version')
	);
	
	// from twentyseventeen

	// Add custom fonts, used in the main stylesheet.
	wp_enqueue_style( 'twentyseventeen-fonts', 'https://fonts.googleapis.com/css?family=Open+Sans:700|Roboto:400,500', array(), null );

	// Load the Internet Explorer 8 specific stylesheet.
	wp_enqueue_style( 'twentyseventeen-ie8', get_theme_file_uri( '/assets/css/ie8.css' ), array( 'twentyseventeen-style' ), '1.0' );
	wp_style_add_data( 'twentyseventeen-ie8', 'conditional', 'lt IE 9' );

	// Load the html5 shiv.
	wp_enqueue_script( 'html5', get_theme_file_uri( '/assets/js/html5.js' ), array(), '3.7.3' );
	wp_script_add_data( 'html5', 'conditional', 'lt IE 9' );

	$twentyseventeen_l10n = array(
		'quote' => twentyseventeen_get_svg( array( 'icon' => 'quote-right' ) ),
	);

	wp_enqueue_script( 'twentyseventeen-global', get_theme_file_uri( '/assets/js/global.js' ), array( 'jquery' ), '1.0', true );

	wp_localize_script( 'twentyseventeen-skip-link-focus-fix', 'twentyseventeenScreenReaderText', $twentyseventeen_l10n );
}
add_action( 'wp_enqueue_scripts', 'commbible_enqueue_styles' );

if ( ! function_exists( 'commbible_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function commbible_posted_on() {
		// Finally, let's write all of this to the page.
		echo '<span class="posted-on">' . twentyseventeen_time_link() . '</span>';
	}
endif;


/* google schema */

add_action('wp_footer', 'business_schema');

function business_schema () {
	$title = get_bloginfo('name');
	$description = get_bloginfo('description');
	$url = get_bloginfo('url');
	$icon = get_site_icon_url();
	?>
	<script type="application/ld+json">
	{
	  "@context": "http://schema.org",
	  "@type": "Organization",
	  <?php if ($icon): ?>
	  "image": "<?php echo $icon; ?>",
	  <?php endif; ?>
	  "address": {
	    "@type": "PostalAddress",
	    "addressLocality": "Timberlea",
	    "addressRegion": "NS",
	    "postalCode": "B3T1J1",
	    "streetAddress": "3284 St Margarets Bay Rd"
	  },
	  "name": "<?php echo $title; ?>",
	  "telephone": "1 (902) 821-2114",
	  "url": "<?php echo $url; ?>"
	}
	</script>
	<?php
}

add_filter('jetpack_enable_opengraph', '__return_false', 99);

add_filter('jetpack_disable_twitter_cards', '__return_true', 99);

add_action('wp_head', 'bjd_social_meta');

function bjd_social_meta () {
	$title = get_bloginfo('name');
	$description = get_bloginfo('description');
	$url = get_bloginfo('url');
	$theme_color = '#00483a';
	$twitter_creator = '@bozdoz';
	$icon = get_site_icon_url();
	?>
	<meta name="description" content="<?php echo $description; ?>" />

	<!-- Chrome, Firefox OS and Opera -->
	<meta name="theme-color" content="<?php echo $theme_color; ?>">

	<!-- Schema.org markup for Google+ -->
	<meta itemprop="name" content="<?php echo $title; ?>">
	<meta itemprop="description" content="<?php echo $description; ?>">
	
	<?php if ($icon): ?>
	<meta itemprop="image" content="<?php echo $icon; ?>">
	<?php endif; ?>

	<!-- Twitter Card data -->
	<meta name="twitter:card" content="summary">
	<meta name="twitter:title" content="<?php echo $title; ?>">
	<meta name="twitter:description" content="<?php echo $description; ?>">
	<meta name="twitter:creator" content="<?php echo $twitter_creator; ?>">
	<?php if ($icon): ?>
	<meta name="twitter:image" content="<?php echo $icon; ?>">
	<?php endif; ?>

	<!-- Open Graph data -->
	<meta property="og:title" content="<?php echo $title; ?>" />
	<meta property="og:type" content="article" />
	<meta property="og:url" content="<?php echo $url; ?>" />
	<?php if ($icon): ?>
	<meta property="og:image" content="<?php echo $icon; ?>" />
	<?php endif; ?>
	<meta property="og:description" content="<?php echo $description; ?>" />
	<meta property="og:site_name" content="<?php echo $title; ?>" />
	<?php
}

/* shortcode for displaying employees */
add_shortcode('teamlist', 'displayTeam');
function displayTeam () {
    $args = array(
    	'posts_per_page'   => 25,
    	'orderby'          => 'id',
    	'order'            => 'ASC',
    	'post_type'        => 'team',
    	'post_status'      => 'publish'); 
    
    $teams = get_posts( $args );
    ob_start(); 
    ?>
    <div class="team-list">
    <?php
    foreach ( $teams as $team ) {
    	$thumb_url = get_the_post_thumbnail_url($team->ID, array(400,400)); 
    	$permalink = get_permalink($team->ID); 
    	$position = get_post_meta($team->ID, 'team_position', true);
    ?>
    	<a class="img-holder" 
			href="$permalink"
			title="$team->post_title">
			<span class="img img-block"
				style="background-image:url(<?php echo $thumb_url; ?>);">
				<span class="img-screen">
					<span class="center-vertical">
					<span class="img-title"><?php echo $team->post_title; ?></span>
					<span class="img-subtitle"><?php echo $position; ?></span>
					</span>
				</span>
			</span>
		</a>
	<?php
    }
    ?>
    </div>
	<?php
    wp_reset_postdata();
    return ob_get_clean();
}

function custom_post_video() {
	$labels = array(
		'name'               => _x( 'Videos', 'post type general name' ),
		'singular_name'      => _x( 'Video', 'post type singular name' ),
		'add_new'            => _x( 'Add New', 'book' ),
		'add_new_item'       => __( 'Add New Video' ),
		'edit_item'          => __( 'Edit Video' ),
		'new_item'           => __( 'New Video' ),
		'all_items'          => __( 'All Videos' ),
		'view_item'          => __( 'View Video' ),
		'search_items'       => __( 'Search Videos' ),
		'not_found'          => __( 'No Videos found' ),
		'not_found_in_trash' => __( 'No Videos found in the Trash' ), 
		'parent_item_colon'  => '',
		'menu_name'          => 'Videos'
	);
	$args = array(
		'labels'        => $labels,
		'description'   => 'Holds our Videos and Video specific data',
		'public'        => true,
		'menu_position' => 5,
		'supports'      => array( 'title', 'editor', 'excerpt' ),
		'has_archive'   => true,
		'with_front'    => true,
	);
	register_post_type( 'video', $args );	
}
add_action( 'init', 'custom_post_video' );

function meta_box_custom_content( $atts=Array() ) {
	extract($atts);
	
	$input_type = empty($input_type) ? "text" : $input_type;

	$value = get_post_meta( $post_id, $name, true );

	if (!$value) {
		$value = empty($default) ? '' : $default;
	}
	?>	
	<input type="<?php echo $input_type; ?>" 
		id="<?php echo $name; ?>"
		value="<?php echo $value; ?>"
		name="<?php echo $name; ?>"
		placeholder="<?php echo empty($placeholder) ? '' : $placeholder; ?>" />
	<?php
}

/* link */
add_action( 'add_meta_boxes', 'video_link_box' );
function video_link_box() {
    add_meta_box( 
        'video_link_box',
        'YT Link',
        'video_link_box_content',
        'video',
        'side',
        'high'
    );
}

function video_link_box_content( $post ) {
	wp_nonce_field( basename( __FILE__ ), 'video_link_box_content_nonce' );
	
	$atts = Array(
		'name' => 'video_link',
		'post_id' => $post->ID,
		'placeholder' => 'YouTube URL',
	);
	
	meta_box_custom_content($atts);
}

/* 
* SAVE META BOXES! 
*/

$custom_meta_box_names = Array(
	'video_link',
);

add_action( 'save_post', 'video_price_box_save' );
function video_price_box_save( $post_id ) {
	global $custom_meta_box_names;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
		return;

	/* verify just one is enough? */
	$first = $custom_meta_box_names[0];
	if (!isset($_POST[$first . '_box_content_nonce']))
		return;

	if (!wp_verify_nonce( $_POST[$first . '_box_content_nonce'], basename( __FILE__ ) ) )
		return;

	if ( !current_user_can( 'edit_post', $post_id ) )
		return;
	
	foreach ($custom_meta_box_names as $name) {
		if (isset($_POST[$name])) {
			update_post_meta( $post_id, $name, $_POST[$name] );
		}
	}
}

/* 
* END META BOXES 
*/