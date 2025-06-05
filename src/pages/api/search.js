// src/pages/api/search.js

export async function GET({ url, request }) {
  try {
    // Verifica che sia una richiesta AJAX
    const requestedWith = request.headers.get('X-Requested-With');
    if (requestedWith !== 'XMLHttpRequest') {
      return new Response(JSON.stringify({
        success: false,
        error: 'Invalid request'
      }), {
        status: 400,
        headers: { 'Content-Type': 'application/json' }
      });
    }

    // Estrai parametri dalla query string
    const query = url.searchParams.get('query');
    const limit = url.searchParams.get('limit') || '10';

    // URL della tua API Laravel (mantieni nascosto)
    const API_BASE_URL = import.meta.env.API_URL || 'http://localhost:8000/api';
    
    // Costruisci URL per la ricerca Laravel
    const searchUrl = new URL(`${API_BASE_URL}/explore`);
    if (query) {
      searchUrl.searchParams.set('query', query);
    }
    if (limit) {
      searchUrl.searchParams.set('limit', limit);
    }

    // Chiamata alla tua API Laravel
    const response = await fetch(searchUrl.toString(), {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'User-Agent': 'AstroApp/1.0',
        // Aggiungi eventuali header di autenticazione qui
        // 'Authorization': `Bearer ${apiToken}`,
      }
    });

    if (!response.ok) {
      throw new Error(`Laravel API responded with status ${response.status}`);
    }

    const data = await response.json();
    
    // Restituisci i dati al frontend
    return new Response(JSON.stringify(data), {
      status: 200,
      headers: { 
        'Content-Type': 'application/json',
        'Cache-Control': 'public, max-age=60', // Cache per 1 minuto
        'X-Powered-By': 'Astro-Middleware'
      }
    });

  } catch (error) {
    console.error('Search API Middleware Error:', error);
    
    return new Response(JSON.stringify({
      success: false,
      error: 'Service temporarily unavailable',
      message: process.env.NODE_ENV === 'development' ? error.message : undefined
    }), {
      status: 500,
      headers: { 'Content-Type': 'application/json' }
    });
  }
}