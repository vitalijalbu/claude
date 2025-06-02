// Search Autocomplete
class SearchAutocomplete {
  constructor(inputSelector = '[data-search-input]') {
    this.inputs = document.querySelectorAll(inputSelector);
    this.apiUrl = '/api/explore';
    this.debounceTimer = null;
    this.debounceDelay = 200;
    this.minQueryLength = 2;
    
    this.init();
  }

  init() {
    this.inputs.forEach(input => {
      this.setupInput(input);
    });
  }

  setupInput(input) {
    const container = input.closest('[data-search-container]') || input.parentElement;
    
    // Create results container if it doesn't exist
    let resultsContainer = container.querySelector('[data-search-results]');
    if (!resultsContainer) {
      resultsContainer = document.createElement('div');
      resultsContainer.setAttribute('data-search-results', '');
      resultsContainer.className = 'absolute z-50 w-full bg-base-100 border border-base-300 rounded-lg shadow-lg mt-1 max-h-96 overflow-y-auto hidden';
      container.appendChild(resultsContainer);
      container.style.position = 'relative';
    }

    // Event listeners
    input.addEventListener('input', (e) => this.handleInput(e, resultsContainer));
    input.addEventListener('focus', (e) => this.handleFocus(e, resultsContainer));
    input.addEventListener('keydown', (e) => this.handleKeydown(e, resultsContainer));
    
    // Close on click outside
    document.addEventListener('click', (e) => {
      if (!container.contains(e.target)) {
        this.hideResults(resultsContainer);
      }
    });
  }

  handleInput(e, resultsContainer) {
    const query = e.target.value.trim();
    
    clearTimeout(this.debounceTimer);
    
    if (query.length < this.minQueryLength) {
      this.hideResults(resultsContainer);
      return;
    }

    this.debounceTimer = setTimeout(() => {
      this.performSearch(query, resultsContainer);
    }, this.debounceDelay);
  }

  handleFocus(e, resultsContainer) {
    const query = e.target.value.trim();
    if (query.length >= this.minQueryLength) {
      this.showResults(resultsContainer);
    }
  }

  handleKeydown(e, resultsContainer) {
    const items = resultsContainer.querySelectorAll('[data-search-item]');
    let currentIndex = Array.from(items).findIndex(item => item.classList.contains('active'));

    switch (e.key) {
      case 'ArrowDown':
        e.preventDefault();
        currentIndex = currentIndex < items.length - 1 ? currentIndex + 1 : 0;
        this.highlightItem(items, currentIndex);
        break;
        
      case 'ArrowUp':
        e.preventDefault();
        currentIndex = currentIndex > 0 ? currentIndex - 1 : items.length - 1;
        this.highlightItem(items, currentIndex);
        break;
        
      case 'Enter':
        e.preventDefault();
        const activeItem = items[currentIndex];
        if (activeItem) {
          const link = activeItem.querySelector('a');
          if (link) {
            window.location.href = link.href;
          }
        }
        break;
        
      case 'Escape':
        this.hideResults(resultsContainer);
        e.target.blur();
        break;
    }
  }

  async performSearch(query, resultsContainer) {
    try {
      this.showLoading(resultsContainer);
      
      const response = await fetch(`${this.apiUrl}?q=${encodeURIComponent(query)}`);
      
      if (!response.ok) {
        throw new Error('Search failed');
      }
      
      const results = await response.json();
      this.displayResults(results, resultsContainer);
      
    } catch (error) {
      console.error('Search error:', error);
      this.showError(resultsContainer);
    }
  }

  showLoading(resultsContainer) {
    resultsContainer.innerHTML = `
      <div class="p-4 text-center">
        <span class="loading loading-spinner loading-sm"></span>
        <span class="ml-2">Ricerca in corso...</span>
      </div>
    `;
    this.showResults(resultsContainer);
  }

  showError(resultsContainer) {
    resultsContainer.innerHTML = `
      <div class="p-4 text-center text-error">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        Errore durante la ricerca
      </div>
    `;
    this.showResults(resultsContainer);
  }

  displayResults(results, resultsContainer) {
    if (!Array.isArray(results) || results.length === 0) {
      resultsContainer.innerHTML = `
        <div class="p-4 text-center text-base-content/60">
          Nessun risultato trovato
        </div>
      `;
    } else {
      const groupedResults = this.groupResults(results);
      resultsContainer.innerHTML = this.renderGroups(groupedResults);
    }
    
    this.showResults(resultsContainer);
  }

  groupResults(results) {
    const groups = {
      cities: [],
      profiles: [],
      listings: []
    };

    results.forEach(item => {
      switch (item.type) {
        case 'city':
          groups.cities.push(item);
          break;
        case 'profile':
          groups.profiles.push(item);
          break;
        case 'listing':
          groups.listings.push(item);
          break;
      }
    });

    return groups;
  }

  renderGroups(groups) {
    let html = '';

    if (groups.cities.length > 0) {
      html += this.renderGroup('Città', groups.cities, 'city');
    }

    if (groups.profiles.length > 0) {
      html += this.renderGroup('Profili', groups.profiles, 'profile');
    }

    if (groups.listings.length > 0) {
      html += this.renderGroup('Annunci', groups.listings, 'listing');
    }

    return html;
  }

  renderGroup(title, items, type) {
    const icon = this.getTypeIcon(type);
    
    let html = `
      <div class="px-4 py-2 bg-base-200 font-semibold text-sm">
        ${icon} ${title}
      </div>
    `;

    items.forEach(item => {
      const href = this.getItemHref(item);
      html += `
        <div data-search-item class="hover:bg-base-200 cursor-pointer transition-colors">
          <a href="${href}" class="block px-4 py-3 text-sm">
            <div class="font-medium">${this.escapeHtml(item.label)}</div>
            ${item.description ? `<div class="text-base-content/60 text-xs">${this.escapeHtml(item.description)}</div>` : ''}
          </a>
        </div>
      `;
    });

    return html;
  }

  getTypeIcon(type) {
    const icons = {
      city: '📍',
      profile: '👤',
      listing: '📄'
    };
    return icons[type] || '';
  }

  getItemHref(item) {
    switch (item.type) {
      case 'city':
        return `/${item.slug}`;
      case 'profile':
        return `/profilo/${item.slug}`;
      case 'listing':
        return `/annuncio/${item.slug}`;
      default:
        return '#';
    }
  }

  highlightItem(items, index) {
    items.forEach((item, i) => {
      if (i === index) {
        item.classList.add('active', 'bg-primary', 'text-primary-content');
      } else {
        item.classList.remove('active', 'bg-primary', 'text-primary-content');
      }
    });
  }

  showResults(resultsContainer) {
    resultsContainer.classList.remove('hidden');
  }

  hideResults(resultsContainer) {
    resultsContainer.classList.add('hidden');
  }

  escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  }
}

// Initialize search autocomplete when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
  // Initialize for both desktop and mobile search inputs
  new SearchAutocomplete('#search-input, #mobile-search-input');
});