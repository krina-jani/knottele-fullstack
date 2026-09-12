import axios from 'axios';
import api from './api';

export const authService = {
    /**
     * Retrieve CSRF cookie for Sanctum authentication
     * Must be called before login or registration
     */
    getCsrfCookie: async () => {
        return axios.get('/sanctum/csrf-cookie', {
            // Use standard axios to avoid the /api base URL prefix
            baseURL: window.location.origin,
            withCredentials: true
        });
    },

    /**
     * Authenticate a user
     */
    login: async (credentials) => {
        await authService.getCsrfCookie();
        const response = await api.post('/login', credentials);
        return response.data;
    },

    /**
     * Register a new user
     */
    register: async (userData) => {
        await authService.getCsrfCookie();
        const response = await api.post('/register', userData);
        return response.data;
    },

    /**
     * Log out the current user
     */
    logout: async () => {
        const response = await api.post('/logout');
        return response.data;
    },

    /**
     * Fetch the authenticated user's profile
     */
    getUser: async () => {
        const response = await api.get('/user');
        return response.data;
    }
};
