<?php
if (!defined('ABSPATH')) exit;

function madac_options_menu() {
    add_menu_page(
        'MADAC Options',
        'MADAC Options',
        'manage_options',
        'madac-options',
        'madac_options_page_html',
        'dashicons-art',
        30
    );
}
add_action('admin_menu', 'madac_options_menu');

function madac_options_init() {
    // Hero
    register_setting('madac_options', 'madac_hero_eyebrow', ['sanitize_callback' => 'sanitize_text_field']);
    register_setting('madac_options', 'madac_hero_title', ['sanitize_callback' => 'sanitize_text_field']);
    register_setting('madac_options', 'madac_hero_subtitle', ['sanitize_callback' => 'sanitize_textarea_field']);
    register_setting('madac_options', 'madac_hero_cta1_text', ['sanitize_callback' => 'sanitize_text_field']);
    register_setting('madac_options', 'madac_hero_cta1_url', ['sanitize_callback' => 'sanitize_text_field']);
    register_setting('madac_options', 'madac_hero_cta2_text', ['sanitize_callback' => 'sanitize_text_field']);
    register_setting('madac_options', 'madac_hero_cta2_url', ['sanitize_callback' => 'sanitize_text_field']);
    register_setting('madac_options', 'madac_hero_bg_image', ['sanitize_callback' => 'esc_url_raw']);

    // Ticker
    register_setting('madac_options', 'madac_ticker_items', ['sanitize_callback' => 'sanitize_textarea_field']);

    // About
    register_setting('madac_options', 'madac_about_tag', ['sanitize_callback' => 'sanitize_text_field']);
    register_setting('madac_options', 'madac_about_title', ['sanitize_callback' => 'wp_kses_post']);
    register_setting('madac_options', 'madac_about_text', ['sanitize_callback' => 'wp_kses_post']);
    register_setting('madac_options', 'madac_about_image', ['sanitize_callback' => 'esc_url_raw']);

    // Contact
    register_setting('madac_options', 'madac_contact_email_masterclass', ['sanitize_callback' => 'sanitize_email']);
    register_setting('madac_options', 'madac_contact_email_entreprises', ['sanitize_callback' => 'sanitize_email']);
    register_setting('madac_options', 'madac_contact_phone', ['sanitize_callback' => 'sanitize_text_field']);
    register_setting('madac_options', 'madac_contact_location', ['sanitize_callback' => 'sanitize_text_field']);
    register_setting('madac_options', 'madac_contact_intro', ['sanitize_callback' => 'sanitize_textarea_field']);
    register_setting('madac_options', 'madac_contact_recipient', ['sanitize_callback' => 'sanitize_email']);

    // Sections
    add_settings_section('madac_hero_section', 'Section Hero', '__return_false', 'madac-options');
    add_settings_section('madac_ticker_section', 'Section Ticker', '__return_false', 'madac-options');
    add_settings_section('madac_about_section', 'Section À propos', '__return_false', 'madac-options');
    add_settings_section('madac_contact_section', 'Section Contact', '__return_false', 'madac-options');
}
add_action('admin_init', 'madac_options_init');

function madac_options_enqueue($hook) {
    if ($hook !== 'toplevel_page_madac-options') return;
    wp_enqueue_media();
}
add_action('admin_enqueue_scripts', 'madac_options_enqueue');

