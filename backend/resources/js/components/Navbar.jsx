import React, { useState, useEffect } from 'react';
import { Link, useNavigate, useLocation } from 'react-router-dom';
import { FiSearch, FiUser, FiShoppingCart, FiMenu, FiX, FiHeart } from 'react-icons/fi';
import { motion, AnimatePresence, useScroll, useMotionValueEvent } from 'framer-motion';
import { useCart } from '../context/CartContext';
import { useWishlist } from '../context/WishlistContext';
import logo from '../assets/logo-cropped.png';
import { categoryService } from '../services/categoryService';
import { offerService } from '../services/offerService';
import { useAutoRefresh } from '../context/AutoRefreshContext';

const Navbar = () => {
  const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
  const [searchQuery, setSearchQuery] = useState('');
  const navigate = useNavigate();
  const location = useLocation();
  const { cartItems, openCart } = useCart();
  const { wishlistItems } = useWishlist();
  const [isVisible, setIsVisible] = useState(true);
  const [categories, setCategories] = useState([]);
  const [activeOffer, setActiveOffer] = useState(null);
  const { refreshTrigger } = useAutoRefresh();

  useEffect(() => {
    const fetchCategoriesAndOffers = async () => {
      const data = await categoryService.getAllCategories();
      setCategories(data);
      
      const offers = await offerService.getActiveOffers();
      if (offers && offers.length > 0) {
        setActiveOffer(offers[0]);
      }
    };
    fetchCategoriesAndOffers();
  }, [refreshTrigger]);

  const { scrollY } = useScroll();

  useMotionValueEvent(scrollY, "change", (latest) => {
    const previous = scrollY.getPrevious();
    if (latest > previous && latest > 100) {
      setIsVisible(false);
    } else {
      setIsVisible(true);
    }
  });

  const handleSearch = (e) => {
    e.preventDefault();
    if (searchQuery.trim()) {
      navigate(`/shop?search=${encodeURIComponent(searchQuery)}`);
      setSearchQuery('');
      setIsMobileMenuOpen(false);
    }
  };

  const navLinks = [
    { name: 'Home', path: '/' },
    { name: 'Shop', path: '/shop' },
    { name: 'Categories ⌄', path: '/shop' },
    { name: 'About Us', path: '/about' },
    { name: 'Contact Us', path: '/contact' },
  ];

  const navLinkStyle = (isActive) => ({
    textDecoration: 'none',
    color: isActive ? 'var(--brand-red)' : 'var(--dark-text)',
    fontWeight: isActive ? '700' : '600',
    fontSize: '0.95rem',
    transition: 'color 0.2s',
  });

  return (
    <>
      {/* Top Green Banner */}
      <div style={{ backgroundColor: 'var(--dark-green)', color: 'var(--white)', fontSize: '0.8rem', padding: '0.5rem 0', fontWeight: '500' }}>
        <div className="container" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', flexWrap: 'wrap', gap: '0.5rem' }}>
          <div style={{ display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
            <span style={{ fontSize: '1rem', lineHeight: 1 }}>{activeOffer ? '🎉' : '🍃'}</span> 
            {activeOffer ? (
              <span><strong>{activeOffer.name}</strong> is live! Use code <strong>{activeOffer.code}</strong> at checkout.</span>
            ) : (
              <span>Free Shipping on orders above ₹799</span>
            )}
          </div>
          <div style={{ opacity: 0.9 }}>
            100% Natural &bull; Premium Quality &bull; Freshly Packed
          </div>
        </div>
      </div>

      <header style={{ 
        backgroundColor: 'var(--white)', 
        borderBottom: '1px solid var(--border)',
        position: 'sticky',
        top: 0,
        zIndex: 1000,
        boxShadow: 'var(--shadow-sm)',
        transform: isVisible ? 'translateY(0)' : 'translateY(-100%)',
        transition: 'transform 0.3s ease-in-out'
      }}>
        <div className="container">
          <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', height: '80px', gap: '2rem' }}>
            
            {/* Logo */}
            <Link to="/" style={{ textDecoration: 'none', display: 'flex', alignItems: 'center', flexShrink: 0 }}>
               <img src={logo} alt="Nuts & Nutrition" style={{ height: '45px', objectFit: 'contain' }} />
            </Link>

            {/* Desktop Navigation Links */}
            <nav className="hide-mobile" style={{ display: 'flex', gap: '2rem', alignItems: 'center' }}>
              <Link to="/" style={navLinkStyle(location.pathname === '/')}>Home</Link>
              <Link to="/shop" style={navLinkStyle(location.pathname === '/shop')}>Shop</Link>
              
              {/* Categories Dropdown */}
              <div 
                style={{ position: 'relative' }}
                onMouseEnter={() => setIsMobileMenuOpen(false)}
                className="nav-dropdown-container"
              >
                <div style={{ ...navLinkStyle(location.pathname.includes('/category/')), cursor: 'pointer', display: 'flex', alignItems: 'center', gap: '4px' }}>
                  Categories <span style={{ fontSize: '0.8em' }}>▼</span>
                </div>
                <div className="nav-dropdown" style={{
                  position: 'absolute',
                  top: '100%',
                  left: '0',
                  backgroundColor: 'var(--white)',
                  boxShadow: 'var(--shadow-md)',
                  borderRadius: 'var(--radius-md)',
                  padding: '0.5rem 0',
                  minWidth: '200px',
                  display: 'none',
                  flexDirection: 'column',
                  zIndex: 10
                }}>
                  {categories.map(cat => (
                    <Link key={cat.id} to={`/category/${cat.slug}`} className="dropdown-link">{cat.name}</Link>
                  ))}
                </div>
              </div>

              <Link to="/about" style={navLinkStyle(location.pathname === '/about')}>About Us</Link>
              <Link to="/contact" style={navLinkStyle(location.pathname === '/contact')}>Contact Us</Link>
            </nav>

            {/* Right Side Actions */}
            <div style={{ display: 'flex', alignItems: 'center', gap: '1.5rem', flex: 1, justifyContent: 'flex-end' }}>
              
              {/* Search Bar Inline */}
              <form onSubmit={handleSearch} className="hide-mobile" style={{ display: 'flex', alignItems: 'center', backgroundColor: '#f5f5f5', borderRadius: 'var(--radius-full)', padding: '0.5rem 1rem', flex: '0 1 300px' }}>
                <input 
                  type="text" 
                  placeholder="Search products..." 
                  value={searchQuery}
                  onChange={(e) => setSearchQuery(e.target.value)}
                  style={{ border: 'none', backgroundColor: 'transparent', outline: 'none', flex: 1, fontSize: '0.9rem' }}
                />
                <button type="submit" aria-label="Search" style={{ backgroundColor: 'transparent', border: 'none', color: 'var(--secondary-text)', display: 'flex', alignItems: 'center' }}>
                  <FiSearch size={18} />
                </button>
              </form>

              {/* Icons */}
              <div style={{ display: 'flex', alignItems: 'center', gap: '1.25rem' }}>
                <Link to="/wishlist" aria-label="Wishlist" style={{ color: 'var(--dark-text)', display: 'flex', alignItems: 'center' }}>
                  <FiHeart size={22} />
                </Link>
                
                <button 
                  onClick={openCart}
                  aria-label="Cart"
                  style={{ color: 'var(--dark-text)', background: 'none', border: 'none', position: 'relative', display: 'flex', alignItems: 'center', cursor: 'pointer' }}
                >
                  <FiShoppingCart size={22} />
                  {cartItems.length > 0 && (
                    <span style={{
                      position: 'absolute',
                      top: '-8px',
                      right: '-8px',
                      backgroundColor: 'var(--brand-red)',
                      color: 'white',
                      fontSize: '0.7rem',
                      fontWeight: 'bold',
                      width: '18px',
                      height: '18px',
                      borderRadius: '50%',
                      display: 'flex',
                      alignItems: 'center',
                      justifyContent: 'center'
                    }}>
                      {cartItems.reduce((acc, item) => acc + item.quantity, 0)}
                    </span>
                  )}
                </button>

                <Link to="/account" aria-label="Account" style={{ color: 'var(--dark-text)', display: 'flex', alignItems: 'center' }} className="hide-mobile">
                  <FiUser size={22} />
                </Link>

                <button 
                  className="show-mobile"
                  onClick={() => setIsMobileMenuOpen(true)}
                  aria-label="Menu"
                  style={{ color: 'var(--dark-text)', background: 'none', border: 'none', display: 'flex', alignItems: 'center', cursor: 'pointer' }}
                >
                  <FiMenu size={24} />
                </button>
              </div>
            </div>

          </div>
        </div>
      </header>

      {/* Mobile Menu Drawer */}
      <AnimatePresence>
        {isMobileMenuOpen && (
          <motion.div
            initial={{ opacity: 0, x: '100%' }}
            animate={{ opacity: 1, x: 0 }}
            exit={{ opacity: 0, x: '100%' }}
            transition={{ type: 'tween', duration: 0.3 }}
            style={{
              position: 'fixed', top: 0, left: 0, right: 0, bottom: 0,
              backgroundColor: 'var(--white)', zIndex: 2000, padding: '2rem',
              display: 'flex', flexDirection: 'column'
            }}
          >
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '2rem' }}>
              <div style={{ fontSize: '1.25rem', fontWeight: 'bold', color: 'var(--brand-red)', fontFamily: 'var(--font-display)' }}>
                Nuts & Nutrition
              </div>
              <button onClick={() => setIsMobileMenuOpen(false)} aria-label="Close Menu" style={{ background: 'none', border: 'none', color: 'var(--dark-text)', cursor: 'pointer' }}>
                <FiX size={28} />
              </button>
            </div>
            
            <form onSubmit={handleSearch} style={{ display: 'flex', marginBottom: '2rem', borderBottom: '1px solid var(--border)', paddingBottom: '0.5rem' }}>
              <input 
                type="text" 
                placeholder="Search products..." 
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                style={{ border: 'none', outline: 'none', flex: 1, fontSize: '1rem', padding: '0.5rem 0' }}
              />
              <button type="submit" aria-label="Search" style={{ backgroundColor: 'transparent', border: 'none', color: 'var(--primary-green)', cursor: 'pointer' }}>
                <FiSearch size={20} />
              </button>
            </form>

            <nav style={{ display: 'flex', flexDirection: 'column', gap: '1.5rem' }}>
              {navLinks.map((link) => (
                <Link 
                  key={link.name} 
                  to={link.path}
                  onClick={() => setIsMobileMenuOpen(false)}
                  style={{
                    textDecoration: 'none',
                    color: 'var(--dark-text)',
                    fontSize: '1.25rem',
                    fontWeight: '600'
                  }}
                >
                  {link.name}
                </Link>
              ))}
              <Link to="/account" onClick={() => setIsMobileMenuOpen(false)} style={{ textDecoration: 'none', color: 'var(--dark-text)', fontSize: '1.25rem', fontWeight: '600', marginTop: '1rem', borderTop: '1px solid var(--border)', paddingTop: '1.5rem' }}>
                My Account
              </Link>
            </nav>
          </motion.div>
        )}
      </AnimatePresence>

      <style>{`
        .show-mobile { display: none !important; }
        @media (max-width: 991px) {
          .hide-mobile { display: none !important; }
          .show-mobile { display: flex !important; }
        }
        
        .nav-dropdown-container:hover .nav-dropdown {
          display: flex !important;
        }
        .dropdown-link {
          padding: 0.75rem 1.5rem;
          color: var(--dark-text);
          text-decoration: none;
          font-weight: 500;
          font-size: 0.9rem;
          transition: background-color 0.2s, color 0.2s;
        }
        .dropdown-link:hover {
          background-color: var(--soft-green);
          color: var(--dark-green);
        }
      `}</style>
    </>
  );
};

export default Navbar;
