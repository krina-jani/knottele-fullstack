import React from 'react';
import { Link } from 'react-router-dom';
import { motion } from 'framer-motion';
import Button from '../components/Button';

const NotFound = () => {
  return (
    <div className="container" style={{ padding: '8rem 1rem', textAlign: 'center', minHeight: '60vh', display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center' }}>
      <motion.div
        initial={{ opacity: 0, scale: 0.8 }}
        animate={{ opacity: 1, scale: 1 }}
        transition={{ duration: 0.5 }}
      >
        <h1 style={{ fontSize: '8rem', margin: 0, color: 'var(--soft-green)', lineHeight: 1, textShadow: '0 4px 10px rgba(0,0,0,0.05)' }}>
          404
        </h1>
        <h2 style={{ fontSize: '2rem', color: 'var(--dark-green)', marginBottom: '1rem', marginTop: '-2rem', position: 'relative', zIndex: 2 }}>
          Oops! Page Not Found
        </h2>
        <p style={{ fontSize: '1.1rem', color: 'var(--secondary-text)', marginBottom: '2.5rem', maxWidth: '400px', margin: '0 auto 2.5rem auto' }}>
          Looks like this page took a wrong turn or doesn't exist anymore. Let's get you back on track to healthier choices.
        </p>
        
        <Link to="/" style={{ textDecoration: 'none' }}>
          <Button variant="primary" size="lg">BACK TO HOME</Button>
        </Link>
      </motion.div>
    </div>
  );
};

export default NotFound;
