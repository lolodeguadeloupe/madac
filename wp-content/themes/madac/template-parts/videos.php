<?php
$video_query = new WP_Query([
    'post_type'      => 'madac_video',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
]);
?>
<section class="section videos" id="videos">
  <div class="videos-header appear">
    <div>
      <div class="section-tag">En vidéo</div>
      <h2 class="section-h">Galerie <em>YouTube</em></h2>
    </div>
    <a href="#" target="_blank" class="btn btn-primary">Voir toutes &rarr;</a>
  </div>
  <div class="videos-grid">
    <?php if ($video_query->have_posts()) : ?>
      <?php while ($video_query->have_posts()) : $video_query->the_post();
        $youtube_url  = get_post_meta(get_the_ID(), '_madac_vid_youtube_url', true);
        $description  = get_post_meta(get_the_ID(), '_madac_vid_description', true);
        $date_display = get_post_meta(get_the_ID(), '_madac_vid_date_display', true);

        // Extract YouTube embed ID
        $embed_id = '';
        if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([^&\?\s]+)/', $youtube_url, $matches)) {
            $embed_id = $matches[1];
        }
      ?>
      <div class="video-card appear">
        <div class="video-frame">
          <?php if ($embed_id) : ?>
          <iframe src="https://www.youtube.com/embed/<?php echo esc_attr($embed_id); ?>" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
          <?php endif; ?>
        </div>
        <div class="video-info">
          <h3 class="video-title"><?php the_title(); ?></h3>
          <p class="video-desc"><?php echo esc_html($description); ?></p>
          <span class="video-date"><?php echo esc_html($date_display); ?></span>
        </div>
      </div>
      <?php endwhile; wp_reset_postdata(); ?>
    <?php endif; ?>
  </div>
</section>
