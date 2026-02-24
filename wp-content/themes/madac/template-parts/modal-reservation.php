<div class="modal-overlay" id="modal" onclick="if(event.target===this)closeModal()">
  <div class="modal">
    <button class="modal-close" onclick="closeModal()">&times;</button>
    <span class="modal-tag" id="m-tag">Réservation</span>
    <h3 class="modal-title" id="m-title">Masterclass Gwoka</h3>
    <p class="modal-subtitle" id="m-subtitle">Formation sur 3 jours</p>
    <form class="modal-form" id="reservation-form">
      <input type="hidden" name="masterclass_name" id="m-masterclass-name" value="" />
      <input type="hidden" name="prix_unitaire" id="m-prix-unitaire" value="450" />
      <?php wp_nonce_field('madac_reservation_nonce', '_wpnonce_reservation'); ?>
      <div class="mf-grid">
        <div><label class="mf-label">Prénom</label><input class="mf-input" type="text" name="prenom" placeholder="Votre prénom" required/></div>
        <div><label class="mf-label">Nom</label><input class="mf-input" type="text" name="nom" placeholder="Votre nom" required/></div>
      </div>
      <div><label class="mf-label">Email</label><input class="mf-input" type="email" name="email" placeholder="votre@email.com" required/></div>
      <div><label class="mf-label">Téléphone</label><input class="mf-input" type="tel" name="telephone" placeholder="+590 xxx xxx xxx" required/></div>
      <div><label class="mf-label">Niveau d'expérience</label>
        <select class="mf-select" name="niveau">
          <option>Débutant - Aucune expérience</option>
          <option>Intermédiaire - Quelques bases</option>
          <option>Avancé - Pratique régulière</option>
          <option>Expert - Niveau professionnel</option>
        </select>
      </div>
      <div><label class="mf-label">Nombre de participants</label>
        <select class="mf-select" id="m-qty" name="quantite" onchange="updateTotal()">
          <option value="1">1 participant</option>
          <option value="2">2 participants</option>
          <option value="3">3 participants</option>
          <option value="4">4 participants</option>
        </select>
      </div>
      <div><label class="mf-label">Message / Questions</label><textarea class="mf-input" name="message" style="min-height:80px;resize:vertical;" placeholder="Informations complémentaires..."></textarea></div>
      <div class="mf-total">
        <span class="mf-total-label">Total à régler</span>
        <span class="mf-total-price" id="m-total">450&euro;</span>
      </div>
      <div id="reservation-feedback" style="display:none;padding:12px;margin-bottom:8px;text-align:center;"></div>
      <button type="submit" class="mf-submit" id="reservation-submit">Confirmer ma réservation &rarr;</button>
    </form>
  </div>
</div>
