export async function fetchData(endpoint, options = {}) {
	const baseUrl = import.meta.env.PUBLIC_API_URL;

	const defaultOptions = {
		headers: {
			'Content-Type': 'application/json',
			Accept: 'application/json',
		},
	};

	const mergedOptions = { ...defaultOptions, ...options };

	let result = {
		data: null,
		isLoading: true,
		isError: false,
		errorCode: null,
	};

	try {
		const response = await fetch(`${baseUrl}${endpoint}`, mergedOptions);

		if (!response.ok) {
			const errorData = await response.json().catch(() => null);
			console.error(`Errore API: ${JSON.stringify(errorData)}`);
			result.isError = true;
			result.isLoading = false;
			result.errorCode = response.status;
			return result;
		}

		const json = await response.json();
		result.data = json;
		result.isLoading = false;
		return result;
	} catch (error) {
		console.error('Errore durante il fetch dei dati:', error);
		result.isError = true;
		result.isLoading = false;
		result.errorCode = error.code || 'UNKNOWN_ERROR';
		return result;
	} finally {
		result.isLoading = false;
	}
}