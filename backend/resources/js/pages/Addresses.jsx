import React, { useState, useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { FiUser, FiPackage, FiHeart, FiMapPin, FiLogOut, FiEdit2, FiTrash2, FiPlus, FiSave, FiX } from 'react-icons/fi';
import { useAuth } from '../context/AuthContext';
import Button from '../components/Button';

const Addresses = () => {
  const { user, isAuthenticated, logout } = useAuth();
  const navigate = useNavigate();

  const defaultAddresses = [
    {
      id: 1,
      type: 'Home',
      line1: '123 Healthy Street, Green City Appts.',
      line2: 'Mumbai, Maharashtra - 400001',
      country: 'India',
      mobile: '+91 9876543210',
      isDefault: true
    },
    {
      id: 2,
      type: 'Office',
      line1: 'Tech Park Phase 2, Floor 5, Block B.',
      line2: 'Pune, Maharashtra - 411057',
      country: 'India',
      mobile: '+91 9876543210',
      isDefault: false
    }
  ];

  const [addresses, setAddresses] = useState(() => {
    const saved = localStorage.getItem('nn_addresses');
    if (saved) {
      try {
        return JSON.parse(saved);
      } catch (e) {
        return defaultAddresses;
      }
    }
    return defaultAddresses;
  });

  useEffect(() => {
    localStorage.setItem('nn_addresses', JSON.stringify(addresses));
  }, [addresses]);

  const [editingId, setEditingId] = useState(null);
  const [formData, setFormData] = useState(null);

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

  const handleAdd = () => {
    const newId = Date.now();
    setEditingId(newId);
    setFormData({ id: newId, type: 'New Address', line1: '', line2: '', country: 'India', mobile: '', isDefault: addresses.length === 0 });
  };

  const handleEdit = (address) => {
    setEditingId(address.id);
    setFormData({ ...address });
  };

  const handleDelete = (id) => {
    setAddresses(addresses.filter(a => a.id !== id));
  };

  const handleSetDefault = (id) => {
    setAddresses(addresses.map(a => ({
      ...a,
      isDefault: a.id === id
    })));
  };

  const handleSave = () => {
    if (!formData.line1 || !formData.mobile) return; // Simple validation
    
    if (addresses.some(a => a.id === formData.id)) {
      // Update existing
      setAddresses(addresses.map(a => a.id === formData.id ? formData : a));
    } else {
      // Add new
      setAddresses([...addresses, formData]);
    }
    setEditingId(null);
    setFormData(null);
  };

  const handleCancel = () => {
    setEditingId(null);
    setFormData(null);
  };

  return (
    <div className="container" style={{ paddingTop: '3rem', paddingBottom: '5rem' }}>
      <h1 style={{ marginBottom: '2rem', color: 'var(--dark-green)' }}>My Addresses</h1>
      
      <div style={{ display: 'flex', gap: '2rem', flexWrap: 'wrap', alignItems: 'flex-start' }}>
        
        {/* Sidebar */}
        <aside style={{ flex: '1 1 250px', backgroundColor: 'var(--white)', borderRadius: 'var(--radius-lg)', border: '1px solid var(--border)', padding: '1.5rem' }}>
          <div style={{ paddingBottom: '1.5rem', borderBottom: '1px solid var(--border)', marginBottom: '1.5rem' }}>
            <h3 style={{ margin: 0 }}>{user?.name}</h3>
            <p style={{ margin: 0, color: 'var(--secondary-text)', fontSize: '0.9rem' }}>{user?.email}</p>
          </div>
          
          <nav style={{ display: 'flex', flexDirection: 'column', gap: '0.5rem' }}>
            <Link to="/account" style={linkStyle}><FiUser /> Profile</Link>
            <Link to="/orders" style={linkStyle}><FiPackage /> Orders</Link>
            <Link to="/wishlist" style={linkStyle}><FiHeart /> Wishlist</Link>
            <Link to="/addresses" style={activeLinkStyle}><FiMapPin /> Addresses</Link>
            <button onClick={handleLogout} style={{ ...linkStyle, color: 'var(--brand-red)', marginTop: '1rem', borderTop: '1px solid var(--border)', paddingTop: '1rem', backgroundColor: 'transparent', border: 'none', width: '100%', textAlign: 'left', cursor: 'pointer' }}>
              <FiLogOut /> Logout
            </button>
          </nav>
        </aside>

        {/* Main Content */}
        <div style={{ flex: '1 1 600px', backgroundColor: 'var(--white)', borderRadius: 'var(--radius-lg)', border: '1px solid var(--border)', padding: '2rem' }}>
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '1.5rem' }}>
            <h2 style={{ fontSize: '1.5rem', color: 'var(--dark-green)' }}>Saved Addresses</h2>
            {editingId === null && (
              <Button variant="outline" size="sm" onClick={handleAdd} style={{ display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
                <FiPlus /> Add New
              </Button>
            )}
          </div>

          <div style={{ display: 'flex', flexDirection: 'column', gap: '1.5rem' }}>
            {editingId !== null && !addresses.some(a => a.id === editingId) && (
              <AddressForm 
                formData={formData} 
                setFormData={setFormData} 
                onSave={handleSave} 
                onCancel={handleCancel} 
              />
            )}

            {addresses.map(address => (
              editingId === address.id ? (
                <AddressForm 
                  key={address.id} 
                  formData={formData} 
                  setFormData={setFormData} 
                  onSave={handleSave} 
                  onCancel={handleCancel} 
                />
              ) : (
                <div key={address.id} style={{ border: `1px solid ${address.isDefault ? 'var(--primary-green)' : 'var(--border)'}`, borderRadius: 'var(--radius-md)', padding: '1.5rem', backgroundColor: address.isDefault ? '#fafffa' : 'var(--white)', position: 'relative' }}>
                  {address.isDefault && (
                    <span style={{ position: 'absolute', top: '1.5rem', right: '1.5rem', backgroundColor: 'var(--primary-green)', color: 'white', padding: '0.2rem 0.6rem', borderRadius: '4px', fontSize: '0.8rem', fontWeight: 'bold' }}>DEFAULT</span>
                  )}
                  <h4 style={{ margin: '0 0 0.5rem 0', color: 'var(--dark-text)' }}>{user?.name} ({address.type})</h4>
                  <p style={{ margin: '0 0 0.25rem 0', color: 'var(--secondary-text)', lineHeight: 1.5 }}>
                    {address.line1}<br/>
                    {address.line2}<br/>
                    {address.country}
                  </p>
                  <p style={{ margin: '0 0 1rem 0', color: 'var(--dark-text)', fontWeight: '500' }}>Mobile: {address.mobile}</p>
                  
                  <div style={{ display: 'flex', gap: '1rem', flexWrap: 'wrap' }}>
                    <button onClick={() => handleEdit(address)} style={actionBtnStyle}><FiEdit2 /> Edit</button>
                    <button onClick={() => handleDelete(address.id)} style={{...actionBtnStyle, color: 'var(--brand-red)'}}><FiTrash2 /> Delete</button>
                    {!address.isDefault && (
                      <button onClick={() => handleSetDefault(address.id)} style={{...actionBtnStyle, color: 'var(--primary-green)'}}>Set as Default</button>
                    )}
                  </div>
                </div>
              )
            ))}
            
            {addresses.length === 0 && editingId === null && (
              <p style={{ color: 'var(--secondary-text)' }}>You have no saved addresses.</p>
            )}
          </div>
        </div>

      </div>
    </div>
  );
};

const AddressForm = ({ formData, setFormData, onSave, onCancel }) => (
  <div style={{ border: '1px solid var(--border)', borderRadius: 'var(--radius-md)', padding: '1.5rem', backgroundColor: '#FAFAFA' }}>
    <h4 style={{ margin: '0 0 1rem 0', color: 'var(--dark-green)' }}>{formData.id > 10000 ? 'Add New Address' : 'Edit Address'}</h4>
    <div style={{ display: 'flex', flexDirection: 'column', gap: '1rem' }}>
      <div>
        <label style={labelStyle}>Address Type (Home, Office, etc.)</label>
        <input 
          type="text" 
          value={formData.type} 
          onChange={e => setFormData({...formData, type: e.target.value})} 
          style={inputStyle} 
        />
      </div>
      <div>
        <label style={labelStyle}>Address Line 1</label>
        <input 
          type="text" 
          value={formData.line1} 
          onChange={e => setFormData({...formData, line1: e.target.value})} 
          style={inputStyle} 
          placeholder="Street address, P.O. box, company name, c/o"
        />
      </div>
      <div>
        <label style={labelStyle}>Address Line 2 (City, State, PIN)</label>
        <input 
          type="text" 
          value={formData.line2} 
          onChange={e => setFormData({...formData, line2: e.target.value})} 
          style={inputStyle} 
          placeholder="Apartment, suite, unit, building, floor, etc."
        />
      </div>
      <div>
        <label style={labelStyle}>Mobile Number</label>
        <input 
          type="text" 
          value={formData.mobile} 
          onChange={e => {
            const val = e.target.value.replace(/\D/g, ''); // Remove non-digits
            if (val.length <= 10) {
              setFormData({...formData, mobile: val});
            }
          }}
          maxLength="10"
          style={inputStyle} 
          placeholder="10-digit mobile number"
        />
      </div>
      
      <div style={{ display: 'flex', gap: '1rem', marginTop: '0.5rem' }}>
        <Button onClick={onSave} style={{ display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
          <FiSave /> Save
        </Button>
        <Button variant="outline" onClick={onCancel} style={{ display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
          <FiX /> Cancel
        </Button>
      </div>
    </div>
  </div>
);

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
  fontFamily: 'inherit'
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

const actionBtnStyle = {
  background: 'none',
  border: 'none',
  padding: 0,
  color: 'var(--secondary-text)',
  cursor: 'pointer',
  display: 'flex',
  alignItems: 'center',
  gap: '0.4rem',
  fontSize: '0.9rem',
  fontWeight: '500'
};

export default Addresses;
