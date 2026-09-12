import React, { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { motion } from 'framer-motion';
import Button from '../components/Button';
import { useAuth } from '../context/AuthContext';
import logo from '../assets/logo-cropped.png';

const Login = () => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const { login, isAuthenticated } = useAuth();
  const navigate = useNavigate();

  React.useEffect(() => {
    if (isAuthenticated) {
      navigate('/account');
    }
  }, [isAuthenticated, navigate]);

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!email || !password) {
      setError('Please fill in all fields.');
      return;
    }
    
    const result = await login(email, password);
    if (result.success) {
      navigate('/account');
    } else {
      setError(result.error || 'Login failed. Please check your credentials.');
    }
  };

  return (
    <div style={{ backgroundColor: 'var(--soft-green)', minHeight: '80vh', display: 'flex', alignItems: 'center', justifyContent: 'center', padding: '4rem 1rem' }}>
      <motion.div 
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
        style={{ 
          backgroundColor: 'var(--white)', 
          padding: '3rem', 
          borderRadius: 'var(--radius-lg)', 
          boxShadow: 'var(--shadow-md)', 
          width: '100%', 
          maxWidth: '450px' 
        }}
      >
        <div style={{ textAlign: 'center', marginBottom: '2rem' }}>
          <div style={{ display: 'flex', justifyContent: 'center', marginBottom: '1.5rem' }}>
            <img src={logo} alt="Nuts & Nutrition" style={{ height: '70px', objectFit: 'contain' }} />
          </div>
          <h2 style={{ fontSize: '1.5rem', color: 'var(--dark-green)' }}>Welcome Back</h2>
          <p style={{ color: 'var(--secondary-text)', fontSize: '0.9rem' }}>Please enter your details to sign in.</p>
        </div>

        {error && (
          <div style={{ backgroundColor: 'var(--light-red)', color: 'var(--brand-red)', padding: '0.75rem', borderRadius: 'var(--radius-sm)', marginBottom: '1.5rem', fontSize: '0.9rem' }}>
            {error}
          </div>
        )}

        <form onSubmit={handleSubmit} style={{ display: 'flex', flexDirection: 'column', gap: '1.5rem' }}>
          <div>
            <label style={labelStyle}>Email Address</label>
            <input 
              type="email" 
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              placeholder="Enter your email" 
              style={inputStyle} 
            />
          </div>
          
          <div>
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '0.5rem' }}>
              <label style={{ ...labelStyle, marginBottom: 0 }}>Password</label>
              <a href="#" style={{ fontSize: '0.85rem', color: 'var(--primary-green)' }}>Forgot Password?</a>
            </div>
            <input 
              type="password" 
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              placeholder="Enter your password" 
              style={inputStyle} 
            />
          </div>

          <div style={{ display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
            <input type="checkbox" id="remember" style={{ width: '16px', height: '16px', accentColor: 'var(--primary-green)' }} />
            <label htmlFor="remember" style={{ fontSize: '0.9rem', color: 'var(--dark-text)', cursor: 'pointer' }}>Remember Me</label>
          </div>

          <Button type="submit" variant="primary" fullWidth size="lg">LOGIN</Button>
        </form>

        <div style={{ textAlign: 'center', marginTop: '2rem', fontSize: '0.9rem' }}>
          <span style={{ color: 'var(--secondary-text)' }}>Don't have an account? </span>
          <Link to="/register" style={{ color: 'var(--primary-green)', fontWeight: '600' }}>Create Account</Link>
        </div>
      </motion.div>
    </div>
  );
};

const labelStyle = {
  display: 'block',
  marginBottom: '0.5rem',
  fontSize: '0.9rem',
  fontWeight: '500',
  color: 'var(--dark-text)'
};

const inputStyle = {
  width: '100%',
  padding: '0.85rem 1rem',
  borderRadius: 'var(--radius-md)',
  border: '1px solid var(--border)',
  fontSize: '1rem',
  outline: 'none',
  transition: 'border-color 0.2s',
  fontFamily: 'inherit'
};

export default Login;
