<?php
if (!defined('ABSPATH')) exit;

// Contact form handler
function madac_handle_contact() {
    if (!isset($_POST['_wpnonce_contact']) || !wp_verify_nonce($_POST['_wpnonce_contact'], 'madac_contact_nonce')) {
        wp_send_json_error(['message' => 'Erreur de sécurité. Rechargez la page et réessayez.']);
    }

    // Rate limiting
    $ip = $_SERVER['REMOTE_ADDR'];
    $transient_key = 'madac_contact_' . md5($ip);
    if (get_transient($transient_key)) {
        wp_send_json_error(['message' => 'Veuillez patienter une minute avant de renvoyer un message.']);
    }

    $prenom  = sanitize_text_field($_POST['prenom'] ?? '');
    $nom     = sanitize_text_field($_POST['nom'] ?? '');
    $email   = sanitize_email($_POST['email'] ?? '');
    $sujet   = sanitize_text_field($_POST['sujet'] ?? '');
    $message = sanitize_textarea_field($_POST['message'] ?? '');

    if (empty($prenom) || empty($nom) || empty($email) || empty($message)) {
        wp_send_json_error(['message' => 'Veuillez remplir tous les champs obligatoires.']);
    }
    if (!is_email($email)) {
        wp_send_json_error(['message' => 'Adresse email invalide.']);
    }

    $recipient = get_option('madac_contact_recipient', get_option('admin_email'));
    $subject   = '[MADAC Contact] ' . $sujet . ' - ' . $prenom . ' ' . $nom;
    $body      = "Nouveau message de contact MADAC\n\n";
    $body     .= "Nom : $prenom $nom\n";
    $body     .= "Email : $email\n";
    $body     .= "Sujet : $sujet\n\n";
    $body     .= "Message :\n$message\n";
    $headers   = ['Reply-To: ' . $prenom . ' ' . $nom . ' <' . $email . '>'];

    $sent = wp_mail($recipient, $subject, $body, $headers);

    if ($sent) {
        set_transient($transient_key, true, 60);
        wp_send_json_success(['message' => 'Votre message a été envoyé avec succès !']);
    } else {
        wp_send_json_error(['message' => 'Erreur lors de l\'envoi. Veuillez réessayer.']);
    }
}
add_action('wp_ajax_madac_contact', 'madac_handle_contact');
add_action('wp_ajax_nopriv_madac_contact', 'madac_handle_contact');

// Reservation form handler
function madac_handle_reservation() {
    if (!isset($_POST['_wpnonce_reservation']) || !wp_verify_nonce($_POST['_wpnonce_reservation'], 'madac_reservation_nonce')) {
        wp_send_json_error(['message' => 'Erreur de sécurité. Rechargez la page et réessayez.']);
    }

    // Rate limiting
    $ip = $_SERVER['REMOTE_ADDR'];
    $transient_key = 'madac_resa_' . md5($ip);
    if (get_transient($transient_key)) {
        wp_send_json_error(['message' => 'Veuillez patienter une minute avant de refaire une réservation.']);
    }

    $prenom      = sanitize_text_field($_POST['prenom'] ?? '');
    $nom         = sanitize_text_field($_POST['nom'] ?? '');
    $email       = sanitize_email($_POST['email'] ?? '');
    $telephone   = sanitize_text_field($_POST['telephone'] ?? '');
    $niveau      = sanitize_text_field($_POST['niveau'] ?? '');
    $quantite    = absint($_POST['quantite'] ?? 1);
    $masterclass = sanitize_text_field($_POST['masterclass_name'] ?? '');
    $message     = sanitize_textarea_field($_POST['message'] ?? '');
    $prix_unit   = absint($_POST['prix_unitaire'] ?? 0);

    if (empty($prenom) || empty($nom) || empty($email) || empty($telephone)) {
        wp_send_json_error(['message' => 'Veuillez remplir tous les champs obligatoires.']);
    }
    if (!is_email($email)) {
        wp_send_json_error(['message' => 'Adresse email invalide.']);
    }

    $total     = $prix_unit * $quantite;
    $recipient = get_option('madac_contact_recipient', get_option('admin_email'));
    $subject   = '[MADAC Réservation] ' . $masterclass . ' - ' . $prenom . ' ' . $nom;
    $body      = "Nouvelle réservation MADAC\n\n";
    $body     .= "Masterclass : $masterclass\n";
    $body     .= "Nom : $prenom $nom\n";
    $body     .= "Email : $email\n";
    $body     .= "Téléphone : $telephone\n";
    $body     .= "Niveau : $niveau\n";
    $body     .= "Participants : $quantite\n";
    $body     .= "Total : {$total}€\n\n";
    if ($message) {
        $body .= "Message :\n$message\n";
    }
    $headers = ['Reply-To: ' . $prenom . ' ' . $nom . ' <' . $email . '>'];

    $sent = wp_mail($recipient, $subject, $body, $headers);

    if ($sent) {
        set_transient($transient_key, true, 60);
        wp_send_json_success([
            'message' => 'Réservation confirmée ! Vous recevrez un email de confirmation avec les détails de la formation et les modalités de paiement. Merci et à très bientôt à MADAC !',
            'total'   => $total,
        ]);
    } else {
        wp_send_json_error(['message' => 'Erreur lors de l\'envoi. Veuillez réessayer.']);
    }
}
add_action('wp_ajax_madac_reservation', 'madac_handle_reservation');
add_action('wp_ajax_nopriv_madac_reservation', 'madac_handle_reservation');
