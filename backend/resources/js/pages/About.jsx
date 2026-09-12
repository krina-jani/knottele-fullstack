import React from 'react';
import { motion } from 'framer-motion';
import { FiCheckCircle, FiSmile, FiClock, FiShield, FiHeart, FiArrowRight } from 'react-icons/fi';
import { Link } from 'react-router-dom';

// Colors based on the Master Prompt palette
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

const About = () => {
  return (
    <div style={{ backgroundColor: colors.offWhite, color: colors.text, overflowX: 'hidden' }}>
      
      <style>{`
        .section-padding {
          padding: 7rem 0;
        }
        @media (max-width: 768px) {
          .section-padding {
            padding: 4rem 0;
          }
        }
        @media (max-width: 480px) {
          .section-padding {
            padding: 3rem 0;
          }
        }
      `}</style>

      {/* HERO VIDEO SECTION */}
      <section style={{ width: '100%', position: 'relative', overflow: 'hidden', backgroundColor: colors.darkBrown }}>
        <motion.div
          initial={{ opacity: 0, scale: 1.1 }}
          animate={{ opacity: 1, scale: 1 }}
          transition={{ duration: 1.5, ease: "easeOut" }}
          style={{ width: '100%', height: 'auto', position: 'relative', display: 'flex', alignItems: 'center', justifyContent: 'center' }}
        >
          <video 
            autoPlay 
            loop 
            muted 
            playsInline
            style={{ width: '100%', height: 'auto', objectFit: 'contain', opacity: 1, display: 'block' }}
          >
            <source src="/images/Commercial_for_Nuts_&_Nutrition_202609020946.mp4" type="video/mp4" />
            Your browser does not support the video tag.
          </video>
        </motion.div>
      </section>

      {/* SECTION A: About Introduction */}
      <section className="section-padding" style={{ position: 'relative', minHeight: '85vh', display: 'flex', alignItems: 'center', backgroundColor: colors.cream, overflow: 'hidden' }}>
        {/* Floating Elements (Abstract representations of almonds, pistachios, etc.) */}
        <motion.div animate={{ y: [0, -20, 0], rotate: [0, 10, 0] }} transition={{ duration: 5, repeat: Infinity, ease: "easeInOut" }} style={{ position: 'absolute', top: '15%', left: '10%', width: '30px', height: '40px', borderRadius: '50% 50% 50% 50% / 60% 60% 40% 40%', backgroundColor: colors.nutBrown, opacity: 0.6 }} />
        <motion.div animate={{ y: [0, 30, 0], rotate: [0, -15, 0] }} transition={{ duration: 6, repeat: Infinity, ease: "easeInOut", delay: 1 }} style={{ position: 'absolute', bottom: '20%', left: '15%', width: '25px', height: '25px', borderRadius: '50%', backgroundColor: colors.softGreen, opacity: 0.8 }} />
        <motion.div animate={{ y: [0, -25, 0], rotate: [0, 20, 0] }} transition={{ duration: 7, repeat: Infinity, ease: "easeInOut", delay: 2 }} style={{ position: 'absolute', top: '25%', right: '10%', width: '15px', height: '15px', borderRadius: '50%', backgroundColor: colors.golden, opacity: 0.7 }} />
        <motion.div animate={{ y: [0, 20, 0], rotate: [0, -10, 0] }} transition={{ duration: 5.5, repeat: Infinity, ease: "easeInOut", delay: 0.5 }} style={{ position: 'absolute', bottom: '30%', right: '15%', width: '40px', height: '20px', borderRadius: '20px', backgroundColor: colors.darkBrown, opacity: 0.4 }} />

        <div className="container">
          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(300px, 1fr))', gap: '4rem', alignItems: 'center' }}>
            <motion.div initial={{ opacity: 0, y: 30 }} animate={{ opacity: 1, y: 0 }} transition={{ duration: 0.8 }}>
              <h1 style={{ fontSize: 'clamp(2.5rem, 5vw, 4rem)', color: colors.darkGreen, lineHeight: 1.1, marginBottom: '1.5rem', fontFamily: 'var(--font-display)' }}>
                Nourishing Your <span style={{ color: colors.nutBrown }}>Everyday Life.</span>
              </h1>
              <p style={{ fontSize: '1.2rem', color: colors.lightText, lineHeight: 1.6, maxWidth: '500px' }}>
                At Nuts & Nutrition, we believe in making everyday nutrition simple, delicious, and deeply convenient. We blend the finest ingredients nature has to offer into perfect daily rituals.
              </p>
            </motion.div>
            <motion.div initial={{ opacity: 0, scale: 0.9 }} animate={{ opacity: 1, scale: 1 }} transition={{ duration: 0.8, delay: 0.2 }} style={{ position: 'relative', display: 'flex', justifyContent: 'center' }}>
              <div style={{ position: 'absolute', width: '300px', height: '300px', backgroundColor: colors.softGreen, borderRadius: '50%', zIndex: 0, top: '50%', transform: 'translateY(-50%)' }} />
              <img src="/images/1.jpeg" alt="Premium Nutrition Powder" style={{ width: '100%', maxWidth: '400px', zIndex: 1, borderRadius: '20px', objectFit: 'cover', boxShadow: '0 20px 40px rgba(0,0,0,0.1)' }} />
            </motion.div>
          </div>
        </div>
      </section>

      {/* SECTION B: Our Story */}
      <section className="section-padding" style={{ backgroundColor: colors.cream, overflow: 'hidden' }}>
        <div className="container">
          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(300px, 1fr))', gap: '5rem', alignItems: 'center' }}>
            
            {/* Left: Image Composition */}
            <motion.div 
              initial={{ opacity: 0, x: -50 }} 
              whileInView={{ opacity: 1, x: 0 }} 
              viewport={{ once: true, margin: "-100px" }} 
              transition={{ duration: 0.8 }}
              style={{ position: 'relative', paddingRight: '2rem', paddingBottom: '2rem' }}
            >
              <div style={{ position: 'absolute', top: '-20px', left: '-20px', width: '100px', height: '100px', backgroundColor: colors.softGreen, borderRadius: '50%', zIndex: 0 }} />
              <img src="/images/almonds_raw.jpg" alt="Our Story" style={{ width: '90%', borderRadius: '24px', objectFit: 'cover', aspectRatio: '4/5', boxShadow: '0 20px 40px rgba(0,0,0,0.1)', position: 'relative', zIndex: 1 }} />
              <img src="/images/pistachios_raw.jpg" alt="Quality Ingredients" style={{ position: 'absolute', bottom: '0', right: '0', width: '55%', borderRadius: '16px', objectFit: 'cover', aspectRatio: '1', border: `8px solid ${colors.cream}`, boxShadow: '0 20px 40px rgba(0,0,0,0.15)', zIndex: 2 }} />
            </motion.div>

            {/* Right: Editorial Text */}
            <motion.div 
              initial={{ opacity: 0, x: 50 }} 
              whileInView={{ opacity: 1, x: 0 }} 
              viewport={{ once: true, margin: "-100px" }} 
              transition={{ duration: 0.8, delay: 0.2 }}
            >
              <div style={{ width: '40px', height: '3px', backgroundColor: colors.nutBrown, marginBottom: '1.5rem' }} />
              <h2 style={{ fontSize: 'clamp(2.5rem, 4vw, 3.5rem)', color: colors.darkBrown, marginBottom: '2rem', fontFamily: 'var(--font-display)', lineHeight: 1.1 }}>The story behind <br/>our blends.</h2>
              <p style={{ fontSize: '1.15rem', color: colors.lightText, lineHeight: 1.8, marginBottom: '1.5rem' }}>
                Nuts & Nutrition was born from a simple realization: combining true, wholesome nutrition with great taste shouldn't be complicated. In a world full of artificial additives and confusing labels, we set out to create something genuinely pure.
              </p>
              <p style={{ fontSize: '1.15rem', color: colors.lightText, lineHeight: 1.8 }}>
                We focus strictly on the quality of our ingredients—sourcing the finest nuts, seeds, and spices—to craft products that seamlessly fit into your busy life. No compromises, just pure, everyday nutrition made convenient and joyful.
              </p>
            </motion.div>

          </div>
        </div>
      </section>

      {/* SECTION C: Why Choose Us - Bento Box Layout */}
      <style>
        {`
          .bento-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
          }
          @media (min-width: 768px) {
            .bento-grid {
              grid-template-columns: repeat(2, 1fr);
            }
            .bento-item-0 { grid-column: span 2; }
            .bento-item-3 { grid-column: span 2; }
          }
          @media (min-width: 1024px) {
            .bento-grid {
              grid-template-columns: repeat(3, 1fr);
            }
            .bento-item-0 { grid-column: span 2; }
            .bento-item-1 { grid-column: span 1; }
            .bento-item-2 { grid-column: span 1; }
            .bento-item-3 { grid-column: span 1; }
            .bento-item-4 { grid-column: span 1; }
          }
        `}
      </style>
      <section className="section-padding" style={{ backgroundColor: '#f9f8f5' }}>
        <div className="container">
          <div style={{ textAlign: 'center', marginBottom: '4rem' }}>
            <h2 style={{ fontSize: '2.5rem', color: colors.darkGreen, fontFamily: 'var(--font-display)' }}>Why Choose Nuts & Nutrition?</h2>
          </div>
          
          <div className="bento-grid">
            {[
              { icon: FiCheckCircle, title: 'Quality Ingredients', desc: 'We source only the finest, carefully selected ingredients from trusted farms. No artificial additives, just pure nature in every scoop to ensure consistent premium quality.', bg: colors.softGreen, color: colors.darkGreen },
              { icon: FiSmile, title: 'Delicious Taste', desc: 'Nutrition should be genuinely enjoyable, never boring or bland.', bg: 'white', color: colors.darkBrown },
              { icon: FiClock, title: 'Everyday Convenience', desc: 'Designed to fit seamlessly into your busy, modern daily routine.', bg: 'white', color: colors.darkBrown },
              { icon: FiShield, title: 'Quality Focused', desc: 'Strict attention to quality and safety throughout the entire journey—from farm to your daily cup.', bg: colors.darkBrown, color: 'white' },
              { icon: FiHeart, title: 'Made With Care', desc: 'Crafted with passion, genuine attention to detail, and a love for wellness.', bg: 'white', color: colors.darkBrown }
            ].map((card, i) => {
              // Natural hover colors
              const hoverBg = colors.softGreen;
              const hoverColor = colors.darkGreen;
              
              return (
              <motion.div 
                key={i}
                initial="initial"
                whileInView="animate"
                whileHover="hover"
                viewport={{ once: true }}
                className={`bento-item-${i}`}
                variants={{
                  initial: { opacity: 0, y: 30, backgroundColor: card.bg, color: card.color },
                  animate: { opacity: 1, y: 0, transition: { duration: 0.6, delay: i * 0.1 } },
                  hover: { 
                    y: -10, 
                    boxShadow: '0 25px 50px rgba(0,0,0,0.12)',
                    backgroundColor: hoverBg,
                    color: hoverColor,
                    transition: { duration: 0.3 }
                  }
                }}
                style={{ 
                  padding: '3rem 2.5rem', 
                  borderRadius: '24px', 
                  display: 'flex',
                  flexDirection: 'column',
                  justifyContent: 'center',
                  alignItems: 'flex-start',
                  border: card.bg === 'white' ? `1px solid rgba(0,0,0,0.04)` : 'none',
                  position: 'relative',
                  overflow: 'hidden',
                  cursor: 'pointer'
                }}
              >
                {/* Subtle large background icon watermark */}
                <motion.div
                  variants={{
                    initial: { scale: 1, rotate: 0, opacity: 0.05 },
                    hover: { scale: 1.4, rotate: -15, opacity: 0.15, transition: { type: 'spring', stiffness: 200 } }
                  }}
                  style={{ position: 'absolute', right: '-20px', bottom: '-20px', color: 'inherit' }}
                >
                  <card.icon size={120} />
                </motion.div>
                
                <motion.div 
                  variants={{
                    initial: { 
                      backgroundColor: card.bg === 'white' ? colors.softGreen : 'rgba(255,255,255,0.2)', 
                      color: card.bg === 'white' ? colors.darkGreen : 'white' 
                    },
                    hover: { 
                      backgroundColor: 'rgba(255,255,255,0.5)', 
                      color: colors.darkGreen 
                    }
                  }}
                  style={{ 
                    display: 'inline-flex', alignItems: 'center', justifyContent: 'center', 
                    width: '50px', height: '50px', borderRadius: '12px', 
                    marginBottom: '1.5rem' 
                  }}>
                  <card.icon size={24} />
                </motion.div>
                <h3 style={{ fontSize: '1.4rem', marginBottom: '1rem', fontFamily: 'var(--font-display)', position: 'relative', zIndex: 1 }}>{card.title}</h3>
                <p style={{ fontSize: '1.05rem', opacity: 0.8, lineHeight: 1.6, maxWidth: '90%', position: 'relative', zIndex: 1 }}>{card.desc}</p>
              </motion.div>
            )})}
          </div>
        </div>
      </section>

      {/* SECTION D: Ingredients & Quality */}
      <section className="section-padding" style={{ backgroundColor: colors.offWhite }}>
        <div className="container">
          <div style={{ textAlign: 'center', marginBottom: '4rem' }}>
            <h2 style={{ fontSize: '2.5rem', color: colors.darkBrown, fontFamily: 'var(--font-display)' }}>Made With Ingredients You Can Appreciate.</h2>
          </div>
          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(180px, 1fr))', gap: '2rem', alignItems: 'start' }}>
            {[
              { name: 'Almonds', img: '/images/almonds_raw.jpg', desc: 'Premium crunch.' },
              { name: 'Pistachios', img: '/images/pistachios_raw.jpg', desc: 'Rich & earthy.' },
              { name: 'Saffron', img: '/images/3.jpeg', desc: 'Golden aroma.' },
              { name: 'Cocoa', img: '/images/cocoa_raw.jpg', desc: 'Deep & satisfying.' },
              { name: 'Nutrition Powder', img: '/images/chocolate_pouch_new.jpg', desc: 'The perfect blend.' }
            ].map((item, i) => (
              <motion.div 
                key={i}
                initial={{ opacity: 0, y: 20 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.5, delay: i * 0.1 }}
                style={{ textAlign: 'center' }}
              >
                <motion.div whileHover={{ scale: 1.05 }} style={{ width: '100%', aspectRatio: '1', borderRadius: '50%', overflow: 'hidden', marginBottom: '1.5rem', border: `4px solid ${colors.cream}`, boxShadow: '0 10px 20px rgba(0,0,0,0.05)' }}>
                  <img src={item.img} alt={item.name} style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                </motion.div>
                <h4 style={{ fontSize: '1.1rem', color: colors.darkGreen, marginBottom: '0.5rem' }}>{item.name}</h4>
                <p style={{ fontSize: '0.9rem', color: colors.lightText }}>{item.desc}</p>
              </motion.div>
            ))}
          </div>
        </div>
      </section>

      {/* SECTION E: Our Promise */}
      <section className="section-padding" style={{ backgroundColor: colors.cream, overflow: 'hidden' }}>
        <div className="container">
          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(300px, 1fr))', gap: '4rem', alignItems: 'center' }}>
            <motion.div initial={{ opacity: 0, x: -30 }} whileInView={{ opacity: 1, x: 0 }} viewport={{ once: true }} transition={{ duration: 0.6 }}>
              <img src="/images/2.jpeg" alt="Our Promise" style={{ width: '100%', borderRadius: '24px', objectFit: 'cover', boxShadow: '0 20px 40px rgba(0,0,0,0.08)' }} />
            </motion.div>
            <motion.div initial={{ opacity: 0, x: 30 }} whileInView={{ opacity: 1, x: 0 }} viewport={{ once: true }} transition={{ duration: 0.6 }}>
              <h2 style={{ fontSize: 'clamp(2rem, 4vw, 3rem)', color: colors.darkBrown, lineHeight: 1.2, marginBottom: '1.5rem', fontFamily: 'var(--font-display)' }}>
                "Good Nutrition Should Be Simple, Delicious & Everyday."
              </h2>
              <p style={{ fontSize: '1.1rem', color: colors.lightText, lineHeight: 1.7 }}>
                Our promise is rooted in a deep commitment to customer satisfaction. We refuse to compromise on taste or quality, ensuring that every product we deliver offers genuine convenience without cutting corners. This is nutrition you can trust, day in and day out.
              </p>
            </motion.div>
          </div>
        </div>
      </section>

      {/* SECTION F: Final About CTA */}
      <section className="section-padding" style={{ 
        backgroundColor: colors.darkGreen, 
        backgroundImage: 'linear-gradient(rgba(42, 93, 44, 0.75), rgba(42, 93, 44, 0.75)), url(/images/nutrition_cta_bg.jpg)',
        backgroundSize: 'cover',
        backgroundPosition: 'center',
        color: 'white', 
        textAlign: 'center' 
      }}>
        <div className="container">
          <motion.div initial={{ opacity: 0, y: 20 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true }} transition={{ duration: 0.6 }}>
            <h2 style={{ fontSize: '2.5rem', color: 'white', marginBottom: '1rem', fontFamily: 'var(--font-display)' }}>Discover Better Everyday Nutrition.</h2>
            <p style={{ fontSize: '1.1rem', color: 'rgba(255,255,255,0.8)', marginBottom: '2.5rem', maxWidth: '500px', margin: '0 auto 2.5rem' }}>
              Explore our range of premium nutrition blends crafted for your healthy lifestyle.
            </p>
            <Link to="/shop" style={{ textDecoration: 'none' }}>
              <motion.button
                whileHover={{ scale: 1.02, backgroundColor: colors.cream, color: colors.darkGreen }}
                whileTap={{ scale: 0.98 }}
                style={{
                  display: 'inline-flex',
                  alignItems: 'center',
                  gap: '0.75rem',
                  backgroundColor: '#ff6b6b',
                  color: 'white',
                  border: 'none',
                  padding: '1rem 2rem',
                  fontSize: '1rem',
                  fontWeight: '600',
                  borderRadius: '30px',
                  cursor: 'pointer',
                  transition: 'all 0.3s ease'
                }}
              >
                Explore Our Products <FiArrowRight />
              </motion.button>
            </Link>
          </motion.div>
        </div>
      </section>

    </div>
  );
};

export default About;
