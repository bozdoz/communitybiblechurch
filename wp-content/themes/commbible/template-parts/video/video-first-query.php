<?php
  $args = array(
    'posts_per_page'   => 1,
    'orderby'          => 'id',
    'order'            => 'DESC',
    'post_type'        => 'video',
    'post_status'      => 'publish'
  );

  $query = new WP_Query($args);

  while ($query->have_posts()):
      $query->the_post();
      get_template_part( 'template-parts/video/video', 'single' );
  endwhile;
?>