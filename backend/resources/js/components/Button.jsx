import React from 'react';
import { motion } from 'framer-motion';

const Button = ({ 
  children, 
  variant = 'primary', 
  size = 'md', 
  onClick, 
  className = '',
  disabled = false,
  type = 'button',
  fullWidth = false,
  icon = null
}) => {
  const baseStyles = {
    display: 'inline-flex',
    alignItems: 'center',
    justifyContent: 'center',
    gap: '0.5rem',
    borderRadius: 'var(--radius-full)',
    fontWeight: '600',
    transition: 'all 0.3s ease',
    textDecoration: 'none',
    border: '2px solid transparent',
  };

  const variants = {
    primary: {
      backgroundColor: 'var(--primary-green)',
      color: 'var(--white)',
      borderColor: 'var(--primary-green)',
    },
    secondary: {
      backgroundColor: 'transparent',
      color: 'var(--primary-green)',
      borderColor: 'var(--primary-green)',
    },
    danger: {
      backgroundColor: 'var(--brand-red)',
      color: 'var(--white)',
      borderColor: 'var(--brand-red)',
    },
    outline: {
      backgroundColor: 'transparent',
      color: 'var(--dark-text)',
      borderColor: 'var(--border)',
    }
  };

  const sizes = {
    sm: { padding: '0.5rem 1rem', fontSize: '0.875rem' },
    md: { padding: '0.75rem 1.5rem', fontSize: '1rem' },
    lg: { padding: '1rem 2rem', fontSize: '1.125rem' },
  };

  const buttonStyle = {
    ...baseStyles,
    ...variants[variant],
    ...sizes[size],
    width: fullWidth ? '100%' : 'auto',
    opacity: disabled ? 0.6 : 1,
    cursor: disabled ? 'not-allowed' : 'pointer',
  };

  return (
    <motion.button
      type={type}
      style={buttonStyle}
      className={className}
      onClick={onClick}
      disabled={disabled}
      whileHover={disabled ? {} : { scale: 1.02, backgroundColor: variant === 'primary' ? 'var(--dark-green)' : undefined }}
      whileTap={disabled ? {} : { scale: 0.98 }}
    >
      {children}
      {icon && <span style={{ display: 'flex' }}>{icon}</span>}
    </motion.button>
  );
};

export default Button;
