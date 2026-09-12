import React from 'react';
import { FiSearch, FiX } from 'react-icons/fi';

const SearchBar = ({ searchQuery, setSearchQuery }) => {
  return (
    <div style={{ position: 'relative', width: '100%', maxWidth: '400px' }}>
      <div style={{ 
        position: 'absolute', 
        left: '12px', 
        top: '50%', 
        transform: 'translateY(-50%)',
        color: 'var(--secondary-text)'
      }}>
        <FiSearch size={18} />
      </div>
      
      <input 
        type="text" 
        placeholder="Search for products, categories..."
        value={searchQuery}
        onChange={(e) => setSearchQuery(e.target.value)}
        style={{
          width: '100%',
          padding: '0.75rem 2.5rem 0.75rem 2.5rem',
          borderRadius: 'var(--radius-full)',
          border: '1px solid var(--border)',
          fontSize: '0.95rem',
          outline: 'none',
          transition: 'border-color 0.3s'
        }}
        onFocus={(e) => e.target.style.borderColor = 'var(--primary-green)'}
        onBlur={(e) => e.target.style.borderColor = 'var(--border)'}
      />

      {searchQuery && (
        <button 
          onClick={() => setSearchQuery('')}
          style={{ 
            position: 'absolute', 
            right: '12px', 
            top: '50%', 
            transform: 'translateY(-50%)',
            color: 'var(--secondary-text)',
            cursor: 'pointer',
            backgroundColor: 'transparent',
            border: 'none',
            display: 'flex',
            alignItems: 'center'
          }}
        >
          <FiX size={18} />
        </button>
      )}
    </div>
  );
};

export default SearchBar;
