<?php
if (!defined('ABSPATH')) exit;

function madac_register_cpt_masterclass() {
    register_post_type('masterclass', [
        'labels' => [
            'name'               => 'Masterclass',
            'singular_name'      => 'Masterclass',
            'add_new'            => 'Ajouter',
            'add_new_item'       => 'Ajouter une Masterclass',
            'edit_item'          => 'Modifier la Masterclass',
            'new_item'           => 'Nouvelle Masterclass',
            'view_item'          => 'Voir la Masterclass',
            'search_items'       => 'Rechercher',
            'not_found'          => 'Aucune masterclass trouvée',
            'not_found_in_trash' => 'Aucune masterclass dans la corbeille',
        ],
        'public'       => true,
        'has_archive'  => false,
        'menu_icon'    => 'dashicons-tickets-alt',
        'supports'     => ['title', 'editor', 'thumbnail', 'page-attributes'],
        'show_in_rest' => true,
    ]);
}
add_action('init', 'madac_register_cpt_masterclass');

function madac_masterclass_metabox() {
    add_meta_box(
        'madac_masterclass_details',
        'Détails Masterclass',
        'madac_masterclass_metabox_html',
        'masterclass',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'madac_masterclass_metabox');

function madac_masterclass_metabox_html($post) {
    wp_nonce_field('madac_mc_save', '_madac_mc_nonce');

    $fields = [
        '_madac_mc_icon'        => ['label' => 'Icône (emoji)', 'type' => 'text'],
        '_madac_mc_category'    => ['label' => 'Catégorie', 'type' => 'text'],
        '_madac_mc_subtitle'    => ['label' => 'Sous-titre', 'type' => 'text'],
        '_madac_mc_duree'       => ['label' => 'Durée', 'type' => 'text'],
        '_madac_mc_niveau'      => ['label' => 'Niveau', 'type' => 'text'],
        '_madac_mc_formateur'   => ['label' => 'Formateur', 'type' => 'text'],
        '_madac_mc_groupe'      => ['label' => 'Groupe', 'type' => 'text'],
        '_madac_mc_programme'   => ['label' => 'Programme (un item par ligne)', 'type' => 'textarea'],
        '_madac_mc_prix'        => ['label' => 'Prix (nombre)', 'type' => 'number'],
        '_madac_mc_prix_detail' => ['label' => 'Détail prix', 'type' => 'text'],
        '_madac_mc_badge'       => ['label' => 'Badge (vide = pas de badge)', 'type' => 'text'],
    ];

    echo '<table class="form-table">';
    foreach ($fields as $key => $field) {
        $value = get_post_meta($post->ID, $key, true);
        echo '<tr><th><label for="' . esc_attr($key) . '">' . esc_html($field['label']) . '</label></th><td>';
        if ($field['type'] === 'textarea') {
            echo '<textarea id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" rows="5" class="large-text">' . esc_textarea($value) . '</textarea>';
        } elseif ($field['type'] === 'number') {
            echo '<input type="number" id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" value="' . esc_attr($value) . '" class="small-text" step="1" min="0" />';
        } else {
            echo '<input type="text" id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" value="' . esc_attr($value) . '" class="regular-text" />';
        }
        echo '</td></tr>';
    }
    echo '</table>';
}

function madac_masterclass_save($post_id) {
    if (!isset($_POST['_madac_mc_nonce']) || !wp_verify_nonce($_POST['_madac_mc_nonce'], 'madac_mc_save')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $text_fields = ['_madac_mc_icon', '_madac_mc_category', '_madac_mc_subtitle', '_madac_mc_duree', '_madac_mc_niveau', '_madac_mc_formateur', '_madac_mc_groupe', '_madac_mc_prix_detail', '_madac_mc_badge'];
    foreach ($text_fields as $key) {
        if (isset($_POST[$key])) {
            update_post_meta($post_id, $key, sanitize_text_field($_POST[$key]));
        }
    }
    if (isset($_POST['_madac_mc_programme'])) {
        update_post_meta($post_id, '_madac_mc_programme', sanitize_textarea_field($_POST['_madac_mc_programme']));
    }
    if (isset($_POST['_madac_mc_prix'])) {
        update_post_meta($post_id, '_madac_mc_prix', absint($_POST['_madac_mc_prix']));
    }
}
add_action('save_post_masterclass', 'madac_masterclass_save');
