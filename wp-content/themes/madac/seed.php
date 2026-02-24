<?php
/**
 * MADAC Seed Script
 * Execute via: docker exec madac-wordpress-1 wp --allow-root eval-file /var/www/html/wp-content/themes/madac/seed.php
 */

if (!defined('ABSPATH')) {
    WP_CLI::error('This script must be run via WP-CLI eval-file.');
}

WP_CLI::log('=== MADAC Seed Script ===');

// ──────────────────────────────────────────────
// 1. MENU
// ──────────────────────────────────────────────
$menu_name = 'Menu Principal';
$menu_exists = wp_get_nav_menu_object($menu_name);

if (!$menu_exists) {
    $menu_id = wp_create_nav_menu($menu_name);

    wp_update_nav_menu_item($menu_id, 0, [
        'menu-item-title'   => 'Masterclass',
        'menu-item-url'     => '#masterclass',
        'menu-item-status'  => 'publish',
        'menu-item-type'    => 'custom',
        'menu-item-position' => 1,
    ]);
    wp_update_nav_menu_item($menu_id, 0, [
        'menu-item-title'   => 'Entreprises',
        'menu-item-url'     => '#catalogue',
        'menu-item-status'  => 'publish',
        'menu-item-type'    => 'custom',
        'menu-item-position' => 2,
    ]);
    wp_update_nav_menu_item($menu_id, 0, [
        'menu-item-title'   => 'Vidéos',
        'menu-item-url'     => '#videos',
        'menu-item-status'  => 'publish',
        'menu-item-type'    => 'custom',
        'menu-item-position' => 3,
    ]);
    $contact_item_id = wp_update_nav_menu_item($menu_id, 0, [
        'menu-item-title'   => 'Contact',
        'menu-item-url'     => '#contact',
        'menu-item-status'  => 'publish',
        'menu-item-type'    => 'custom',
        'menu-item-classes' => 'nav-reserve',
        'menu-item-position' => 4,
    ]);

    // Assign to primary location
    $locations = get_theme_mod('nav_menu_locations', []);
    $locations['primary'] = $menu_id;
    set_theme_mod('nav_menu_locations', $locations);

    WP_CLI::success("Menu '$menu_name' créé avec 4 items et assigné au location 'primary'.");
} else {
    WP_CLI::log("Menu '$menu_name' existe déjà. Ignoré.");
}

// ──────────────────────────────────────────────
// 2. OPTIONS
// ──────────────────────────────────────────────
$options = [
    'madac_hero_eyebrow'             => 'Guadeloupe · 971',
    'madac_hero_title'               => 'MADAC',
    'madac_hero_subtitle'            => "Maison des Arts Créoles de Guadeloupe\nVivez l'expérience MADAC",
    'madac_hero_cta1_text'           => 'Nos Masterclass',
    'madac_hero_cta1_url'            => '#masterclass',
    'madac_hero_cta2_text'           => 'Entreprises',
    'madac_hero_cta2_url'            => '#catalogue',
    'madac_hero_bg_image'            => 'https://images.unsplash.com/photo-1501386761578-eac5c94b800a?w=1600&q=85',
    'madac_ticker_items'             => "Masterclass Gwoka\nFormation Danse Créole\nSéminaires Entreprises\nTeam Building\nConcerts Privés\nMasterclass Percussions",
    'madac_about_tag'                => 'Qui sommes-nous',
    'madac_about_title'              => 'La <em>Maison des Arts Créoles</em> de Guadeloupe',
    'madac_about_text'               => "<p>MADAC est un espace de vie, de partage et de création artistique au cœur de la Guadeloupe. Notre mission : <strong>valoriser les arts et la culture créoles</strong> sous toutes leurs formes.</p>\n<p>Du gwoka au jazz caribéen, des expositions d'art aux séjours thématiques, nous célébrons <strong>la richesse de notre culture des Antilles</strong>.</p>",
    'madac_about_image'              => 'https://images.unsplash.com/photo-1511379938547-c1f69419868d?w=800&q=85',
    'madac_contact_email_masterclass' => 'masterclass@madac.fr',
    'madac_contact_email_entreprises' => 'entreprises@madac.fr',
    'madac_contact_phone'            => '+590 690 XX XX XX',
    'madac_contact_location'         => 'Guadeloupe, 971',
    'madac_contact_intro'            => "Masterclass, événements entreprises, réservations : nous sommes à votre écoute.",
    'madac_contact_recipient'        => 'admin@madac.fr',
];

