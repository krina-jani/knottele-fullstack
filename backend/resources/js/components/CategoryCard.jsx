import React from 'react';
import { Link } from 'react-router-dom';
import { motion } from 'framer-motion';

const CategoryCard = ({ category }) => {
  // Hardcode the subtitle structure from the mockup based on category slug for exact match
  let subtitle1 = "";
  let subtitle2 = "";
  
  if (category.slug === 'nutrition-powder') {
    subtitle1 = "Chocolate | Kesar Pista";
    subtitle2 = "8 Sachets | 16 Sachets | 32 Sachets";
  } else if (category.slug === 'dry-fruits-seeds') {
    subtitle1 = "250g | 500g | 1kg";
  } else if (category.slug === 'seeds-mix') {
    subtitle1 = "250g | 500g";
  }

  return (
    <Link to={`/category/${category.slug}`} style={{ textDecoration: 'none', color: 'inherit' }}>
      <motion.div 
        whileHover={{ y: -15, scale: 1.03, rotateX: 2, rotateY: -2, boxShadow: '0 20px 40px rgba(0,0,0,0.1)' }}
        transition={{ type: 'spring', stiffness: 300, damping: 20 }}
        style={{
          backgroundColor: '#fdfbf7', // light cream background from mockup
          borderRadius: 'var(--radius-lg)',
          padding: '2rem 1.5rem',
          display: 'flex',
          flexDirection: 'column',
          alignItems: 'center',
          textAlign: 'center',
          height: '100%',
          border: '1px solid rgba(0,0,0,0.05)',
          boxShadow: '0 4px 15px rgba(0,0,0,0.03)',
          transformStyle: 'preserve-3d',
          perspective: '1000px'
        }}
      >
        <div style={{ height: '220px', width: '100%', marginBottom: '1.5rem', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
          <motion.img 
            whileHover={{ scale: 1.05 }}
            transition={{ duration: 0.3 }}
            src={category.image} 
            alt={category.name} 
            style={{ maxWidth: '100%', maxHeight: '100%', objectFit: 'contain', borderRadius: 'var(--radius-lg)' }} 
          />
        </div>
        
        <h3 style={{ fontSize: '1.1rem', fontWeight: 'bold', color: 'var(--dark-text)', marginBottom: '0.5rem', fontFamily: 'var(--font-display)' }}>
          {category.name}
        </h3>
        
        {subtitle1 && <p style={{ fontSize: '0.8rem', color: 'var(--dark-text)', margin: '0 0 0.25rem 0', fontWeight: '500' }}>{subtitle1}</p>}
        {subtitle2 && <p style={{ fontSize: '0.75rem', color: 'var(--secondary-text)', margin: '0 0 1rem 0' }}>{subtitle2}</p>}
        {!subtitle2 && <div style={{ marginBottom: '1rem' }}></div>}

        <div style={{ marginTop: 'auto' }}>
          <span style={{
            display: 'inline-block',
            backgroundColor: '#356d39',
            color: 'var(--white)',
            fontSize: '0.8rem',
            fontWeight: '600',
            padding: '0.5rem 1.25rem',
            borderRadius: '4px',
            textTransform: 'uppercase',
            letterSpacing: '0.5px'
          }}>
            EXPLORE
          </span>
        </div>
      </motion.div>
    </Link>
  );
};

export default CategoryCard;
