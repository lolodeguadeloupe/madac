<?php
/**
 * Front Page Template - MADAC
 *
 * @package MADAC
 */

get_header();

get_template_part('template-parts/hero');
get_template_part('template-parts/ticker');
get_template_part('template-parts/about');
get_template_part('template-parts/masterclass');
get_template_part('template-parts/modal-reservation');
get_template_part('template-parts/catalogue');
get_template_part('template-parts/videos');
get_template_part('template-parts/contact');

get_footer();
