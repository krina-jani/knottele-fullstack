import React from 'react';

const FooterWaves = () => {
  return (
    <div style={{
      position: 'absolute',
      top: 0,
      left: 0,
      width: '100%',
      height: '100%',
      overflow: 'hidden',
      zIndex: -2,
      backgroundColor: '#fdfbf7' // Premium soft cream background
    }}>
      <style>{`
        .wave-container {
          position: absolute;
          bottom: 0;
          left: 0;
          width: 200%;
          height: 100%;
          background-repeat: repeat-x;
          background-position: 0 bottom;
          background-size: 50% 100%;
          animation: wave-animation linear infinite;
        }

        .wave1 {
          background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320' preserveAspectRatio='none'%3E%3Cpath fill='rgba(212, 175, 55, 0.15)' d='M0,192L48,197.3C96,203,192,213,288,229.3C384,245,480,267,576,250.7C672,235,768,181,864,181.3C960,181,1056,235,1152,234.7C1248,235,1344,181,1392,154.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z'%3E%3C/path%3E%3C/svg%3E");
          animation-duration: 35s;
          filter: drop-shadow(0 -5px 15px rgba(212, 175, 55, 0.2));
          z-index: 1;
        }

        .wave2 {
          background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320' preserveAspectRatio='none'%3E%3Cpath fill='rgba(139, 195, 74, 0.2)' d='M0,160L60,176C120,192,240,224,360,208C480,192,600,128,720,106.7C840,85,960,107,1080,133.3C1200,160,1320,192,1380,208L1440,224L1440,320L1380,320C1320,320,1200,320,1080,320C960,320,840,320,720,320C600,320,480,320,360,320C240,320,120,320,60,320L0,320Z'%3E%3C/path%3E%3C/svg%3E");
          animation-duration: 25s;
          animation-direction: reverse;
          filter: drop-shadow(0 -10px 20px rgba(139, 195, 74, 0.3));
          z-index: 2;
        }

        .wave3 {
          background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320' preserveAspectRatio='none'%3E%3Cpath fill='rgba(42, 93, 44, 0.15)' d='M0,256L80,234.7C160,213,320,171,480,165.3C640,160,800,192,960,202.7C1120,213,1280,203,1360,197.3L1440,192L1440,320L1360,320C1280,320,1120,320,960,320C800,320,640,320,480,320C320,320,160,320,80,320L0,320Z'%3E%3C/path%3E%3C/svg%3E");
          animation-duration: 20s;
          filter: drop-shadow(0 -15px 25px rgba(42, 93, 44, 0.25));
          z-index: 3;
        }
        
        .wave4 {
          background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320' preserveAspectRatio='none'%3E%3Cpath fill='rgba(255, 255, 255, 0.4)' d='M0,64L48,85.3C96,107,192,149,288,154.7C384,160,480,128,576,133.3C672,139,768,181,864,186.7C960,192,1056,160,1152,149.3C1248,139,1344,149,1392,154.7L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z'%3E%3C/path%3E%3C/svg%3E");
          animation-duration: 28s;
          filter: drop-shadow(0 -5px 20px rgba(255, 255, 255, 0.8));
          z-index: 4;
        }

        @keyframes wave-animation {
          0% { transform: translateX(0); }
          100% { transform: translateX(-50%); }
        }

        .particle {
          position: absolute;
          border-radius: 50%;
          background: rgba(139, 195, 74, 0.2);
          animation: float linear infinite;
          pointer-events: none;
          box-shadow: 0 0 10px rgba(139, 195, 74, 0.3);
        }

        @keyframes float {
          0% { transform: translateY(100vh) scale(0.5); opacity: 0; }
          10% { opacity: 1; }
          80% { opacity: 1; }
          100% { transform: translateY(-20vh) scale(1.5); opacity: 0; }
        }
      `}</style>

      {/* Floating Particles */}
      <div className="particle" style={{ width: '6px', height: '6px', left: '15%', animationDuration: '30s', animationDelay: '0s' }}></div>
      <div className="particle" style={{ width: '12px', height: '12px', left: '35%', animationDuration: '25s', animationDelay: '5s', background: 'rgba(212,175,55,0.2)' }}></div>
      <div className="particle" style={{ width: '8px', height: '8px', left: '55%', animationDuration: '35s', animationDelay: '2s' }}></div>
      <div className="particle" style={{ width: '15px', height: '15px', left: '75%', animationDuration: '22s', animationDelay: '8s' }}></div>
      <div className="particle" style={{ width: '10px', height: '10px', left: '85%', animationDuration: '28s', animationDelay: '1s' }}></div>
      <div className="particle" style={{ width: '5px', height: '5px', left: '5%', animationDuration: '40s', animationDelay: '12s' }}></div>

      {/* Waves */}
      <div className="wave-container wave1"></div>
      <div className="wave-container wave2"></div>
      <div className="wave-container wave3"></div>
      <div className="wave-container wave4"></div>
      
      {/* Light gradient overlay to blend top seamlessly */}
      <div style={{
        position: 'absolute',
        top: 0,
        left: 0,
        width: '100%',
        height: '30%',
        background: 'linear-gradient(to bottom, rgba(253,251,247,1) 0%, transparent 100%)',
        zIndex: 5
      }}></div>
    </div>
  );
};

export default FooterWaves;
