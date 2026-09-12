import React from 'react';
import { Link } from 'react-router-dom';
import { FiTrash2, FiArrowRight, FiShield } from 'react-icons/fi';
import { useCart } from '../context/CartContext';
import Button from '../components/Button';
import QuantitySelector from '../components/QuantitySelector';

const Cart = () => {
  const { cartItems, removeFromCart, updateQuantity, cartTotal, taxTotal } = useCart();

  const shipping = cartTotal > 799 || cartTotal === 0 ? 0 : 50;
  const grandTotal = cartTotal + shipping + taxTotal;

  if (cartItems.length === 0) {
    return (
      <div className="container" style={{ paddingTop: '5rem', paddingBottom: '8rem', textAlign: 'center' }}>
        <h1 style={{ marginBottom: '1.5rem', color: 'var(--dark-green)' }}>Your Shopping Cart</h1>
        <div style={{ backgroundColor: 'var(--white)', padding: '4rem 2rem', borderRadius: 'var(--radius-lg)', border: '1px solid var(--border)' }}>
          <p style={{ fontSize: '1.25rem', color: 'var(--secondary-text)', marginBottom: '2rem' }}>
            Your cart is waiting for something delicious.
          </p>
          <Link to="/shop" style={{ textDecoration: 'none' }}>
            <Button variant="primary" size="lg">START SHOPPING</Button>
          </Link>
        </div>
      </div>
    );
  }

  return (
    <div className="container" style={{ paddingTop: '3rem', paddingBottom: '5rem' }}>
      <h1 style={{ marginBottom: '2rem', color: 'var(--dark-green)' }}>Shopping Cart</h1>
      
      <div style={{ display: 'flex', gap: '2rem', flexWrap: 'wrap', alignItems: 'flex-start' }}>
        
        {/* Left: Cart Items */}
        <div style={{ flex: '1 1 600px', backgroundColor: 'var(--white)', borderRadius: 'var(--radius-lg)', border: '1px solid var(--border)', overflow: 'hidden' }}>
          
          <div style={{ display: 'none', padding: '1.5rem', backgroundColor: 'var(--soft-green)', borderBottom: '1px solid var(--border)' }} className="cart-header">
            <div style={{ display: 'flex', width: '100%', fontWeight: '600', color: 'var(--dark-green)' }}>
              <div style={{ flex: 3 }}>Product</div>
              <div style={{ flex: 1, textAlign: 'center' }}>Price</div>
              <div style={{ flex: 1, textAlign: 'center' }}>Quantity</div>
              <div style={{ flex: 1, textAlign: 'right' }}>Total</div>
            </div>
          </div>

          <div style={{ display: 'flex', flexDirection: 'column' }}>
            {cartItems.map((item, index) => (
              <div key={`${item.id}-${item.variant.sku}`} style={{ 
                display: 'flex', 
                padding: '1.5rem', 
                borderBottom: index === cartItems.length - 1 ? 'none' : '1px solid var(--border)',
                flexWrap: 'wrap',
                gap: '1rem',
                alignItems: 'center'
              }}>
                {/* Product Info */}
                <div style={{ display: 'flex', gap: '1rem', flex: '1 1 250px', alignItems: 'center' }}>
                  <div style={{ width: '80px', height: '80px', backgroundColor: 'var(--soft-green)', borderRadius: 'var(--radius-sm)', padding: '0.5rem', flexShrink: 0 }}>
                    <img src={item.images[0]} alt={item.name} style={{ width: '100%', height: '100%', objectFit: 'contain' }} />
                  </div>
                  <div>
                    <Link to={`/product/${item.slug}`} style={{ textDecoration: 'none', color: 'inherit' }}>
                      <h3 style={{ fontSize: '1.1rem', marginBottom: '0.25rem', color: 'var(--dark-text)' }}>{item.name}</h3>
                    </Link>
                    <p style={{ color: 'var(--secondary-text)', fontSize: '0.9rem', marginBottom: '0.5rem' }}>Size: {item.variant.size}</p>
                    <button 
                      onClick={() => removeFromCart(item.id, item.variant.sku)}
                      style={{ color: 'var(--brand-red)', display: 'flex', alignItems: 'center', gap: '0.25rem', fontSize: '0.85rem' }}
                    >
                      <FiTrash2 size={14} /> Remove
                    </button>
                  </div>
                </div>

                {/* Price (Desktop) */}
                <div style={{ flex: 1, textAlign: 'center', fontWeight: '600' }} className="hide-mobile">
                  ₹{item.variant.price}
                </div>

                {/* Quantity */}
                <div style={{ flex: 1, display: 'flex', justifyContent: 'center' }}>
                  <QuantitySelector 
                    quantity={item.quantity} 
                    setQuantity={(q) => updateQuantity(item.id, item.variant.sku, q)} 
                    max={item.variant.stock}
                  />
                </div>

                {/* Total */}
                <div style={{ flex: 1, textAlign: 'right', fontWeight: 'bold', color: 'var(--dark-green)' }}>
                  ₹{item.variant.price * item.quantity}
                </div>
              </div>
            ))}
          </div>
        </div>

        {/* Right: Order Summary */}
        <div style={{ flex: '1 1 300px', backgroundColor: 'var(--white)', borderRadius: 'var(--radius-lg)', border: '1px solid var(--border)', padding: '2rem' }}>
          <h3 style={{ marginBottom: '1.5rem', fontSize: '1.25rem', borderBottom: '1px solid var(--border)', paddingBottom: '1rem' }}>
            Order Summary
          </h3>
          
          <div style={{ display: 'flex', flexDirection: 'column', gap: '1rem', marginBottom: '2rem' }}>
            <div style={{ display: 'flex', justifyContent: 'space-between', color: 'var(--secondary-text)' }}>
              <span>Subtotal</span>
              <span style={{ color: 'var(--dark-text)', fontWeight: '600' }}>₹{cartTotal}</span>
            </div>
            <div style={{ display: 'flex', justifyContent: 'space-between', color: 'var(--secondary-text)' }}>
              <span>Estimated Shipping</span>
              <span style={{ color: 'var(--dark-text)', fontWeight: '600' }}>
                {shipping === 0 ? 'Free' : `₹${shipping}`}
              </span>
            </div>

            {taxTotal > 0 && (
              <div style={{ display: 'flex', justifyContent: 'space-between', color: 'var(--secondary-text)' }}>
                <span>Taxes</span>
                <span style={{ color: 'var(--dark-text)', fontWeight: '600' }}>₹{taxTotal.toFixed(2)}</span>
              </div>
            )}
            
            {shipping > 0 && (
              <div style={{ fontSize: '0.85rem', color: 'var(--primary-green)', backgroundColor: 'var(--soft-green)', padding: '0.5rem', borderRadius: 'var(--radius-sm)', textAlign: 'center' }}>
                Add items worth ₹{799 - cartTotal} more for FREE shipping!
              </div>
            )}
            
            <div style={{ display: 'flex', justifyContent: 'space-between', marginTop: '1rem', paddingTop: '1rem', borderTop: '1px solid var(--border)', fontSize: '1.25rem', fontWeight: 'bold' }}>
              <span>Total</span>
              <span style={{ color: 'var(--dark-green)' }}>₹{grandTotal}</span>
            </div>
          </div>

          <Link to="/checkout" style={{ textDecoration: 'none' }}>
            <Button variant="primary" fullWidth size="lg">
              PROCEED TO CHECKOUT
            </Button>
          </Link>
          
          <div style={{ marginTop: '1.5rem', display: 'flex', alignItems: 'center', justifyContent: 'center', gap: '0.5rem', color: 'var(--secondary-text)', fontSize: '0.85rem' }}>
            <FiShield /> Secure Checkout Guaranteed
          </div>
        </div>
      </div>

      <style>{`
        @media (min-width: 768px) {
          .cart-header { display: flex !important; }
        }
        @media (max-width: 767px) {
          .hide-mobile { display: none !important; }
        }
      `}</style>
    </div>
  );
};

export default Cart;
