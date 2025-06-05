import './flyonui.js';
import './carousel.js';
import { SearchAutocomplete } from './modules/search-autocomplete.js';

document.addEventListener('DOMContentLoaded', () => {
	const container = document.querySelector('[data-search-component]');
	if (container) {
		new SearchAutocomplete(container);
	}
});
