import React from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { FiUser, FiPackage, FiHeart, FiMapPin, FiLogOut } from 'react-icons/fi';
import { useAuth } from '../context/AuthContext';
import { useWishlist } from '../context/WishlistContext';
import Button from '../components/Button';
import ProductCard from '../components/ProductCard';

const Wishlist = () => {
  const { user, isAuthenticated, logout } = useAuth();
  const { wishlistItems } = useWishlist();
  const navigate = useNavigate();

  const handleLogout = () => {
    logout();
    navigate('/');
  };

  return (
    <div className="container" style={{ paddingTop: '3rem', paddingBottom: '5rem' }}>
      <h1 style={{ marginBottom: '2rem', color: 'var(--dark-green)' }}>My Wishlist</h1>
      
      <div style={{ display: 'flex', gap: '2rem', flexWrap: 'wrap', alignItems: 'flex-start' }}>
        
        {/* Sidebar */}
        <aside className="hide-mobile" style={{ flex: '1 1 250px', backgroundColor: 'var(--white)', borderRadius: 'var(--radius-lg)', border: '1px solid var(--border)', padding: '1.5rem' }}>
          <nav style={{ display: 'flex', flexDirection: 'column', gap: '0.5rem' }}>
            <Link to="/account" style={linkStyle}><FiUser /> Profile</Link>
            <Link to="/orders" style={linkStyle}><FiPackage /> Orders</Link>
            <Link to="/wishlist" style={activeLinkStyle}><FiHeart /> Wishlist</Link>
            <Link to="/addresses" style={linkStyle}><FiMapPin /> Addresses</Link>
            {isAuthenticated ? (
              <button onClick={handleLogout} style={{ ...linkStyle, color: 'var(--brand-red)', marginTop: '1rem', borderTop: '1px solid var(--border)', paddingTop: '1rem' }}>
                <FiLogOut /> Logout
              </button>
            ) : (
              <Link to="/login" style={{ ...linkStyle, color: 'var(--primary-green)', marginTop: '1rem', borderTop: '1px solid var(--border)', paddingTop: '1rem' }}>
                <FiLogOut /> Login
              </Link>
            )}
          </nav>
        </aside>

        {/* Main Content */}
        <div style={{ flex: '1 1 600px' }}>
          {wishlistItems.length === 0 ? (
            <div style={{ textAlign: 'center', padding: '4rem 2rem', backgroundColor: 'var(--white)', borderRadius: 'var(--radius-lg)', border: '1px solid var(--border)' }}>
              <FiHeart size={48} color="var(--border)" style={{ marginBottom: '1rem' }} />
              <h3 style={{ color: 'var(--secondary-text)', marginBottom: '1.5rem' }}>No favorites yet.</h3>
              <Link to="/shop" style={{ textDecoration: 'none' }}>
                <Button variant="primary">EXPLORE PRODUCTS</Button>
              </Link>
            </div>
          ) : (
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill, minmax(250px, 1fr))', gap: '1.5rem' }}>
              {wishlistItems.map((product) => (
                <ProductCard key={product.id} product={product} />
              ))}
            </div>
          )}
        </div>

      </div>

      <style>{`
        @media (max-width: 767px) {
          .hide-mobile { display: none !important; }
        }
      `}</style>
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

export default Wishlist;