$options_created = 0;
foreach ($options as $key => $value) {
    if (get_option($key) === false) {
        update_option($key, $value);
        $options_created++;
    }
}
WP_CLI::success("$options_created options créées (" . (count($options) - $options_created) . " existaient déjà).");

// ──────────────────────────────────────────────
// 3. MASTERCLASS (4 posts)
// ──────────────────────────────────────────────
$masterclasses = [
    [
        'title'   => 'Masterclass Gwoka',
        'content' => "Formation intensive au gwoka, patrimoine immatériel de l'UNESCO. Apprenez les 7 rythmes fondamentaux, la technique du ka et l'histoire de cette tradition musicale guadeloupéenne.",
        'order'   => 1,
        'meta'    => [
            '_madac_mc_icon'        => '🥁',
            '_madac_mc_category'    => 'Percussion Traditionnelle',
            '_madac_mc_subtitle'    => "Maîtrisez l'art ancestral du tambour ka",
            '_madac_mc_duree'       => '3 jours (18h)',
            '_madac_mc_niveau'      => 'Débutant à avancé',
            '_madac_mc_formateur'   => 'Maître Jean-Claude',
            '_madac_mc_groupe'      => '8-12 participants',
            '_madac_mc_programme'   => "Histoire et origines du gwoka\nLes 7 rythmes traditionnels (toumblak, léwòz, kaladja...)\nTechnique de frappe et posture\nPratique en groupe et improvisation\nSpectacle de clôture participatif",
            '_madac_mc_prix'        => 450,
            '_madac_mc_prix_detail' => 'Matériel inclus',
            '_madac_mc_badge'       => '',
        ],
    ],
    [
        'title'   => 'Danse Créole & Quadrille',
        'content' => "Immersion dans les danses traditionnelles créoles : quadrille, biguine, mazurka antillaise. Découvrez l'histoire, les pas et l'élégance de ces danses patrimoniales.",
        'order'   => 2,
        'meta'    => [
            '_madac_mc_icon'        => '💃',
            '_madac_mc_category'    => 'Danse Traditionnelle',
            '_madac_mc_subtitle'    => "L'expression corporelle des Antilles",
            '_madac_mc_duree'       => '2 jours (12h)',
            '_madac_mc_niveau'      => 'Tous niveaux',
            '_madac_mc_formateur'   => 'Solange Martin',
            '_madac_mc_groupe'      => '10-16 participants',
            '_madac_mc_programme'   => "Histoire des danses créoles\nQuadrille des lanciers et figures\nBiguine et mazurka antillaise\nCostumes traditionnels et codes\nBal créole en fin de formation",
            '_madac_mc_prix'        => 320,
            '_madac_mc_prix_detail' => 'Tenue offerte',
            '_madac_mc_badge'       => 'Nouveauté',
        ],
    ],
    [
        'title'   => 'Cuisine Créole Authentique',
        'content' => "Apprenez les techniques et recettes authentiques de la gastronomie créole avec un chef étoilé. Du colombo au boudin créole, maîtrisez les saveurs des îles.",
        'order'   => 3,
        'meta'    => [
            '_madac_mc_icon'        => '👨‍🍳',
            '_madac_mc_category'    => 'Gastronomie',
            '_madac_mc_subtitle'    => 'Secrets des grands chefs antillais',
            '_madac_mc_duree'       => '2 jours (14h)',
            '_madac_mc_niveau'      => 'Tous niveaux',
            '_madac_mc_formateur'   => 'Chef Marcus Ledoux',
            '_madac_mc_groupe'      => '8-10 participants',
            '_madac_mc_programme'   => "Épices et ingrédients créoles\nColombo, blaff et court-bouillon\nAccras, boudins et ti-punch\nPâtisserie antillaise (blanc-manger, tourments d'amour)\nDégustation et dîner final",
            '_madac_mc_prix'        => 380,
            '_madac_mc_prix_detail' => 'Repas inclus',
            '_madac_mc_badge'       => '',
        ],
    ],
    [
        'title'   => 'Percussions Caribéennes',
        'content' => "Explorez la diversité des percussions caribéennes : ka guadeloupéen, steel pan trinidadien, congas cubaines. Stage intensif multi-instruments.",
        'order'   => 4,
        'meta'    => [
            '_madac_mc_icon'        => '🎶',
            '_madac_mc_category'    => 'Musique',
            '_madac_mc_subtitle'    => 'Du tambour ka au steel pan',
            '_madac_mc_duree'       => '4 jours (24h)',
            '_madac_mc_niveau'      => 'Intermédiaire',
            '_madac_mc_formateur'   => 'Collectif Tambours',
            '_madac_mc_groupe'      => '6-10 participants',
            '_madac_mc_programme'   => "Tambour ka et rythmes gwoka\nSteel pan et calypso\nCongas et rythmes afro-cubains\nPolyrythmie et improvisation\nConcert de clôture public",
            '_madac_mc_prix'        => 580,
            '_madac_mc_prix_detail' => 'Instruments fournis',
            '_madac_mc_badge'       => '',
        ],
    ],
];

