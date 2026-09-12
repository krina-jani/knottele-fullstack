import React from 'react';
import { Link } from 'react-router-dom';
import { motion } from 'framer-motion';
import { FiInstagram, FiFacebook, FiYoutube, FiPhone, FiMail, FiMapPin, FiTruck, FiRefreshCw, FiShield, FiClock, FiArrowRight } from 'react-icons/fi';
import { FaWhatsapp, FaCcVisa, FaCcMastercard, FaCcPaypal, FaCcStripe } from 'react-icons/fa';
import logo from '../assets/logo-cropped.png';
import FooterTerrain from './FooterTerrain';

const Footer = () => {
  // Animation variants
  const staggerContainer = {
    hidden: { opacity: 0 },
    show: {
      opacity: 1,
      transition: { staggerChildren: 0.1, delayChildren: 0.2 }
    }
  };

  const fadeUp = {
    hidden: { opacity: 0, y: 20 },
    show: { opacity: 1, y: 0, transition: { duration: 0.6, ease: "easeOut" } }
  };

  const benefits = [
    { icon: <FiTruck size={24} />, title: "Free Shipping", subtitle: "On orders above ₹799" },
    { icon: <FiRefreshCw size={24} />, title: "Easy Returns", subtitle: "7 days easy returns" },
    { icon: <FiShield size={24} />, title: "Secure Payment", subtitle: "100% secure payments" },
    { icon: <FiClock size={24} />, title: "24/7 Support", subtitle: "We are here to help" },
  ];

  return (
    <footer style={{
      position: 'relative',
      overflow: 'hidden',
      backgroundColor: 'var(--soft-green)', // Natural soft green background
      color: 'var(--brand-red)'
    }}>
      <style>{`
        .social-icon {
          display: flex;
          align-items: center;
          justify-content: center;
          width: 42px;
          height: 42px;
          border-radius: 50%;
          background-color: transparent;
          color: var(--brand-red);
          transition: all 0.3s ease;
        }
        .social-icon:hover {
          color: var(--dark-text);
          transform: translateY(-4px) scale(1.1);
        }
        
        .nav-link {
          display: flex;
          align-items: center;
          color: var(--brand-red);
          text-decoration: none;
          font-size: 0.95rem;
          font-weight: 700;
          transition: all 0.3s ease;
          position: relative;
          padding-left: 0;
        }
        .nav-link .arrow {
          opacity: 0;
          margin-right: 0px;
          color: var(--dark-text);
          transition: all 0.3s ease;
          width: 0;
          overflow: hidden;
        }
        .nav-link:hover {
          color: var(--dark-text);
          padding-left: 4px;
        }
        .nav-link:hover .arrow {
          opacity: 1;
          width: 16px;
          margin-right: 8px;
        }
        .nav-link::after {
          content: '';
          position: absolute;
          bottom: -2px;
          left: 0;
          width: 0;
          height: 1px;
          background-color: var(--brand-red);
          transition: width 0.3s ease;
        }
        .nav-link:hover::after {
          width: 100%;
        }

        .contact-item {
          display: flex;
          gap: 1rem;
          color: var(--brand-red);
          font-size: 0.95rem;
          transition: all 0.3s ease;
        }
        .contact-icon {
          color: var(--brand-red);
          transition: all 0.3s ease;
        }
        .contact-item:hover .contact-icon {
          color: var(--dark-text);
          filter: drop-shadow(0 0 8px rgba(0,0,0,0.1));
        }

        .payment-logo {
          opacity: 0.7;
          transition: all 0.3s ease;
          color: var(--secondary-text);
        }
        .payment-logo:hover {
          opacity: 1;
          transform: translateY(-2px);
          color: var(--brand-red);
        }

        .footer-logo {
          max-width: 180px;
          width: 100%;
          height: auto;
          margin-bottom: 1.5rem;
          display: block;
        }

        .footer-grid {
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
          gap: 3rem;
          padding-top: 4rem;
          padding-bottom: 2rem;
        }

        @media (max-width: 768px) {
          .footer-logo {
            max-width: 130px;
            margin-bottom: 1rem;
          }
          .footer-grid {
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            padding-top: 2rem;
          }
          .footer-brand {
            grid-column: 1 / -1;
          }
        }
      `}</style>

      {/* Background Depth layer (subtle noise/grain would go here if using image) */}

      <div className="container" style={{ position: 'relative', zIndex: 10 }}>
        {/* MAIN CONTENT AREA */}
        <motion.div
          initial="hidden"
          whileInView="show"
          viewport={{ once: true, margin: "-100px" }}
          variants={staggerContainer}
          className="footer-grid"
        >
          {/* BRAND COLUMN */}
          <motion.div variants={fadeUp} className="footer-brand">
            <img src={logo} alt="Nuts & Nutrition" className="footer-logo" />
            <div style={{ display: 'flex', gap: '1rem', marginTop: '0.5rem' }}>
              <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" className="social-icon" aria-label="Instagram"><FiInstagram size={20} /></a>
              <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" className="social-icon" aria-label="Facebook"><FiFacebook size={20} /></a>
              <a href="https://youtube.com" target="_blank" rel="noopener noreferrer" className="social-icon" aria-label="YouTube"><FiYoutube size={20} /></a>
              <a href="https://whatsapp.com" target="_blank" rel="noopener noreferrer" className="social-icon" aria-label="WhatsApp"><FaWhatsapp size={20} /></a>
            </div>
          </motion.div>

          {/* SHOP & EXPLORE */}
          <motion.div variants={fadeUp}>
            <h4 style={headingStyle}>EXPLORE</h4>
            <ul style={listStyle}>
              <li><Link to="/" className="nav-link"><FiArrowRight className="arrow" />Home</Link></li>
              <li><Link to="/shop" className="nav-link"><FiArrowRight className="arrow" />Shop All</Link></li>
              <li><Link to="/categories" className="nav-link"><FiArrowRight className="arrow" />Categories</Link></li>
              <li><Link to="/about" className="nav-link"><FiArrowRight className="arrow" />About Us</Link></li>
              <li><Link to="/contact" className="nav-link"><FiArrowRight className="arrow" />Contact Us</Link></li>
            </ul>
          </motion.div>

          {/* MY ACCOUNT */}
          <motion.div variants={fadeUp}>
            <h4 style={headingStyle}>MY ACCOUNT</h4>
            <ul style={listStyle}>
              <li><Link to="/account" className="nav-link"><FiArrowRight className="arrow" />Dashboard</Link></li>
              <li><Link to="/orders" className="nav-link"><FiArrowRight className="arrow" />Orders</Link></li>
              <li><Link to="/wishlist" className="nav-link"><FiArrowRight className="arrow" />Wishlist</Link></li>
              <li><Link to="/cart" className="nav-link"><FiArrowRight className="arrow" />Shopping Cart</Link></li>
              <li><Link to="/login" className="nav-link"><FiArrowRight className="arrow" />Login / Register</Link></li>
            </ul>
          </motion.div>

        </motion.div>
      </div>


      {/* BOTTOM COPYRIGHT BAR */}
      <motion.div
        initial={{ opacity: 0 }}
        whileInView={{ opacity: 1 }}
        transition={{ delay: 0.5, duration: 1 }}
        viewport={{ once: true }}
        style={{
          position: 'relative',
          zIndex: 10,
          borderTop: '1px solid rgba(0, 0, 0, 0.1)',
          padding: '1.5rem 0',
          marginTop: '2rem'
        }}
      >
        <div className="container" style={{
          display: 'flex',
          justifyContent: 'space-between',
          alignItems: 'center',
          flexWrap: 'wrap',
          gap: '1rem',
          color: 'var(--brand-red)',
          fontSize: '0.9rem'
        }}>
          <p style={{ margin: 0 }}>&copy; 2026 Nutri. All rights reserved.</p>

          <div style={{ display: 'flex', gap: '1rem', alignItems: 'center' }}>
            <span style={{ fontSize: '0.85rem' }}>We accept</span>
            <FaCcVisa size={28} className="payment-logo" />
            <FaCcMastercard size={28} className="payment-logo" />
            <span className="payment-logo" style={{ fontWeight: 'bold', fontSize: '1rem', fontStyle: 'italic' }}>UPI</span>
            <span className="payment-logo" style={{ fontWeight: 'bold', fontSize: '1rem' }}>Paytm</span>
          </div>

          <div style={{ fontWeight: '600', fontSize: '1.1rem', color: 'var(--brand-red)' }}>
            Stronger <span style={{ color: 'var(--dark-text)' }}>Every Day.</span>
          </div>
        </div>
      </motion.div>
    </footer>
  );
};

const headingStyle = {
  fontSize: '1.05rem',
  fontWeight: '800',
  marginBottom: '1.5rem',
  letterSpacing: '0.5px'
};

const listStyle = {
  listStyle: 'none',
  padding: 0,
  margin: 0,
  display: 'flex',
  flexDirection: 'column',
  gap: '1rem'
};

export default Footer;
