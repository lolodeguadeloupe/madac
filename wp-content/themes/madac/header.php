<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="cur"></div>
<div id="cur2"></div>

<nav id="nav">
  <a href="<?php echo esc_url(home_url('/')); ?>" class="nav-logo">
    <div class="nav-logo-text">
      <span class="nav-logo-name"><?php bloginfo('name'); ?></span>
      <span class="nav-logo-tagline"><?php bloginfo('description'); ?></span>
    </div>
  </a>
  <button class="nav-hamburger" id="hamburger" aria-label="Menu">
    <span></span><span></span><span></span>
  </button>
  <?php
  wp_nav_menu([
      'theme_location' => 'primary',
      'container'      => false,
      'menu_class'     => 'nav-links',
      'menu_id'        => 'navLinks',
      'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
      'fallback_cb'    => 'madac_fallback_menu',
  ]);
  ?>
</nav>
