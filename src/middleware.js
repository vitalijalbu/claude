export function onRequest(context, next) {
  const { request, redirect, cookies } = context;
  const url = new URL(request.url);
  
  // Controlla se la rotta inizia con /account
  if (url.pathname.startsWith('/account')) {
    // Verifica se il cookie esiste (sostituisci 'auth' con il nome del tuo cookie)
    const authCookie = cookies.get('auth');
    
    if (!authCookie || !authCookie.value) {
      return redirect('/login');
    }
  }
  
  return next();
}