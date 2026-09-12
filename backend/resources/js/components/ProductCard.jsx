import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import { motion } from 'framer-motion';
import { FiHeart, FiShoppingCart, FiCheck } from 'react-icons/fi';
import { useCart } from '../context/CartContext';
import { useWishlist } from '../context/WishlistContext';

const ProductCard = ({ product }) => {
  const { addToCart, cartItems } = useCart();
  const { toggleWishlist, isInWishlist } = useWishlist();
  const [isHovered, setIsHovered] = useState(false);
  const [selectedVariant, setSelectedVariant] = useState(product.variants[0]);

  // Check if the currently selected variant is in the cart
  const isAdded = cartItems.some(item => item.id === product.id && item.variant.sku === selectedVariant.sku);

  
  // Determine badge text
  let badgeText = null;
  let badgeColor = 'var(--brand-red)';
  if (product.bestseller) {
    badgeText = 'BESTSELLER';
  } else if (product.featured) {
    badgeText = 'POPULAR';
  } else {
    badgeText = 'NEW';
    badgeColor = 'var(--primary-green)';
  }

  return (
    <motion.div 
      whileHover={{ y: -10, scale: 1.02, rotateX: 1, rotateY: -1, boxShadow: '0 15px 35px rgba(0,0,0,0.08)' }}
      transition={{ type: 'spring', stiffness: 300, damping: 20 }}
      style={{
        backgroundColor: 'var(--white)',
        borderRadius: 'var(--radius-lg)',
        border: '1px solid var(--border)',
        overflow: 'hidden',
        display: 'flex',
        flexDirection: 'column',
        position: 'relative',
        height: '100%',
        boxShadow: 'var(--shadow-sm)',
        transformStyle: 'preserve-3d',
        perspective: '1000px'
      }}
    >
      {/* Badges */}
      {badgeText && (
        <div style={{
          position: 'absolute',
          top: '1rem',
          left: '1rem',
          backgroundColor: badgeColor,
          color: 'var(--white)',
          fontSize: '0.65rem',
          fontWeight: 'bold',
          padding: '4px 8px',
          borderRadius: '4px',
          zIndex: 10,
          textTransform: 'uppercase',
          letterSpacing: '0.5px'
        }}>
          {badgeText}
        </div>
      )}

      {/* Wishlist Button */}
      <button 
        onClick={(e) => { e.preventDefault(); toggleWishlist(product); }}
        style={{
          position: 'absolute',
          top: '1rem',
          right: '1rem',
          backgroundColor: 'transparent',
          border: 'none',
          color: isInWishlist(product.id) ? 'var(--brand-red)' : 'var(--secondary-text)',
          cursor: 'pointer',
          zIndex: 10,
          padding: '4px'
        }}
      >
        <FiHeart size={20} fill={isInWishlist(product.id) ? 'var(--brand-red)' : 'none'} />
      </button>

      {/* Image */}
      <Link to={`/product/${product.slug}`} style={{ textDecoration: 'none', color: 'inherit', display: 'flex', flexDirection: 'column', flex: 1 }}>
        <div 
          style={{ height: '220px', width: '100%', overflow: 'hidden', backgroundColor: '#fcfcfc', borderBottom: '1px solid var(--border)', display: 'flex', justifyContent: 'center', alignItems: 'center', position: 'relative' }}
          onMouseEnter={() => setIsHovered(true)}
          onMouseLeave={() => setIsHovered(false)}
        >
          <motion.img 
            animate={{ scale: isHovered ? 1.05 : 1 }}
            transition={{ duration: 0.3 }}
            src={product.images[0]} 
            alt={product.name} 
            style={{ width: '100%', height: '100%', objectFit: 'contain', padding: '1rem', position: 'absolute', top: 0, left: 0 }}
          />
        </div>

        {/* Content */}
        <div style={{ flex: 1, display: 'flex', flexDirection: 'column', alignItems: 'center', textAlign: 'center', padding: '1.25rem' }}>
          {product.brand && (
            <span style={{ fontSize: '0.75rem', textTransform: 'uppercase', color: 'var(--secondary-text)', letterSpacing: '1px', marginBottom: '0.25rem', fontWeight: '600' }}>
              {product.brand.name}
            </span>
          )}
          <h3 style={{ fontSize: '1rem', marginBottom: '0.5rem', lineHeight: 1.3, fontWeight: '700', color: 'var(--dark-text)', display: '-webkit-box', WebkitLineClamp: 2, WebkitBoxOrient: 'vertical', overflow: 'hidden' }}>
            {product.name}
          </h3>
          
          <div style={{ display: 'flex', alignItems: 'center', gap: '0.25rem', marginBottom: '0.75rem', color: '#ffb800', fontSize: '0.8rem' }}>
            {"★★★★★".split('').map((star, i) => <span key={i}>{star}</span>)}
            {product.reviews ? <span style={{ color: 'var(--secondary-text)', fontSize: '0.75rem', marginLeft: '2px' }}>({product.reviews})</span> : null}
          </div>

          <div style={{ fontSize: '0.85rem', color: 'var(--secondary-text)', marginBottom: '0.75rem' }}>
            <span style={{ fontSize: '1.1rem', fontWeight: 'bold', color: 'var(--dark-text)' }}>₹{selectedVariant.price}</span>
          </div>

          {/* Variant Pills */}
          <div style={{ display: 'flex', gap: '0.25rem', flexWrap: 'wrap', justifyContent: 'center', marginBottom: '1rem', marginTop: 'auto' }}>
            {product.variants.slice(0,3).map(v => (
              <button 
                key={v.sku} 
                onClick={(e) => { e.preventDefault(); setSelectedVariant(v); }}
                style={{ 
                  fontSize: '0.65rem', 
                  padding: '4px 8px', 
                  border: selectedVariant.sku === v.sku ? '1px solid var(--primary-color)' : '1px solid var(--border)', 
                  backgroundColor: selectedVariant.sku === v.sku ? 'rgba(46, 125, 50, 0.05)' : 'transparent',
                  color: selectedVariant.sku === v.sku ? 'var(--primary-color)' : 'var(--secondary-text)',
                  borderRadius: '4px', 
                  cursor: 'pointer',
                  transition: 'all 0.2s',
                  fontWeight: selectedVariant.sku === v.sku ? '600' : '400'
                }}>
                {v.size}
              </button>
            ))}
          </div>

          {/* Add to Cart Button */}
          <motion.button 
            whileHover={!isAdded ? { scale: 1.02 } : {}}
            whileTap={!isAdded ? { scale: 0.98 } : {}}
            onClick={(e) => { 
              e.preventDefault(); 
              if (!isAdded) {
                addToCart(product, selectedVariant, 1);
              }
            }}
            style={{
              width: '100%',
              backgroundColor: isAdded ? '#E52F36' : '#68B348', // red : light green
              color: 'white',
              border: 'none',
              padding: '0.8rem',
              borderRadius: '12px',
              fontWeight: '600',
              fontSize: '0.9rem',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              gap: '0.5rem',
              cursor: isAdded ? 'default' : 'pointer',
              transition: 'background-color 0.3s, box-shadow 0.3s',
              boxShadow: isAdded ? 'none' : '0 8px 15px rgba(104, 179, 72, 0.2)'
            }}
            onMouseOver={(e) => !isAdded && (e.currentTarget.style.backgroundColor = '#5ca040')} // slightly darker green on hover
            onMouseOut={(e) => !isAdded && (e.currentTarget.style.backgroundColor = '#68B348')} // back to light green
          >
            {isAdded ? (
              <>
                <FiCheck size={18} /> ADDED!
              </>
            ) : (
              <>
                <FiShoppingCart size={16} /> ADD TO CART
              </>
            )}
          </motion.button>
        </div>
      </Link>
    </motion.div>
  );
};

export default ProductCard;
