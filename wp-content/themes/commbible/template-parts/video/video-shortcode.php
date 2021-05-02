<?php
  $video_link = get_post_meta(get_the_ID(), 'video_link', true);

  if ($video_link) {
    // [embed]https://youtu.be/4P27u4TK7bI[/embed]
    $shortcode = "[embed]${video_link}[/embed]";
    // can't use embed shortcode outside of `the_content`
    $video = $GLOBALS['wp_embed']->run_shortcode($shortcode);

    ?>
    <div style="display: flex; justify-content: center;">
      <?php echo $video; ?>
    </div>
    <?php
  }