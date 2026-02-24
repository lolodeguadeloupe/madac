<?php
$eyebrow  = get_option('madac_hero_eyebrow', 'Guadeloupe &middot; 971');
$title    = get_option('madac_hero_title', 'MADAC');
$subtitle = get_option('madac_hero_subtitle', "Maison des Arts Créoles de Guadeloupe\nVivez l'expérience MADAC");
$cta1_text = get_option('madac_hero_cta1_text', 'Nos Masterclass');
$cta1_url  = get_option('madac_hero_cta1_url', '#masterclass');
$cta2_text = get_option('madac_hero_cta2_text', 'Entreprises');
$cta2_url  = get_option('madac_hero_cta2_url', '#catalogue');
$bg_image  = get_option('madac_hero_bg_image', 'https://images.unsplash.com/photo-1501386761578-eac5c94b800a?w=1600&q=85');
?>
<section class="hero">
  <div class="hero-bg"<?php if ($bg_image) : ?> style="background-image:url('<?php echo esc_url($bg_image); ?>')"<?php endif; ?>></div>
  <div class="hero-overlay"></div>
  <div class="hero-content">
    <div class="hero-eyebrow"><?php echo esc_html($eyebrow); ?></div>
    <h1 class="hero-title"><?php echo esc_html($title); ?></h1>
    <p class="hero-subtitle"><?php echo nl2br(esc_html($subtitle)); ?></p>
    <div class="hero-actions">
      <?php if ($cta1_text) : ?><a href="<?php echo esc_attr($cta1_url); ?>" class="btn btn-primary"><?php echo esc_html($cta1_text); ?></a><?php endif; ?>
      <?php if ($cta2_text) : ?><a href="<?php echo esc_attr($cta2_url); ?>" class="btn btn-outline"><?php echo esc_html($cta2_text); ?></a><?php endif; ?>
    </div>
  </div>
</section>
