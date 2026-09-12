import React from 'react';
import { Link } from 'react-router-dom';
import { motion, AnimatePresence } from 'framer-motion';
import { FiX, FiTrash2 } from 'react-icons/fi';
import { useCart } from '../context/CartContext';
import Button from './Button';
import QuantitySelector from './QuantitySelector';

const CartDrawer = () => {
  const { isCartOpen, closeCart, cartItems, removeFromCart, updateQuantity, cartTotal } = useCart();

  return (
    <AnimatePresence>
      {isCartOpen && (
        <>
          {/* Overlay */}
          <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 0.5 }}
            exit={{ opacity: 0 }}
            onClick={closeCart}
            style={{
              position: 'fixed',
              top: 0,
              left: 0,
              right: 0,
              bottom: 0,
              backgroundColor: '#000',
              zIndex: 1001,
            }}
          />

          {/* Drawer */}
          <motion.div
            initial={{ x: '100%' }}
            animate={{ x: 0 }}
            exit={{ x: '100%' }}
            transition={{ type: 'tween', duration: 0.3 }}
            style={{
              position: 'fixed',
              top: 0,
              right: 0,
              bottom: 0,
              width: '100%',
              maxWidth: '400px',
              backgroundColor: 'var(--white)',
              zIndex: 1002,
              boxShadow: 'var(--shadow-lg)',
              display: 'flex',
              flexDirection: 'column'
            }}
          >
            {/* Header */}
            <div style={{ 
              display: 'flex', 
              justifyContent: 'space-between', 
              alignItems: 'center', 
              padding: '1.5rem',
              borderBottom: '1px solid var(--border)'
            }}>
              <h3 style={{ margin: 0, fontSize: '1.25rem' }}>Your Cart</h3>
              <button onClick={closeCart} aria-label="Close Cart" style={{ color: 'var(--dark-text)', background: 'none', border: 'none', cursor: 'pointer' }}>
                <FiX size={24} />
              </button>
            </div>

            {/* Items */}
            <div style={{ flex: 1, overflowY: 'auto', padding: '1.5rem' }}>
              {cartItems.length === 0 ? (
                <div style={{ textAlign: 'center', marginTop: '3rem' }}>
                  <p style={{ color: 'var(--secondary-text)', marginBottom: '1.5rem' }}>
                    Your cart is waiting for something delicious.
                  </p>
                  <Button onClick={closeCart} variant="primary">START SHOPPING</Button>
                </div>
              ) : (
                <div style={{ display: 'flex', flexDirection: 'column', gap: '1.5rem' }}>
                  {cartItems.map(item => (
                    <div key={`${item.id}-${item.variant.sku}`} style={{ display: 'flex', gap: '1rem', borderBottom: '1px solid var(--border)', paddingBottom: '1rem' }}>
                      <div style={{ width: '80px', height: '80px', backgroundColor: 'var(--soft-green)', borderRadius: 'var(--radius-sm)', padding: '0.5rem' }}>
                        <img src={item.images[0]} alt={item.name} style={{ width: '100%', height: '100%', objectFit: 'contain' }} />
                      </div>
                      
                      <div style={{ flex: 1, display: 'flex', flexDirection: 'column' }}>
                        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start' }}>
                          <div>
                            <h4 style={{ fontSize: '0.95rem', margin: 0, color: 'var(--dark-text)', lineHeight: 1.2 }}>{item.name}</h4>
                            <p style={{ fontSize: '0.85rem', color: 'var(--secondary-text)', margin: '0.25rem 0 0.5rem 0' }}>{item.variant.size}</p>
                          </div>
                          <button 
                            onClick={() => removeFromCart(item.id, item.variant.sku)}
                            aria-label="Remove item"
                            style={{ color: 'var(--brand-red)', padding: '4px', background: 'none', border: 'none', cursor: 'pointer' }}
                          >
                            <FiTrash2 size={16} />
                          </button>
                        </div>
                        
                        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginTop: 'auto' }}>
                          <QuantitySelector 
                            quantity={item.quantity} 
                            setQuantity={(q) => updateQuantity(item.id, item.variant.sku, q)} 
                          />
                          <span style={{ fontWeight: '600', color: 'var(--dark-green)' }}>
                            ₹{item.variant.price * item.quantity}
                          </span>
                        </div>
                      </div>
                    </div>
                  ))}
                </div>
              )}
            </div>

            {/* Footer */}
            {cartItems.length > 0 && (
              <div style={{ padding: '1.5rem', borderTop: '1px solid var(--border)', backgroundColor: 'var(--background)' }}>
                <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '1.5rem', fontSize: '1.1rem', fontWeight: 'bold' }}>
                  <span>Subtotal</span>
                  <span>₹{cartTotal}</span>
                </div>
                
                <div style={{ display: 'flex', flexDirection: 'column', gap: '1rem' }}>
                  <Link to="/cart" onClick={closeCart} style={{ textDecoration: 'none' }}>
                    <Button variant="outline" fullWidth>VIEW CART</Button>
                  </Link>
                  <Link to="/checkout" onClick={closeCart} style={{ textDecoration: 'none' }}>
                    <Button variant="primary" fullWidth>CHECKOUT</Button>
                  </Link>
                </div>
              </div>
            )}
          </motion.div>
        </>
      )}
    </AnimatePresence>
  );
};

export default CartDrawer;