$mc_created = 0;
foreach ($masterclasses as $mc) {
    $exists = get_posts([
        'post_type'   => 'masterclass',
        'title'       => $mc['title'],
        'post_status' => 'publish',
        'numberposts' => 1,
    ]);
    if (empty($exists)) {
        $post_id = wp_insert_post([
            'post_title'   => $mc['title'],
            'post_content' => $mc['content'],
            'post_type'    => 'masterclass',
            'post_status'  => 'publish',
            'menu_order'   => $mc['order'],
        ]);
        foreach ($mc['meta'] as $key => $value) {
            update_post_meta($post_id, $key, $value);
        }
        $mc_created++;
    }
}
WP_CLI::success("$mc_created masterclass créées (" . (count($masterclasses) - $mc_created) . " existaient déjà).");

// ──────────────────────────────────────────────
// 4. PRESTATIONS (3 posts)
// ──────────────────────────────────────────────
$prestations = [
    [
        'title'   => 'Team Building Créole',
        'content' => "Ateliers culturels créoles : percussion, danse, cuisine locale.",
        'order'   => 1,
        'meta'    => [
            '_madac_pr_icon'       => '🥁',
            '_madac_pr_tagline'    => "Cohésion d'équipe rythmée",
            '_madac_pr_features'   => "Atelier percussion et tambours\nInitiation danse traditionnelle\nDéjeuner créole gastronomique",
            '_madac_pr_prix'       => '85€',
            '_madac_pr_prix_label' => 'À partir de',
            '_madac_pr_prix_type'  => 'normal',
            '_madac_pr_badge'      => '',
            '_madac_pr_featured'   => '0',
        ],
    ],
    [
        'title'   => 'Séminaire Résidentiel',
        'content' => "Organisation complète de séminaire : hébergement, salle équipée, activités.",
        'order'   => 2,
        'meta'    => [
            '_madac_pr_icon'       => '🏝️',
            '_madac_pr_tagline'    => "Cadre d'exception",
            '_madac_pr_features'   => "Hébergement 4★\nSalle de réunion équipée\nRestauration gastronomique",
            '_madac_pr_prix'       => '',
            '_madac_pr_prix_label' => 'Sur mesure',
            '_madac_pr_prix_type'  => 'devis',
            '_madac_pr_badge'      => '⭐ Populaire',
            '_madac_pr_featured'   => '1',
        ],
    ],
    [
        'title'   => 'Soirée Privée',
        'content' => "Privatisation, concert live, dîner gastronomique.",
        'order'   => 3,
        'meta'    => [
            '_madac_pr_icon'       => '🎵',
            '_madac_pr_tagline'    => "Événement d'entreprise",
            '_madac_pr_features'   => "Privatisation complète\nConcert live artistes\nDîner-buffet créole",
            '_madac_pr_prix'       => '150€',
            '_madac_pr_prix_label' => 'À partir de',
            '_madac_pr_prix_type'  => 'normal',
            '_madac_pr_badge'      => '',
            '_madac_pr_featured'   => '0',
        ],
    ],
];

