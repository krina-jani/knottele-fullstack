import React, { createContext, useContext, useState, useEffect } from 'react';
import api from '../services/api';

const CartContext = createContext();

export const useCart = () => {
  return useContext(CartContext);
};

export const CartProvider = ({ children }) => {
  const [cartItems, setCartItems] = useState(() => {
    const savedCart = localStorage.getItem('nuts_cart');
    return savedCart ? JSON.parse(savedCart) : [];
  });
  const [isCartOpen, setIsCartOpen] = useState(false);

  // Validate cart on mount to fetch fresh prices and taxes
  useEffect(() => {
    const validateCart = async () => {
      if (cartItems.length > 0) {
        try {
          const response = await api.post('/customer/cart/validate', { items: cartItems });
          if (response.data.success && response.data.data) {
            setCartItems(response.data.data);
          }
        } catch (error) {
          console.error("Failed to validate cart:", error);
        }
      }
    };
    validateCart();
  }, []); // Run only once on mount

  useEffect(() => {
    localStorage.setItem('nuts_cart', JSON.stringify(cartItems));
  }, [cartItems]);

  const toggleCart = () => setIsCartOpen(!isCartOpen);
  const openCart = () => setIsCartOpen(true);
  const closeCart = () => setIsCartOpen(false);

  const addToCart = (product, variant, quantity = 1) => {
    setCartItems(prev => {
      const existingItemIndex = prev.findIndex(
        item => item.id === product.id && item.variant.sku === variant.sku
      );

      if (existingItemIndex >= 0) {
        const updated = [...prev];
        updated[existingItemIndex].quantity += quantity;
        return updated;
      }

      return [...prev, { ...product, variant, quantity }];
    });
    openCart();
  };

  const removeFromCart = (productId, variantSku) => {
    setCartItems(prev => prev.filter(
      item => !(item.id === productId && item.variant.sku === variantSku)
    ));
  };

  const updateQuantity = (productId, variantSku, newQuantity) => {
    if (newQuantity < 1) return;
    setCartItems(prev => prev.map(item => {
      if (item.id === productId && item.variant.sku === variantSku) {
        return { ...item, quantity: newQuantity };
      }
      return item;
    }));
  };

  const clearCart = () => setCartItems([]);

  const cartTotal = cartItems.reduce((total, item) => total + (item.variant.price * item.quantity), 0);
  const taxTotal = cartItems.reduce((total, item) => {
    const itemTotal = item.variant.price * item.quantity;
    const taxRate = item.tax_rate || 0;
    return total + (itemTotal * taxRate) / 100;
  }, 0);
  const cartCount = cartItems.reduce((count, item) => count + item.quantity, 0);

  const value = {
    cartItems,
    isCartOpen,
    toggleCart,
    openCart,
    closeCart,
    addToCart,
    removeFromCart,
    updateQuantity,
    clearCart,
    cartTotal,
    taxTotal,
    cartCount
  };

  return (
    <CartContext.Provider value={value}>
      {children}
    </CartContext.Provider>
  );
};
