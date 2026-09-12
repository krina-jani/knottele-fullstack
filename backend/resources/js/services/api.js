import axios from 'axios';

// Create a configured axios instance
const api = axios.create({
    baseURL: '/api',
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
    // Required for Laravel Sanctum authentication to send cookies
    withCredentials: true,
});

// Request interceptor to add token and prevent aggressive browser caching
api.interceptors.request.use((config) => {
    // Add auth token if exists
    const token = localStorage.getItem('customer_token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    
    // Prevent browser from caching API responses (forces fresh data from backend)
    if (config.method === 'get') {
        config.headers['Cache-Control'] = 'no-cache, no-store, must-revalidate';
        config.headers['Pragma'] = 'no-cache';
        config.headers['Expires'] = '0';
    }
    
    return config;
});

// Interceptor to handle errors globally
api.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response) {
            // Handle specific status codes (e.g., 401 Unauthorized)
            if (error.response.status === 401) {
                // Optionally redirect to login or trigger an event
                console.warn('Unauthorized request. Redirecting to login...');
            }
        }
        return Promise.reject(error);
    }
);

export default api;