function madac_options_page_html() {
    if (!current_user_can('manage_options')) return;
    ?>
    <div class="wrap">
        <h1>MADAC Options</h1>
        <?php settings_errors(); ?>
        <form method="post" action="options.php">
            <?php settings_fields('madac_options'); ?>

            <h2 class="title">Section Hero</h2>
            <table class="form-table">
                <tr><th>Eyebrow</th><td><input type="text" name="madac_hero_eyebrow" value="<?php echo esc_attr(get_option('madac_hero_eyebrow')); ?>" class="regular-text" /></td></tr>
                <tr><th>Titre</th><td><input type="text" name="madac_hero_title" value="<?php echo esc_attr(get_option('madac_hero_title')); ?>" class="regular-text" /></td></tr>
                <tr><th>Sous-titre</th><td><textarea name="madac_hero_subtitle" rows="3" class="large-text"><?php echo esc_textarea(get_option('madac_hero_subtitle')); ?></textarea></td></tr>
                <tr><th>CTA 1 - Texte</th><td><input type="text" name="madac_hero_cta1_text" value="<?php echo esc_attr(get_option('madac_hero_cta1_text')); ?>" class="regular-text" /></td></tr>
                <tr><th>CTA 1 - URL</th><td><input type="text" name="madac_hero_cta1_url" value="<?php echo esc_attr(get_option('madac_hero_cta1_url')); ?>" class="regular-text" /></td></tr>
                <tr><th>CTA 2 - Texte</th><td><input type="text" name="madac_hero_cta2_text" value="<?php echo esc_attr(get_option('madac_hero_cta2_text')); ?>" class="regular-text" /></td></tr>
                <tr><th>CTA 2 - URL</th><td><input type="text" name="madac_hero_cta2_url" value="<?php echo esc_attr(get_option('madac_hero_cta2_url')); ?>" class="regular-text" /></td></tr>
                <tr><th>Image de fond</th><td>
                    <input type="text" id="madac_hero_bg_image" name="madac_hero_bg_image" value="<?php echo esc_attr(get_option('madac_hero_bg_image')); ?>" class="large-text" />
                    <button type="button" class="button madac-upload-btn" data-target="madac_hero_bg_image">Choisir une image</button>
                </td></tr>
            </table>

            <h2 class="title">Section Ticker</h2>
            <table class="form-table">
                <tr><th>Items (un par ligne)</th><td><textarea name="madac_ticker_items" rows="6" class="large-text"><?php echo esc_textarea(get_option('madac_ticker_items')); ?></textarea></td></tr>
            </table>

            <h2 class="title">Section À propos</h2>
            <table class="form-table">
                <tr><th>Tag</th><td><input type="text" name="madac_about_tag" value="<?php echo esc_attr(get_option('madac_about_tag')); ?>" class="regular-text" /></td></tr>
                <tr><th>Titre (HTML autorisé pour &lt;em&gt;)</th><td><input type="text" name="madac_about_title" value="<?php echo esc_attr(get_option('madac_about_title')); ?>" class="large-text" /></td></tr>
                <tr><th>Texte</th><td>
                    <?php wp_editor(get_option('madac_about_text', ''), 'madac_about_text', ['textarea_rows' => 8, 'media_buttons' => false]); ?>
                </td></tr>
                <tr><th>Image</th><td>
                    <input type="text" id="madac_about_image" name="madac_about_image" value="<?php echo esc_attr(get_option('madac_about_image')); ?>" class="large-text" />
                    <button type="button" class="button madac-upload-btn" data-target="madac_about_image">Choisir une image</button>
                </td></tr>
            </table>

            <h2 class="title">Section Contact</h2>
            <table class="form-table">
                <tr><th>Email Masterclass</th><td><input type="email" name="madac_contact_email_masterclass" value="<?php echo esc_attr(get_option('madac_contact_email_masterclass')); ?>" class="regular-text" /></td></tr>
                <tr><th>Email Entreprises</th><td><input type="email" name="madac_contact_email_entreprises" value="<?php echo esc_attr(get_option('madac_contact_email_entreprises')); ?>" class="regular-text" /></td></tr>
                <tr><th>Téléphone</th><td><input type="text" name="madac_contact_phone" value="<?php echo esc_attr(get_option('madac_contact_phone')); ?>" class="regular-text" /></td></tr>
                <tr><th>Localisation</th><td><input type="text" name="madac_contact_location" value="<?php echo esc_attr(get_option('madac_contact_location')); ?>" class="regular-text" /></td></tr>
                <tr><th>Texte d'introduction</th><td><textarea name="madac_contact_intro" rows="3" class="large-text"><?php echo esc_textarea(get_option('madac_contact_intro')); ?></textarea></td></tr>
                <tr><th>Email destinataire formulaires</th><td><input type="email" name="madac_contact_recipient" value="<?php echo esc_attr(get_option('madac_contact_recipient')); ?>" class="regular-text" /></td></tr>
            </table>

            <?php submit_button(); ?>
        </form>
    </div>
    <script>
    jQuery(document).ready(function($) {
        $('.madac-upload-btn').on('click', function(e) {
            e.preventDefault();
            var targetId = $(this).data('target');
            var frame = wp.media({ title: 'Choisir une image', multiple: false, library: { type: 'image' } });
            frame.on('select', function() {
                var url = frame.state().get('selection').first().toJSON().url;
                $('#' + targetId).val(url);
            });
            frame.open();
        });
    });
    </script>
    <?php
}
