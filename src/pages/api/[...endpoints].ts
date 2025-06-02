import type { APIRoute } from 'astro';

const API_URL = import.meta.env.PUBLIC_API_URL;

export const POST: APIRoute = async ({ params, request, cookies }) => {
  const endpoint = params.endpoints;
  
  switch (endpoint) {
    case 'logout':
      return handleLogout(cookies);
    
    case 'favorites/toggle':
      return handleFavoriteToggle(request, cookies);
    
    default:
      return new Response(JSON.stringify({ error: 'Endpoint not found' }), {
        status: 404,
        headers: { 'Content-Type': 'application/json' }
      });
  }
};

export const GET: APIRoute = async ({ params, url, cookies }) => {
  const endpoint = params.endpoints;
  
  switch (endpoint) {
    case 'explore':
      return handleExplore(url);
    
    case 'me':
      return handleMe(cookies);
    
    default:
      return new Response(JSON.stringify({ error: 'Endpoint not found' }), {
        status: 404,
        headers: { 'Content-Type': 'application/json' }
      });
  }
};

async function handleLogout(cookies: any) {
  try {
    const token = cookies.get('auth_token')?.value;
    
    if (token) {
      // Call Laravel API to logout
      await fetch(`${API_URL}/logout`, {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Accept': 'application/json',
        },
      });
    }
    
    // Clear cookies
    cookies.delete('auth_token', { path: '/' });
    cookies.delete('user', { path: '/' });
    
    return new Response(JSON.stringify({ success: true }), {
      status: 200,
      headers: { 'Content-Type': 'application/json' }
    });
  } catch (error) {
    // Clear cookies even if API call fails
    cookies.delete('auth_token', { path: '/' });
    cookies.delete('user', { path: '/' });
    
    return new Response(JSON.stringify({ success: true }), {
      status: 200,
      headers: { 'Content-Type': 'application/json' }
    });
  }
}

async function handleFavoriteToggle(request: Request, cookies: any) {
  try {
    const token = cookies.get('auth_token')?.value;
    
    if (!token) {
      return new Response(JSON.stringify({ error: 'Authentication required' }), {
        status: 401,
        headers: { 'Content-Type': 'application/json' }
      });
    }
    
    const body = await request.json();
    
    const response = await fetch(`${API_URL}/favorites/toggle`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify(body)
    });
    
    const data = await response.json();
    
    return new Response(JSON.stringify(data), {
      status: response.status,
      headers: { 'Content-Type': 'application/json' }
    });
  } catch (error) {
    return new Response(JSON.stringify({ error: 'Internal server error' }), {
      status: 500,
      headers: { 'Content-Type': 'application/json' }
    });
  }
}

async function handleExplore(url: URL) {
  try {
    const query = url.searchParams.get('q');
    
    if (!query || query.length < 2) {
      return new Response(JSON.stringify([]), {
        status: 200,
        headers: { 'Content-Type': 'application/json' }
      });
    }
    
    const response = await fetch(`${API_URL}/explore?q=${encodeURIComponent(query)}`);
    const data = await response.json();
    
    return new Response(JSON.stringify(data), {
      status: response.status,
      headers: { 'Content-Type': 'application/json' }
    });
  } catch (error) {
    return new Response(JSON.stringify([]), {
      status: 200,
      headers: { 'Content-Type': 'application/json' }
    });
  }
}

async function handleMe(cookies: any) {
  try {
    const token = cookies.get('auth_token')?.value;
    
    if (!token) {
      return new Response(JSON.stringify({ error: 'Authentication required' }), {
        status: 401,
        headers: { 'Content-Type': 'application/json' }
      });
    }
    
    const response = await fetch(`${API_URL}/me`, {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json',
      },
    });
    
    const data = await response.json();
    
    return new Response(JSON.stringify(data), {
      status: response.status,
      headers: { 'Content-Type': 'application/json' }
    });
  } catch (error) {
    return new Response(JSON.stringify({ error: 'Internal server error' }), {
      status: 500,
      headers: { 'Content-Type': 'application/json' }
    });
  }
}