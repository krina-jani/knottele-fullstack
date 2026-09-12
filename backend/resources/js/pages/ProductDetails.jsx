import React, { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { FiStar, FiHeart, FiCheck, FiTruck, FiShield } from 'react-icons/fi';
import { productService } from '../services/productService';
import { useCart } from '../context/CartContext';
import { useWishlist } from '../context/WishlistContext';
import QuantitySelector from '../components/QuantitySelector';
import Button from '../components/Button';
import { useAutoRefresh } from '../context/AutoRefreshContext';

const ProductDetails = () => {
  const { slug } = useParams();
  const navigate = useNavigate();
  const { addToCart } = useCart();
  const { isInWishlist, toggleWishlist } = useWishlist();
  
  const [product, setProduct] = useState(null);
  const [selectedVariant, setSelectedVariant] = useState(null);
  const [quantity, setQuantity] = useState(1);
  const [mainImage, setMainImage] = useState('');
  const { refreshTrigger } = useAutoRefresh();

  useEffect(() => {
    const fetchProduct = async () => {
      try {
        const response = await productService.getBySlug(slug);
        const data = response.data;
        if (data) {
          // Transform backend images array if needed
          const imageList = data.images && data.images.length > 0 
            ? data.images.map(img => img.url) 
            : [data.main_image];
          
          const transformedProduct = {
            ...data,
            images: imageList,
            bestseller: data.is_bestseller
          };
          
          setProduct(transformedProduct);
          setSelectedVariant(data.variants && data.variants.length > 0 ? data.variants[0] : null);
          setMainImage(imageList[0]);
          setQuantity(1);
        }
      } catch (error) {
        console.error("Failed to fetch product:", error);
      }
    };
    fetchProduct();
  }, [slug, refreshTrigger]);

  if (!product) {
    return (
      <div className="container" style={{ padding: '4rem 0', textAlign: 'center' }}>
        <h2>Product not found.</h2>
        <Button onClick={() => navigate('/shop')} style={{ marginTop: '1rem' }}>Back to Shop</Button>
      </div>
    );
  }

  const handleAddToCart = () => {
    addToCart(product, selectedVariant, quantity);
  };

  const handleBuyNow = () => {
    addToCart(product, selectedVariant, quantity);
    navigate('/checkout');
  };

  return (
    <div className="container" style={{ paddingTop: '3rem', paddingBottom: '5rem' }}>
      <div className="product-details-grid" style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(300px, 1fr))', gap: '4rem' }}>
        
        {/* Left: Image Gallery */}
        <div className="product-gallery-container">
          
          <style>{`
            .product-gallery-container {
              display: flex;
              gap: 1.5rem;
              height: 500px;
            }
            .product-thumbnails {
              display: flex;
              flex-direction: column;
              gap: 1rem;
              overflow-y: auto;
              padding-right: 0.5rem;
            }
            /* Hide scrollbar for clean UI */
            .product-thumbnails::-webkit-scrollbar { display: none; }
            .product-main-image {
              flex: 1;
              background-color: var(--soft-green);
              border-radius: 24px;
              box-shadow: inset 0 0 50px rgba(0,0,0,0.02);
              overflow: hidden;
              position: relative;
            }
            
            @media (max-width: 768px) {
              .product-gallery-container {
                flex-direction: column-reverse;
                height: auto;
              }
              .product-thumbnails {
                flex-direction: row;
                overflow-x: auto;
                overflow-y: hidden;
                padding-right: 0;
                padding-bottom: 0.5rem;
              }
              .product-main-image {
                height: 350px;
                flex: none;
              }
            }
          `}</style>

          {/* Thumbnails */}
          <div className="product-thumbnails">
            
            {product.images.map((img, idx) => (
              <button 
                key={idx}
                onClick={() => setMainImage(img)}
                style={{
                  width: '90px',
                  height: '90px',
                  backgroundColor: 'var(--soft-green)',
                  borderRadius: 'var(--radius-md)',
                  border: mainImage === img ? '2px solid var(--dark-green)' : '2px solid transparent',
                  overflow: 'hidden',
                  flexShrink: 0,
                  cursor: 'pointer',
                  padding: 0,
                  transition: 'all 0.2s'
                }}
                onMouseOver={(e) => {
                  if (mainImage !== img) e.currentTarget.style.border = '2px solid rgba(42,93,44,0.3)';
                }}
                onMouseOut={(e) => {
                  if (mainImage !== img) e.currentTarget.style.border = '2px solid transparent';
                }}
              >
                {img.endsWith('.mp4') ? (
                  <div style={{ position: 'relative', width: '100%', height: '100%' }}>
                    <img src={product.images[0]} alt="Video Thumbnail" style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                    <div style={{ position: 'absolute', top: 0, left: 0, right: 0, bottom: 0, backgroundColor: 'rgba(0,0,0,0.4)', display: 'flex', alignItems: 'center', justifyContent: 'center', color: 'white', fontSize: '1.5rem' }}>
                       ▶
                    </div>
                  </div>
                ) : (
                  <img src={img} alt={`View ${idx+1}`} style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                )}
              </button>
            ))}
          </div>

          {/* Main Image */}
          <div className="product-main-image">
            {mainImage.endsWith('.mp4') ? (
               <video 
                 src={mainImage} 
                 autoPlay 
                 loop 
                 muted 
                 playsInline 
                 style={{ width: '100%', height: '100%', objectFit: 'cover', display: 'block' }} 
               />
            ) : (
               <img 
                 src={mainImage} 
                 alt={product.name} 
                 style={{ width: '100%', height: '100%', objectFit: 'cover', display: 'block' }}
               />
            )}
          </div>
        </div>

        {/* Right: Product Info */}
        <div>
          {product.bestseller && (
            <span style={{ 
              backgroundColor: 'var(--brand-red)', 
              color: 'var(--white)', 
              fontSize: '0.8rem', 
              padding: '4px 12px', 
              borderRadius: 'var(--radius-full)', 
              fontWeight: 'bold',
              display: 'inline-block',
              marginBottom: '1rem'
            }}>
              BESTSELLER
            </span>
          )}

          {product.brand && (
            <h2 style={{ fontSize: '1rem', textTransform: 'uppercase', color: 'var(--secondary-text)', letterSpacing: '2px', marginBottom: '0.5rem', fontWeight: '600' }}>
              {product.brand.name}
            </h2>
          )}
          
          <h1 style={{ fontSize: '2.5rem', marginBottom: '0.5rem', color: 'var(--dark-text)' }}>{product.name}</h1>
          
          <div style={{ display: 'flex', alignItems: 'center', gap: '0.5rem', marginBottom: '1.5rem' }}>
            <div style={{ display: 'flex', color: '#FFB800' }}>
              <FiStar fill="currentColor" />
              <FiStar fill="currentColor" />
              <FiStar fill="currentColor" />
              <FiStar fill="currentColor" />
              <FiStar fill="currentColor" />
            </div>
            <span style={{ fontWeight: '600' }}>{product.rating}</span>
            {product.reviews ? <span style={{ color: 'var(--secondary-text)' }}>({product.reviews} reviews)</span> : null}
          </div>

          <div 
            style={{ fontSize: '1.1rem', marginBottom: '2rem', color: 'var(--secondary-text)' }}
            dangerouslySetInnerHTML={{ __html: product.description }}
          />

          <div style={{ fontSize: '2rem', fontWeight: 'bold', color: 'var(--brand-red)', marginBottom: '1.5rem' }}>
            ₹{selectedVariant?.price}
          </div>

          {/* Variants */}
          <div style={{ marginBottom: '2rem' }}>
            <h4 style={{ fontSize: '1rem', marginBottom: '0.75rem' }}>Select Size</h4>
            <style>{`
              .variant-btn {
                padding: 0.75rem 1.5rem;
                border-radius: var(--radius-md);
                font-weight: 600;
                transition: all 0.2s ease-in-out;
                cursor: pointer;
              }
              .variant-btn.selected {
                border: 2px solid var(--primary-green);
                background-color: var(--soft-green);
                color: var(--dark-green);
                transform: scale(1.02);
                box-shadow: 0 4px 12px rgba(42, 93, 44, 0.1);
              }
              .variant-btn.unselected {
                border: 2px solid var(--border);
                background-color: var(--white);
                color: var(--dark-text);
              }
              .variant-btn.unselected:hover {
                border-color: var(--primary-green);
                background-color: rgba(42, 93, 44, 0.03);
                color: var(--dark-green);
                transform: translateY(-2px);
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
              }
            `}</style>
            <div style={{ display: 'flex', gap: '1rem', flexWrap: 'wrap' }}>
              {product.variants.map(variant => (
                <button
                  key={variant.sku}
                  onClick={() => setSelectedVariant(variant)}
                  className={`variant-btn ${selectedVariant?.sku === variant.sku ? 'selected' : 'unselected'}`}
                >
                  {variant.size}
                </button>
              ))}
            </div>
            <div style={{ marginTop: '0.5rem', color: 'var(--secondary-text)', fontSize: '0.9rem' }}>
              <FiCheck color="var(--primary-green)" /> In Stock ({selectedVariant?.stock} available)
            </div>
          </div>

          {/* Quantity & Actions */}
          <div className="product-actions" style={{ marginBottom: '2rem' }}>
            <style>{`
              .product-actions {
                display: flex;
                gap: 1rem;
                flex-wrap: wrap;
                align-items: stretch;
              }
              .product-actions-btns {
                display: flex;
                gap: 1rem;
                flex: 1;
                min-width: 300px;
              }
              @media (max-width: 500px) {
                .product-actions {
                  flex-direction: column;
                }
                .product-actions-top {
                  display: flex;
                  justify-content: space-between;
                  gap: 1rem;
                  width: 100%;
                }
                .product-actions-btns {
                  min-width: 100%;
                }
              }
            `}</style>
            
            <div className="product-actions-top" style={{ display: 'flex', gap: '1rem' }}>
              <QuantitySelector quantity={quantity} setQuantity={setQuantity} max={selectedVariant?.stock} />
              <button 
                onClick={() => toggleWishlist(product)}
                style={{
                  width: '50px',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  borderRadius: 'var(--radius-md)',
                  border: '1px solid var(--border)',
                  backgroundColor: 'var(--white)',
                  cursor: 'pointer'
                }}
              >
                <FiHeart 
                  size={22} 
                  color={isInWishlist(product.id) ? 'var(--brand-red)' : 'var(--dark-text)'} 
                  fill={isInWishlist(product.id) ? 'var(--brand-red)' : 'none'}
                />
              </button>
            </div>
            
            <div className="product-actions-btns">
              <Button variant="outline" style={{ flex: 1 }} onClick={handleAddToCart}>
                ADD TO CART
              </Button>
              <Button variant="danger" style={{ flex: 1 }} onClick={handleBuyNow}>
                BUY NOW
              </Button>
            </div>
          </div>

          {/* Trust features */}
          <div style={{ display: 'flex', flexDirection: 'column', gap: '1rem', paddingTop: '2rem', borderTop: '1px solid var(--border)' }}>
            <div style={{ display: 'flex', alignItems: 'center', gap: '1rem', color: 'var(--secondary-text)' }}>
              <FiTruck size={20} color="var(--primary-green)" />
              <span>Free Delivery on orders above ₹799</span>
            </div>
            <div style={{ display: 'flex', alignItems: 'center', gap: '1rem', color: 'var(--secondary-text)' }}>
              <FiShield size={20} color="var(--primary-green)" />
              <span>100% Secure Checkout</span>
            </div>
          </div>
        </div>
      </div>

      {/* Details Tabs Area */}
      <div style={{ marginTop: '5rem' }}>
        <h3 style={{ borderBottom: '2px solid var(--border)', paddingBottom: '1rem', marginBottom: '2rem', color: 'var(--brand-red)' }}>
          Product Information
        </h3>
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(250px, 1fr))', gap: '3rem' }}>
          <div>
            <h4 style={{ marginBottom: '1rem', color: 'var(--dark-green)' }}>Benefits</h4>
            <p style={{ color: 'var(--secondary-text)' }}>{product.benefits}</p>
          </div>
          <div>
            <h4 style={{ marginBottom: '1rem', color: 'var(--dark-green)' }}>Ingredients</h4>
            <p style={{ color: 'var(--secondary-text)' }}>{product.ingredients}</p>
          </div>
        </div>
      </div>

      {/* Reviews Area */}
      {product.reviews_data && product.reviews_data.length > 0 && (
        <div style={{ marginTop: '5rem' }}>
          <h3 style={{ borderBottom: '2px solid var(--border)', paddingBottom: '1rem', marginBottom: '2rem', color: 'var(--brand-red)' }}>
            Customer Reviews
          </h3>
          <div style={{ display: 'flex', flexDirection: 'column', gap: '2rem' }}>
            {product.reviews_data.map(review => (
              <div key={review.id} style={{ padding: '1.5rem', backgroundColor: 'var(--white)', borderRadius: 'var(--radius-lg)', border: '1px solid var(--border)' }}>
                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '1rem' }}>
                  <div style={{ display: 'flex', alignItems: 'center', gap: '1rem' }}>
                    <div style={{ width: '40px', height: '40px', borderRadius: '50%', backgroundColor: 'var(--soft-green)', display: 'flex', alignItems: 'center', justifyContent: 'center', fontWeight: 'bold', color: 'var(--dark-green)' }}>
                      {review.user_icon || review.user_name.charAt(0)}
                    </div>
                    <div>
                      <h5 style={{ margin: 0, color: 'var(--dark-text)' }}>{review.user_name}</h5>
                      <span style={{ fontSize: '0.8rem', color: 'var(--secondary-text)' }}>{review.created_at}</span>
                    </div>
                  </div>
                  <div style={{ display: 'flex', color: '#FFB800' }}>
                    {[...Array(5)].map((_, i) => (
                      <FiStar key={i} fill={i < Math.floor(review.rating) ? 'currentColor' : 'none'} color={i < Math.floor(review.rating) ? 'currentColor' : 'var(--border)'} />
                    ))}
                  </div>
                </div>
                <p style={{ color: 'var(--secondary-text)', lineHeight: '1.6' }}>{review.review}</p>
              </div>
            ))}
          </div>
        </div>
      )}
    </div>
  );
};

export default ProductDetails;
