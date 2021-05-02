<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <header class="entry-header">
    <?php
    echo '<div class="entry-meta">';
    if ( is_single() ) {
      commbible_posted_on();
    } else {
      echo twentyseventeen_time_link();
      twentyseventeen_edit_link();
    };
    echo '</div><!-- .entry-meta -->';

    if (is_front_page()) {
      the_title( '<h2 class="entry-title">', '</h2>' );
    } else if ( is_single() ) {
      the_title( '<h1 class="entry-title">', '</h1>' );
    } else {
      the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
    }
    ?>
  </header><!-- .entry-header -->

  <?php if ( '' !== get_the_post_thumbnail() && ! is_single() ) : ?>
    <div class="post-thumbnail">
      <a href="<?php the_permalink(); ?>">
        <?php the_post_thumbnail( 'twentyseventeen-featured-image' ); ?>
      </a>
    </div><!-- .post-thumbnail -->
  <?php endif; ?>

  <div class="entry-content">
    <?php
    get_template_part( 'template-parts/video/video', 'shortcode' );

    the_content(
      sprintf(
        /* translators: %s: Post title. */
        __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'twentyseventeen' ),
        get_the_title()
      )
    );

    wp_link_pages(
      array(
        'before'      => '<div class="page-links">' . __( 'Pages:', 'twentyseventeen' ),
        'after'       => '</div>',
        'link_before' => '<span class="page-number">',
        'link_after'  => '</span>',
      )
    );
    ?>
  </div><!-- .entry-content -->

  <?php
  if ( is_single() ) {
    twentyseventeen_entry_footer();
  }
  ?>
</article><!-- #post-<?php the_ID(); ?> -->