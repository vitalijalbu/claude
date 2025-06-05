// utils/search-autocomplete.js

/**
 * SearchAutocomplete - Componente per ricerca con autocompletamento
 */
export class SearchAutocomplete {
  constructor(container, options = {}) {
    this.container = container;
    this.options = {
      minChars: 2,
      debounceDelay: 300,
      maxResults: 10,
      ...options
    };
    
    this.input = container.querySelector('#search-input');
    this.resultsBox = container.querySelector('#autocomplete-results');
    this.loadingIndicator = container.querySelector('#search-loading');
    
    this.debounceTimer = null;
    this.currentRequest = null;
    this.isOpen = false;
    
    this.init();
  }
  
  init() {
    if (!this.input || !this.resultsBox) {
      console.error('SearchAutocomplete: elementi richiesti non trovati');
      return;
    }
    
    this.bindEvents();
    this.loadInitialData();
  }
  
  bindEvents() {
    // Input event per ricerca
    this.input.addEventListener('input', (e) => {
      this.handleInput(e.target.value.trim());
    });
    
    // Focus event per mostrare risultati se disponibili
    this.input.addEventListener('focus', () => {
      if (this.resultsBox.children.length > 0) {
        this.showResults();
      }
    });
    
    // Navigazione con tastiera
    this.input.addEventListener('keydown', (e) => {
      this.handleKeyNavigation(e);
    });
    
    // Click fuori dal componente per chiudere
    document.addEventListener('click', (e) => {
      if (!this.container.contains(e.target)) {
        this.hideResults();
      }
    });
    
    // Gestione resize finestra
    window.addEventListener('resize', () => {
      if (this.isOpen) {
        this.adjustResultsPosition();
      }
    });
  }
  
  async handleInput(query) {
    // Cancella timer precedente
    if (this.debounceTimer) {
      clearTimeout(this.debounceTimer);
    }
    
    // Cancella richiesta precedente
    if (this.currentRequest) {
      this.currentRequest.abort();
      this.currentRequest = null;
    }
    
    // Query troppo corta - mostra dati iniziali o nascondi
    if (query.length < this.options.minChars) {
      if (query.length === 0) {
        await this.loadInitialData();
      } else {
        this.hideResults();
      }
      return;
    }
    
    // Debounce la ricerca
    this.debounceTimer = setTimeout(async () => {
      await this.performSearch(query);
    }, this.options.debounceDelay);
  }
  
