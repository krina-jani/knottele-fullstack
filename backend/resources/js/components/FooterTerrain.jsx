import React, { useRef, useState, useEffect } from 'react';
import { motion, useScroll, useTransform, useSpring } from 'framer-motion';

const FooterTerrain = () => {
  const containerRef = useRef(null);
  
  // Scroll Parallax Setup
  const { scrollYProgress } = useScroll({
    target: containerRef,
    offset: ["start end", "end end"]
  });

  // Mountain layer scroll movements (parallax)
  const distantY = useTransform(scrollYProgress, [0, 1], [0, -10]);
  const backY = useTransform(scrollYProgress, [0, 1], [20, -30]);
  const middleY = useTransform(scrollYProgress, [0, 1], [50, -60]);
  const frontY = useTransform(scrollYProgress, [0, 1], [100, -20]); // Foreground moves up dramatically

  // Mouse Parallax Setup
  const [mousePosition, setMousePosition] = useState({ x: 0, y: 0 });
  const springConfig = { damping: 25, stiffness: 120, mass: 0.5 };
  
  const mouseX = useSpring(0, springConfig);
  const mouseY = useSpring(0, springConfig);

  useEffect(() => {
    const handleMouseMove = (e) => {
      // Normalize mouse coordinates to range [-1, 1]
      const { innerWidth, innerHeight } = window;
      const x = (e.clientX / innerWidth) * 2 - 1;
      const y = (e.clientY / innerHeight) * 2 - 1;
      
      mouseX.set(x);
      mouseY.set(y);
    };

    window.addEventListener('mousemove', handleMouseMove);
    return () => window.removeEventListener('mousemove', handleMouseMove);
  }, [mouseX, mouseY]);

  // Layer Horizontal Parallax Multipliers (Mouse)
  const distantX = useTransform(mouseX, [-1, 1], [-2, 2]);
  const backX = useTransform(mouseX, [-1, 1], [-6, 6]);
  const middleX = useTransform(mouseX, [-1, 1], [-15, 15]);
  const frontX = useTransform(mouseX, [-1, 1], [-30, 30]);

  // Particles
  const particles = Array.from({ length: 25 }).map((_, i) => ({
    id: i,
    left: `${Math.random() * 100}%`,
    bottom: `${Math.random() * 80}%`,
    size: Math.random() * 6 + 2, // 2px - 8px
    duration: Math.random() * 15 + 10, // 10s - 25s
    delay: Math.random() * 10,
    color: Math.random() > 0.8 ? 'rgba(220, 38, 38, 0.4)' : (Math.random() > 0.5 ? 'rgba(253, 251, 247, 0.3)' : 'rgba(139, 195, 74, 0.3)')
  }));

  return (
    <div 
      ref={containerRef}
      style={{
        position: 'absolute',
        bottom: 0,
        left: 0,
        width: '100%',
        height: '400px', // Tall enough for parallax travel
        overflow: 'hidden',
        pointerEvents: 'none',
        zIndex: 0
      }}
    >
      <style>{`
        .terrain-layer {
          position: absolute;
          bottom: -50px;
          left: -5%;
          width: 110%; /* wider to allow mouse pan without exposing edges */
          height: 100%;
          background-position: bottom center;
          background-repeat: no-repeat;
          background-size: cover;
          will-change: transform;
        }

        .particle {
          position: absolute;
          border-radius: 50%;
          filter: blur(1px);
          animation: floatUp linear infinite;
        }

        @keyframes floatUp {
          0% { transform: translateY(50px) translateX(0px); opacity: 0; }
          20% { opacity: 1; }
          80% { opacity: 1; }
          100% { transform: translateY(-300px) translateX(50px); opacity: 0; }
        }
      `}</style>

      {/* Layer 4: Distant Atmospheric Terrain */}
      <motion.div 
        className="terrain-layer"
        style={{
          y: distantY,
          x: distantX,
          backgroundImage: 'url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 1440 400\' preserveAspectRatio=\'none\'%3E%3Cpath fill=\'%23d5ded3\' d=\'M0,250 C300,100 600,300 900,150 C1200,50 1440,250 1440,250 L1440,400 L0,400 Z\'/%3E%3C/svg%3E")',
          opacity: 0.6,
          filter: 'blur(3px)'
        }}
      />

      {/* Layer 3: Background Mountain */}
      <motion.div 
        className="terrain-layer"
        style={{
          y: backY,
          x: backX,
          backgroundImage: 'url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 1440 400\' preserveAspectRatio=\'none\'%3E%3Cpath fill=\'%238db38f\' d=\'M0,300 C200,200 400,350 700,200 C1000,50 1200,250 1440,150 L1440,400 L0,400 Z\'/%3E%3C/svg%3E")',
          opacity: 0.9,
          filter: 'blur(1px) drop-shadow(0 -10px 20px rgba(0,0,0,0.05))'
        }}
      />

      {/* Particles injected between middle and back layer */}
      {particles.map((p) => (
        <div 
          key={p.id}
          className="particle"
          style={{
            left: p.left,
            bottom: p.bottom,
            width: p.size,
            height: p.size,
            backgroundColor: p.color,
            animationDuration: `${p.duration}s`,
            animationDelay: `${p.delay}s`,
            boxShadow: `0 0 ${p.size * 2}px ${p.color}`
          }}
        />
      ))}

      {/* Layer 2: Middle Mountain */}
      <motion.div 
        className="terrain-layer"
        style={{
          y: middleY,
          x: middleX,
          backgroundImage: 'url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 1440 400\' preserveAspectRatio=\'none\'%3E%3Cpath fill=\'%232a5d2c\' d=\'M0,350 C300,450 500,200 800,280 C1100,360 1300,100 1440,250 L1440,400 L0,400 Z\'/%3E%3C/svg%3E")',
          filter: 'drop-shadow(0 -10px 30px rgba(0,0,0,0.15))'
        }}
      />

      {/* Layer 1: Foreground Mountain with Red Contour */}
      <motion.div 
        className="terrain-layer"
        style={{
          y: frontY,
          x: frontX,
          backgroundImage: 'url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 1440 400\' preserveAspectRatio=\'none\'%3E%3Cpath fill=\'%23112812\' stroke=\'%23dc2626\' stroke-width=\'3\' stroke-opacity=\'0.7\' filter=\'drop-shadow(0 -5px 15px rgba(220, 38, 38, 0.4))\' d=\'M0,450 C200,300 400,380 650,250 C900,120 1150,320 1440,280 L1440,400 L0,400 Z\'/%3E%3C/svg%3E")',
          filter: 'drop-shadow(0 -15px 40px rgba(0,0,0,0.3))'
        }}
      />

      {/* Blend overlay at the very bottom for footer text readability if needed */}
      <div style={{
        position: 'absolute',
        bottom: 0,
        left: 0,
        width: '100%',
        height: '150px',
        background: 'linear-gradient(to top, #112812 40%, transparent 100%)',
        zIndex: 5
      }}/>
    </div>
  );
};

export default FooterTerrain;
