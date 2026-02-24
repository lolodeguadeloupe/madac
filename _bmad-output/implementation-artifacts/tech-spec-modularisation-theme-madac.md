---
title: 'Modularisation du theme MADAC - Contenu dynamique WordPress'
slug: 'modularisation-theme-madac'
created: '2026-02-24'
status: 'implementation-complete'
stepsCompleted: [1, 2, 3, 4]
tech_stack: ['WordPress 6.9.1', 'PHP 8.x', 'MariaDB 10.11', 'WP-CLI 2.x', 'Docker', 'Apache 2.4']
files_to_modify:
  - 'wp-content/themes/madac/functions.php'
  - 'wp-content/themes/madac/front-page.php'
  - 'wp-content/themes/madac/assets/js/main.js'
files_to_create:
  - 'wp-content/themes/madac/inc/cpt-masterclass.php'
  - 'wp-content/themes/madac/inc/cpt-prestation.php'
  - 'wp-content/themes/madac/inc/cpt-video.php'
  - 'wp-content/themes/madac/inc/options-page.php'
  - 'wp-content/themes/madac/inc/ajax-handlers.php'
  - 'wp-content/themes/madac/template-parts/hero.php'
  - 'wp-content/themes/madac/template-parts/ticker.php'
  - 'wp-content/themes/madac/template-parts/about.php'
  - 'wp-content/themes/madac/template-parts/masterclass.php'
  - 'wp-content/themes/madac/template-parts/catalogue.php'
  - 'wp-content/themes/madac/template-parts/videos.php'
  - 'wp-content/themes/madac/template-parts/contact.php'
  - 'wp-content/themes/madac/template-parts/modal-reservation.php'
  - 'wp-content/themes/madac/seed.php'
files_unchanged:
  - 'wp-content/themes/madac/header.php'
  - 'wp-content/themes/madac/footer.php'
  - 'wp-content/themes/madac/style.css'
  - 'wp-content/themes/madac/index.php'
  - 'index.html'
code_patterns:
  - 'register_post_type pour CPT'
  - 'add_meta_box / save_post pour metaboxes natives'
  - 'WP_Query pour boucles dynamiques'
  - 'wp_nav_menu pour menu dynamique'
  - 'get_option / update_option pour options globales'
  - 'get_template_part pour template parts modulaires'
  - 'wp_ajax_ / wp_ajax_nopriv_ pour handlers AJAX'
  - 'wp_localize_script pour passer ajaxurl au JS'
  - 'wp_mail pour envoi emails'
  - 'wp_verify_nonce pour securite formulaires'
test_patterns:
  - 'Verification visuelle front vs index.html'
  - 'CRUD admin CPT + rendu front'
  - 'Navigation menu ancres'
  - 'Soumission formulaires AJAX'
---

# Tech-Spec: Modularisation du theme MADAC - Contenu dynamique WordPress

**Created:** 2026-02-24

## Overview

### Problem Statement

Le theme MADAC a tout son contenu code en dur dans `front-page.php` (750+ lignes). Le menu de navigation n'existe pas en base de donnees WordPress. Aucun contenu n'est editable depuis l'interface admin. Toute modification requiert d'editer le code PHP directement.

### Solution

Decouper le template monolithique en sections modulaires via `get_template_part()`, creer des Custom Post Types (masterclass, prestation, video) avec des metaboxes natives pour les contenus structures, rendre le hero/ticker/about/contact editables via les options WordPress, creer le menu en BDD, et injecter tout le contenu initial du `index.html` via un script WP-CLI de seed.

### Scope

