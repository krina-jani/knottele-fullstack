import api from './api';

export const offerService = {
  getActiveOffers: async () => {
    try {
      const response = await api.get('/customer/offers/active');
      return response.data?.data || [];
    } catch (error) {
      console.error('Error fetching active offers:', error);
      return [];
    }
  },
  
  getStartBanner: async () => {
    try {
      const response = await api.get('/customer/offers/start-banner');
      return response.data?.data || null;
    } catch (error) {
      console.error('Error fetching start banner:', error);
      return null;
    }
  },

  validateOffer: async (code, subtotal) => {
    try {
      const response = await api.post('/customer/offers/validate', { code, subtotal });
      return response.data;
    } catch (error) {
      if (error.response && error.response.data) {
        return error.response.data;
      }
      return { success: false, message: 'An error occurred while validating the offer.' };
    }
  }
};
