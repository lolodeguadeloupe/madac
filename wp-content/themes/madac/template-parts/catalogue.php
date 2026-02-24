<?php
$prestation_query = new WP_Query([
    'post_type'      => 'prestation',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
]);
?>
<section class="section catalogue" id="catalogue">
  <div class="appear">
    <div class="section-tag">Prestations B2B</div>
    <h2 class="section-h">Catalogue <em>Entreprises</em></h2>
  </div>
  <div class="catalogue-intro appear">
    <p>Des événements sur-mesure pour fédérer vos équipes et créer des moments inoubliables.</p>
  </div>
  <div class="catalogue-grid">
    <?php if ($prestation_query->have_posts()) : ?>
      <?php while ($prestation_query->have_posts()) : $prestation_query->the_post();
        $icon       = get_post_meta(get_the_ID(), '_madac_pr_icon', true);
        $tagline    = get_post_meta(get_the_ID(), '_madac_pr_tagline', true);
        $features   = get_post_meta(get_the_ID(), '_madac_pr_features', true);
        $prix       = get_post_meta(get_the_ID(), '_madac_pr_prix', true);
        $prix_label = get_post_meta(get_the_ID(), '_madac_pr_prix_label', true);
        $prix_type  = get_post_meta(get_the_ID(), '_madac_pr_prix_type', true);
        $badge      = get_post_meta(get_the_ID(), '_madac_pr_badge', true);
        $featured   = get_post_meta(get_the_ID(), '_madac_pr_featured', true);
        $feat_items = $features ? array_filter(array_map('trim', explode("\n", $features))) : [];
        $card_class = 'prestation-card appear' . ($featured === '1' ? ' featured' : '');
      ?>
      <div class="<?php echo esc_attr($card_class); ?>">
        <?php if ($badge) : ?><div class="prestation-badge"><?php echo esc_html($badge); ?></div><?php endif; ?>
        <div class="prestation-header">
          <div class="prestation-icon"><?php echo esc_html($icon); ?></div>
          <h3 class="prestation-name"><?php the_title(); ?></h3>
          <p class="prestation-tagline"><?php echo esc_html($tagline); ?></p>
        </div>
        <div class="prestation-body">
          <p class="prestation-desc"><?php echo esc_html(get_the_content()); ?></p>
          <?php if ($feat_items) : ?>
          <ul class="prestation-features">
            <?php foreach ($feat_items as $item) : ?>
            <li><?php echo esc_html($item); ?></li>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>
          <div class="prestation-price-block">
            <div class="prestation-price">
              <span class="price-label"><?php echo esc_html($prix_label); ?></span>
              <?php if ($prix_type === 'devis') : ?>
              <span class="price-value devis">Devis</span>
              <?php else : ?>
              <span class="price-value"><?php echo esc_html($prix); ?>&euro;</span>
              <?php endif; ?>
            </div>
            <button class="prestation-cta">Devis</button>
          </div>
        </div>
      </div>
      <?php endwhile; wp_reset_postdata(); ?>
    <?php endif; ?>
  </div>
</section>
