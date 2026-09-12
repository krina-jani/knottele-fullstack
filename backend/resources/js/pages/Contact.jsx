import React, { useState, useEffect } from 'react';
import { motion } from 'framer-motion';
import { FiMail, FiPhone, FiMapPin, FiSend } from 'react-icons/fi';
import api from '../services/api';

// Same premium palette used in About page
const colors = {
  cream: '#fdfbf7',
  offWhite: '#faf9f6',
  nutBrown: '#8b5a2b',
  darkBrown: '#4a3b32',
  softGreen: '#e6efe6',
  darkGreen: '#68B348',
  golden: '#d4af37',
  text: '#333333',
  lightText: '#666666'
};

const Contact = () => {
  const [formData, setFormData] = useState({ name: '', email: '', phone: '', message: '' });
  const [isSuccess, setIsSuccess] = useState(false);
  const [isLoading, setIsLoading] = useState(false);
  const [error, setError] = useState('');
  
  const [contactInfo, setContactInfo] = useState([
    { icon: FiMapPin, title: 'Visit Us', desc: '123 Healthy Street, Green City\nIndia - 400001' },
    { icon: FiPhone, title: 'Call Us', desc: '+91 98765 43210\nMon - Fri, 9am - 6pm' },
    { icon: FiMail, title: 'Email Us', desc: 'hello@nutsandnutrition.com\nWe reply within 24 hours' }
  ]);

  useEffect(() => {
    const fetchSettings = async () => {
      try {
        const response = await api.get('/customer/contact/settings');
        if (response.data?.status === 'success') {
          const data = response.data.data;
          setContactInfo([
            { icon: FiMapPin, title: 'Visit Us', desc: `${data.address_line_1}\n${data.address_line_2 || ''}` },
            { icon: FiPhone, title: 'Call Us', desc: `${data.phone}\n${data.hours}` },
            { icon: FiMail, title: 'Email Us', desc: `${data.email}\n${data.response_time}` }
          ]);
        }
      } catch (err) {
        console.error('Error fetching contact settings:', err);
      }
    };
    fetchSettings();
  }, []);

  const handleChange = (e) => {
    const { name, value } = e.target;
    if (name === 'phone') {
      const numericValue = value.replace(/\D/g, '').slice(0, 10);
      setFormData({ ...formData, [name]: numericValue });
    } else {
      setFormData({ ...formData, [name]: value });
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setIsLoading(true);
    setError('');
    
    try {
      await api.post('/customer/contact', formData);
      setIsSuccess(true);
      setFormData({ name: '', email: '', phone: '', message: '' });
    } catch (err) {
      console.error('Error submitting contact form:', err);
      setError(err.response?.data?.message || 'Something went wrong. Please try again.');
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <div style={{ backgroundColor: colors.offWhite, minHeight: '100vh', overflowX: 'hidden' }}>
      
      <style>{`
        .hero-padding {
          padding: 8rem 0;
        }
        .main-padding {
          padding: 6rem 0;
          margin-top: -4rem;
        }
        @media (max-width: 768px) {
          .hero-padding {
            padding: 5rem 0;
          }
          .main-padding {
            padding: 3rem 0;
            margin-top: -2rem;
          }
        }
        @media (max-width: 480px) {
          .hero-padding {
            padding: 4rem 0;
          }
          .main-padding {
            padding: 2rem 0;
            margin-top: -1rem;
          }
        }
      `}</style>
      
      {/* Premium Image Hero */}
      <section className="hero-padding" style={{ 
        position: 'relative', 
        backgroundImage: `linear-gradient(rgba(42, 93, 44, 0.8), rgba(74, 59, 50, 0.9)), url('/images/cocoa_raw.jpg')`,
        backgroundSize: 'cover',
        backgroundPosition: 'center',
        backgroundAttachment: 'fixed',
        textAlign: 'center',
        color: 'white'
      }}>
        <div className="container" style={{ position: 'relative', zIndex: 1 }}>
          <motion.div initial={{ opacity: 0, y: 30 }} animate={{ opacity: 1, y: 0 }} transition={{ duration: 0.8 }}>
            <h1 style={{ fontSize: 'clamp(2.5rem, 5vw, 4.5rem)', marginBottom: '1rem', fontFamily: 'var(--font-display)', color: 'white' }}>
              Let's Start a Conversation.
            </h1>
            <p style={{ fontSize: '1.2rem', color: 'rgba(255,255,255,0.85)', maxWidth: '600px', margin: '0 auto' }}>
              Have questions about our blends? Looking for wholesale opportunities? We'd love to hear from you.
            </p>
          </motion.div>
        </div>
      </section>

      {/* Main Content Area */}
      <section className="main-padding">
        <div className="container" style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(320px, 1fr))', gap: '3rem', alignItems: 'start' }}>
          
          {/* Left: Interactive Contact Info Cards */}
          <div style={{ display: 'flex', flexDirection: 'column', gap: '1.5rem', marginTop: '4rem' }}>
            <h3 style={{ fontSize: '2rem', color: colors.darkBrown, marginBottom: '1.5rem', fontFamily: 'var(--font-display)' }}>Get In Touch</h3>
            
            {contactInfo.map((info, i) => (
              <motion.div 
                key={i}
                initial={{ opacity: 0, x: -30 }}
                whileInView={{ opacity: 1, x: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.5, delay: i * 0.1 }}
                whileHover={{ x: 10, backgroundColor: 'white', boxShadow: '0 15px 35px rgba(0,0,0,0.06)' }}
                style={{ 
                  display: 'flex', 
                  gap: '1.5rem', 
                  alignItems: 'center', 
                  padding: '1.5rem',
                  borderRadius: '20px',
                  backgroundColor: 'transparent',
                  border: `1px solid rgba(0,0,0,0.03)`,
                  transition: 'all 0.3s ease',
                  cursor: 'pointer'
                }}
              >
                <div style={{ width: '60px', height: '60px', borderRadius: '50%', backgroundColor: colors.softGreen, display: 'flex', alignItems: 'center', justifyContent: 'center', color: colors.darkGreen, flexShrink: 0 }}>
                  <info.icon size={26} />
                </div>
                <div>
                  <h4 style={{ margin: '0 0 0.25rem 0', fontSize: '1.15rem', color: colors.darkBrown, fontFamily: 'var(--font-display)' }}>{info.title}</h4>
                  <p style={{ color: colors.lightText, margin: 0, whiteSpace: 'pre-line', lineHeight: 1.5, fontSize: '0.95rem' }}>{info.desc}</p>
                </div>
              </motion.div>
            ))}
          </div>

          {/* Right: Elegant Form */}
          <motion.div 
            initial={{ opacity: 0, y: 50 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.7 }}
            style={{ 
              backgroundColor: 'white', 
              padding: '3.5rem 3rem', 
              borderRadius: '24px', 
              boxShadow: '0 25px 50px rgba(0,0,0,0.05)', 
              border: `1px solid ${colors.cream}`,
              position: 'relative',
              zIndex: 2
            }}
          >
            <h3 style={{ fontSize: '2rem', color: '#000000', marginBottom: '2rem', fontFamily: 'var(--font-display)' }}>Send a Message</h3>
            
            {isSuccess ? (
              <motion.div 
                initial={{ opacity: 0, scale: 0.9 }} animate={{ opacity: 1, scale: 1 }}
                style={{ backgroundColor: colors.softGreen, padding: '3rem 2rem', borderRadius: '20px', textAlign: 'center', color: colors.darkGreen }}
              >
                <div style={{ fontSize: '4rem', marginBottom: '1rem' }}>🌱</div>
                <h4 style={{ fontSize: '1.5rem', marginBottom: '1rem', fontFamily: 'var(--font-display)' }}>Message Sent!</h4>
                <p style={{ margin: '0 auto 2rem', fontSize: '1.05rem', color: colors.darkBrown, maxWidth: '250px' }}>Thank you for reaching out. We will get back to you soon.</p>
                <button 
                  onClick={() => setIsSuccess(false)}
                  style={{
                    backgroundColor: 'transparent',
                    border: `2px solid ${colors.darkGreen}`,
                    color: colors.darkGreen,
                    padding: '0.75rem 1.5rem',
                    borderRadius: '30px',
                    fontWeight: '600',
                    cursor: 'pointer',
                    transition: 'all 0.3s ease'
                  }}
                  onMouseEnter={(e) => { e.target.style.backgroundColor = colors.darkGreen; e.target.style.color = 'white'; }}
                  onMouseLeave={(e) => { e.target.style.backgroundColor = 'transparent'; e.target.style.color = colors.darkGreen; }}
                >
                  Send Another
                </button>
              </motion.div>
            ) : (
              <form onSubmit={handleSubmit} style={{ display: 'flex', flexDirection: 'column', gap: '1.5rem' }}>
                <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(200px, 1fr))', gap: '1.5rem' }}>
                  <div>
                    <label style={labelStyle}>Your Name <span style={{ color: '#d32f2f' }}>*</span></label>
                    <input type="text" name="name" value={formData.name} onChange={handleChange} required style={inputStyle} placeholder="Jane Doe" />
                  </div>
                  <div>
                    <label style={labelStyle}>Email Address <span style={{ color: '#d32f2f' }}>*</span></label>
                    <input type="email" name="email" value={formData.email} onChange={handleChange} required style={inputStyle} placeholder="jane@example.com" />
                  </div>
                </div>
                
                <div>
                  <label style={labelStyle}>Phone Number <span style={{ color: '#d32f2f' }}>*</span></label>
                  <input type="tel" name="phone" value={formData.phone} onChange={handleChange} required style={inputStyle} placeholder="9876543210" minLength="10" maxLength="10" pattern="\d{10}" title="Please enter exactly 10 digits" />
                </div>
                <div>
                  <label style={labelStyle}>Message (Optional)</label>
                  <textarea name="message" value={formData.message} onChange={handleChange} style={{ ...inputStyle, minHeight: '140px', resize: 'vertical' }} placeholder="How can we help you today?"></textarea>
                </div>
                
                {error && (
                  <div style={{ color: '#d32f2f', backgroundColor: '#ffebee', padding: '1rem', borderRadius: '8px', fontSize: '0.95rem' }}>
                    {error}
                  </div>
                )}
                
                <motion.button
                  whileHover={!isLoading ? { scale: 1.02 } : {}}
                  whileTap={!isLoading ? { scale: 0.98 } : {}}
                  type="submit"
                  disabled={isLoading}
                  style={{
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    gap: '0.75rem',
                    backgroundColor: isLoading ? '#9ccc65' : colors.darkGreen,
                    color: 'white',
                    border: 'none',
                    padding: '1.2rem',
                    fontSize: '1.1rem',
                    fontWeight: '600',
                    borderRadius: '12px',
                    cursor: isLoading ? 'not-allowed' : 'pointer',
                    marginTop: '1rem',
                    boxShadow: '0 10px 20px rgba(104, 179, 72, 0.2)'
                  }}
                >
                  {isLoading ? 'SENDING...' : <><>SEND MESSAGE</> <FiSend /></>}
                </motion.button>
              </form>
            )}
          </motion.div>

        </div>
      </section>
    </div>
  );
};

const labelStyle = {
  display: 'block',
  marginBottom: '0.5rem',
  fontSize: '0.95rem',
  fontWeight: '500',
  color: colors.darkBrown
};

const inputStyle = {
  width: '100%',
  padding: '1rem 1.25rem',
  borderRadius: '12px',
  border: '1px solid rgba(0,0,0,0.15)', // Added visible border
  backgroundColor: '#fafafa', // Slightly darker than white to stand out
  fontSize: '1rem',
  outline: 'none',
  transition: 'border-color 0.3s',
  fontFamily: 'inherit',
  color: colors.text
};

export default Contact;
