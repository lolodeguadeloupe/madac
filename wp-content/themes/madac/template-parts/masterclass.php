<?php
$masterclass_query = new WP_Query([
    'post_type'      => 'masterclass',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
]);
?>
<section class="section masterclass" id="masterclass">
  <div class="appear">
    <div class="section-tag">Formations</div>
    <h2 class="section-h">Nos <em>Masterclass</em></h2>
  </div>
  <div class="masterclass-intro appear">
    <p>Apprenez auprès de maîtres reconnus et plongez au cœur des traditions créoles. Nos masterclass allient technique, histoire et pratique intensive.</p>
  </div>
  <div class="masterclass-grid">
    <?php if ($masterclass_query->have_posts()) : $index = 0; ?>
      <?php while ($masterclass_query->have_posts()) : $masterclass_query->the_post();
        $icon        = get_post_meta(get_the_ID(), '_madac_mc_icon', true);
        $category    = get_post_meta(get_the_ID(), '_madac_mc_category', true);
        $subtitle    = get_post_meta(get_the_ID(), '_madac_mc_subtitle', true);
        $duree       = get_post_meta(get_the_ID(), '_madac_mc_duree', true);
        $niveau      = get_post_meta(get_the_ID(), '_madac_mc_niveau', true);
        $formateur   = get_post_meta(get_the_ID(), '_madac_mc_formateur', true);
        $groupe      = get_post_meta(get_the_ID(), '_madac_mc_groupe', true);
        $programme   = get_post_meta(get_the_ID(), '_madac_mc_programme', true);
        $prix        = get_post_meta(get_the_ID(), '_madac_mc_prix', true);
        $prix_detail = get_post_meta(get_the_ID(), '_madac_mc_prix_detail', true);
        $badge       = get_post_meta(get_the_ID(), '_madac_mc_badge', true);
        $delay       = $index * 0.1;
        $prog_items  = $programme ? array_filter(array_map('trim', explode("\n", $programme))) : [];
      ?>
      <div class="master-card appear"<?php if ($index > 0) : ?> style="transition-delay:<?php echo esc_attr($delay); ?>s"<?php endif; ?>>
        <?php if ($badge) : ?><div class="master-badge"><?php echo esc_html($badge); ?></div><?php endif; ?>
        <div class="master-header">
          <div class="master-icon"><?php echo esc_html($icon); ?></div>
          <div class="master-category"><?php echo esc_html($category); ?></div>
          <h3 class="master-title"><?php the_title(); ?></h3>
          <p class="master-subtitle"><?php echo esc_html($subtitle); ?></p>
        </div>
        <div class="master-body">
          <p class="master-desc"><?php echo esc_html(get_the_content()); ?></p>
          <div class="master-details">
            <div class="master-detail"><span class="detail-label">Durée</span><span class="detail-value"><?php echo esc_html($duree); ?></span></div>
            <div class="master-detail"><span class="detail-label">Niveau</span><span class="detail-value"><?php echo esc_html($niveau); ?></span></div>
            <div class="master-detail"><span class="detail-label">Formateur</span><span class="detail-value"><?php echo esc_html($formateur); ?></span></div>
            <div class="master-detail"><span class="detail-label">Groupe</span><span class="detail-value"><?php echo esc_html($groupe); ?></span></div>
          </div>
          <?php if ($prog_items) : ?>
          <div class="master-program">
            <h4>Au programme :</h4>
            <ul>
              <?php foreach ($prog_items as $item) : ?>
              <li><?php echo esc_html($item); ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
          <?php endif; ?>
        </div>
        <div class="master-footer">
          <div class="master-price">
            <span class="master-price-label">Tarif</span>
            <span class="master-price-value"><?php echo esc_html($prix); ?>&euro;</span>
            <?php if ($prix_detail) : ?><span class="master-price-detail"><?php echo esc_html($prix_detail); ?></span><?php endif; ?>
          </div>
          <button class="master-cta" data-title="<?php echo esc_attr(get_the_title()); ?>" data-prix="<?php echo esc_attr($prix); ?>" data-duree="<?php echo esc_attr($duree); ?>">Réserver</button>
        </div>
      </div>
      <?php $index++; endwhile; wp_reset_postdata(); ?>
    <?php endif; ?>
  </div>
</section>
