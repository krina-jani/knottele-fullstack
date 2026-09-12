import React, { useState, useMemo, useEffect } from 'react';
import { useLocation, useNavigate } from 'react-router-dom';
import { motion } from 'framer-motion';
import { FiFilter } from 'react-icons/fi';
import ProductCard from '../components/ProductCard';
import SearchBar from '../components/SearchBar';
import { productService } from '../services/productService';
import { categoryService } from '../services/categoryService';
import { useAutoRefresh } from '../context/AutoRefreshContext';

// Same premium palette used in About/Contact pages
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

const Shop = () => {
  const location = useLocation();
  const navigate = useNavigate();
  const searchParams = new URLSearchParams(location.search);
  const initialSort = searchParams.get('sort') || 'featured';
  
  // State
  const [searchQuery, setSearchQuery] = useState('');
  const [selectedCategory, setSelectedCategory] = useState('all');
  const [sortBy, setSortBy] = useState(initialSort);
  const [categories, setCategories] = useState([]);
  const [products, setProducts] = useState([]);

  const { refreshTrigger } = useAutoRefresh();

  useEffect(() => {
    const fetchProducts = async () => {
      try {
        const response = await productService.getAll();
        setProducts(response.data?.products || []);
      } catch (error) {
        console.error("Failed to fetch products:", error);
      }
    };
    fetchProducts();
  }, [refreshTrigger]);

  useEffect(() => {
    const fetchCategories = async () => {
      const data = await categoryService.getAllCategories();
      setCategories(data);
    };
    fetchCategories();
  }, [refreshTrigger]);

  useEffect(() => {
    const path = location.pathname;
    if (path.startsWith('/category/')) {
      const slug = path.split('/')[2];
      setSelectedCategory(slug);
    } else {
      setSelectedCategory('all');
    }
    
    const sort = searchParams.get('sort');
    if (sort) setSortBy(sort);
  }, [location]);

  // Filtering and Sorting Logic
  const filteredProducts = useMemo(() => {
    let result = [...products];

    // Category filter
    if (selectedCategory !== 'all') {
      result = result.filter(p => p.category_slug === selectedCategory);
    }

    // Search filter
    if (searchQuery.trim()) {
      const query = searchQuery.toLowerCase();
      result = result.filter(p => 
        p.name.toLowerCase().includes(query) || 
        p.description.toLowerCase().includes(query)
      );
    }

    // Sort
    switch (sortBy) {
      case 'price-low':
        result.sort((a, b) => a.variants[0].price - b.variants[0].price);
        break;
      case 'price-high':
        result.sort((a, b) => b.variants[0].price - a.variants[0].price);
        break;
      case 'rating':
        result.sort((a, b) => b.rating - a.rating);
        break;
      case 'bestseller':
        result.sort((a, b) => (b.bestseller === a.bestseller) ? 0 : b.bestseller ? 1 : -1);
        break;
      case 'featured':
      default:
        result.sort((a, b) => (b.featured === a.featured) ? 0 : b.featured ? 1 : -1);
        break;
    }

    return result;
  }, [selectedCategory, searchQuery, sortBy, products]);

  return (
    <div style={{ backgroundColor: colors.offWhite, minHeight: '100vh', paddingBottom: '6rem' }}>
      
      {/* Premium Hero Banner */}
      <section style={{ 
        position: 'relative', 
        padding: '6rem 0 8rem', 
        backgroundImage: `linear-gradient(rgba(42, 93, 44, 0.75), rgba(74, 59, 50, 0.85)), url('/images/2.jpeg')`,
        backgroundSize: 'cover',
        backgroundPosition: 'center',
        textAlign: 'center',
        color: 'white'
      }}>
        <div className="container" style={{ position: 'relative', zIndex: 1 }}>
          <motion.div initial={{ opacity: 0, y: 30 }} animate={{ opacity: 1, y: 0 }} transition={{ duration: 0.8 }}>
            <h1 style={{ fontSize: 'clamp(2.5rem, 5vw, 4rem)', marginBottom: '1rem', fontFamily: 'var(--font-display)', color: 'white' }}>
              Nourish Your Body.
            </h1>
            <p style={{ fontSize: '1.15rem', color: 'rgba(255,255,255,0.85)', maxWidth: '600px', margin: '0 auto' }}>
              Explore our complete collection of premium, natural nutrition blends crafted to seamlessly fit into your daily routine.
            </p>
          </motion.div>
        </div>
      </section>

      {/* Main Shop Container (Overlapping Hero) */}
      <div className="container" style={{ marginTop: '-4rem', position: 'relative', zIndex: 2 }}>
        
        {/* Horizontal Category Pills */}
        <div style={{ 
          backgroundColor: 'white', 
          borderRadius: '24px', 
          boxShadow: '0 20px 40px rgba(0,0,0,0.06)',
          marginBottom: '2rem',
          border: `1px solid ${colors.cream}`
        }}>
          <div style={{ 
            display: 'flex', 
            gap: '0.75rem', 
            overflowX: 'auto', 
            padding: '1rem', // Responsive padding inside the scroll container
            alignItems: 'center',
            scrollbarWidth: 'none', // Firefox
            msOverflowStyle: 'none', // IE/Edge
            WebkitOverflowScrolling: 'touch' // Smooth scrolling on iOS
          }}>
            <style>{`
              /* Hide scrollbar for Chrome, Safari and Opera */
              div::-webkit-scrollbar {
                display: none;
              }
            `}</style>
            
            <button
              onClick={() => navigate('/shop')}
              style={{
                flexShrink: 0,
                padding: '0.75rem 1.5rem',
                borderRadius: '30px',
                fontWeight: '600',
                fontSize: '1rem',
                border: 'none',
                cursor: 'pointer',
                transition: 'all 0.3s ease',
                backgroundColor: selectedCategory === 'all' ? colors.darkGreen : colors.cream,
                color: selectedCategory === 'all' ? 'white' : colors.darkBrown,
                boxShadow: selectedCategory === 'all' ? '0 10px 20px rgba(42,93,44,0.15)' : 'none'
              }}
            >
              All Products
            </button>
            
            {categories.map(cat => (
              <button
                key={cat.id}
                onClick={() => navigate(`/category/${cat.slug}`)}
                style={{
                  flexShrink: 0,
                  padding: '0.75rem 1.5rem',
                  borderRadius: '30px',
                  fontWeight: '600',
                  fontSize: '1rem',
                  border: 'none',
                  cursor: 'pointer',
                  transition: 'all 0.3s ease',
                  backgroundColor: selectedCategory === cat.slug ? colors.darkGreen : colors.cream,
                  color: selectedCategory === cat.slug ? 'white' : colors.darkBrown,
                  boxShadow: selectedCategory === cat.slug ? '0 10px 20px rgba(42,93,44,0.15)' : 'none'
                }}
              >
                {cat.name}
              </button>
            ))}
          </div>
        </div>

        {/* Controls Bar */}
        <div style={{ 
          display: 'flex', 
          justifyContent: 'space-between', 
          alignItems: 'center', 
          flexWrap: 'wrap',
          gap: '1rem',
          marginBottom: '3rem'
        }}>
          <div style={{ flex: '1 1 300px', maxWidth: '400px' }}>
            <SearchBar searchQuery={searchQuery} setSearchQuery={setSearchQuery} />
          </div>

          <div style={{ display: 'flex', alignItems: 'center', gap: '1rem' }}>
            <span style={{ color: colors.lightText, fontSize: '0.95rem', fontWeight: '500' }}>
              Showing {filteredProducts.length} items
            </span>
            <select 
              value={sortBy} 
              onChange={(e) => setSortBy(e.target.value)}
              style={{
                padding: '0.75rem 1.5rem',
                borderRadius: '30px',
                border: `1px solid ${colors.cream}`,
                backgroundColor: 'white',
                color: colors.darkBrown,
                fontSize: '0.95rem',
                fontWeight: '500',
                cursor: 'pointer',
                boxShadow: '0 5px 15px rgba(0,0,0,0.02)',
                outline: 'none'
              }}
            >
              <option value="featured">Featured</option>
              <option value="bestseller">Best Sellers</option>
              <option value="price-low">Price: Low to High</option>
              <option value="price-high">Price: High to Low</option>
              <option value="rating">Highest Rated</option>
            </select>
          </div>
        </div>

        {/* Product Grid */}
        <div>
          {filteredProducts.length === 0 ? (
            <motion.div 
              initial={{ opacity: 0 }} animate={{ opacity: 1 }}
              style={{ textAlign: 'center', padding: '6rem 0', backgroundColor: 'white', borderRadius: '24px', border: `1px solid ${colors.cream}` }}
            >
              <h3 style={{ color: colors.darkBrown, fontSize: '1.5rem', marginBottom: '0.5rem', fontFamily: 'var(--font-display)' }}>No products found.</h3>
              <p style={{ color: colors.lightText }}>Try adjusting your search or category filters.</p>
              <button 
                onClick={() => {setSearchQuery(''); setSelectedCategory('all');}}
                style={{ 
                  marginTop: '1.5rem',
                  backgroundColor: 'transparent',
                  border: `2px solid ${colors.darkGreen}`,
                  color: colors.darkGreen,
                  padding: '0.75rem 2rem',
                  borderRadius: '30px',
                  fontWeight: '600',
                  cursor: 'pointer',
                  transition: 'all 0.3s ease'
                }}
                onMouseEnter={(e) => { e.target.style.backgroundColor = colors.darkGreen; e.target.style.color = 'white'; }}
                onMouseLeave={(e) => { e.target.style.backgroundColor = 'transparent'; e.target.style.color = colors.darkGreen; }}
              >
                Clear Filters
              </button>
            </motion.div>
          ) : (
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill, minmax(280px, 1fr))', gap: '2rem' }}>
              {filteredProducts.map((product, i) => (
                <motion.div 
                  key={product.id}
                  initial={{ opacity: 0, y: 30 }}
                  animate={{ opacity: 1, y: 0 }}
                  transition={{ duration: 0.5, delay: i * 0.05 }}
                >
                  <ProductCard product={product} />
                </motion.div>
              ))}
            </div>
          )}
        </div>
      </div>
    </div>
  );
};

export default Shop;
