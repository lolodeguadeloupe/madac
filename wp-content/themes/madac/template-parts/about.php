<?php
$tag   = get_option('madac_about_tag', 'Qui sommes-nous');
$title = get_option('madac_about_title', 'La <em>Maison des Arts Créoles</em> de Guadeloupe');
$text  = get_option('madac_about_text', '<p>MADAC est un espace de vie, de partage et de création artistique au cœur de la Guadeloupe. Notre mission : <strong>valoriser les arts et la culture créoles</strong> sous toutes leurs formes.</p><p>Du gwoka au jazz caribéen, des expositions d\'art aux séjours thématiques, nous célébrons <strong>la richesse de notre culture des Antilles</strong>.</p>');
$image = get_option('madac_about_image', 'https://images.unsplash.com/photo-1511379938547-c1f69419868d?w=800&q=85');
?>
<section class="section about">
  <div class="about-inner">
    <div class="about-text appear">
      <div class="section-tag"><?php echo esc_html($tag); ?></div>
      <h2 class="section-h"><?php echo wp_kses_post($title); ?></h2>
      <?php echo wp_kses_post($text); ?>
    </div>
    <div class="about-poster appear">
      <?php if ($image) : ?>
      <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>"/>
      <?php endif; ?>
    </div>
  </div>
</section>
