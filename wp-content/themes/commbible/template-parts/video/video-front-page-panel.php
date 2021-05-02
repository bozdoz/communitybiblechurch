<?php
  global $twentyseventeencounter;
?>
<article id="panel<?php echo $twentyseventeencounter; ?>" <?php post_class( 'twentyseventeen-panel ' ); ?> >
<div class="panel-content">
  <div class="wrap">
    <?php
      get_template_part( 'template-parts/video/video', 'first-query' );
    
      // TODO: this should just be the archive page:
      // https://communitybiblechapel.ca/video/
      $page = get_page_by_title( 'All Videos' );
				
      if ($page): ?>
        <a href="<?php echo esc_url( get_permalink( $page ) ); ?>">See Older Videos</a>
      <?php endif;
		?>
  </div><!-- .wrap -->
</div><!-- .panel-content -->

</article><!-- #post-<?php the_ID(); ?> -->