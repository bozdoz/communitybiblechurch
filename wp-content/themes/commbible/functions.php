<?php

remove_action( 'wp_enqueue_scripts', 'twentyseventeen_scripts' );

function commbible_enqueue_styles() {
	// get parent style
    wp_enqueue_style( 'twentyseventeen-style', get_template_directory_uri() . '/style.css' );
    // get child style
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/commbible-style.css',
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

function custom_post_project() {
	$labels = array(
		'name'               => _x( 'Projects', 'post type general name' ),
		'singular_name'      => _x( 'Project', 'post type singular name' ),
		'add_new'            => _x( 'Add New', 'book' ),
		'add_new_item'       => __( 'Add New Project' ),
		'edit_item'          => __( 'Edit Project' ),
		'new_item'           => __( 'New Project' ),
		'all_items'          => __( 'All Projects' ),
		'view_item'          => __( 'View Project' ),
		'search_items'       => __( 'Search Projects' ),
		'not_found'          => __( 'No projects found' ),
		'not_found_in_trash' => __( 'No projects found in the Trash' ), 
		'parent_item_colon'  => '',
		'menu_name'          => 'Projects'
	);
	$args = array(
		'labels'        => $labels,
		'description'   => 'Holds our Projects and Project specific data',
		'public'        => true,
		'menu_position' => 5,
		'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
		'has_archive'   => true,
	);
	register_post_type( 'project', $args );	
}
add_action( 'init', 'custom_post_project' );

/* size */
add_action( 'add_meta_boxes', 'project_size_box' );
function project_size_box() {
    add_meta_box( 
        'project_size_box',
        'Date',
        'project_size_box_content',
        'project',
        'side',
        'high'
    );
}

function meta_box_custom_content ( $post_type, $text, $id, $input_type="text", $default = "") {
	$name = $post_type . '_' . $text;
	$value = get_post_meta( $id, $name, true );

	if (!$value) {
		$value = $default;
	}
	?>	
	<input type="<?php echo $input_type; ?>" 
		id="<?php echo $name; ?>"
		value="<?php echo $value; ?>"
		name="<?php echo $name; ?>"
		placeholder="enter a <?php echo $text ?>" />
	<?php
}

function project_size_box_content( $post ) {
	wp_nonce_field( basename( __FILE__ ), 'project_size_box_content_nonce' );
	
	meta_box_custom_content('project', 'size', $post->ID, 'date', date('Y-m-d'));
}

/* 
* SAVE META BOXES! 
*/

$custom_meta_box_names = Array(
	'project_size'
);

add_action( 'save_post', 'project_price_box_save' );
function project_price_box_save( $post_id ) {
	
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
		return;

	/* verify just one is enough? */
	if (!isset($_POST['project_size_box_content_nonce']))
		return;

	if (!wp_verify_nonce( $_POST['project_size_box_content_nonce'], basename( __FILE__ ) ) )
		return;

	if ( !current_user_can( 'edit_post', $post_id ) )
		return;

	global $custom_meta_box_names;
	
	foreach ($custom_meta_box_names as $name) {
		if (isset($_POST[$name])) {
			update_post_meta( $post_id, $name, $_POST[$name] );
		}
	}
}

/* 
* END META BOXES 
*/