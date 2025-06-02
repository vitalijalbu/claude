// Age Gate Management
class AgeGate {
  constructor() {
    this.cookieName = 'age_verified';
    this.cookieValue = 'true';
    this.cookieExpiry = 30; // days
    this.modal = null;
    
    this.init();
  }

  init() {
    if (!this.getCookie(this.cookieName)) {
      this.showModal();
    }
  }

  createModal() {
    const modalHTML = `
      <div id="age-modal" class="modal modal-open">
        <div class="modal-box max-w-lg">
          <div class="text-center">
            <div class="w-16 h-16 mx-auto mb-4">
              <img src="/images/plus-18.png" alt="Plus 18" class="w-full h-full object-contain">
            </div>
            <h2 class="text-2xl font-bold mb-4">Questo è un sito per adulti</h2>
            <p class="text-base-content/70 mb-6">
              Questo sito contiene materiale vietato ai minori, tra cui nudità e rappresentazioni 
              esplicite di attività sessuali. Entrando, dichiari di avere almeno 18 anni o la 
              maggiore età nella giurisdizione da cui accedi al sito e acconsenti alla 
              visualizzazione di contenuti sessualmente espliciti.
            </p>
            
            <div class="flex flex-col gap-4">
              <button id="accept-age" class="btn btn-primary btn-lg">
                Ho 18 anni o più - Entra
              </button>
              <button id="decline-age" class="btn btn-ghost btn-sm">
                Ho meno di 18 anni - Esci
              </button>
            </div>
          </div>
        </div>
      </div>
    `;

    document.body.insertAdjacentHTML('beforeend', modalHTML);
    this.modal = document.getElementById('age-modal');
    this.attachEvents();
  }

  showModal() {
    this.createModal();
  }

  hideModal() {
    if (this.modal) {
      this.modal.remove();
      this.modal = null;
    }
  }

  attachEvents() {
    const acceptBtn = document.getElementById('accept-age');
    const declineBtn = document.getElementById('decline-age');

    acceptBtn?.addEventListener('click', () => {
      this.setCookie(this.cookieName, this.cookieValue, this.cookieExpiry);
      this.hideModal();
    });

    declineBtn?.addEventListener('click', () => {
      window.location.href = 'https://www.google.com';
    });
  }

  setCookie(name, value, days) {
    const d = new Date();
    d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
    const expires = "expires=" + d.toUTCString();
    document.cookie = `${name}=${value};${expires};path=/;SameSite=Strict;Secure`;
  }

  getCookie(name) {
    const nameEQ = name + "=";
    const ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
      let c = ca[i];
      while (c.charAt(0) === ' ') c = c.substring(1, c.length);
      if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
  }
}

// Initialize age gate when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
  new AgeGate();
});