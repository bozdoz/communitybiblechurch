<?php
/**
 * Template Name: All Video Template
 */
  global $post;
  global $wp_query;

  $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
 
  $args = array(
    'posts_per_page' => 3,
    'post_type' => 'video', 
    'paged' => $paged
  );

  $wp_query = new WP_Query($args);

  get_header(); ?>
  
  <div class="wrap">
    <?php if ( is_home() && ! is_front_page() ) : ?>
      <header class="page-header">
        <h1 class="page-title"><?php single_post_title(); ?></h1>
      </header>
    <?php else : ?>
    <header class="page-header">
      <h2 class="page-title"><?php _e( 'Video Posts', 'twentyseventeen' ); ?></h2>
    </header>
    <?php endif; ?>
  
    <div id="primary" class="content-area">
      <main id="main" class="site-main" role="main">
  
        <?php
          while ($wp_query->have_posts()):
            $wp_query->the_post();
  
            /*
             * Include the Post-Format-specific template for the content.
             * If you want to override this in a child theme, then include a file
             * called content-___.php (where ___ is the Post Format name) and that
             * will be used instead.
             */
            get_template_part( 'template-parts/post/video' );
  
          endwhile;
  
          the_posts_pagination(
            array(
              'prev_text'          => twentyseventeen_get_svg( array( 'icon' => 'arrow-left' ) ) . '<span class="screen-reader-text">' . __( 'Previous page', 'twentyseventeen' ) . '</span>',
              'next_text'          => '<span class="screen-reader-text">' . __( 'Next page', 'twentyseventeen' ) . '</span>' . twentyseventeen_get_svg( array( 'icon' => 'arrow-right' ) ),
              'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentyseventeen' ) . ' </span>',
            )
          );
        ?>
  
      </main><!-- #main -->
    </div><!-- #primary -->
    <?php get_sidebar(); ?>
  </div><!-- .wrap -->
  
  <?php
  get_footer();
  