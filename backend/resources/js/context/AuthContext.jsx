import React, { createContext, useContext, useState, useEffect } from 'react';
import api from '../services/api';

const AuthContext = createContext();

export const useAuth = () => {
  return useContext(AuthContext);
};

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null);
  const [isAuthenticated, setIsAuthenticated] = useState(false);
  const [isLoading, setIsLoading] = useState(true);

  // Check auth on load
  useEffect(() => {
    const checkAuth = async () => {
      const token = localStorage.getItem('customer_token');
      if (token) {
        try {
          const response = await api.get('/customer/profile');
          if (response.data.success) {
            setUser(response.data.user);
            setIsAuthenticated(true);
          } else {
            localStorage.removeItem('customer_token');
          }
        } catch (error) {
          console.error("Auth check failed:", error);
          localStorage.removeItem('customer_token');
        }
      }
      setIsLoading(false);
    };
    checkAuth();
  }, []);

  const login = async (email, password) => {
    try {
      const response = await api.post('/customer/login', { email, password });
      if (response.data.success) {
        localStorage.setItem('customer_token', response.data.token);
        setUser(response.data.user);
        setIsAuthenticated(true);
        return { success: true };
      }
      return { success: false, error: response.data.message || 'Login failed' };
    } catch (error) {
      return { success: false, error: error.response?.data?.message || 'Login failed' };
    }
  };

  const register = async (userData) => {
    try {
      const response = await api.post('/customer/register', userData);
      if (response.data.success) {
        localStorage.setItem('customer_token', response.data.token);
        setUser(response.data.user);
        setIsAuthenticated(true);
        return { success: true };
      }
      return { success: false, error: response.data.message || 'Registration failed' };
    } catch (error) {
      return { success: false, error: error.response?.data?.message || 'Registration failed' };
    }
  };

  const logout = async () => {
    try {
      if (isAuthenticated) {
        await api.post('/customer/logout');
      }
    } catch (error) {
      console.error("Logout failed:", error);
    } finally {
      localStorage.removeItem('customer_token');
      setUser(null);
      setIsAuthenticated(false);
    }
  };

  const updateProfile = (userData) => {
    setUser(prev => ({ ...prev, ...userData }));
  };

  const value = {
    user,
    isAuthenticated,
    isLoading,
    login,
    register,
    logout,
    updateProfile
  };

  return (
    <AuthContext.Provider value={value}>
      {children}
    </AuthContext.Provider>
  );
};