**In Scope:**
- Menu dynamique WordPress avec ancres `#section` sur la page d'accueil, cree en BDD via WP-CLI
- CPT `masterclass` avec meta fields natifs (icone, categorie, sous-titre, description, duree, niveau, formateur, groupe, programme, prix, detail prix, badge)
- CPT `prestation` avec meta fields natifs (icone, tagline, description, features, prix, type prix, badge, featured)
- CPT `video` avec meta fields natifs (URL YouTube, description, date affichee)
- Section Hero dynamique (options WordPress via Customizer ou `get_option`)
- Section About dynamique (contenu de la page d'accueil WordPress)
- Section Ticker dynamique (option WordPress, tableau d'items)
- Section Contact (infos editables via options + formulaire fonctionnel avec envoi email `wp_mail`)
- Modal de reservation fonctionnelle (soumission AJAX vers `admin-ajax.php` + envoi email)
- Template parts modulaires dans `template-parts/`
- Script de seed WP-CLI pour injecter tout le contenu du `index.html` en BDD
- `front-page.php` refactorise en assemblage de template parts avec WP_Query dynamiques

**Out of Scope:**
- Migration FSE / theme.json (phase ulterieure)
- Block Patterns Gutenberg
- Design responsive (deja gere dans style.css)
- Deploiement Dokploy
- ACF ou plugins tiers pour les champs
- Internationalisation (i18n)

## Context for Development

### Codebase Patterns

- Theme classique WordPress (pas FSE), WordPress 6.9.1
- `functions.php` deja configure avec `register_nav_menus(['primary'])` et enqueue Google Fonts + style + JS
- `header.php` utilise deja `wp_nav_menu()` avec location `primary`, fallback `madac_fallback_menu` (non defini)
- `front-page.php` contient tout le contenu statique en dur (420 lignes, hero + ticker + about + masterclass x4 + modal + catalogue x3 + videos x3 + contact)
- `footer.php` utilise `bloginfo('name')` dynamiquement, deja fonctionnel
- `assets/js/main.js` gere : curseur custom, scroll nav solid, hamburger toggle, IntersectionObserver `.appear`, modal reservation (openReservation/closeModal/updateTotal/submitReservation)
- CSS complet dans `style.css` (toutes les classes de l'index.html original, responsive inclus)
- Docker Compose avec WordPress + MariaDB + phpMyAdmin
- WP-CLI installe manuellement dans le conteneur (pas persistant au restart)
- Page "Accueil" (ID 4) configuree comme `page_on_front`, `show_on_front = page`
- **Aucun menu** n'existe en BDD, aucun CPT enregistre, aucun plugin actif
- Convention de nommage : prefixe `madac_` pour fonctions PHP

### Files to Reference

| File | Purpose | Action |
| ---- | ------- | ------ |
| `index.html` | Template de reference original (maquette statique) | Inchange |
| `functions.php` | Setup theme, enqueue, nav menus | Modifier : ajouter includes des fichiers `inc/` |
| `front-page.php` | Page d'accueil monolithique (420 lignes statiques) | Remplacer : assemblage de `get_template_part()` |
| `header.php` | Nav avec `wp_nav_menu('primary')` + hamburger | Inchange (le menu se branche automatiquement) |
| `footer.php` | Footer dynamique avec `bloginfo()` | Inchange |
| `assets/js/main.js` | Curseur, scroll, hamburger, modal, animations | Modifier : ajouter AJAX contact + reservation |
| `style.css` | CSS complet theme (variables, sections, responsive) | Inchange |
| `index.php` | Template fallback WordPress | Inchange |
| `inc/cpt-masterclass.php` | A creer : CPT + metaboxes masterclass | Nouveau |
| `inc/cpt-prestation.php` | A creer : CPT + metaboxes prestation | Nouveau |
| `inc/cpt-video.php` | A creer : CPT + metaboxes video | Nouveau |
| `inc/options-page.php` | A creer : page options admin (hero, ticker, contact) | Nouveau |
| `inc/ajax-handlers.php` | A creer : handlers AJAX contact + reservation | Nouveau |
| `template-parts/hero.php` | A creer : section hero dynamique | Nouveau |
| `template-parts/ticker.php` | A creer : bandeau defilant dynamique | Nouveau |
| `template-parts/about.php` | A creer : section a propos | Nouveau |
| `template-parts/masterclass.php` | A creer : boucle WP_Query masterclass | Nouveau |
| `template-parts/catalogue.php` | A creer : boucle WP_Query prestations | Nouveau |
| `template-parts/videos.php` | A creer : boucle WP_Query videos | Nouveau |
| `template-parts/contact.php` | A creer : infos + formulaire contact | Nouveau |
| `template-parts/modal-reservation.php` | A creer : modal reservation AJAX | Nouveau |
| `seed.php` | A creer : script WP-CLI seed contenu initial | Nouveau |

### Technical Decisions

- **Custom fields natifs** (`post_meta` + `add_meta_box`) plutot qu'ACF, pour zero dependance externe
- **Menu avec ancres** (`#masterclass`, `#catalogue`, `#videos`, `#contact`) sur la page d'accueil (one-page). Liens custom dans le menu WP.
- **Options WordPress** (`get_option` / `update_option`) pour les contenus globaux (hero, ticker, contact infos). Page d'options dans le menu admin sous "MADAC Options".
- **WP_Query** pour boucler sur les CPT dans les template parts. Tri par `menu_order` pour controler l'affichage.
- **Template parts** dans `template-parts/` pour chaque section. `front-page.php` devient un simple assembleur.
- **WP-CLI script** (`seed.php`) pour le seed initial : cree le menu, les posts CPT avec meta, et les options. Execute via `docker exec madac-wordpress-1 wp --allow-root eval-file /var/www/html/wp-content/themes/madac/seed.php`.
- **AJAX natif WordPress** (`wp_ajax_` / `wp_ajax_nopriv_`) pour contact + reservation. Nonce via `wp_create_nonce` + `wp_verify_nonce`.
- **`wp_mail()`** pour l'envoi des emails. Destinataire configurable dans les options MADAC.
- **`wp_localize_script()`** pour injecter `ajaxurl` et le nonce dans le JS front.
- **Securite** : `sanitize_text_field()`, `sanitize_email()`, `esc_html()`, `esc_attr()`, `esc_url()` appliques systematiquement.

## Implementation Plan

### Tasks

#### Phase 1 : Infrastructure CPT et metaboxes (fondations)

- [x] **Task 1 : Creer `inc/cpt-masterclass.php`**
  - File: `wp-content/themes/madac/inc/cpt-masterclass.php`
  - Action: `register_post_type('masterclass', ...)` avec supports `title`, `editor`, `thumbnail`, `page-attributes`. Ajouter `add_meta_box` pour les champs : `_madac_mc_icon` (text), `_madac_mc_category` (text), `_madac_mc_subtitle` (text), `_madac_mc_duree` (text), `_madac_mc_niveau` (text), `_madac_mc_formateur` (text), `_madac_mc_groupe` (text), `_madac_mc_programme` (textarea, un item par ligne), `_madac_mc_prix` (number), `_madac_mc_prix_detail` (text), `_madac_mc_badge` (text, vide = pas de badge). Hook `save_post_masterclass` pour sauvegarder les meta avec `sanitize_text_field` / `sanitize_textarea_field`. Le contenu `post_content` sert de description longue (champ `master-desc`).
  - Notes: Le label admin sera "Masterclass" au singulier, "Masterclass" au pluriel. Icone dashicon `dashicons-tickets-alt`. `menu_order` pour le tri.

- [x] **Task 2 : Creer `inc/cpt-prestation.php`**
  - File: `wp-content/themes/madac/inc/cpt-prestation.php`
  - Action: `register_post_type('prestation', ...)` avec supports `title`, `editor`, `page-attributes`. Metaboxes : `_madac_pr_icon` (text/emoji), `_madac_pr_tagline` (text), `_madac_pr_features` (textarea, un item par ligne), `_madac_pr_prix` (text), `_madac_pr_prix_label` (text, ex: "A partir de"), `_madac_pr_prix_type` (select: normal/devis), `_madac_pr_badge` (text), `_madac_pr_featured` (checkbox). Le `post_content` sert de description courte.
  - Notes: Icone dashicon `dashicons-building`. `menu_order` pour le tri.

- [x] **Task 3 : Creer `inc/cpt-video.php`**
  - File: `wp-content/themes/madac/inc/cpt-video.php`
  - Action: `register_post_type('madac_video', ...)` avec supports `title`. Metaboxes : `_madac_vid_youtube_url` (url), `_madac_vid_description` (textarea), `_madac_vid_date_display` (text, ex: "Septembre 2024"). On extrait l'embed ID depuis l'URL YouTube dans le template part.
  - Notes: Slug `madac_video` pour eviter conflit avec le CPT natif WordPress `video`. Icone `dashicons-video-alt3`. `menu_order` pour le tri.

- [x] **Task 4 : Creer `inc/options-page.php`**
  - File: `wp-content/themes/madac/inc/options-page.php`
  - Action: Creer une page d'options admin via `add_menu_page('MADAC Options', ...)` avec icone `dashicons-art`. Sections et champs via `register_setting` / `add_settings_section` / `add_settings_field` :
    - **Section Hero** : `madac_hero_eyebrow` (text, defaut "Guadeloupe · 971"), `madac_hero_title` (text, defaut "MADAC"), `madac_hero_subtitle` (textarea), `madac_hero_cta1_text` (text), `madac_hero_cta1_url` (text), `madac_hero_cta2_text` (text), `madac_hero_cta2_url` (text), `madac_hero_bg_image` (url avec media uploader)
    - **Section Ticker** : `madac_ticker_items` (textarea, un item par ligne)
    - **Section About** : `madac_about_tag` (text), `madac_about_title` (text, HTML autorise pour `<em>`), `madac_about_text` (wp_editor), `madac_about_image` (url avec media uploader)
    - **Section Contact** : `madac_contact_email_masterclass` (email), `madac_contact_email_entreprises` (email), `madac_contact_phone` (text), `madac_contact_location` (text), `madac_contact_intro` (textarea), `madac_contact_recipient` (email, destinataire des formulaires)
  - Notes: Utiliser `wp_editor()` pour le champ about_text. Media uploader via `wp_enqueue_media()` + JS admin inline.

- [x] **Task 5 : Creer `inc/ajax-handlers.php`**
  - File: `wp-content/themes/madac/inc/ajax-handlers.php`
  - Action: Deux handlers AJAX :
    - `madac_handle_contact` : hook `wp_ajax_madac_contact` + `wp_ajax_nopriv_madac_contact`. Verifie nonce `madac_contact_nonce`. Sanitize prenom, nom, email, sujet, message. Envoie via `wp_mail()` au `madac_contact_recipient`. Retourne JSON success/error.
    - `madac_handle_reservation` : hook `wp_ajax_madac_reservation` + `wp_ajax_nopriv_madac_reservation`. Verifie nonce `madac_reservation_nonce`. Sanitize prenom, nom, email, telephone, niveau, quantite, masterclass, message. Calcule total. Envoie via `wp_mail()` au `madac_contact_recipient`. Retourne JSON success/error avec message de confirmation.
  - Notes: Les deux handlers verifient `wp_verify_nonce()` et retournent `wp_send_json_error()` si echec. Rate limiting basique via transient (1 soumission par IP par minute).

- [x] **Task 6 : Modifier `functions.php`**
  - File: `wp-content/themes/madac/functions.php`
  - Action: Ajouter les includes des fichiers `inc/` apres le bloc existant. Ajouter `wp_localize_script()` dans `madac_scripts()` pour injecter `madac_ajax` avec `ajaxurl`, `contact_nonce` et `reservation_nonce` dans `madac-script`. Supprimer le fallback menu `madac_fallback_menu` inexistant du `wp_nav_menu` call (deja dans header.php, mais le fallback n'est pas defini).
  - Notes: Ordre des includes important : CPT d'abord, puis options, puis AJAX.

#### Phase 2 : Template parts (decoupe du front-page.php)

- [x] **Task 7 : Creer `template-parts/hero.php`**
  - File: `wp-content/themes/madac/template-parts/hero.php`
  - Action: Extraire la section hero de `front-page.php`. Remplacer le contenu statique par `get_option('madac_hero_*')`. L'image de fond utilise `madac_hero_bg_image` en inline style. Les CTA utilisent `madac_hero_cta1_text/url` et `madac_hero_cta2_text/url`. Appliquer `esc_html()` sur les textes, `esc_url()` sur les URLs, `esc_attr()` sur les attributs.

- [x] **Task 8 : Creer `template-parts/ticker.php`**
  - File: `wp-content/themes/madac/template-parts/ticker.php`
  - Action: Extraire la section ticker. Lire `get_option('madac_ticker_items')`, exploser par `\n`, boucler pour generer les `<span class="ticker-item">`. Dupliquer le tableau pour l'effet de defilement infini (comme dans l'original).

- [x] **Task 9 : Creer `template-parts/about.php`**
  - File: `wp-content/themes/madac/template-parts/about.php`
  - Action: Extraire la section about. Lire les options `madac_about_*`. Le texte utilise `wp_kses_post()` pour autoriser le HTML basique (strong, em). L'image utilise `madac_about_image`.

- [x] **Task 10 : Creer `template-parts/masterclass.php`**
  - File: `wp-content/themes/madac/template-parts/masterclass.php`
  - Action: Extraire la section masterclass. Remplacer les 4 cartes statiques par une `WP_Query` sur `post_type => 'masterclass'`, `posts_per_page => -1`, `orderby => 'menu_order'`, `order => 'ASC'`. Dans la boucle, lire chaque meta field avec `get_post_meta($post->ID, '_madac_mc_*', true)`. Le programme est stocke en textarea (un item/ligne), exploser par `\n` pour generer les `<li>`. Le badge s'affiche seulement si `_madac_mc_badge` n'est pas vide. Le `transition-delay` s'incremente avec l'index de boucle (`$index * 0.1s`). Le bouton "Reserver" passe titre, prix et duree a `openReservation()` via des `data-*` attributes echappes.

- [x] **Task 11 : Creer `template-parts/modal-reservation.php`**
  - File: `wp-content/themes/madac/template-parts/modal-reservation.php`
  - Action: Extraire la modal de reservation. Le formulaire reste identique visuellement mais le `onsubmit` est remplace par une soumission AJAX. Ajouter un champ hidden `masterclass_name` rempli par JS. Ajouter un champ hidden nonce via `wp_nonce_field('madac_reservation_nonce', '_wpnonce_reservation')`.

- [x] **Task 12 : Creer `template-parts/catalogue.php`**
  - File: `wp-content/themes/madac/template-parts/catalogue.php`
  - Action: Extraire la section catalogue. `WP_Query` sur `post_type => 'prestation'`, tri par `menu_order`. Lire les meta `_madac_pr_*`. La classe `featured` s'ajoute si `_madac_pr_featured` est truthy. Le badge s'affiche si `_madac_pr_badge` n'est pas vide. Les features sont stockees en textarea (un item/ligne), explosees par `\n`. Le prix affiche soit la valeur numerique soit "Devis" selon `_madac_pr_prix_type`.

- [x] **Task 13 : Creer `template-parts/videos.php`**
  - File: `wp-content/themes/madac/template-parts/videos.php`
  - Action: Extraire la section videos. `WP_Query` sur `post_type => 'madac_video'`, tri par `menu_order`. Extraire l'ID YouTube depuis `_madac_vid_youtube_url` avec une regex (`preg_match`). Generer l'iframe embed. Titre = `the_title()`, description = `_madac_vid_description`, date = `_madac_vid_date_display`.

- [x] **Task 14 : Creer `template-parts/contact.php`**
  - File: `wp-content/themes/madac/template-parts/contact.php`
  - Action: Extraire la section contact. Les infos (emails, telephone, adresse) viennent des options `madac_contact_*`. Le formulaire HTML reste identique visuellement mais le `onsubmit` est remplace par soumission AJAX. Ajouter `wp_nonce_field('madac_contact_nonce', '_wpnonce_contact')`. Ajouter un `<div>` pour les messages de feedback.

- [x] **Task 15 : Remplacer `front-page.php`**
  - File: `wp-content/themes/madac/front-page.php`
  - Action: Remplacer tout le contenu statique (420 lignes) par un assemblage propre de template parts :
    ```php
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
    ```

#### Phase 3 : JavaScript AJAX

- [x] **Task 16 : Modifier `assets/js/main.js`**
  - File: `wp-content/themes/madac/assets/js/main.js`
  - Action: Modifier `submitReservation(e)` pour envoyer un `fetch()` POST vers `madac_ajax.ajaxurl` avec `action=madac_reservation`, le nonce `madac_ajax.reservation_nonce`, et les donnees du formulaire. Afficher un message de succes/erreur dans la modal. Modifier le formulaire de contact (section contact) de la meme maniere avec `action=madac_contact` et `madac_ajax.contact_nonce`. Remplacer les `onclick="openReservation(...)"` par une lecture des `data-*` attributes. Ajouter un spinner/loading state pendant les requetes.
  - Notes: `madac_ajax` est injecte par `wp_localize_script()` dans functions.php. Garder tout le code existant (curseur, scroll, hamburger, IntersectionObserver) intact.

#### Phase 4 : Seed du contenu initial

- [x] **Task 17 : Creer `seed.php`**
  - File: `wp-content/themes/madac/seed.php`
  - Action: Script WP-CLI executable via `wp eval-file`. Le script :
    1. **Cree le menu** "Menu Principal" avec 4 items custom links : Masterclass (`#masterclass`), Entreprises (`#catalogue`), Videos (`#videos`), Contact (`#contact`, classe CSS `nav-reserve`). Assigne au location `primary`.
    2. **Cree les options** hero (eyebrow, title, subtitle, CTA texts/urls, bg image URL Unsplash), ticker (6 items), about (tag, title, text, image URL Unsplash), contact (emails, telephone, location, intro, recipient).
    3. **Cree 4 masterclass** : Gwoka (450EUR, 3j, icone emoji, 5 items programme, badge vide), Danse Creole (320EUR, 2j, badge "Nouveaute"), Cuisine Creole (380EUR, 2j), Percussions Caribeennes (580EUR, 4j). Chaque post avec `menu_order` 1-4, tous les meta fields remplis exactement comme dans `index.html`.
    4. **Cree 3 prestations** : Team Building (85EUR, normal), Seminaire Residentiel (devis, featured, badge "Populaire"), Soiree Privee (150EUR). `menu_order` 1-3.
    5. **Cree 3 videos** : Masterclass Gwoka 2024, Concert Gwoka & Jazz, Danse Creole Workshop. URLs YouTube placeholder. `menu_order` 1-3.
    6. Affiche un resume de ce qui a ete cree.
  - Notes: Le script verifie si le contenu existe deja avant de creer (idempotent). Utilise `wp_insert_post()`, `update_post_meta()`, `wp_create_nav_menu()`, `wp_update_nav_menu_item()`, `update_option()`.

### Acceptance Criteria

#### Menu

- [x] AC 1: Given le seed a ete execute, when je charge la page d'accueil, then le menu affiche 4 liens (Masterclass, Entreprises, Videos, Contact) avec les classes CSS correctes
- [x] AC 2: Given je suis sur la page d'accueil, when je clique sur "Masterclass" dans le menu, then la page scrolle vers la section `#masterclass`
- [x] AC 3: Given je suis sur la page d'accueil en mobile, when je clique sur le hamburger puis sur un lien, then le menu overlay se ferme et la page scrolle vers la section

#### Masterclass CPT

- [x] AC 4: Given je suis dans l'admin WordPress, when je vais dans "Masterclass", then je vois les 4 masterclass creees par le seed avec leurs titres corrects
- [x] AC 5: Given je modifie le prix d'une masterclass dans l'admin, when je sauvegarde et recharge la page d'accueil, then le nouveau prix s'affiche dans la carte correspondante
- [x] AC 6: Given je cree une nouvelle masterclass avec tous les meta fields, when je recharge la page d'accueil, then une 5eme carte s'affiche avec toutes les informations correctes
- [x] AC 7: Given une masterclass a un badge "Nouveaute", when la page d'accueil s'affiche, then le badge rouge apparait en haut a droite de la carte

#### Prestation CPT

- [x] AC 8: Given les 3 prestations du seed existent, when je charge la page d'accueil, then les 3 cartes s'affichent dans la section Catalogue avec les bons prix et features
- [x] AC 9: Given une prestation a `featured = true`, when la page s'affiche, then la carte a la bordure doree et le header avec gradient or

#### Video CPT

- [x] AC 10: Given les 3 videos du seed existent, when je charge la page d'accueil, then les 3 cartes video s'affichent avec les iframes YouTube embed correctes

#### Sections dynamiques (Hero, Ticker, About, Contact)

- [x] AC 11: Given je modifie le titre hero dans MADAC Options, when je recharge la page d'accueil, then le nouveau titre s'affiche dans la section hero
- [x] AC 12: Given je modifie les items du ticker dans MADAC Options, when je recharge la page, then le bandeau defilant affiche les nouveaux items
- [x] AC 13: Given je modifie le texte about dans MADAC Options, when je recharge la page, then la section A propos affiche le nouveau texte
- [x] AC 14: Given je modifie l'email de contact dans MADAC Options, when je recharge la page, then le nouvel email s'affiche dans la section contact

#### Formulaires AJAX

- [x] AC 15: Given je remplis le formulaire de contact avec des donnees valides, when je clique Envoyer, then un message de succes s'affiche sans rechargement de page et un email est envoye au destinataire configure
- [x] AC 16: Given je remplis le formulaire de reservation pour une masterclass, when je clique Confirmer, then la modal affiche un message de succes et un email de reservation est envoye
- [x] AC 17: Given je soumets un formulaire sans remplir les champs requis, when le JS valide, then les champs requis sont signales et la soumission est bloquee
- [x] AC 18: Given je soumets un formulaire avec un nonce invalide, when le serveur traite la requete, then une erreur JSON est retournee et aucun email n'est envoye

#### Integrite visuelle

- [x] AC 19: Given tout le contenu est en BDD, when je compare visuellement la page d'accueil avec `index.html`, then le rendu est identique (memes couleurs, typographies, espacements, animations)
- [x] AC 20: Given la page d'accueil est chargee, when les sections apparaissent au scroll, then les animations `.appear` fonctionnent comme avant (fade-in + translateY)

#### Seed idempotent

- [x] AC 21: Given le seed a deja ete execute une fois, when je le re-execute, then aucun doublon n'est cree et le script confirme que le contenu existe deja

## Additional Context

### Dependencies

- WordPress 6.9.1 (image Docker `wordpress:latest`)
- WP-CLI installe dans le conteneur (installation manuelle, pas persistante)
- MariaDB 10.11 (image Docker `mariadb:10.11`)
- Aucun plugin externe requis
- Fonction `wp_mail()` disponible (configuration SMTP eventuelle hors scope)

### Testing Strategy

**Tests manuels :**
1. Executer le seed et verifier dans l'admin que tout le contenu est cree (menu, 4 masterclass, 3 prestations, 3 videos, options)
2. Charger `http://localhost:8080` et comparer visuellement avec `index.html` ouvert en local
3. Cliquer sur chaque lien du menu et verifier le scroll vers la section
4. Modifier un contenu dans l'admin et verifier le rendu front
5. Soumettre les formulaires contact et reservation, verifier les messages de feedback
6. Tester en mobile (responsive < 1024px) : hamburger, layout, curseur masque
7. Re-executer le seed et verifier qu'aucun doublon n'est cree

**Verification CSS :**
- Aucune modification de `style.css` : les classes HTML dans les template parts doivent etre strictement identiques a celles de `index.html`

### Notes

- Le fichier `index.html` original est conserve comme reference de design et ne doit pas etre modifie
- Le contenu initial (4 masterclass, 3 prestations, 3 videos) est injecte via le script de seed avec des donnees identiques a `index.html`
- WP-CLI n'est pas persistant dans le conteneur Docker (reinstallation necessaire apres `docker compose down/up`)
- La configuration SMTP pour `wp_mail()` peut necessiter un plugin comme WP Mail SMTP en production, mais c'est hors scope
- Les images hero et about utilisent des URLs Unsplash en attendant de vraies images. Le media uploader dans les options permet de les remplacer facilement
- Le prefixe `_madac_` (avec underscore initial) sur les meta keys les masque de l'interface "Custom Fields" native de WordPress, evitant la confusion
- L'ordre d'affichage des cartes est controle par `menu_order` dans chaque CPT, modifiable via drag & drop si un plugin de tri est installe (hors scope)
