import './flyonui.js';
//import './carousel.js';
import { SearchAutocomplete } from './modules/search-autocomplete.js';
import Alpine from 'alpinejs'
 
window.Alpine = Alpine
 
Alpine.start()

document.addEventListener('DOMContentLoaded', () => {
	const container = document.querySelector('[data-search-component]');
	if (container) {
		new SearchAutocomplete(container);
	}
});
