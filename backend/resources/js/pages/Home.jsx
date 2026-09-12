import React from 'react';
import { Link } from 'react-router-dom';
import { motion } from 'framer-motion';
import { FiCheckCircle, FiPackage, FiStar, FiHeart, FiSmile, FiArrowRight } from 'react-icons/fi';
import Button from '../components/Button';
import CategoryCard from '../components/CategoryCard';
import ProductCard from '../components/ProductCard';
import { useState, useEffect } from 'react';
import { categoryService } from '../services/categoryService';
import { productService } from '../services/productService';
import { useAutoRefresh } from '../context/AutoRefreshContext';

const TypewriterText = () => {
  const [line1, setLine1] = useState('');
  const [line2, setLine2] = useState('');
  const [sloganIndex, setSloganIndex] = useState(0);
  const [isDeleting, setIsDeleting] = useState(false);
  const [showCursor, setShowCursor] = useState(true);

  const text1 = "Better Nutrition.";
  const slogans = [
    "Better Everyday.",
    "Fuel Your Body.",
    "Live Healthier.",
    "Pure & Natural.",
    "Nourish Yourself."
  ];

  // Typing effect for line1 (runs once)
  useEffect(() => {
    let i = 0;
    const interval = setInterval(() => {
      setLine1(text1.slice(0, i + 1));
      i++;
      if (i > text1.length) {
        clearInterval(interval);
      }
    }, 70);
    return () => clearInterval(interval);
  }, []);

  // Typing effect for line2 (cycles slogans)
  useEffect(() => {
    if (line1.length < text1.length) return; // Wait for line1 to finish

    const currentSlogan = slogans[sloganIndex];
    let timeout;

    if (isDeleting) {
      timeout = setTimeout(() => {
        setLine2(currentSlogan.substring(0, line2.length - 1));
        if (line2.length <= 1) {
          setIsDeleting(false);
          setSloganIndex((prev) => (prev + 1) % slogans.length);
        }
      }, 50); // Deletion speed
    } else {
      if (line2.length === currentSlogan.length) {
        // Pause before deleting
        timeout = setTimeout(() => setIsDeleting(true), 2500);
      } else {
        timeout = setTimeout(() => {
          setLine2(currentSlogan.substring(0, line2.length + 1));
        }, 100); // Typing speed
      }
    }

    return () => clearTimeout(timeout);
  }, [line1.length, line2, isDeleting, sloganIndex]);

  // Cursor blinking
  useEffect(() => {
    const interval = setInterval(() => setShowCursor(prev => !prev), 500);
    return () => clearInterval(interval);
  }, []);

  return (
    <>
      {line1}
      {line1.length < text1.length && <span style={{ opacity: showCursor ? 1 : 0, fontFamily: 'sans-serif', fontWeight: 300 }}>|</span>}
      <br/>
      <span style={{ color: 'var(--brand-red)' }}>
        {line2}
        {line1.length >= text1.length && <span style={{ opacity: showCursor ? 1 : 0, color: 'var(--brand-red)', fontFamily: 'sans-serif', fontWeight: 300 }}>|</span>}
      </span>
    </>
  );
};

