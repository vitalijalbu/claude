// src/assets/js/modules/filterModal.js

class FilterModal {
  constructor() {
    this.triggerButton = null;
    this.container = null;
    this.modalCreated = false;
    this.modalId = 'slide-up-animated-modal';
    this.init();
  }

  init() {
    this.triggerButton = document.getElementById('filter-modal-trigger');
    this.container = document.getElementById('filter-modal-container');
    
    if (this.triggerButton && this.container) {
      this.bindEvents();
    }
  }

  bindEvents() {
    this.triggerButton.addEventListener('click', () => this.handleTriggerClick());
  }

  handleTriggerClick() {
    if (!this.modalCreated) {
      this.createModal();
    }
    this.showModal();
  }

  createModal() {
    const modalHTML = this.getModalHTML();
    this.container.innerHTML = modalHTML;
    this.modalCreated = true;
    this.updateTriggerAttributes();
    this.bindModalEvents();
  }

  getModalHTML() {
    return `
      <div
        id="${this.modalId}"
        class="overlay modal overlay-open:opacity-100 overlay-open:duration-300 hidden"
        role="dialog"
        tabindex="-1">
        <div class="overlay-animation-target modal-dialog overlay-open:mt-4 overlay-open:opacity-100 overlay-open:duration-300 mt-12 transition-all ease-out">
          <div class="modal-content">
            <div class="modal-header">
              <div class="modal-title text-lg font-bold">Filtri</div>
              <button
                type="button"
                class="btn btn-text btn-circle btn-sm absolute end-3 top-3"
                aria-label="Close"
                data-overlay="#${this.modalId}">
                <span class="icon-[tabler--x] size-4"></span>
              </button>
            </div>
            <div class="modal-body">
              ${this.getFiltersHTML()}
            </div>
            <div class="modal-footer">
              <button
                type="button"
                class="btn btn-soft btn-secondary"
                data-overlay="#${this.modalId}">
                Chiudi
              </button>
              <button
                type="button"
                class="btn btn-primary"
                id="apply-filters">
                Applica filtri
              </button>
            </div>
          </div>
        </div>
      </div>
    `;
  }

  getFiltersHTML() {
    return `
      <div class="space-y-4">
        <div>
          <label class="block text-sm font-medium mb-2">Categoria</label>
          <select class="w-full px-3 py-2 border border-gray-300 rounded-md" id="filter-category">
            <option value="">Tutte le categorie</option>
            <option value="escort">Escort</option>
            <option value="massage">Massaggi</option>
            <option value="trans">Trans</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium mb-2">Prezzo</label>
          <div class="flex gap-2">
            <input 
              type="number" 
              placeholder="Min" 
              class="flex-1 px-3 py-2 border border-gray-300 rounded-md"
              id="filter-price-min">
            <input 
              type="number" 
              placeholder="Max" 
              class="flex-1 px-3 py-2 border border-gray-300 rounded-md"
              id="filter-price-max">
          </div>
        </div>
        <div>
          <label class="block text-sm font-medium mb-2">Città</label>
          <input 
            type="text" 
            placeholder="Inserisci città" 
            class="w-full px-3 py-2 border border-gray-300 rounded-md"
            id="filter-city">
        </div>
        <div>
          <label class="block text-sm font-medium mb-2">Età</label>
          <div class="flex gap-2">
            <input 
              type="number" 
              placeholder="Min" 
              class="flex-1 px-3 py-2 border border-gray-300 rounded-md"
              id="filter-age-min">
            <input 
              type="number" 
              placeholder="Max" 
              class="flex-1 px-3 py-2 border border-gray-300 rounded-md"
              id="filter-age-max">
          </div>
        </div>
      </div>
    `;
  }

  updateTriggerAttributes() {
    this.triggerButton.setAttribute('aria-controls', this.modalId);
    this.triggerButton.setAttribute('data-overlay', `#${this.modalId}`);
  }

  bindModalEvents() {
    const applyButton = document.getElementById('apply-filters');
    applyButton?.addEventListener('click', () => this.handleApplyFilters());
  }

  showModal() {
    const modal = document.getElementById(this.modalId);
    if (modal) {
      modal.classList.remove('hidden');
      this.triggerButton.setAttribute('aria-expanded', 'true');
    }
  }

  hideModal() {
    const modal = document.getElementById(this.modalId);
    if (modal) {
      modal.classList.add('hidden');
      this.triggerButton.setAttribute('aria-expanded', 'false');
    }
  }

  handleApplyFilters() {
    const filters = this.getFilterValues();
    console.log('Filtri applicati:', filters);
    
    // Qui puoi aggiungere la logica per applicare i filtri
    // ad esempio: this.applyFiltersToPage(filters);
    
    this.hideModal();
  }

  getFilterValues() {
    return {
      category: document.getElementById('filter-category')?.value || '',
      priceMin: document.getElementById('filter-price-min')?.value || '',
      priceMax: document.getElementById('filter-price-max')?.value || '',
      city: document.getElementById('filter-city')?.value || '',
      ageMin: document.getElementById('filter-age-min')?.value || '',
      ageMax: document.getElementById('filter-age-max')?.value || ''
    };
  }

  // Metodo pubblico per applicare filtri dall'esterno
  applyFiltersToPage(filters) {
    // Implementa qui la logica per filtrare il contenuto della pagina
    console.log('Applicazione filtri alla pagina:', filters);
  }
}

// Inizializza automaticamente quando il DOM è pronto
function initFilterModal() {
  return new FilterModal();
}

export { FilterModal, initFilterModal };