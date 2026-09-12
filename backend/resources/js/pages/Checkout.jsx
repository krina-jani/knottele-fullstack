import React, { useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { FiCheckCircle, FiShield, FiTruck } from 'react-icons/fi';
import { useCart } from '../context/CartContext';
import Button from '../components/Button';
import { motion } from 'framer-motion';
import api from '../services/api';
import { offerService } from '../services/offerService';
import PaymentModal from '../components/PaymentModal';

const Checkout = () => {
  const { cartItems, cartTotal, taxTotal, clearCart } = useCart();
  const navigate = useNavigate();
  
  const [isSuccess, setIsSuccess] = useState(false);
  const [orderId, setOrderId] = useState('');
  const [isLoading, setIsLoading] = useState(false);
  const [errorMsg, setErrorMsg] = useState('');
  const [finalTotal, setFinalTotal] = useState(0);

  // Payment Modal State
  const [showPaymentModal, setShowPaymentModal] = useState(false);
  const [modalAmount, setModalAmount] = useState(0);
  const [modalOrderId, setModalOrderId] = useState('');

  // Form State
  const [paymentMethod, setPaymentMethod] = useState('upi');
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    phone: '',
    address_line_1: '',
    address_line_2: '',
    city: '',
    pin_code: ''
  });

  // Offer State
  const [offerCode, setOfferCode] = useState('');
  const [appliedOffer, setAppliedOffer] = useState(null);
  const [discountAmount, setDiscountAmount] = useState(0);
  const [offerError, setOfferError] = useState('');
  const [isApplyingOffer, setIsApplyingOffer] = useState(false);

  const shipping = (cartTotal - discountAmount) > 799 || cartTotal === 0 ? 0 : 50;
  const grandTotal = cartTotal - discountAmount + shipping + taxTotal;

  const handleApplyOffer = async (e) => {
    e.preventDefault();
    if (!offerCode.trim()) return;
    
    setIsApplyingOffer(true);
    setOfferError('');
    
    const response = await offerService.validateOffer(offerCode, cartTotal);
    
    if (response.success) {
      setAppliedOffer(response.data.offer);
      setDiscountAmount(response.data.discount_amount);
      setOfferCode('');
    } else {
      setOfferError(response.message || 'Invalid offer code');
      setAppliedOffer(null);
      setDiscountAmount(0);
    }
    setIsApplyingOffer(false);
  };

  const handleRemoveOffer = () => {
    setAppliedOffer(null);
    setDiscountAmount(0);
    setOfferError('');
  };

  const handleInputChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handlePlaceOrder = async (e) => {
    e.preventDefault();
    setIsLoading(true);
    setErrorMsg('');

    try {
      const payload = {
        items: cartItems.map(item => ({
          variant_id: item.variant.id,
          quantity: item.quantity
        })),
        shipping_address: formData,
        payment_method: paymentMethod,
      };

      if (appliedOffer) {
        payload.offer_code = appliedOffer.code;
      }

      const response = await api.post('/customer/orders', payload);

      if (response.data.success) {
        const orderNum = response.data.data.order_number;
        const total = response.data.data.grand_total;
        
        setOrderId(orderNum);
        setFinalTotal(total);
        
        if (paymentMethod === 'cod') {
          clearCart();
          setIsSuccess(true);
        } else {
          setModalAmount(total);
          setModalOrderId(orderNum);
          setShowPaymentModal(true);
        }
      } else {
        setErrorMsg('Failed to place order. Please try again.');
      }
    } catch (error) {
      setErrorMsg(error.response?.data?.message || 'Something went wrong. Please check your details.');
    } finally {
      setIsLoading(false);
    }
  };

  if (isSuccess) {
    return (
      <div className="container" style={{ paddingTop: '5rem', paddingBottom: '8rem', textAlign: 'center' }}>
        <motion.div
          initial={{ scale: 0.8, opacity: 0 }}
          animate={{ scale: 1, opacity: 1 }}
          transition={{ duration: 0.5 }}
        >
          <FiCheckCircle size={80} color="var(--primary-green)" style={{ marginBottom: '1.5rem' }} />
          <h1 style={{ marginBottom: '1rem', color: 'var(--dark-green)' }}>Order Placed Successfully!</h1>
          <p style={{ fontSize: '1.125rem', color: 'var(--secondary-text)', marginBottom: '2rem' }}>
            Thank you for shopping with Nuts & Nutrition. Your order #{orderId} has been confirmed.
          </p>
          <div style={{ padding: '1.5rem', backgroundColor: 'var(--soft-green)', borderRadius: 'var(--radius-lg)', display: 'inline-block', marginBottom: '3rem' }}>
            <p style={{ margin: 0, fontWeight: 'bold' }}>Total Amount: ₹{finalTotal}</p>
          </div>
          <div style={{ display: 'flex', gap: '1rem', justifyContent: 'center' }}>
            <Link to="/orders" style={{ textDecoration: 'none' }}>
              <Button variant="outline">TRACK ORDER</Button>
            </Link>
            <Link to="/shop" style={{ textDecoration: 'none' }}>
              <Button variant="primary">CONTINUE SHOPPING</Button>
            </Link>
          </div>
        </motion.div>
      </div>
    );
  }

  if (cartItems.length === 0) {
    return (
      <div className="container" style={{ paddingTop: '5rem', paddingBottom: '8rem', textAlign: 'center' }}>
        <h2>Your cart is empty</h2>
        <Button onClick={() => navigate('/shop')} style={{ marginTop: '1rem' }}>Back to Shop</Button>
      </div>
    );
  }

  const handlePaymentSuccess = () => {
    setShowPaymentModal(false);
    clearCart();
    setIsSuccess(true);
  };

  return (
    <div className="container" style={{ paddingTop: '3rem', paddingBottom: '5rem' }}>
      <PaymentModal 
        isOpen={showPaymentModal}
        onClose={() => setShowPaymentModal(false)}
        onSuccess={handlePaymentSuccess}
        amount={modalAmount}
        orderId={modalOrderId}
        paymentMethod={paymentMethod}
      />
      <h1 style={{ marginBottom: '2rem', color: 'var(--dark-green)' }}>Checkout</h1>
      
      {errorMsg && (
        <div style={{ padding: '1rem', backgroundColor: '#ffe5e5', color: '#c71f25', borderRadius: '8px', marginBottom: '2rem' }}>
          {errorMsg}
        </div>
      )}

      <form onSubmit={handlePlaceOrder} style={{ display: 'flex', gap: '2rem', flexWrap: 'wrap', alignItems: 'flex-start' }}>
        
        {/* Left: Forms */}
        <div style={{ flex: '1 1 600px', display: 'flex', flexDirection: 'column', gap: '2rem' }}>
          
          {/* 1. Delivery Address */}
          <section style={{ backgroundColor: 'var(--white)', padding: '2rem', borderRadius: 'var(--radius-lg)', border: '1px solid var(--border)' }}>
            <h3 style={{ marginBottom: '1.5rem', display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
              <span style={{ backgroundColor: 'var(--primary-green)', color: 'var(--white)', width: '28px', height: '28px', borderRadius: '50%', display: 'inline-flex', alignItems: 'center', justifyContent: 'center', fontSize: '0.9rem' }}>1</span>
              Delivery Address
            </h3>
            
            <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '1.5rem' }}>
              <div style={{ gridColumn: '1 / -1' }}>
                <label style={labelStyle}>Full Name</label>
                <input type="text" name="name" value={formData.name} onChange={handleInputChange} placeholder="John Doe" style={inputStyle} required />
              </div>
              <div>
                <label style={labelStyle}>Email Address</label>
                <input type="email" name="email" value={formData.email} onChange={handleInputChange} placeholder="john@example.com" style={inputStyle} required />
              </div>
              <div>
                <label style={labelStyle}>Phone Number</label>
                <input type="tel" name="phone" value={formData.phone} onChange={handleInputChange} placeholder="9876543210" maxLength="10" style={inputStyle} required />
              </div>
              <div style={{ gridColumn: '1 / -1' }}>
                <label style={labelStyle}>Address Line 1</label>
                <input type="text" name="address_line_1" value={formData.address_line_1} onChange={handleInputChange} placeholder="House/Flat No., Building Name" style={inputStyle} required />
              </div>
              <div style={{ gridColumn: '1 / -1' }}>
                <label style={labelStyle}>Address Line 2 (Optional)</label>
                <input type="text" name="address_line_2" value={formData.address_line_2} onChange={handleInputChange} placeholder="Street Name, Landmark" style={inputStyle} />
              </div>
              <div>
                <label style={labelStyle}>City</label>
                <input type="text" name="city" value={formData.city} onChange={handleInputChange} placeholder="Mumbai" style={inputStyle} required />
              </div>
              <div>
                <label style={labelStyle}>PIN Code</label>
                <input type="text" name="pin_code" value={formData.pin_code} onChange={handleInputChange} placeholder="400001" style={inputStyle} required />
              </div>
            </div>
          </section>

          {/* 2. Payment Method */}
          <section style={{ backgroundColor: 'var(--white)', padding: '2rem', borderRadius: 'var(--radius-lg)', border: '1px solid var(--border)' }}>
            <h3 style={{ marginBottom: '1.5rem', display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
              <span style={{ backgroundColor: 'var(--primary-green)', color: 'var(--white)', width: '28px', height: '28px', borderRadius: '50%', display: 'inline-flex', alignItems: 'center', justifyContent: 'center', fontSize: '0.9rem' }}>2</span>
              Payment Method
            </h3>
            
            <div style={{ display: 'flex', flexDirection: 'column', gap: '1rem' }}>
              {[
                { id: 'upi', label: 'UPI (GPay, PhonePe, Paytm)' },
                { id: 'card', label: 'Credit / Debit Card' },
                { id: 'netbanking', label: 'Net Banking' },
                { id: 'cod', label: 'Cash on Delivery' }
              ].map(method => (
                <label 
                  key={method.id} 
                  style={{
                    display: 'flex', 
                    alignItems: 'center', 
                    gap: '1rem', 
                    padding: '1.25rem',
                    border: `1px solid ${paymentMethod === method.id ? 'var(--primary-green)' : 'var(--border)'}`,
                    borderRadius: 'var(--radius-md)',
                    backgroundColor: paymentMethod === method.id ? 'var(--soft-green)' : 'var(--white)',
                    cursor: 'pointer',
                    transition: 'all 0.2s'
                  }}
                >
                  <input 
                    type="radio" 
                    name="payment" 
                    value={method.id}
                    checked={paymentMethod === method.id}
                    onChange={() => setPaymentMethod(method.id)}
                    style={{ width: '18px', height: '18px', accentColor: 'var(--primary-green)' }}
                  />
                  <span style={{ fontWeight: paymentMethod === method.id ? '600' : '400' }}>
                    {method.label}
                  </span>
                </label>
              ))}
            </div>

            {paymentMethod === 'card' && (
              <div style={{ marginTop: '1.5rem', padding: '1.5rem', border: '1px solid var(--border)', borderRadius: 'var(--radius-md)', backgroundColor: '#FAFAFA' }}>
                <div style={{ marginBottom: '1rem' }}>
                  <label style={labelStyle}>Card Number</label>
                  <input type="text" placeholder="XXXX XXXX XXXX XXXX" style={inputStyle} />
                </div>
                <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '1rem' }}>
                  <div>
                    <label style={labelStyle}>Expiry (MM/YY)</label>
                    <input type="text" placeholder="MM/YY" style={inputStyle} />
                  </div>
                  <div>
                    <label style={labelStyle}>CVV</label>
                    <input type="text" placeholder="123" style={inputStyle} />
                  </div>
                </div>
              </div>
            )}
          </section>

        </div>

        {/* Right: Order Summary */}
        <div style={{ flex: '1 1 350px', position: 'sticky', top: '100px' }}>
          <div style={{ backgroundColor: 'var(--white)', borderRadius: 'var(--radius-lg)', border: '1px solid var(--border)', padding: '2rem' }}>
            <h3 style={{ marginBottom: '1.5rem', fontSize: '1.25rem', borderBottom: '1px solid var(--border)', paddingBottom: '1rem' }}>
              Order Summary
            </h3>
            
            <div style={{ display: 'flex', flexDirection: 'column', gap: '1rem', marginBottom: '1.5rem', maxHeight: '300px', overflowY: 'auto' }}>
              {cartItems.map(item => (
                <div key={`${item.id}-${item.variant.sku}`} style={{ display: 'flex', gap: '1rem' }}>
                  <div style={{ width: '60px', height: '60px', backgroundColor: 'var(--soft-green)', borderRadius: 'var(--radius-sm)', padding: '0.25rem' }}>
                    <img src={item.images[0]} alt="" style={{ width: '100%', height: '100%', objectFit: 'contain' }} />
                  </div>
                  <div style={{ flex: 1 }}>
                    <h4 style={{ fontSize: '0.9rem', margin: 0 }}>{item.name}</h4>
                    <p style={{ fontSize: '0.8rem', color: 'var(--secondary-text)', margin: '0.25rem 0' }}>{item.variant.size} x {item.quantity}</p>
                    <div style={{ fontWeight: '600' }}>₹{item.variant.price * item.quantity}</div>
                  </div>
                </div>
              ))}
            </div>
            
            <div style={{ display: 'flex', flexDirection: 'column', gap: '1rem', marginBottom: '2rem', borderTop: '1px solid var(--border)', paddingTop: '1.5rem' }}>
              
              {/* Offer Section */}
              <div style={{ marginBottom: '0.5rem' }}>
                <div style={{ display: 'flex', gap: '0.5rem' }}>
                  <input 
                    type="text" 
                    placeholder="Discount code" 
                    value={offerCode}
                    onChange={(e) => setOfferCode(e.target.value)}
                    style={{ ...inputStyle, padding: '0.5rem 1rem', flex: 1, textTransform: 'uppercase' }}
                    disabled={appliedOffer !== null || isApplyingOffer}
                  />
                  <Button 
                    variant={appliedOffer ? "outline" : "primary"} 
                    style={{ padding: '0.5rem 1.5rem' }} 
                    onClick={appliedOffer ? handleRemoveOffer : handleApplyOffer}
                    disabled={isApplyingOffer || (!offerCode.trim() && !appliedOffer)}
                  >
                    {isApplyingOffer ? '...' : (appliedOffer ? 'Remove' : 'Apply')}
                  </Button>
                </div>
                {offerError && <div style={{ color: 'var(--brand-red)', fontSize: '0.8rem', marginTop: '0.5rem' }}>{offerError}</div>}
                {appliedOffer && <div style={{ color: 'var(--primary-green)', fontSize: '0.8rem', marginTop: '0.5rem', display: 'flex', alignItems: 'center', gap: '0.25rem' }}>
                  <FiCheckCircle /> '{appliedOffer.code}' applied
                </div>}
              </div>

              <div style={{ display: 'flex', justifyContent: 'space-between', color: 'var(--secondary-text)' }}>
                <span>Subtotal</span>
                <span style={{ color: 'var(--dark-text)', fontWeight: '600' }}>₹{cartTotal}</span>
              </div>
              
              {discountAmount > 0 && (
                <div style={{ display: 'flex', justifyContent: 'space-between', color: 'var(--brand-red)' }}>
                  <span>Discount</span>
                  <span style={{ fontWeight: '600' }}>-₹{discountAmount}</span>
                </div>
              )}

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
              
              <div style={{ display: 'flex', justifyContent: 'space-between', marginTop: '0.5rem', paddingTop: '1rem', borderTop: '1px dashed var(--border)', fontSize: '1.25rem', fontWeight: 'bold' }}>
                <span>Total</span>
                <span style={{ color: 'var(--dark-green)' }}>₹{grandTotal}</span>
              </div>
            </div>

            <Button type="submit" variant="primary" fullWidth size="lg" disabled={isLoading}>
              {isLoading ? 'PROCESSING...' : `PLACE ORDER (₹${grandTotal})`}
            </Button>
            
            <div style={{ marginTop: '1.5rem', display: 'flex', flexDirection: 'column', gap: '0.75rem', color: 'var(--secondary-text)', fontSize: '0.85rem' }}>
              <div style={{ display: 'flex', alignItems: 'center', gap: '0.5rem' }}><FiShield color="var(--primary-green)"/> 100% Secure Payment</div>
              <div style={{ display: 'flex', alignItems: 'center', gap: '0.5rem' }}><FiTruck color="var(--primary-green)"/> Delivered in 2-4 business days</div>
            </div>
          </div>
        </div>

      </form>
    </div>
  );
};

// Simple styles for the forms
const labelStyle = {
  display: 'block',
  marginBottom: '0.5rem',
  fontSize: '0.9rem',
  fontWeight: '500',
  color: 'var(--dark-text)'
};

const inputStyle = {
  width: '100%',
  padding: '0.75rem 1rem',
  borderRadius: 'var(--radius-md)',
  border: '1px solid var(--border)',
  fontSize: '1rem',
  outline: 'none',
  transition: 'border-color 0.2s',
  fontFamily: 'inherit'
};

export default Checkout;
