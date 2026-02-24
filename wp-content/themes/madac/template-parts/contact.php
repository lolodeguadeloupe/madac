<?php
$email_mc   = get_option('madac_contact_email_masterclass', 'masterclass@madac.fr');
$email_ent  = get_option('madac_contact_email_entreprises', 'entreprises@madac.fr');
$phone      = get_option('madac_contact_phone', '+590 690 XX XX XX');
$location   = get_option('madac_contact_location', 'Guadeloupe, 971');
$intro      = get_option('madac_contact_intro', 'Masterclass, événements entreprises, réservations : nous sommes à votre écoute.');
?>
<section class="contact" id="contact">
  <div class="appear">
    <div class="section-tag">Nous rejoindre</div>
    <h2 class="section-h">Contactez-<em>nous</em></h2>
  </div>
  <div class="contact-inner">
    <div class="appear">
      <p><?php echo esc_html($intro); ?></p>
      <p style="margin-top:20px;">
        &#x1F4E7; <strong style="color:var(--or);"><?php echo esc_html($email_mc); ?></strong><br/>
        &#x1F4E7; <strong style="color:var(--or);"><?php echo esc_html($email_ent); ?></strong><br/>
        &#x1F4DE; <?php echo esc_html($phone); ?><br/>
        &#x1F4CD; <?php echo esc_html($location); ?>
      </p>
    </div>
    <form class="appear" id="contact-form">
      <?php wp_nonce_field('madac_contact_nonce', '_wpnonce_contact'); ?>
      <div class="cf-grid">
        <div><label class="cf-label">Prénom</label><input class="cf-input" type="text" name="prenom" required/></div>
        <div><label class="cf-label">Nom</label><input class="cf-input" type="text" name="nom" required/></div>
      </div>
      <label class="cf-label">Email</label><input class="cf-input" type="email" name="email" required/>
      <label class="cf-label">Sujet</label>
      <select class="cf-select" name="sujet">
        <option>Réservation Masterclass</option>
        <option>Devis Entreprise</option>
        <option>Information Générale</option>
        <option>Autre</option>
      </select>
      <label class="cf-label">Message</label><textarea class="cf-textarea" name="message"></textarea>
      <div id="contact-feedback" style="display:none;padding:12px;margin-bottom:8px;text-align:center;"></div>
      <button type="submit" class="cf-submit" id="contact-submit">Envoyer &rarr;</button>
    </form>
  </div>
</section>
