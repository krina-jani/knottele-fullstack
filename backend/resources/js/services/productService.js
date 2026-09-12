import api from './api';

export const productService = {
    /**
     * Fetch all products (Customer view)
     */
    getAll: async (params = {}) => {
        const response = await api.get('/customer/products', { params });
        return response.data;
    },

    /**
     * Fetch a single product by slug
     */
    getBySlug: async (slug) => {
        const response = await api.get(`/customer/products/${slug}`);
        return response.data;
    },

    /**
     * Fetch featured collections
     */
    getFeaturedCollections: async () => {
        const response = await api.get('/customer/collections/featured');
        return response.data;
    }
};
