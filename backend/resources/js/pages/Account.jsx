import React, { useState, useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { FiUser, FiPackage, FiHeart, FiMapPin, FiLogOut } from 'react-icons/fi';
import { useAuth } from '../context/AuthContext';
import Button from '../components/Button';

const Account = () => {
  const { user, isAuthenticated, logout, updateProfile } = useAuth();
  const navigate = useNavigate();
  
  const [isEditing, setIsEditing] = useState(false);
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    mobile: ''
  });

  useEffect(() => {
    if (user) {
      setFormData({
        name: user.name || '',
        email: user.email || '',
        mobile: user.mobile || '+91 9876543210'
      });
    }
  }, [user]);

  if (!isAuthenticated) {
    return (
      <div className="container" style={{ padding: '5rem 0', textAlign: 'center' }}>
        <h2>Please login to view your account.</h2>
        <Button onClick={() => navigate('/login')} style={{ marginTop: '1rem' }}>Login</Button>
      </div>
    );
  }

  const handleLogout = () => {
    logout();
    navigate('/');
  };

  const handleSave = () => {
    updateProfile(formData);
    setIsEditing(false);
  };

  const handleChange = (e) => {
    const { name, value } = e.target;
    if (name === 'mobile') {
      const numericValue = value.replace(/\D/g, '').slice(0, 10);
      setFormData({ ...formData, [name]: numericValue });
    } else {
      setFormData({ ...formData, [name]: value });
    }
  };

  return (
    <div className="container" style={{ paddingTop: '3rem', paddingBottom: '5rem' }}>
      <h1 style={{ marginBottom: '2rem', color: 'var(--dark-green)' }}>My Account</h1>
      
      <div style={{ display: 'flex', gap: '2rem', flexWrap: 'wrap', alignItems: 'flex-start' }}>
        
        {/* Sidebar */}
        <aside style={{ flex: '1 1 250px', backgroundColor: 'var(--white)', borderRadius: 'var(--radius-lg)', border: '1px solid var(--border)', padding: '1.5rem' }}>
          <div style={{ paddingBottom: '1.5rem', borderBottom: '1px solid var(--border)', marginBottom: '1.5rem' }}>
            <h3 style={{ margin: 0 }}>{user?.name}</h3>
            <p style={{ margin: 0, color: 'var(--secondary-text)', fontSize: '0.9rem' }}>{user?.email}</p>
          </div>
          
          <nav style={{ display: 'flex', flexDirection: 'column', gap: '0.5rem' }}>
            <Link to="/account" style={activeLinkStyle}><FiUser /> Profile</Link>
            <Link to="/orders" style={linkStyle}><FiPackage /> Orders</Link>
            <Link to="/wishlist" style={linkStyle}><FiHeart /> Wishlist</Link>
            <Link to="/addresses" style={linkStyle}><FiMapPin /> Addresses</Link>
            <button onClick={handleLogout} style={{ ...linkStyle, color: 'var(--brand-red)', marginTop: '1rem', borderTop: '1px solid var(--border)', paddingTop: '1rem', backgroundColor: 'transparent', border: 'none', width: '100%', textAlign: 'left', cursor: 'pointer' }}>
              <FiLogOut /> Logout
            </button>
          </nav>
        </aside>

        {/* Main Content */}
        <div style={{ flex: '1 1 600px', backgroundColor: 'var(--white)', borderRadius: 'var(--radius-lg)', border: '1px solid var(--border)', padding: '2rem' }}>
          <h2 style={{ marginBottom: '1.5rem', fontSize: '1.5rem', color: 'var(--dark-green)' }}>Profile Details</h2>
          
          <style>{`
            .profile-grid {
              display: grid;
              grid-template-columns: 1fr 1fr;
              gap: 1.5rem;
            }
            @media (max-width: 600px) {
              .profile-grid {
                grid-template-columns: 1fr;
              }
            }
          `}</style>

          <div className="profile-grid">
            <div>
              <label style={labelStyle}>Full Name</label>
              <input type="text" name="name" value={formData.name} onChange={handleChange} readOnly={!isEditing} style={{...inputStyle, backgroundColor: isEditing ? 'white' : '#f9f9f9', border: isEditing ? '1px solid var(--primary-green)' : '1px solid var(--border)'}} />
            </div>
            <div>
              <label style={labelStyle}>Email Address</label>
              <input type="email" name="email" value={formData.email} onChange={handleChange} readOnly={!isEditing} style={{...inputStyle, backgroundColor: isEditing ? 'white' : '#f9f9f9', border: isEditing ? '1px solid var(--primary-green)' : '1px solid var(--border)'}} />
            </div>
            <div>
              <label style={labelStyle}>Mobile Number</label>
              <input type="tel" name="mobile" value={formData.mobile} onChange={handleChange} readOnly={!isEditing} maxLength="10" pattern="\d{10}" title="Please enter exactly 10 digits" style={{...inputStyle, backgroundColor: isEditing ? 'white' : '#f9f9f9', border: isEditing ? '1px solid var(--primary-green)' : '1px solid var(--border)'}} />
            </div>
          </div>
          
          <div style={{ marginTop: '2rem', display: 'flex', gap: '1rem' }}>
            {isEditing ? (
              <>
                <Button variant="primary" onClick={handleSave}>Save Changes</Button>
                <Button variant="outline" onClick={() => {
                  setIsEditing(false);
                  setFormData({ name: user?.name || '', email: user?.email || '', mobile: user?.mobile || '+91 9876543210' });
                }}>Cancel</Button>
              </>
            ) : (
              <Button variant="outline" onClick={() => setIsEditing(true)}>Edit Profile</Button>
            )}
          </div>
        </div>

      </div>
    </div>
  );
};

const linkStyle = {
  display: 'flex',
  alignItems: 'center',
  gap: '0.75rem',
  padding: '0.75rem 1rem',
  textDecoration: 'none',
  color: 'var(--dark-text)',
  borderRadius: 'var(--radius-sm)',
  transition: 'background-color 0.2s',
  fontWeight: '500'
};

const activeLinkStyle = {
  ...linkStyle,
  backgroundColor: 'var(--soft-green)',
  color: 'var(--primary-green)',
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
  padding: '0.75rem 1rem',
  borderRadius: 'var(--radius-md)',
  border: '1px solid var(--border)',
  fontSize: '1rem',
  outline: 'none',
  backgroundColor: '#f9f9f9',
  color: 'var(--secondary-text)',
  fontFamily: 'inherit'
};

export default Account;
