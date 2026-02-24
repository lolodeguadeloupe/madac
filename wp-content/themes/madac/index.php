<?php
/**
 * The main template file
 *
 * @package MADAC
 */

get_header();
?>

<section class="section" style="min-height:60vh;padding-top:160px;">
  <?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>
      <article class="appear">
        <h2 class="section-h"><?php the_title(); ?></h2>
        <div style="margin-top:24px;font-family:'Cormorant Garamond',serif;font-size:1.1rem;line-height:1.9;color:var(--texte-light);">
          <?php the_content(); ?>
        </div>
      </article>
    <?php endwhile; ?>
  <?php else : ?>
    <p>Aucun contenu trouv&eacute;.</p>
  <?php endif; ?>
</section>

<?php get_footer(); ?>
