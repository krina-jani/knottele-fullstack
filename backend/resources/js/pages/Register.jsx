import React, { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { motion } from 'framer-motion';
import Button from '../components/Button';
import { useAuth } from '../context/AuthContext';
import logo from '../assets/logo-cropped.png';

const Register = () => {
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    mobile: '',
    password: '',
    confirmPassword: '',
    terms: false
  });
  const [error, setError] = useState('');
  const { register, isAuthenticated } = useAuth();
  const navigate = useNavigate();

  React.useEffect(() => {
    if (isAuthenticated) {
      navigate('/account');
    }
  }, [isAuthenticated, navigate]);

  const handleChange = (e) => {
    const { name, value, type, checked } = e.target;
    if (name === 'mobile') {
      const numericValue = value.replace(/\D/g, '').slice(0, 10);
      setFormData(prev => ({
        ...prev,
        [name]: numericValue
      }));
    } else {
      setFormData(prev => ({
        ...prev,
        [name]: type === 'checkbox' ? checked : value
      }));
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    
    if (!formData.name || !formData.email || !formData.mobile || !formData.password || !formData.confirmPassword) {
      setError('Please fill in all required fields.');
      return;
    }
    
    if (formData.password !== formData.confirmPassword) {
      setError('Passwords do not match.');
      return;
    }

    if (!formData.terms) {
      setError('You must agree to the Terms & Conditions.');
      return;
    }

    // Format Validations
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(formData.email)) {
      setError('Please enter a valid email address containing an "@".');
      return;
    }

    // Mobile validation: allows optional +91, spaces, dashes, but must have exactly 10 core digits
    const cleanedMobile = formData.mobile.replace(/[\s\-\+91]/g, ''); // strip formatting and +91 for length check
    // Wait, replacing +91 like this might remove 9s and 1s from the actual number.
    // Better logic: strip non-digits. If starts with 91 and length is 12, strip 91. Check if length is exactly 10.
    const digitsOnly = formData.mobile.replace(/\D/g, '');
    let coreMobile = digitsOnly;
    if (digitsOnly.length > 10 && digitsOnly.startsWith('91')) {
      coreMobile = digitsOnly.substring(2);
    }
    
    if (coreMobile.length !== 10) {
      setError('Please enter a valid 10-digit mobile number.');
      return;
    }

    // Remove local storage logic
    // const existingUsers = JSON.parse(localStorage.getItem('registeredUsers') || '[]');
    
    // Normalize inputs for strict comparison
    const userData = {
      name: formData.name,
      email: formData.email,
      mobile: coreMobile,
      password: formData.password
    };

    const result = await register(userData);
    
    if (result.success) {
      navigate('/account');
    } else {
      setError(result.error || 'Registration failed. Please try again.');
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
          maxWidth: '550px' 
        }}
      >
        <div style={{ textAlign: 'center', marginBottom: '2rem' }}>
          <div style={{ display: 'flex', justifyContent: 'center', marginBottom: '1.5rem' }}>
            <img src={logo} alt="Nuts & Nutrition" style={{ height: '70px', objectFit: 'contain' }} />
          </div>
          <h2 style={{ fontSize: '1.5rem', color: 'var(--dark-green)' }}>Create Account</h2>
          <p style={{ color: 'var(--secondary-text)', fontSize: '0.9rem' }}>Join us for a healthier lifestyle.</p>
        </div>

        {error && (
          <div style={{ backgroundColor: 'var(--light-red)', color: 'var(--brand-red)', padding: '0.75rem', borderRadius: 'var(--radius-sm)', marginBottom: '1.5rem', fontSize: '0.9rem' }}>
            {error}
          </div>
        )}

        <style>{`
          .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.25rem;
          }
          @media (max-width: 600px) {
            .form-grid {
              grid-template-columns: 1fr;
            }
          }
        `}</style>

        <form onSubmit={handleSubmit} style={{ display: 'flex', flexDirection: 'column', gap: '1.25rem' }}>
          <div>
            <label style={labelStyle}>Full Name <span style={{ color: '#d32f2f' }}>*</span></label>
            <input 
              type="text" name="name" value={formData.name} onChange={handleChange} required
              placeholder="John Doe" style={inputStyle} 
            />
          </div>
          
          <div className="form-grid">
            <div>
              <label style={labelStyle}>Email Address <span style={{ color: '#d32f2f' }}>*</span></label>
              <input 
                type="email" name="email" value={formData.email} onChange={handleChange} required
                placeholder="john@example.com" style={inputStyle} 
              />
            </div>
            <div>
              <label style={labelStyle}>Mobile Number <span style={{ color: '#d32f2f' }}>*</span></label>
              <input 
                type="tel" name="mobile" value={formData.mobile} onChange={handleChange} required
                placeholder="9876543210" style={inputStyle} maxLength="10" pattern="\d{10}" title="Please enter exactly 10 digits"
              />
            </div>
          </div>

          <div className="form-grid">
            <div>
              <label style={labelStyle}>Password <span style={{ color: '#d32f2f' }}>*</span></label>
              <input 
                type="password" name="password" value={formData.password} onChange={handleChange} required
                placeholder="Min. 6 characters" style={inputStyle} minLength="6"
              />
            </div>
            <div>
              <label style={labelStyle}>Confirm Password <span style={{ color: '#d32f2f' }}>*</span></label>
              <input 
                type="password" name="confirmPassword" value={formData.confirmPassword} onChange={handleChange} required
                placeholder="Retype password" style={inputStyle} minLength="6"
              />
            </div>
          </div>

          <div style={{ display: 'flex', alignItems: 'flex-start', gap: '0.5rem', marginTop: '0.5rem' }}>
            <input 
              type="checkbox" name="terms" id="terms" checked={formData.terms} onChange={handleChange}
              style={{ width: '16px', height: '16px', accentColor: 'var(--primary-green)', marginTop: '2px' }} 
            />
            <label htmlFor="terms" style={{ fontSize: '0.85rem', color: 'var(--secondary-text)', cursor: 'pointer', lineHeight: 1.4 }}>
              I agree to the <a href="#" style={{ color: 'var(--primary-green)' }}>Terms & Conditions</a> and <a href="#" style={{ color: 'var(--primary-green)' }}>Privacy Policy</a>.
            </label>
          </div>

          <Button type="submit" variant="primary" fullWidth size="lg" style={{ marginTop: '1rem' }}>CREATE ACCOUNT</Button>
        </form>

        <div style={{ textAlign: 'center', marginTop: '2rem', fontSize: '0.9rem' }}>
          <span style={{ color: 'var(--secondary-text)' }}>Already have an account? </span>
          <Link to="/login" style={{ color: 'var(--primary-green)', fontWeight: '600' }}>Login</Link>
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

export default Register;
