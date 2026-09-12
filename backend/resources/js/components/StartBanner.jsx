import React, { useState, useEffect } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { FiX } from 'react-icons/fi';
import { offerService } from '../services/offerService';
import Button from './Button';

const StartBanner = () => {
  const [bannerData, setBannerData] = useState(null);
  const [isVisible, setIsVisible] = useState(false);

  useEffect(() => {
    const fetchBanner = async () => {
      // Check if user has already closed the banner in this session
      const hasSeenBanner = sessionStorage.getItem('hasSeenStartBanner');
      if (hasSeenBanner) return;

      const data = await offerService.getStartBanner();
      if (data) {
        setBannerData(data);
        // Add a slight delay before showing the banner for better UX
        setTimeout(() => setIsVisible(true), 1500);
      }
    };
    
    fetchBanner();
  }, []);

  const handleClose = () => {
    setIsVisible(false);
    sessionStorage.setItem('hasSeenStartBanner', 'true');
  };

  if (!bannerData) return null;

  return (
    <AnimatePresence>
      {isVisible && (
        <div style={{
          position: 'fixed',
          top: 0, left: 0, right: 0, bottom: 0,
          backgroundColor: 'rgba(0,0,0,0.6)',
          zIndex: 9999,
          display: 'flex',
          justifyContent: 'center',
          alignItems: 'center',
          padding: '1rem'
        }}>
          <motion.div
            initial={{ opacity: 0, scale: 0.9, y: 20 }}
            animate={{ opacity: 1, scale: 1, y: 0 }}
            exit={{ opacity: 0, scale: 0.9, y: 20 }}
            style={{
              backgroundColor: 'var(--white)',
              borderRadius: '1rem',
              maxWidth: '500px',
              width: '100%',
              position: 'relative',
              overflow: 'hidden',
              boxShadow: '0 25px 50px -12px rgba(0, 0, 0, 0.25)'
            }}
          >
            <button
              onClick={handleClose}
              style={{
                position: 'absolute',
                top: '1rem',
                right: '1rem',
                background: 'rgba(255, 255, 255, 0.8)',
                border: 'none',
                borderRadius: '50%',
                width: '32px',
                height: '32px',
                display: 'flex',
                justifyContent: 'center',
                alignItems: 'center',
                cursor: 'pointer',
                zIndex: 10,
                color: 'var(--dark-text)'
              }}
            >
              <FiX size={20} />
            </button>

            {bannerData.banner_url && (
              <img 
                src={bannerData.banner_url} 
                alt={bannerData.name}
                style={{
                  width: '100%',
                  height: 'auto',
                  maxHeight: '300px',
                  objectFit: 'cover'
                }}
              />
            )}

            <div style={{ padding: '2rem', textAlign: 'center' }}>
              <h2 style={{ color: 'var(--dark-green)', marginBottom: '0.5rem' }}>{bannerData.name}</h2>
              <p style={{ color: 'var(--gray-text)', marginBottom: '1.5rem' }}>
                Use code <strong style={{ color: 'var(--brand-red)' }}>{bannerData.code}</strong> at checkout.
              </p>
              
              {bannerData.banner_button_link ? (
                <a href={bannerData.banner_button_link} style={{ textDecoration: 'none' }}>
                  <Button fullWidth onClick={handleClose}>
                    {bannerData.banner_button_text || 'Shop Now'}
                  </Button>
                </a>
              ) : (
                <Button fullWidth onClick={handleClose}>
                  {bannerData.banner_button_text || 'Continue Shopping'}
                </Button>
              )}
            </div>
          </motion.div>
        </div>
      )}
    </AnimatePresence>
  );
};

export default StartBanner;