const Home = () => {
  const [featuredProducts, setFeaturedProducts] = useState([]);
  const [categories, setCategories] = useState([]);
  const [dynamicSections, setDynamicSections] = useState([]);
  const [isBoxOpened, setIsBoxOpened] = useState(false);
  const [isSubscribed, setIsSubscribed] = useState(false);
  const [email, setEmail] = useState('');
  const { refreshTrigger } = useAutoRefresh();

  // Fetch data on mount and on auto-refresh
  useEffect(() => {
    const fetchData = async () => {
      try {
        const catData = await categoryService.getAllCategories();
        setCategories(catData);

        const prodData = await productService.getAll({ is_featured: true });
        setFeaturedProducts(prodData.data?.products ? prodData.data.products.slice(0, 4) : []);

        const homeData = await fetch('/api/customer/home').then(res => res.json());
        if (homeData.success) {
            setDynamicSections(homeData.data.dynamicSections || []);
        }
      } catch (error) {
        console.error("Failed to fetch data", error);
      }
    };
    fetchData();
  }, [refreshTrigger]);

  const handleSubscribe = (e) => {
    e.preventDefault();
    if(email) {
      setIsSubscribed(true);
      setEmail('');
    }
  };

  // Reviews State
  const [currentReview, setCurrentReview] = useState(0);
  const reviews = [
    { text: "Excellent quality products and amazing taste. My family loves the chocolate nutrition powder!", author: "Priya Sharma", role: "Verified Customer", avatar: "https://placehold.co/100x100/e6efe6/2a5d2c?text=PS" },
    { text: "The nuts are always fresh and crunchy. It's so hard to find genuine premium quality these days, but Nuts & Nutrition delivers.", author: "Rahul Desai", role: "Verified Customer", avatar: "https://placehold.co/100x100/e6efe6/2a5d2c?text=RD" },
    { text: "I highly recommend the seeds mix for anyone looking to add a healthy crunch to their salads or morning oats. Absolutely delicious.", author: "Sneha Patel", role: "Verified Customer", avatar: "https://placehold.co/100x100/e6efe6/2a5d2c?text=SP" }
  ];

  useEffect(() => {
    const timer = setInterval(() => {
      setCurrentReview((prev) => (prev + 1) % reviews.length);
    }, 5000);
    return () => clearInterval(timer);
  }, []);

  return (
    <div>
      <style>{`
        .home-hero-container {
          background-image: url(/images/hero.jpeg);
          background-size: cover;
          background-position: right bottom;
          background-repeat: no-repeat;
          background-color: #f9f8f3;
          padding: 4rem 0 5rem 0;
          min-height: auto;
          display: flex;
          align-items: center;
          overflow: hidden;
          position: relative;
        }
        @media (max-width: 768px) {
          .home-hero-container {
            background-image: none !important;
          }
        }
      `}</style>
      {/* 1. HERO SECTION */}
      <section className="home-hero-container">
        <div className="container" style={{ width: '100%' }}>
          <div className="hero-grid">
            
            {/* Hero Left Content */}
            <div style={{ paddingRight: '1rem' }}>
              <motion.div 
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.5 }}
                style={{ color: 'var(--primary-green)', fontWeight: 'bold', letterSpacing: '1px', marginBottom: '1rem', fontSize: '0.9rem', textTransform: 'uppercase' }}
              >
                Natural Nutrition
              </motion.div>
              
              <style>{`
                .home-title {
                  font-size: 3.5rem;
                  line-height: 1.1;
                  color: #68B348;
                  margin-bottom: 0.5rem;
                  font-family: var(--font-display);
                  letter-spacing: -1px;
                }
                @media (max-width: 768px) {
                  .home-title {
                    font-size: 2.2rem;
                  }
                }
                @media (max-width: 480px) {
                  .home-title {
                    font-size: 1.8rem;
                  }
                }
                .section-padding {
                  padding: 5rem 0;
                }
                @media (max-width: 768px) {
                  .section-padding {
                    padding: 3rem 0;
                  }
                }
                @media (max-width: 480px) {
                  .section-padding {
                    padding: 2rem 0;
                  }
                }
              `}</style>
              <motion.h1 
                className="home-title"
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.5, delay: 0.1 }}
              >
                <TypewriterText />
              </motion.h1>
              
              <motion.p 
                className="home-desc"
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.5, delay: 0.2 }}
                style={{ color: 'var(--dark-text)', marginBottom: '2.5rem', maxWidth: '450px', lineHeight: 1.6 }}
              >
                Premium nuts, seeds and nutrition products crafted for your healthy lifestyle.
              </motion.p>
              
              <motion.div 
                className="hero-buttons"
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.5, delay: 0.3 }}
                style={{ display: 'flex', gap: '1rem', marginBottom: '3rem', flexWrap: 'wrap' }}
              >
                <Link to="/shop" style={{ textDecoration: 'none' }} className="mobile-full-width">
                  <Button variant="primary" icon={<FiArrowRight />} className="mobile-full-width-btn">SHOP NOW</Button>
                </Link>
                <Link to="/shop" style={{ textDecoration: 'none' }} className="mobile-full-width">
                  <Button variant="outline" style={{ borderColor: 'var(--border)', color: 'var(--dark-text)' }} className="mobile-full-width-btn">EXPLORE PRODUCTS</Button>
                </Link>
              </motion.div>

              {/* Hero Features Strip */}
              <motion.div
                className="hero-features-strip"
                initial={{ opacity: 0 }}
                animate={{ opacity: 1 }}
                transition={{ duration: 0.5, delay: 0.5 }}
                style={{ display: 'flex', gap: '2rem', borderTop: '1px solid rgba(0,0,0,0.05)', paddingTop: '1.5rem', flexWrap: 'wrap' }}
              >
                <div style={{ display: 'flex', alignItems: 'center', gap: '0.5rem', fontSize: '0.8rem', fontWeight: '600' }}>
                  <FiCheckCircle color="var(--primary-green)" size={18} /> Quality<br/>Ingredients
                </div>
                <div style={{ display: 'flex', alignItems: 'center', gap: '0.5rem', fontSize: '0.8rem', fontWeight: '600' }}>
                  <FiPackage color="var(--primary-green)" size={18} /> Freshly<br/>Packed
                </div>
                <div style={{ display: 'flex', alignItems: 'center', gap: '0.5rem', fontSize: '0.8rem', fontWeight: '600' }}>
                  <FiStar color="var(--primary-green)" size={18} /> Premium<br/>Products
                </div>
                <div style={{ display: 'flex', alignItems: 'center', gap: '0.5rem', fontSize: '0.8rem', fontWeight: '600' }}>
                  <FiSmile color="var(--primary-green)" size={18} /> Customer<br/>First
                </div>
              </motion.div>
            </div>

            {/* Spacer to keep text on the left while background image shows on the right */}
            <div></div>

          </div>
        </div>
      </section>

      {/* 2. SHOP BY CATEGORY */}
      <section className="section-padding" style={{ backgroundColor: 'var(--white)' }}>
        <div className="container">
          <div style={{ textAlign: 'center', marginBottom: '3rem' }}>
            <h2 style={{ color: 'var(--dark-text)', fontSize: '2rem', fontFamily: 'var(--font-display)', display: 'inline-flex', alignItems: 'center', gap: '0.5rem' }}>
              SHOP BY CATEGORY <span style={{ fontSize: '1.2rem' }}>🍃</span>
            </h2>
          </div>
          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '2rem', maxWidth: '1000px', margin: '0 auto' }}>
            {categories.slice(0, 3).map((category) => (
              <CategoryCard key={category.id} category={category} />
            ))}
          </div>
        </div>
      </section>

      {/* 3. WHY CHOOSE US */}
      <section className="section-padding" style={{ backgroundColor: '#fdfbf7', borderTop: '1px solid rgba(0,0,0,0.03)', borderBottom: '1px solid rgba(0,0,0,0.03)' }}>
        <div className="container">
          <div style={{ textAlign: 'center', marginBottom: '3rem' }}>
            <h2 style={{ color: 'var(--dark-text)', fontSize: '1.75rem', fontFamily: 'var(--font-display)', display: 'inline-flex', alignItems: 'center', gap: '0.5rem', textTransform: 'uppercase' }}>
              Why Choose Nuts & Nutrition? <span style={{ fontSize: '1rem' }}>🍃</span>
            </h2>
          </div>
        </div>
          
        <div style={{ overflow: 'hidden', width: '100%', position: 'relative' }}>
          <motion.div 
              animate={{ x: ["0%", "-50%"] }}
              transition={{ repeat: Infinity, ease: "linear", duration: 20 }}
              style={{ display: 'flex', width: 'max-content', gap: '4rem', padding: '1rem 0' }}
            >
              {[
                { icon: <FiCheckCircle size={32}/>, title: "Quality Ingredients", desc: "We use only the finest natural ingredients." },
                { icon: <FiStar size={32}/>, title: "Premium Products", desc: "Carefully crafted for your healthy lifestyle." },
                { icon: <FiPackage size={32}/>, title: "Freshly Packed", desc: "Packed with care to retain freshness." },
                { icon: <FiHeart size={32}/>, title: "Natural Goodness", desc: "Pure, natural and absolutely delicious." },
                { icon: <FiSmile size={32}/>, title: "Customer First", desc: "Your satisfaction is our top priority." },
                // Duplicate for infinite scroll
                { icon: <FiCheckCircle size={32}/>, title: "Quality Ingredients", desc: "We use only the finest natural ingredients." },
                { icon: <FiStar size={32}/>, title: "Premium Products", desc: "Carefully crafted for your healthy lifestyle." },
                { icon: <FiPackage size={32}/>, title: "Freshly Packed", desc: "Packed with care to retain freshness." },
                { icon: <FiHeart size={32}/>, title: "Natural Goodness", desc: "Pure, natural and absolutely delicious." },
                { icon: <FiSmile size={32}/>, title: "Customer First", desc: "Your satisfaction is our top priority." }
              ].map((feature, idx) => (
                <motion.div 
                  key={idx} 
                  whileHover={{ y: -10, scale: 1.05 }}
                  transition={{ type: 'spring', stiffness: 300 }}
                  style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', cursor: 'default', minWidth: '200px', textAlign: 'center' }}
                >
                  <div style={{ width: '80px', height: '80px', borderRadius: '50%', backgroundColor: 'var(--white)', border: '1px solid rgba(0,0,0,0.05)', display: 'flex', alignItems: 'center', justifyContent: 'center', color: 'var(--primary-green)', marginBottom: '1rem', boxShadow: '0 8px 20px rgba(0,0,0,0.06)' }}>
                    {feature.icon}
                  </div>
                  <h4 style={{ fontSize: '0.9rem', marginBottom: '0.5rem', fontWeight: 'bold' }}>{feature.title}</h4>
                  <p style={{ fontSize: '0.75rem', color: 'var(--secondary-text)', lineHeight: 1.4 }}>{feature.desc}</p>
                </motion.div>
              ))}
            </motion.div>
          </div>
      </section>

      {/* 4. BEST SELLERS */}
      <section className="section-padding" style={{ backgroundColor: 'var(--white)' }}>
        <div className="container">
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '3rem' }}>
            <h2 style={{ color: 'var(--dark-text)', fontSize: '2rem', fontFamily: 'var(--font-display)', display: 'inline-flex', alignItems: 'center', gap: '0.5rem', margin: 0 }}>
              BEST SELLERS <span style={{ fontSize: '1.2rem' }}>🍃</span>
            </h2>
            <Link to="/shop" style={{ color: 'var(--primary-green)', fontWeight: '600', textDecoration: 'none', fontSize: '0.9rem', display: 'flex', alignItems: 'center', gap: '0.25rem' }}>
              View All Products <FiArrowRight />
            </Link>
          </div>
          
          {!isBoxOpened ? (
            <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', padding: '4rem 0', minHeight: '300px' }}>
              <motion.div
                initial={{ scale: 0 }}
                animate={{ 
                  scale: [0, 1, 1.1, 1, 1.05, 1],
                  rotate: [0, -10, 10, -15, 15, -20, 20, -10, 10, 0]
                }}
                transition={{ duration: 1.5, ease: "easeInOut" }}
                onAnimationComplete={() => {
                  setTimeout(() => setIsBoxOpened(true), 300);
                }}
                onClick={() => setIsBoxOpened(true)}
                style={{ 
                  color: '#d4af37', 
                  padding: '2.5rem', 
                  backgroundColor: '#fdfbf7', 
                  borderRadius: '50%', 
                  boxShadow: '0 15px 35px rgba(212,175,55,0.2)', 
                  cursor: 'pointer',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center'
                }}
              >
                <FiPackage size={70} />
              </motion.div>
              <h3 style={{ marginTop: '2rem', color: 'var(--dark-brown)', fontFamily: 'var(--font-display)', fontSize: '1.5rem', letterSpacing: '1px' }}>
                Unboxing Best Sellers...
              </h3>
            </div>
          ) : (
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(240px, 1fr))', gap: '1.5rem', marginBottom: '2rem' }}>
              {featuredProducts.map((product, i) => (
                <motion.div 
                  key={product.id}
                  initial={{ opacity: 0, scale: 0.8, y: 30 }}
                  animate={{ opacity: 1, scale: 1, y: 0 }}
                  transition={{ delay: i * 0.15, type: "spring", stiffness: 200, damping: 20 }}
                >
                  <ProductCard product={product} />
                </motion.div>
              ))}
            </div>
          )}

          {/* Carousel Dots Mock */}
          <div style={{ display: 'flex', justifyContent: 'center', gap: '0.5rem' }}>
            <div style={{ width: '10px', height: '10px', borderRadius: '50%', backgroundColor: 'var(--primary-green)' }}></div>
            <div style={{ width: '10px', height: '10px', borderRadius: '50%', backgroundColor: 'var(--border)' }}></div>
            <div style={{ width: '10px', height: '10px', borderRadius: '50%', backgroundColor: 'var(--border)' }}></div>
            <div style={{ width: '10px', height: '10px', borderRadius: '50%', backgroundColor: 'var(--border)' }}></div>
            <div style={{ width: '10px', height: '10px', borderRadius: '50%', backgroundColor: 'var(--border)' }}></div>
          </div>
        </div>
      </section>

      {/* DYNAMIC SECTIONS FROM CRM */}
      {dynamicSections.map(section => (
        <section key={section.id} className="section-padding" style={{ backgroundColor: 'var(--white)', borderTop: '1px solid rgba(0,0,0,0.03)' }}>
          <div className="container">
            <div style={{ display: 'flex', flexDirection: 'column', marginBottom: '3rem' }}>
              <h2 style={{ color: 'var(--dark-text)', fontSize: '2rem', fontFamily: 'var(--font-display)', display: 'inline-flex', alignItems: 'center', gap: '0.5rem', margin: 0 }}>
                {section.title} <span style={{ fontSize: '1.2rem' }}>🍃</span>
              </h2>
              {section.subtitle && <p style={{ color: 'var(--secondary-text)', marginTop: '0.5rem' }}>{section.subtitle}</p>}
            </div>
            
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(240px, 1fr))', gap: '1.5rem', marginBottom: '2rem' }}>
              {section.products && section.products.map((product, i) => (
                <motion.div 
                  key={product.id}
                  initial={{ opacity: 0, scale: 0.8, y: 30 }}
                  whileInView={{ opacity: 1, scale: 1, y: 0 }}
                  viewport={{ once: true, amount: 0.1 }}
                  transition={{ delay: (i % 4) * 0.15, type: "spring", stiffness: 200, damping: 20 }}
                >
                  <ProductCard product={product} />
                </motion.div>
              ))}
              
              {(!section.products || section.products.length === 0) && (
                <div style={{ gridColumn: '1 / -1', textAlign: 'center', padding: '3rem', color: 'var(--secondary-text)' }}>
                  No products found for this section.
                </div>
              )}
            </div>
          </div>
        </section>
      ))}

      {/* 5. TESTIMONIAL & NEWSLETTER */}
      <section className="section-padding" style={{ backgroundColor: '#fdfbf7', borderTop: '1px solid rgba(0,0,0,0.03)' }}>
        <div className="container">
          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(320px, 1fr))', gap: '5rem', alignItems: 'center' }}>
            
            {/* Interactive Testimonial Carousel */}
            <div>
              <h3 style={{ fontSize: '1.2rem', fontFamily: 'var(--font-display)', marginBottom: '2rem', display: 'flex', alignItems: 'center', gap: '0.5rem', textTransform: 'uppercase', letterSpacing: '1px', color: 'var(--brand-red)' }}>
                What Our Customers Say <span style={{ fontSize: '1.2rem' }}>🍃</span>
              </h3>
              
              <div style={{ position: 'relative', minHeight: '220px' }}>
                <div style={{ color: '#d4af37', fontSize: '4rem', fontFamily: 'serif', lineHeight: 0.5, marginBottom: '1rem', opacity: 0.5 }}>"</div>
                
                <motion.div
                  key={currentReview}
                  initial={{ opacity: 0, x: 20 }}
                  animate={{ opacity: 1, x: 0 }}
                  exit={{ opacity: 0, x: -20 }}
                  transition={{ duration: 0.5 }}
                >
                  <p style={{ fontSize: '1.25rem', color: 'var(--dark-brown)', lineHeight: 1.7, marginBottom: '2rem', fontWeight: '400', fontStyle: 'italic' }}>
                    {reviews[currentReview].text}
                  </p>
                  <div style={{ display: 'flex', alignItems: 'center', gap: '1rem' }}>
                    <div style={{ width: '55px', height: '55px', borderRadius: '50%', backgroundColor: 'var(--soft-green)', overflow: 'hidden', border: '2px solid white', boxShadow: '0 5px 15px rgba(0,0,0,0.05)' }}>
                      <img src={reviews[currentReview].avatar} alt={reviews[currentReview].author} style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                    </div>
                    <div>
                      <div style={{ color: '#d4af37', fontSize: '0.8rem', marginBottom: '0.25rem', display: 'flex', gap: '2px' }}>
                        <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                      </div>
                      <h5 style={{ margin: 0, fontSize: '1rem', color: 'var(--dark-green)' }}>{reviews[currentReview].author}</h5>
                      <span style={{ fontSize: '0.8rem', color: 'var(--secondary-text)' }}>{reviews[currentReview].role}</span>
                    </div>
                  </div>
                </motion.div>
              </div>

              {/* Interactive Dots */}
              <div style={{ display: 'flex', gap: '0.75rem', marginTop: '2rem' }}>
                {reviews.map((_, idx) => (
                  <button 
                    key={idx}
                    onClick={() => setCurrentReview(idx)}
                    style={{ 
                      width: '44px', 
                      height: '44px', 
                      display: 'flex',
                      alignItems: 'center',
                      justifyContent: 'center',
                      backgroundColor: 'transparent',
                      border: 'none',
                      cursor: 'pointer',
                      padding: 0
                    }}
                    aria-label={`Go to review ${idx + 1}`}
                  >
                    <div style={{
                      width: '10px',
                      height: '10px',
                      borderRadius: '50%',
                      backgroundColor: currentReview === idx ? 'var(--dark-green)' : 'rgba(0,0,0,0.1)',
                      transition: 'all 0.3s ease',
                    }}></div>
                  </button>
                ))}
              </div>
            </div>

            {/* Redesigned Premium Newsletter */}
            <div style={{ 
              backgroundColor: 'white', 
              padding: '4rem 3rem', 
              borderRadius: '24px', 
              boxShadow: '0 20px 40px rgba(0,0,0,0.04)',
              border: '1px solid rgba(0,0,0,0.02)'
            }}>
              <h3 style={{ fontSize: '1.75rem', fontFamily: 'var(--font-display)', marginBottom: '1rem', color: 'var(--dark-brown)' }}>
                Stay Healthy. Stay Updated.
              </h3>
              
              {isSubscribed ? (
                <div style={{ padding: '2rem 0', textAlign: 'center' }}>
                  <FiCheckCircle size={48} color="var(--primary-green)" style={{ marginBottom: '1rem' }} />
                  <h4 style={{ color: 'var(--dark-green)', marginBottom: '0.5rem', fontSize: '1.25rem' }}>Thank You!</h4>
                  <p style={{ color: 'var(--secondary-text)', fontSize: '0.95rem' }}>You have successfully subscribed to our newsletter.</p>
                </div>
              ) : (
                <>
                  <p style={{ fontSize: '1.05rem', color: 'var(--secondary-text)', marginBottom: '2.5rem', lineHeight: 1.6 }}>
                    Subscribe to our newsletter to receive exclusive offers, nutrition tips, and early access to our new product launches.
                  </p>
                  <form style={{ display: 'flex', flexDirection: 'column', gap: '1rem' }} onSubmit={handleSubscribe}>
                    <input 
                      type="email" 
                      required
                      value={email}
                      onChange={(e) => setEmail(e.target.value)}
                      placeholder="Enter your email address" 
                      style={{ 
                        width: '100%', 
                        padding: '1.1rem 1.25rem', 
                        borderRadius: '12px', 
                        border: '1px solid rgba(0,0,0,0.15)', 
                        backgroundColor: '#fafafa',
                        outline: 'none', 
                        fontSize: '1rem',
                        transition: 'all 0.3s ease',
                        color: 'var(--dark-text)'
                      }}
                      onFocus={(e) => e.target.style.border = '1px solid var(--primary-green)'}
                      onBlur={(e) => e.target.style.border = '1px solid rgba(0,0,0,0.15)'}
                    />
                    <motion.button 
                      whileHover={{ scale: 1.02, backgroundColor: '#c71f25' }}
                      whileTap={{ scale: 0.98 }}
                      type="submit" 
                      style={{ 
                        width: '100%',
                        backgroundColor: 'var(--brand-red)', 
                        color: 'white', 
                        border: 'none', 
                        padding: '1.1rem', 
                        borderRadius: '12px', 
                        fontWeight: '600', 
                        fontSize: '1rem',
                        cursor: 'pointer',
                        boxShadow: '0 10px 20px rgba(229, 47, 54, 0.2)',
                        transition: 'background-color 0.3s ease'
                      }}
                    >
                      SUBSCRIBE NOW
                    </motion.button>
                  </form>
                  <p style={{ fontSize: '0.85rem', color: 'var(--secondary-text)', marginTop: '1.5rem', textAlign: 'center' }}>We respect your privacy. No spam, ever.</p>
                </>
              )}
            </div>

          </div>
        </div>
      </section>

      {/* 6. BRAND VIDEO */}
      <section style={{ width: '100%', overflow: 'hidden', backgroundColor: 'var(--dark-green)' }}>
        <video 
          src="/images/main video.mp4" 
          autoPlay 
          loop 
          muted 
          playsInline 
          style={{ 
            width: '100%', 
            height: 'auto',
            display: 'block'
          }}
        />
      </section>

    </div>
  );
};

export default Home;