  async performSearch(query) {
    try {
      this.showLoading(true);
      
      // Crea AbortController per cancellare richieste
      const controller = new AbortController();
      this.currentRequest = controller;
      
      // Costruisci URL con query parameters
      const searchUrl = new URL('/api/search', window.location.origin);
      searchUrl.searchParams.set('query', query);
      searchUrl.searchParams.set('limit', this.options.maxResults);
      
      const response = await fetch(searchUrl, {
        method: 'GET',
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        },
        signal: controller.signal
      });
      
      if (!response.ok) {
        throw new Error(`HTTP ${response.status}`);
      }
      
      const data = await response.json();
      
      if (data.success && Array.isArray(data.data)) {
        this.renderResults(data.data, query);
      } else {
        this.renderNoResults();
      }
      
    } catch (error) {
      if (error.name !== 'AbortError') {
        console.error('Errore ricerca:', error);
        this.renderError();
      }
    } finally {
      this.showLoading(false);
      this.currentRequest = null;
    }
  }
  
  async loadInitialData() {
    try {
      this.showLoading(true);
      
      // Carica dati iniziali senza query
      const searchUrl = new URL('/api/search', window.location.origin);
      searchUrl.searchParams.set('limit', this.options.maxResults);
      
      const response = await fetch(searchUrl, {
        method: 'GET',
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      });
      
      if (!response.ok) return;
      
      const data = await response.json();
      
      if (data.success && Array.isArray(data.data)) {
        this.renderResults(data.data, '', true);
      }
      
    } catch (error) {
      console.error('Errore caricamento dati iniziali:', error);
    } finally {
      this.showLoading(false);
    }
  }
  
  renderResults(results, query = '', isInitial = false) {
    this.resultsBox.innerHTML = '';
    
    if (results.length === 0) {
      this.renderNoResults();
      return;
    }
    
    // Raggruppa risultati per tipo
    const grouped = this.groupResultsByType(results);
    
    Object.entries(grouped).forEach(([type, items]) => {
      if (items.length === 0) return;
      
      // Header di categoria
      const categoryHeader = this.createCategoryHeader(type, items.length);
      this.resultsBox.appendChild(categoryHeader);
      
      // Items della categoria
      items.forEach((item, index) => {
        const li = this.createResultItem(item, query, index);
        this.resultsBox.appendChild(li);
      });
    });
    
    this.showResults();
  }
  
  groupResultsByType(results) {
    const typeLabels = {
      'city': 'Città',
      'profile': 'Profili', 
      'listing': 'Annunci'
    };
    
    const grouped = {};
    
    // Inizializza gruppi
    Object.keys(typeLabels).forEach(type => {
      grouped[type] = [];
    });
    
    // Raggruppa risultati
    results.forEach(item => {
      if (grouped[item.type]) {
        grouped[item.type].push(item);
      }
    });
    
    return grouped;
  }
  
  createCategoryHeader(type, count) {
    const typeLabels = {
      'city': 'Città',
      'profile': 'Profili',
      'listing': 'Annunci'
    };
    
    const header = document.createElement('li');
    header.className = 'px-4 py-2 bg-gray-50 text-sm font-semibold text-gray-600 border-b sticky top-0';
    header.innerHTML = `${typeLabels[type]} (${count})`;
    return header;
  }
  
  createResultItem(item, query, index) {
    const li = document.createElement('li');
    li.className = 'search-result-item';
    li.setAttribute('data-index', index);
    
    const href = this.generateItemUrl(item);
    const highlightedLabel = this.highlightText(item.label || 'N/A', query);
    
    li.innerHTML = `
      <a href="${href}" class="block px-4 py-3 hover:bg-blue-50 transition-colors duration-150 border-b border-gray-100 last:border-b-0">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-3">
            <div class="flex-shrink-0">
              ${this.getTypeIcon(item.type)}
            </div>
            <div class="min-w-0 flex-1">
              <div class="text-sm font-medium text-gray-900 truncate">
                ${highlightedLabel}
              </div>
              <div class="text-xs text-gray-500">
                ${this.getTypeLabel(item.type)}
              </div>
            </div>
          </div>
          <div class="flex-shrink-0">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
          </div>
        </div>
      </a>
    `;
    
    return li;
  }
  
  generateItemUrl(item) {
    const urls = {
      'city': `/${item.slug}`,
      'profile': `/profilo/${item.slug}`,
      'listing': `/annuncio/${item.slug}`
    };
    
    return urls[item.type] || '#';
  }
  
  getTypeIcon(type) {
    const icons = {
      'city': `<svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>`,
      'profile': `<svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>`,
      'listing': `<svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>`
    };
    
    return icons[type] || '';
  }
  
  getTypeLabel(type) {
    const labels = {
      'city': 'Città',
      'profile': 'Profilo',
      'listing': 'Annuncio'
    };
    
    return labels[type] || type;
  }
  
  highlightText(text, query) {
    if (!query || query.length < 2) return text;
    
    const regex = new RegExp(`(${query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
    return text.replace(regex, '<mark class="bg-yellow-200 font-semibold">$1</mark>');
  }
  
  renderNoResults() {
    this.resultsBox.innerHTML = `
      <li class="px-4 py-8 text-center text-gray-500">
        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.467.881-6.127 2.325M15 17H9v-2.733a8.001 8.001 0 0112-6.924V17z"/>
        </svg>
        <p class="text-sm">Nessun risultato trovato</p>
        <p class="text-xs mt-1">Prova con altri termini di ricerca</p>
      </li>
    `;
    this.showResults();
  }
  
  renderError() {
    this.resultsBox.innerHTML = `
      <li class="px-4 py-6 text-center text-red-500">
        <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-sm">Errore durante la ricerca</p>
      </li>
    `;
    this.showResults();
  }
  
  handleKeyNavigation(e) {
    const items = this.resultsBox.querySelectorAll('.search-result-item');
    if (items.length === 0) return;
    
    const currentActive = this.resultsBox.querySelector('.search-result-item.active');
    let currentIndex = currentActive ? parseInt(currentActive.dataset.index) : -1;
    
    switch (e.key) {
      case 'ArrowDown':
        e.preventDefault();
        currentIndex = (currentIndex + 1) % items.length;
        this.setActiveItem(items[currentIndex]);
        break;
        
      case 'ArrowUp':
        e.preventDefault();
        currentIndex = currentIndex <= 0 ? items.length - 1 : currentIndex - 1;
        this.setActiveItem(items[currentIndex]);
        break;
        
      case 'Enter':
        e.preventDefault();
        if (currentActive) {
          const link = currentActive.querySelector('a');
          if (link) link.click();
        }
        break;
        
      case 'Escape':
        this.hideResults();
        this.input.blur();
        break;
    }
  }
  
  setActiveItem(item) {
    // Rimuovi classe active da tutti gli elementi
    this.resultsBox.querySelectorAll('.search-result-item').forEach(el => {
      el.classList.remove('active');
      el.querySelector('a').classList.remove('bg-blue-50');
    });
    
    // Aggiungi classe active all'elemento corrente
    if (item) {
      item.classList.add('active');
      item.querySelector('a').classList.add('bg-blue-50');
      item.scrollIntoView({ block: 'nearest' });
    }
  }
  
  showResults() {
    this.resultsBox.classList.remove('hidden');
    this.isOpen = true;
    this.adjustResultsPosition();
  }
  
  hideResults() {
    this.resultsBox.classList.add('hidden');
    this.isOpen = false;
  }
  
  showLoading(show) {
    if (this.loadingIndicator) {
      this.loadingIndicator.classList.toggle('hidden', !show);
    }
  }
  
  adjustResultsPosition() {
    if (!this.isOpen) return;
    
    const rect = this.input.getBoundingClientRect();
    const viewport = {
      height: window.innerHeight,
      width: window.innerWidth
    };
    
    // Se non c'è spazio sotto, mostra sopra
    const spaceBelow = viewport.height - rect.bottom;
    const spaceAbove = rect.top;
    
    if (spaceBelow < 200 && spaceAbove > spaceBelow) {
      this.resultsBox.style.bottom = '100%';
      this.resultsBox.style.top = 'auto';
      this.resultsBox.style.marginBottom = '0.25rem';
      this.resultsBox.style.marginTop = '0';
    } else {
      this.resultsBox.style.top = '100%';
      this.resultsBox.style.bottom = 'auto';
      this.resultsBox.style.marginTop = '0.25rem';
      this.resultsBox.style.marginBottom = '0';
    }
  }
  
  // Metodi pubblici
  focus() {
    this.input.focus();
  }
  
  clear() {
    this.input.value = '';
    this.hideResults();
  }
  
  setValue(value) {
    this.input.value = value;
    this.handleInput(value);
  }
  
  destroy() {
    if (this.debounceTimer) {
      clearTimeout(this.debounceTimer);
    }
    
    if (this.currentRequest) {
      this.currentRequest.abort();
    }
    
    // Rimuovi event listeners se necessario
    // (in questo caso sono gestiti automaticamente)
  }
}