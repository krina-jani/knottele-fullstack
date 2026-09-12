import React from 'react';
import { FiMinus, FiPlus } from 'react-icons/fi';

const QuantitySelector = ({ quantity, setQuantity, min = 1, max = 99 }) => {
  const decrease = () => {
    if (quantity > min) setQuantity(quantity - 1);
  };

  const increase = () => {
    if (quantity < max) setQuantity(quantity + 1);
  };

  return (
    <div style={{
      display: 'inline-flex',
      alignItems: 'center',
      border: '1px solid var(--border)',
      borderRadius: 'var(--radius-sm)',
      overflow: 'hidden',
      backgroundColor: 'var(--white)'
    }}>
      <button 
        onClick={decrease}
        disabled={quantity <= min}
        style={{
          padding: '0.5rem 0.75rem',
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
          color: quantity <= min ? 'var(--border)' : 'var(--dark-text)',
          cursor: quantity <= min ? 'not-allowed' : 'pointer',
          backgroundColor: 'transparent',
          border: 'none'
        }}
      >
        <FiMinus />
      </button>
      
      <div style={{
        width: '40px',
        textAlign: 'center',
        fontWeight: '600',
        fontSize: '1rem',
        userSelect: 'none'
      }}>
        {quantity}
      </div>
      
      <button 
        onClick={increase}
        disabled={quantity >= max}
        style={{
          padding: '0.5rem 0.75rem',
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
          color: quantity >= max ? 'var(--border)' : 'var(--dark-text)',
          cursor: quantity >= max ? 'not-allowed' : 'pointer',
          backgroundColor: 'transparent',
          border: 'none'
        }}
      >
        <FiPlus />
      </button>
    </div>
  );
};

export default QuantitySelector;
