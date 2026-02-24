<?php
if (!defined('ABSPATH')) exit;

function madac_register_cpt_prestation() {
    register_post_type('prestation', [
        'labels' => [
            'name'               => 'Prestations',
            'singular_name'      => 'Prestation',
            'add_new'            => 'Ajouter',
            'add_new_item'       => 'Ajouter une Prestation',
            'edit_item'          => 'Modifier la Prestation',
            'new_item'           => 'Nouvelle Prestation',
            'view_item'          => 'Voir la Prestation',
            'search_items'       => 'Rechercher',
            'not_found'          => 'Aucune prestation trouvée',
            'not_found_in_trash' => 'Aucune prestation dans la corbeille',
        ],
        'public'       => true,
        'has_archive'  => false,
        'menu_icon'    => 'dashicons-building',
        'supports'     => ['title', 'editor', 'page-attributes'],
        'show_in_rest' => true,
    ]);
}
add_action('init', 'madac_register_cpt_prestation');

function madac_prestation_metabox() {
    add_meta_box(
        'madac_prestation_details',
        'Détails Prestation',
        'madac_prestation_metabox_html',
        'prestation',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'madac_prestation_metabox');

function madac_prestation_metabox_html($post) {
    wp_nonce_field('madac_pr_save', '_madac_pr_nonce');

    $fields = [
        '_madac_pr_icon'       => ['label' => 'Icône (emoji)', 'type' => 'text'],
        '_madac_pr_tagline'    => ['label' => 'Tagline', 'type' => 'text'],
        '_madac_pr_features'   => ['label' => 'Features (un item par ligne)', 'type' => 'textarea'],
        '_madac_pr_prix'       => ['label' => 'Prix', 'type' => 'text'],
        '_madac_pr_prix_label' => ['label' => 'Label prix (ex: À partir de)', 'type' => 'text'],
        '_madac_pr_prix_type'  => ['label' => 'Type prix', 'type' => 'select', 'options' => ['normal' => 'Normal', 'devis' => 'Devis']],
        '_madac_pr_badge'      => ['label' => 'Badge (vide = pas de badge)', 'type' => 'text'],
        '_madac_pr_featured'   => ['label' => 'Mise en avant', 'type' => 'checkbox'],
    ];

    echo '<table class="form-table">';
    foreach ($fields as $key => $field) {
        $value = get_post_meta($post->ID, $key, true);
        echo '<tr><th><label for="' . esc_attr($key) . '">' . esc_html($field['label']) . '</label></th><td>';
        if ($field['type'] === 'textarea') {
            echo '<textarea id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" rows="4" class="large-text">' . esc_textarea($value) . '</textarea>';
        } elseif ($field['type'] === 'select') {
            echo '<select id="' . esc_attr($key) . '" name="' . esc_attr($key) . '">';
            foreach ($field['options'] as $opt_val => $opt_label) {
                echo '<option value="' . esc_attr($opt_val) . '"' . selected($value, $opt_val, false) . '>' . esc_html($opt_label) . '</option>';
            }
            echo '</select>';
        } elseif ($field['type'] === 'checkbox') {
            echo '<input type="checkbox" id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" value="1"' . checked($value, '1', false) . ' />';
        } else {
            echo '<input type="text" id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" value="' . esc_attr($value) . '" class="regular-text" />';
        }
        echo '</td></tr>';
    }
    echo '</table>';
}

function madac_prestation_save($post_id) {
    if (!isset($_POST['_madac_pr_nonce']) || !wp_verify_nonce($_POST['_madac_pr_nonce'], 'madac_pr_save')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $text_fields = ['_madac_pr_icon', '_madac_pr_tagline', '_madac_pr_prix', '_madac_pr_prix_label', '_madac_pr_prix_type', '_madac_pr_badge'];
    foreach ($text_fields as $key) {
        if (isset($_POST[$key])) {
            update_post_meta($post_id, $key, sanitize_text_field($_POST[$key]));
        }
    }
    if (isset($_POST['_madac_pr_features'])) {
        update_post_meta($post_id, '_madac_pr_features', sanitize_textarea_field($_POST['_madac_pr_features']));
    }
    update_post_meta($post_id, '_madac_pr_featured', isset($_POST['_madac_pr_featured']) ? '1' : '0');
}
add_action('save_post_prestation', 'madac_prestation_save');
