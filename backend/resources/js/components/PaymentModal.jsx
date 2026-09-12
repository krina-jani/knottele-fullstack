import React, { useState, useEffect } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { FiCheckCircle, FiX, FiShield, FiSmartphone } from 'react-icons/fi';

const PaymentModal = ({ isOpen, onClose, onSuccess, amount, orderId, paymentMethod }) => {
  const [paymentStatus, setPaymentStatus] = useState('pending'); // pending, processing, success, failed

  // Reset status when opened
  useEffect(() => {
    if (isOpen) {
      setPaymentStatus('pending');
      
      // Auto process non-UPI payments after a delay
      if (paymentMethod === 'card' || paymentMethod === 'netbanking') {
        setPaymentStatus('processing');
        setTimeout(() => {
          setPaymentStatus('success');
          setTimeout(() => {
            onSuccess();
          }, 1500);
        }, 3000);
      }
    }
  }, [isOpen, paymentMethod, onSuccess]);

  if (!isOpen) return null;

  const upiUrl = `upi://pay?pa=nutsnutrition@upi&pn=Nuts%20and%20Nutrition&tr=${orderId}&am=${amount}&cu=INR`;
  const qrCodeUrl = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(upiUrl)}`;

  const handleSimulateSuccess = () => {
    setPaymentStatus('processing');
    setTimeout(() => {
      setPaymentStatus('success');
      setTimeout(() => {
        onSuccess();
      }, 1000);
    }, 1500);
  };

  return (
    <AnimatePresence>
      <div style={{
        position: 'fixed',
        top: 0, left: 0, right: 0, bottom: 0,
        backgroundColor: 'rgba(0, 0, 0, 0.75)',
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center',
        zIndex: 9999,
        padding: '1rem',
        backdropFilter: 'blur(4px)'
      }}>
        <motion.div 
          initial={{ opacity: 0, scale: 0.9, y: 20 }}
          animate={{ opacity: 1, scale: 1, y: 0 }}
          exit={{ opacity: 0, scale: 0.9, y: 20 }}
          style={{
            backgroundColor: 'white',
            borderRadius: '12px',
            width: '100%',
            maxWidth: '420px',
            overflow: 'hidden',
            boxShadow: '0 25px 50px -12px rgba(0, 0, 0, 0.25)',
            position: 'relative'
          }}
        >
          {/* Header */}
          <div style={{ backgroundColor: '#1A202C', color: 'white', padding: '1.5rem', display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
            <div style={{ display: 'flex', alignItems: 'center', gap: '0.75rem' }}>
              <div style={{ backgroundColor: 'white', color: '#1A202C', padding: '0.25rem 0.5rem', borderRadius: '4px', fontWeight: 'bold', fontSize: '0.8rem' }}>NN</div>
              <div>
                <div style={{ fontWeight: '500' }}>Nuts & Nutrition</div>
                <div style={{ fontSize: '0.8rem', opacity: 0.8 }}>Order #{orderId}</div>
              </div>
            </div>
            {paymentStatus !== 'success' && (
              <button onClick={onClose} aria-label="Close Payment Modal" style={{ background: 'none', border: 'none', color: 'white', cursor: 'pointer', opacity: 0.7 }}>
                <FiX size={24} />
              </button>
            )}
          </div>

          {/* Amount Bar */}
          <div style={{ backgroundColor: '#2D3748', color: 'white', padding: '1rem 1.5rem', display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
            <div style={{ fontSize: '0.9rem', opacity: 0.9 }}>Amount Payable</div>
            <div style={{ fontSize: '1.25rem', fontWeight: 'bold' }}>₹{amount}</div>
          </div>

          {/* Body */}
          <div style={{ padding: '2rem 1.5rem', minHeight: '320px', display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center' }}>
            
            {paymentStatus === 'processing' && (
              <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: '1rem' }}>
                <div style={{ 
                  width: '40px', height: '40px', 
                  border: '3px solid #f3f3f3', borderTop: '3px solid #68B348', 
                  borderRadius: '50%', animation: 'spin 1s linear infinite' 
                }} />
                <style>{`@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }`}</style>
                <div style={{ color: '#4A5568', fontWeight: '500' }}>Processing Payment...</div>
                <div style={{ fontSize: '0.8rem', color: '#A0AEC0', textAlign: 'center' }}>Please do not close this window or press back.</div>
              </div>
            )}

            {paymentStatus === 'success' && (
              <motion.div initial={{ scale: 0.8 }} animate={{ scale: 1 }} style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: '1rem' }}>
                <FiCheckCircle size={64} color="#68B348" />
                <div style={{ color: '#2D3748', fontWeight: 'bold', fontSize: '1.2rem' }}>Payment Successful</div>
                <div style={{ fontSize: '0.9rem', color: '#718096' }}>Redirecting to confirmation...</div>
              </motion.div>
            )}

            {paymentStatus === 'pending' && paymentMethod === 'upi' && (
              <div style={{ width: '100%', display: 'flex', flexDirection: 'column', alignItems: 'center' }}>
                <h3 style={{ fontSize: '1.1rem', marginBottom: '1.5rem', color: '#2D3748' }}>Pay via UPI</h3>
                
                {/* QR Code */}
                <div style={{ padding: '1rem', border: '1px solid #E2E8F0', borderRadius: '8px', marginBottom: '1.5rem' }}>
                  <img src={qrCodeUrl} alt="UPI QR Code" style={{ width: '160px', height: '160px' }} />
                </div>
                <p style={{ fontSize: '0.85rem', color: '#718096', marginBottom: '1.5rem', textAlign: 'center' }}>
                  Scan QR with any UPI app on your phone (GPay, PhonePe, Paytm, etc.)
                </p>

                {/* Mobile Button */}
                <button 
                  onClick={() => {
                    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                      window.location.href = upiUrl;
                    }
                    handleSimulateSuccess();
                  }}
                  style={{
                    display: 'flex', alignItems: 'center', justifyContent: 'center', gap: '0.5rem',
                    width: '100%', padding: '0.875rem', backgroundColor: '#68B348', color: 'white',
                    borderRadius: '6px', border: 'none', cursor: 'pointer', fontWeight: 'bold', marginBottom: '1rem',
                    fontSize: '1rem'
                  }}
                >
                  <FiSmartphone size={18} /> Open UPI App
                </button>
                
                <button 
                  onClick={handleSimulateSuccess}
                  style={{
                    background: 'none', border: 'none', color: '#3182CE', fontSize: '0.85rem', cursor: 'pointer', textDecoration: 'underline'
                  }}
                >
                  (Test Mode: Simulate Success)
                </button>
              </div>
            )}
            
            {paymentStatus === 'pending' && paymentMethod !== 'upi' && (
              <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: '1rem' }}>
                <div style={{ 
                  width: '40px', height: '40px', 
                  border: '3px solid #f3f3f3', borderTop: '3px solid #68B348', 
                  borderRadius: '50%', animation: 'spin 1s linear infinite' 
                }} />
                <div style={{ color: '#4A5568', fontWeight: '500' }}>Waiting for bank...</div>
              </div>
            )}
            
          </div>
          
          {/* Footer */}
          <div style={{ backgroundColor: '#F7FAFC', borderTop: '1px solid #E2E8F0', padding: '1rem', display: 'flex', justifyContent: 'center', alignItems: 'center', gap: '0.5rem', color: '#A0AEC0', fontSize: '0.75rem' }}>
            <FiShield size={14} /> Secured by Razorpay Mockup
          </div>
        </motion.div>
      </div>
    </AnimatePresence>
  );
};

export default PaymentModal;
