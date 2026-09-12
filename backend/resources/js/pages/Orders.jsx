import React, { useState, useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { FiUser, FiPackage, FiHeart, FiMapPin, FiLogOut, FiCheckCircle, FiClock } from 'react-icons/fi';
import { useAuth } from '../context/AuthContext';
import Button from '../components/Button';
import { orderService } from '../services/orderService';

const Orders = () => {
  const { user, isAuthenticated, logout } = useAuth();
  const navigate = useNavigate();

  if (!isAuthenticated) {
    return (
      <div className="container" style={{ padding: '5rem 0', textAlign: 'center' }}>
        <h2>Please login to view your orders.</h2>
        <Button onClick={() => navigate('/login')} style={{ marginTop: '1rem' }}>Login</Button>
      </div>
    );
  }

  const handleLogout = () => {
    logout();
    navigate('/');
  };

  const [orders, setOrders] = useState([]);
  const [isLoading, setIsLoading] = useState(true);

  useEffect(() => {
    if (isAuthenticated) {
      loadOrders();
    }
  }, [isAuthenticated]);

  const loadOrders = async () => {
    setIsLoading(true);
    const data = await orderService.getMyOrders();
    setOrders(data);
    setIsLoading(false);
  };

  const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString(undefined, options);
  };

  return (
    <div className="container" style={{ paddingTop: '3rem', paddingBottom: '5rem' }}>
      <h1 style={{ marginBottom: '2rem', color: 'var(--dark-green)' }}>My Orders</h1>
      
      <div style={{ display: 'flex', gap: '2rem', flexWrap: 'wrap', alignItems: 'flex-start' }}>
        
        {/* Sidebar */}
        <aside style={{ flex: '1 1 250px', backgroundColor: 'var(--white)', borderRadius: 'var(--radius-lg)', border: '1px solid var(--border)', padding: '1.5rem' }}>
          <nav style={{ display: 'flex', flexDirection: 'column', gap: '0.5rem' }}>
            <Link to="/account" style={linkStyle}><FiUser /> Profile</Link>
            <Link to="/orders" style={activeLinkStyle}><FiPackage /> Orders</Link>
            <Link to="/wishlist" style={linkStyle}><FiHeart /> Wishlist</Link>
            <Link to="/addresses" style={linkStyle}><FiMapPin /> Addresses</Link>
            <button onClick={handleLogout} style={{ ...linkStyle, color: 'var(--brand-red)', marginTop: '1rem', borderTop: '1px solid var(--border)', paddingTop: '1rem' }}>
              <FiLogOut /> Logout
            </button>
          </nav>
        </aside>

        {/* Main Content */}
        <div style={{ flex: '1 1 600px', display: 'flex', flexDirection: 'column', gap: '1.5rem' }}>
          {isLoading ? (
            <div style={{ textAlign: 'center', padding: '3rem', backgroundColor: 'var(--white)', borderRadius: 'var(--radius-lg)' }}>
              Loading your orders...
            </div>
          ) : orders.length === 0 ? (
            <div style={{ textAlign: 'center', padding: '3rem', backgroundColor: 'var(--white)', borderRadius: 'var(--radius-lg)' }}>
              <h3>You have no orders yet.</h3>
              <p style={{ color: 'var(--secondary-text)' }}>Start shopping to see your orders here.</p>
              <Button onClick={() => navigate('/products')} style={{ marginTop: '1rem' }}>Shop Now</Button>
            </div>
          ) : (
            orders.map((order) => (
              <div key={order.id} style={{ backgroundColor: 'var(--white)', borderRadius: 'var(--radius-lg)', border: '1px solid var(--border)', overflow: 'hidden' }}>
              
              {/* Order Header */}
              <div style={{ padding: '1.5rem', backgroundColor: 'var(--soft-green)', borderBottom: '1px solid var(--border)', display: 'flex', justifyContent: 'space-between', alignItems: 'center', flexWrap: 'wrap', gap: '1rem' }}>
                <div>
                  <h3 style={{ margin: 0, fontSize: '1.1rem' }}>Order #{order.order_number}</h3>
                  <p style={{ margin: 0, color: 'var(--secondary-text)', fontSize: '0.9rem' }}>Placed on {formatDate(order.created_at)}</p>
                </div>
                <div style={{ textAlign: 'right' }}>
                  <div style={{ fontWeight: 'bold', color: 'var(--dark-green)' }}>Total: ₹{order.grand_total}</div>
                  <div style={{ color: order.status === 'delivered' ? 'var(--primary-green)' : (order.status === 'pending' ? 'var(--secondary-text)' : 'var(--brand-red)'), fontSize: '0.9rem', display: 'flex', alignItems: 'center', gap: '0.25rem' }}>
                    {order.status === 'delivered' ? <FiCheckCircle /> : <FiClock />} {order.status.charAt(0).toUpperCase() + order.status.slice(1)}
                  </div>
                </div>
              </div>

              {/* Order Items */}
              <div style={{ padding: '1.5rem' }}>
                {order.items && order.items.map((item, idx) => {
                  const imageUrl = item.product_variant?.media?.[0]?.url || '/images/placeholder.jpg';
                  const size = item.product_variant?.size || 'Standard';
                  
                  return (
                    <div key={idx} style={{ display: 'flex', gap: '1rem', alignItems: 'center', marginBottom: idx !== order.items.length - 1 ? '1rem' : 0 }}>
                      <div style={{ width: '60px', height: '60px', backgroundColor: 'var(--soft-green)', borderRadius: 'var(--radius-sm)', padding: '0.5rem' }}>
                        <img src={imageUrl} alt={item.product_name} style={{ width: '100%', height: '100%', objectFit: 'contain' }} />
                      </div>
                      <div style={{ flex: 1 }}>
                        <h4 style={{ margin: 0, fontSize: '1rem' }}>{item.product_name}</h4>
                        <p style={{ margin: '0.25rem 0', color: 'var(--secondary-text)', fontSize: '0.9rem' }}>{size} x {item.quantity}</p>
                      </div>
                      <div style={{ fontWeight: '600' }}>
                        ₹{item.total}
                      </div>
                    </div>
                  );
                })}
              </div>
              
              {/* Order Actions */}
              <div style={{ padding: '1rem 1.5rem', borderTop: '1px solid var(--border)', display: 'flex', justifyContent: 'flex-end', gap: '1rem' }}>
                <Button variant="outline" size="sm" onClick={() => alert(`Viewing details for Order #${order.order_number}`)}>View Details</Button>
                {order.status === 'delivered' ? (
                  <Button variant="primary" size="sm" onClick={() => alert(`Write a review for Order #${order.order_number}`)}>Write a Review</Button>
                ) : (
                  <Button variant="primary" size="sm" onClick={() => alert(`Tracking Order #${order.order_number}`)}>Track Order</Button>
                )}
              </div>
              
            </div>
            ))
          )}
        </div>

      </div>
    </div>
  );
};

const linkStyle = {
  display: 'flex',
  alignItems: 'center',
  gap: '0.75rem',
  padding: '0.75rem 1rem',
  textDecoration: 'none',
  color: 'var(--dark-text)',
  borderRadius: 'var(--radius-sm)',
  transition: 'background-color 0.2s',
  fontWeight: '500'
};

const activeLinkStyle = {
  ...linkStyle,
  backgroundColor: 'var(--soft-green)',
  color: 'var(--primary-green)',
};

export default Orders;
