import api from './api';

export const categoryService = {
    /**
     * Fetch all active categories
     * @returns {Promise<Array>} Array of category objects
     */
    getAllCategories: async () => {
        try {
            const response = await api.get('/customer/categories');
            if (response.data.status === 'success') {
                return response.data.data;
            }
            return [];
        } catch (error) {
            console.error("Error fetching categories:", error);
            return [];
        }
    }
};