$pr_created = 0;
foreach ($prestations as $pr) {
    $exists = get_posts([
        'post_type'   => 'prestation',
        'title'       => $pr['title'],
        'post_status' => 'publish',
        'numberposts' => 1,
    ]);
    if (empty($exists)) {
        $post_id = wp_insert_post([
            'post_title'   => $pr['title'],
            'post_content' => $pr['content'],
            'post_type'    => 'prestation',
            'post_status'  => 'publish',
            'menu_order'   => $pr['order'],
        ]);
        foreach ($pr['meta'] as $key => $value) {
            update_post_meta($post_id, $key, $value);
        }
        $pr_created++;
    }
}
WP_CLI::success("$pr_created prestations créées (" . (count($prestations) - $pr_created) . " existaient déjà).");

// ──────────────────────────────────────────────
// 5. VIDEOS (3 posts)
// ──────────────────────────────────────────────
$videos = [
    [
        'title' => 'Masterclass Gwoka 2024',
        'order' => 1,
        'meta'  => [
            '_madac_vid_youtube_url'  => 'https://www.youtube.com/watch?v=VOTRE_VIDEO_ID_1',
            '_madac_vid_description'  => 'Retour en images sur notre formation intensive de 3 jours.',
            '_madac_vid_date_display' => 'Septembre 2024',
        ],
    ],
    [
        'title' => 'Concert Gwoka & Jazz',
        'order' => 2,
        'meta'  => [
            '_madac_vid_youtube_url'  => 'https://www.youtube.com/watch?v=VOTRE_VIDEO_ID_2',
            '_madac_vid_description'  => 'Soirée exceptionnelle au Parc Culturel.',
            '_madac_vid_date_display' => 'Mars 2024',
        ],
    ],
    [
        'title' => 'Danse Créole Workshop',
        'order' => 3,
        'meta'  => [
            '_madac_vid_youtube_url'  => 'https://www.youtube.com/watch?v=VOTRE_VIDEO_ID_3',
            '_madac_vid_description'  => 'Atelier de danse traditionnelle et quadrille.',
            '_madac_vid_date_display' => 'Août 2024',
        ],
    ],
];

$vid_created = 0;
foreach ($videos as $vid) {
    $exists = get_posts([
        'post_type'   => 'madac_video',
        'title'       => $vid['title'],
        'post_status' => 'publish',
        'numberposts' => 1,
    ]);
    if (empty($exists)) {
        $post_id = wp_insert_post([
            'post_title'  => $vid['title'],
            'post_type'   => 'madac_video',
            'post_status' => 'publish',
            'menu_order'  => $vid['order'],
        ]);
        foreach ($vid['meta'] as $key => $value) {
            update_post_meta($post_id, $key, $value);
        }
        $vid_created++;
    }
}
WP_CLI::success("$vid_created vidéos créées (" . (count($videos) - $vid_created) . " existaient déjà).");

// ──────────────────────────────────────────────
// RESUME
// ──────────────────────────────────────────────
WP_CLI::log('');
WP_CLI::log('=== Résumé du seed ===');
WP_CLI::log("Menu : " . ($menu_exists ? 'existait déjà' : 'créé'));
WP_CLI::log("Options : $options_created nouvelles / " . count($options) . " total");
WP_CLI::log("Masterclass : $mc_created nouvelles / " . count($masterclasses) . " total");
WP_CLI::log("Prestations : $pr_created nouvelles / " . count($prestations) . " total");
WP_CLI::log("Vidéos : $vid_created nouvelles / " . count($videos) . " total");
WP_CLI::success('Seed terminé !');
