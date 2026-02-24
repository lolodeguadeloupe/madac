/**
 * MADAC Theme - Main JavaScript
 */

document.addEventListener('DOMContentLoaded', function () {

  // -- Custom Cursor --
  var cur = document.getElementById('cur');
  var cur2 = document.getElementById('cur2');
  var mx = 0, my = 0, rx = 0, ry = 0;

  if (cur && cur2) {
    document.addEventListener('mousemove', function (e) {
      mx = e.clientX;
      my = e.clientY;
      cur.style.left = mx + 'px';
      cur.style.top = my + 'px';
    });

    (function animate() {
      rx += (mx - rx) * 0.13;
      ry += (my - ry) * 0.13;
      cur2.style.left = rx + 'px';
      cur2.style.top = ry + 'px';
      requestAnimationFrame(animate);
    })();

    document.querySelectorAll('a,button,.master-card,.prestation-card,.video-card').forEach(function (el) {
      el.addEventListener('mouseenter', function () {
        cur.style.width = '16px';
        cur.style.height = '16px';
        cur2.style.width = '52px';
        cur2.style.height = '52px';
      });
      el.addEventListener('mouseleave', function () {
        cur.style.width = '8px';
        cur.style.height = '8px';
        cur2.style.width = '32px';
        cur2.style.height = '32px';
      });
    });
  }

  // -- Nav solid on scroll --
  var nav = document.getElementById('nav');
  if (nav) {
    window.addEventListener('scroll', function () {
      nav.classList.toggle('solid', window.scrollY > 60);
    });
  }

  // -- Hamburger menu --
  var hamburger = document.getElementById('hamburger');
  var navLinks = document.getElementById('navLinks');
  if (hamburger && navLinks) {
    hamburger.addEventListener('click', function () {
      navLinks.classList.toggle('open');
    });
    navLinks.querySelectorAll('a').forEach(function (a) {
      a.addEventListener('click', function () {
        navLinks.classList.remove('open');
      });
    });
  }

  // -- Intersection Observer for appear animations --
  var observer = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
      }
    });
  }, { threshold: 0.08 });

  document.querySelectorAll('.appear').forEach(function (el) {
    observer.observe(el);
  });

  // -- Reservation buttons (data-* attributes) --
  document.querySelectorAll('.master-cta[data-title]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      openReservation(
        this.getAttribute('data-title'),
        this.getAttribute('data-prix'),
        this.getAttribute('data-duree')
      );
    });
  });

  // -- Contact form AJAX --
  var contactForm = document.getElementById('contact-form');
  if (contactForm) {
    contactForm.addEventListener('submit', function (e) {
      e.preventDefault();
      var feedback = document.getElementById('contact-feedback');
      var submitBtn = document.getElementById('contact-submit');
      var originalText = submitBtn.textContent;
      submitBtn.textContent = 'Envoi en cours...';
      submitBtn.disabled = true;

      var formData = new FormData(contactForm);
      formData.append('action', 'madac_contact');

      fetch(madac_ajax.ajaxurl, {
        method: 'POST',
        body: formData,
      })
      .then(function (r) { return r.json(); })
      .then(function (data) {
        feedback.style.display = 'block';
        if (data.success) {
          feedback.style.background = '#d4edda';
          feedback.style.color = '#155724';
          feedback.textContent = data.data.message;
          contactForm.reset();
        } else {
          feedback.style.background = '#f8d7da';
          feedback.style.color = '#721c24';
          feedback.textContent = data.data.message;
        }
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
      })
      .catch(function () {
        feedback.style.display = 'block';
        feedback.style.background = '#f8d7da';
        feedback.style.color = '#721c24';
        feedback.textContent = 'Erreur réseau. Veuillez réessayer.';
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
      });
    });
  }

  // -- Reservation form AJAX --
  var reservationForm = document.getElementById('reservation-form');
  if (reservationForm) {
    reservationForm.addEventListener('submit', function (e) {
      e.preventDefault();
      var feedback = document.getElementById('reservation-feedback');
      var submitBtn = document.getElementById('reservation-submit');
      var originalText = submitBtn.textContent;
      submitBtn.textContent = 'Envoi en cours...';
      submitBtn.disabled = true;

      var formData = new FormData(reservationForm);
      formData.append('action', 'madac_reservation');

      fetch(madac_ajax.ajaxurl, {
        method: 'POST',
        body: formData,
      })
      .then(function (r) { return r.json(); })
      .then(function (data) {
        feedback.style.display = 'block';
        if (data.success) {
          feedback.style.background = '#d4edda';
          feedback.style.color = '#155724';
          feedback.textContent = data.data.message;
          submitBtn.style.display = 'none';
        } else {
          feedback.style.background = '#f8d7da';
          feedback.style.color = '#721c24';
          feedback.textContent = data.data.message;
          submitBtn.textContent = originalText;
          submitBtn.disabled = false;
        }
      })
      .catch(function () {
        feedback.style.display = 'block';
        feedback.style.background = '#f8d7da';
        feedback.style.color = '#721c24';
        feedback.textContent = 'Erreur réseau. Veuillez réessayer.';
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
      });
    });
  }

});

// -- Reservation Modal --
var modalPrice = 450;

function openReservation(titre, prix, duree) {
  document.getElementById('m-title').textContent = titre;
  document.getElementById('m-subtitle').textContent = 'Formation sur ' + duree;
  document.getElementById('m-masterclass-name').value = titre;
  document.getElementById('m-prix-unitaire').value = prix;
  modalPrice = parseFloat(prix);
  document.getElementById('m-qty').value = '1';
  document.getElementById('m-total').textContent = prix + '\u20AC';
  // Reset form state
  var feedback = document.getElementById('reservation-feedback');
  if (feedback) { feedback.style.display = 'none'; }
  var submitBtn = document.getElementById('reservation-submit');
  if (submitBtn) { submitBtn.style.display = ''; submitBtn.disabled = false; submitBtn.textContent = 'Confirmer ma réservation \u2192'; }
  document.getElementById('modal').classList.add('open');
}

function closeModal() {
  document.getElementById('modal').classList.remove('open');
}

function updateTotal() {
  var qty = parseInt(document.getElementById('m-qty').value);
  document.getElementById('m-total').textContent = (modalPrice * qty) + '\u20AC';
}
