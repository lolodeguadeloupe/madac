<?php
if (!defined('ABSPATH')) exit;

function madac_register_cpt_video() {
    register_post_type('madac_video', [
        'labels' => [
            'name'               => 'Vidéos',
            'singular_name'      => 'Vidéo',
            'add_new'            => 'Ajouter',
            'add_new_item'       => 'Ajouter une Vidéo',
            'edit_item'          => 'Modifier la Vidéo',
            'new_item'           => 'Nouvelle Vidéo',
            'view_item'          => 'Voir la Vidéo',
            'search_items'       => 'Rechercher',
            'not_found'          => 'Aucune vidéo trouvée',
            'not_found_in_trash' => 'Aucune vidéo dans la corbeille',
        ],
        'public'       => true,
        'has_archive'  => false,
        'menu_icon'    => 'dashicons-video-alt3',
        'supports'     => ['title', 'page-attributes'],
        'show_in_rest' => true,
    ]);
}
add_action('init', 'madac_register_cpt_video');

function madac_video_metabox() {
    add_meta_box(
        'madac_video_details',
        'Détails Vidéo',
        'madac_video_metabox_html',
        'madac_video',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'madac_video_metabox');

function madac_video_metabox_html($post) {
    wp_nonce_field('madac_vid_save', '_madac_vid_nonce');

    $youtube_url  = get_post_meta($post->ID, '_madac_vid_youtube_url', true);
    $description  = get_post_meta($post->ID, '_madac_vid_description', true);
    $date_display = get_post_meta($post->ID, '_madac_vid_date_display', true);

    echo '<table class="form-table">';
    echo '<tr><th><label for="_madac_vid_youtube_url">URL YouTube</label></th>';
    echo '<td><input type="url" id="_madac_vid_youtube_url" name="_madac_vid_youtube_url" value="' . esc_attr($youtube_url) . '" class="large-text" placeholder="https://www.youtube.com/watch?v=..." /></td></tr>';
    echo '<tr><th><label for="_madac_vid_description">Description</label></th>';
    echo '<td><textarea id="_madac_vid_description" name="_madac_vid_description" rows="3" class="large-text">' . esc_textarea($description) . '</textarea></td></tr>';
    echo '<tr><th><label for="_madac_vid_date_display">Date affichée</label></th>';
    echo '<td><input type="text" id="_madac_vid_date_display" name="_madac_vid_date_display" value="' . esc_attr($date_display) . '" class="regular-text" placeholder="Septembre 2024" /></td></tr>';
    echo '</table>';
}

function madac_video_save($post_id) {
    if (!isset($_POST['_madac_vid_nonce']) || !wp_verify_nonce($_POST['_madac_vid_nonce'], 'madac_vid_save')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['_madac_vid_youtube_url'])) {
        update_post_meta($post_id, '_madac_vid_youtube_url', esc_url_raw($_POST['_madac_vid_youtube_url']));
    }
    if (isset($_POST['_madac_vid_description'])) {
        update_post_meta($post_id, '_madac_vid_description', sanitize_textarea_field($_POST['_madac_vid_description']));
    }
    if (isset($_POST['_madac_vid_date_display'])) {
        update_post_meta($post_id, '_madac_vid_date_display', sanitize_text_field($_POST['_madac_vid_date_display']));
    }
}
add_action('save_post_madac_video', 'madac_video_save');
