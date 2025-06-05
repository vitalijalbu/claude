// Authentication Management System
class AuthManager {
  constructor() {
    this.apiUrl = import.meta.env.PUBLIC_API_URL || '/api';
    this.tokenKey = 'auth_token';
    this.userKey = 'user_data';
    this.currentUser = null;
    this.isInitialized = false;
    
    this.init();
  }

  async init() {
    try {
      await this.checkAuthStatus();
      this.bindGlobalEvents();
      this.isInitialized = true;
      this.dispatchEvent('auth:initialized', { user: this.currentUser });
    } catch (error) {
      console.error('Auth initialization failed:', error);
    }
  }

  async checkAuthStatus() {
    const token = this.getStoredToken();
    
    if (!token) {
      this.clearAuth();
      return false;
    }

    try {
      const response = await fetch(`${this.apiUrl}/me`, {
        headers: {
          'Authorization': `Bearer ${token}`,
          'Accept': 'application/json',
        },
      });

      if (response.ok) {
        const userData = await response.json();
        this.setAuth(token, userData);
        return true;
      } else {
        this.clearAuth();
        return false;
      }
    } catch (error) {
      console.error('Auth check failed:', error);
      this.clearAuth();
      return false;
    }
  }

  async login(credentials) {
    try {
      const response = await fetch(`${this.apiUrl}/login`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify(credentials),
      });

      const data = await response.json();

      if (response.ok && data.token) {
        this.setAuth(data.token, data.user);
        this.dispatchEvent('auth:login', { user: data.user });
        return { success: true, user: data.user };
      } else {
        return { 
          success: false, 
          error: data.message || 'Login failed',
          errors: data.errors 
        };
      }
    } catch (error) {
      console.error('Login error:', error);
      return { 
        success: false, 
        error: 'Errore di connessione. Riprova più tardi.' 
      };
    }
  }

  async register(userData) {
    try {
      const response = await fetch(`${this.apiUrl}/register`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify(userData),
      });

      const data = await response.json();

      if (response.ok && data.token) {
        this.setAuth(data.token, data.user);
        this.dispatchEvent('auth:register', { user: data.user });
        return { success: true, user: data.user };
      } else {
        return { 
          success: false, 
          error: data.message || 'Registration failed',
          errors: data.errors 
        };
      }
    } catch (error) {
      console.error('Registration error:', error);
      return { 
        success: false, 
        error: 'Errore di connessione. Riprova più tardi.' 
      };
    }
  }

  async logout() {
    const token = this.getStoredToken();
    
    try {
      if (token) {
        await fetch(`${this.apiUrl}/logout`, {
          method: 'POST',
          headers: {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json',
          },
        });
      }
    } catch (error) {
      console.error('Logout API call failed:', error);
    } finally {
      this.clearAuth();
      this.dispatchEvent('auth:logout');
    }
  }

  async updateProfile(profileData) {
    const token = this.getStoredToken();
    
    if (!token) {
      throw new Error('Not authenticated');
    }

    try {
      const response = await fetch(`${this.apiUrl}/profile`, {
        method: 'PUT',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify(profileData),
      });

      const data = await response.json();

      if (response.ok) {
        this.currentUser = { ...this.currentUser, ...data.user };
        this.storeUser(this.currentUser);
        this.dispatchEvent('auth:profile-updated', { user: this.currentUser });
        return { success: true, user: this.currentUser };
      } else {
        return { 
          success: false, 
          error: data.message || 'Profile update failed',
          errors: data.errors 
        };
      }
    } catch (error) {
      console.error('Profile update error:', error);
      return { 
        success: false, 
        error: 'Errore di connessione. Riprova più tardi.' 
      };
    }
  }

  async changePassword(passwordData) {
    const token = this.getStoredToken();
    
    if (!token) {
      throw new Error('Not authenticated');
    }

    try {
      const response = await fetch(`${this.apiUrl}/change-password`, {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify(passwordData),
      });

      const data = await response.json();

      if (response.ok) {
        this.dispatchEvent('auth:password-changed');
        return { success: true };
      } else {
        return { 
          success: false, 
          error: data.message || 'Password change failed',
          errors: data.errors 
        };
      }
    } catch (error) {
      console.error('Password change error:', error);
      return { 
        success: false, 
        error: 'Errore di connessione. Riprova più tardi.' 
      };
    }
  }

  setAuth(token, user) {
    this.currentUser = user;
    this.storeToken(token);
    this.storeUser(user);
    this.updateUIForAuth(true);
  }

  clearAuth() {
    this.currentUser = null;
    this.removeStoredToken();
    this.removeStoredUser();
    this.updateUIForAuth(false);
  }

  isAuthenticated() {
    return Boolean(this.currentUser && this.getStoredToken());
  }

  getUser() {
    return this.currentUser;
  }

  getToken() {
    return this.getStoredToken();
  }

  // Storage methods (use both localStorage and cookies for redundancy)
  storeToken(token) {
    try {
      localStorage.setItem(this.tokenKey, token);
      // Also set as httpOnly cookie via API call for SSR
      this.setCookie(this.tokenKey, token, 30);
    } catch (error) {
      console.error('Failed to store token:', error);
    }
  }

  getStoredToken() {
    try {
      return localStorage.getItem(this.tokenKey) || this.getCookie(this.tokenKey);
    } catch (error) {
      console.error('Failed to get stored token:', error);
      return null;
    }
  }

  removeStoredToken() {
    try {
      localStorage.removeItem(this.tokenKey);
      this.deleteCookie(this.tokenKey);
    } catch (error) {
      console.error('Failed to remove token:', error);
    }
  }

  storeUser(user) {
    try {
      localStorage.setItem(this.userKey, JSON.stringify(user));
    } catch (error) {
      console.error('Failed to store user:', error);
    }
  }

  getStoredUser() {
    try {
      const userData = localStorage.getItem(this.userKey);
      return userData ? JSON.parse(userData) : null;
    } catch (error) {
      console.error('Failed to get stored user:', error);
      return null;
    }
  }

  removeStoredUser() {
    try {
      localStorage.removeItem(this.userKey);
    } catch (error) {
      console.error('Failed to remove user:', error);
    }
  }

  // Cookie helpers
  setCookie(name, value, days) {
    const expires = new Date();
    expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
    document.cookie = `${name}=${value};expires=${expires.toUTCString()};path=/;SameSite=Strict`;
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

  deleteCookie(name) {
    document.cookie = `${name}=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/`;
  }

  // UI Updates
  updateUIForAuth(isAuthenticated) {
    // Update auth-required elements
    const authElements = document.querySelectorAll('[data-auth-required]');
    authElements.forEach(el => {
      if (isAuthenticated) {
        el.classList.remove('hidden');
        el.removeAttribute('disabled');
      } else {
        el.classList.add('hidden');
        el.setAttribute('disabled', 'disabled');
      }
    });

    // Update guest-only elements
    const guestElements = document.querySelectorAll('[data-guest-only]');
    guestElements.forEach(el => {
      if (isAuthenticated) {
        el.classList.add('hidden');
      } else {
        el.classList.remove('hidden');
      }
    });

    // Update user info displays
    if (isAuthenticated && this.currentUser) {
      this.updateUserDisplays();
    }

    // Update body class for styling
    document.body.classList.toggle('user-authenticated', isAuthenticated);
  }

  updateUserDisplays() {
    // Update user name displays
    const nameElements = document.querySelectorAll('[data-user-name]');
    nameElements.forEach(el => {
      el.textContent = this.currentUser.name || '';
    });

    // Update user email displays
    const emailElements = document.querySelectorAll('[data-user-email]');
    emailElements.forEach(el => {
      el.textContent = this.currentUser.email || '';
    });

    // Update user avatar displays
    const avatarElements = document.querySelectorAll('[data-user-avatar]');
    avatarElements.forEach(el => {
      if (el.tagName === 'IMG') {
        el.src = this.currentUser.avatar || '/images/default-avatar.svg';
        el.alt = `Avatar di ${this.currentUser.name}`;
      } else {
        el.style.backgroundImage = `url(${this.currentUser.avatar || '/images/default-avatar.svg'})`;
      }
    });
  }

  // Global event bindings
  bindGlobalEvents() {
    // Handle page navigation for protected routes
    window.addEventListener('beforeunload', () => {
      if (this.isAuthenticated()) {
        // Refresh token before page unload
        this.refreshToken();
      }
    });

    // Handle 401 responses globally
    window.addEventListener('unhandledrejection', (event) => {
      if (event.reason && event.reason.status === 401) {
        this.handleUnauthorized();
      }
    });

    // Auto-refresh token periodically
    setInterval(() => {
      if (this.isAuthenticated()) {
        this.refreshToken();
      }
    }, 15 * 60 * 1000); // 15 minutes
  }

  async refreshToken() {
    const token = this.getStoredToken();
    
    if (!token) return;

    try {
      const response = await fetch(`${this.apiUrl}/refresh`, {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Accept': 'application/json',
        },
      });

      if (response.ok) {
        const data = await response.json();
        if (data.token) {
          this.storeToken(data.token);
        }
      }
    } catch (error) {
      console.error('Token refresh failed:', error);
    }
  }

  handleUnauthorized() {
    this.clearAuth();
    this.dispatchEvent('auth:unauthorized');
    
    // Redirect to login if not already there
    if (!window.location.pathname.includes('/login')) {
      const returnUrl = encodeURIComponent(window.location.pathname + window.location.search);
      window.location.href = `/login?return=${returnUrl}`;
    }
  }

  // Event system
  dispatchEvent(eventName, detail = {}) {
    const event = new CustomEvent(eventName, { detail });
    document.dispatchEvent(event);
  }

  on(eventName, callback) {
    document.addEventListener(eventName, callback);
  }

  off(eventName, callback) {
    document.removeEventListener(eventName, callback);
  }

  // Route protection
  requireAuth(redirectUrl = '/login') {
    if (!this.isAuthenticated()) {
      const returnUrl = encodeURIComponent(window.location.href);
      window.location.href = `${redirectUrl}?return=${returnUrl}`;
      return false;
    }
    return true;
  }

  requireGuest(redirectUrl = '/account') {
    if (this.isAuthenticated()) {
      window.location.href = redirectUrl;
      return false;
    }
    return true;
  }
}

// Create global instance
const authManager = new AuthManager();

// Export for ES modules
export default authManager;

// Also make available globally
window.authManager = authManager;

// Helper functions for common auth operations
export const auth = {
  login: (credentials) => authManager.login(credentials),
  register: (userData) => authManager.register(userData),
  logout: () => authManager.logout(),
  isAuthenticated: () => authManager.isAuthenticated(),
  getUser: () => authManager.getUser(),
  getToken: () => authManager.getToken(),
  requireAuth: (redirectUrl) => authManager.requireAuth(redirectUrl),
  requireGuest: (redirectUrl) => authManager.requireGuest(redirectUrl),
  updateProfile: (data) => authManager.updateProfile(data),
  changePassword: (data) => authManager.changePassword(data),
  on: (event, callback) => authManager.on(event, callback),
  off: (event, callback) => authManager.off(event, callback),
};

// Auto-initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
  // Auth manager is already initialized in constructor
});