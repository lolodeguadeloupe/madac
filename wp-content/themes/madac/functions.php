<?php
/**
 * MADAC Theme Functions
 *
 * @package MADAC
 */

if (!defined('ABSPATH')) {
    exit;
}

define('MADAC_VERSION', '1.0.0');

/**
 * Theme setup
 */
function madac_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption']);
    add_theme_support('editor-styles');

    register_nav_menus([
        'primary' => __('Menu Principal', 'madac'),
    ]);
}
add_action('after_setup_theme', 'madac_setup');

/**
 * Enqueue styles and scripts
 */
function madac_scripts() {
    // Google Fonts
    wp_enqueue_style(
        'madac-google-fonts',
        'https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300&family=Jost:wght@300;400;500;600&display=swap',
        [],
        null
    );

    // Theme stylesheet
    wp_enqueue_style('madac-style', get_stylesheet_uri(), ['madac-google-fonts'], MADAC_VERSION);

    // Theme scripts
    wp_enqueue_script('madac-script', get_template_directory_uri() . '/assets/js/main.js', [], MADAC_VERSION, true);

    wp_localize_script('madac-script', 'madac_ajax', [
        'ajaxurl'            => admin_url('admin-ajax.php'),
        'contact_nonce'      => wp_create_nonce('madac_contact_nonce'),
        'reservation_nonce'  => wp_create_nonce('madac_reservation_nonce'),
    ]);
}
add_action('wp_enqueue_scripts', 'madac_scripts');

// Includes
require_once get_template_directory() . '/inc/cpt-masterclass.php';
require_once get_template_directory() . '/inc/cpt-prestation.php';
require_once get_template_directory() . '/inc/cpt-video.php';
require_once get_template_directory() . '/inc/options-page.php';
require_once get_template_directory() . '/inc/ajax-handlers.php';
