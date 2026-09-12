import api from './api';

export const orderService = {
  getMyOrders: async () => {
    try {
      const response = await api.get('/customer/orders');
      if (response.data && response.data.success) {
        return response.data.data;
      }
      return [];
    } catch (error) {
      console.error('Error fetching orders:', error);
      throw error;
    }
  }
};
