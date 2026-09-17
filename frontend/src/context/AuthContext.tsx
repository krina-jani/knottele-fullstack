"use client";

import React, { createContext, useContext, useState, useEffect, useCallback } from "react";
import { Order, ShippingAddress } from "@/types/order";
import { INITIAL_ORDERS } from "@/data/orders";
import { useToast } from "./ToastContext";
import { fetchCustomerOrders } from "@/lib/api";

export interface UserProfile {
  name: string;
  email: string;
  phone: string;
  avatar: string;
  addresses: (ShippingAddress & { id: string; isDefault?: boolean })[];
}

interface AuthContextType {
  user: UserProfile | null;
  isLoggedIn: boolean;
  orders: Order[];
  login: (email: string, userData?: Partial<UserProfile>, token?: string) => void;
  logout: () => void;
  updateProfile: (data: Partial<UserProfile>) => void;
  addAddress: (address: Omit<ShippingAddress, "id">) => void;
  deleteAddress: (id: string) => void;
  setDefaultAddress: (id: string) => void;
  addOrder: (order: Order) => void;
  refetchOrders: () => Promise<void>;
}

const DEFAULT_USER: UserProfile = {
  name: "Ananya Sharma",
  email: "ananya.sharma@example.com",
  phone: "+91 98765 43210",
  avatar: "",
  addresses: [
    {
      id: "addr-1",
      fullName: "Ananya Sharma",
      email: "ananya.sharma@example.com",
      phone: "+91 98765 43210",
      addressLine1: "Flat 402, Rosewood Residency",
      addressLine2: "12th Main, 4th Cross, Indiranagar",
      city: "Bengaluru",
      state: "Karnataka",
      pincode: "560038",
      country: "India",
      isDefault: true,
    },
    {
      id: "addr-2",
      fullName: "Ananya Sharma (Office)",
      email: "ananya.work@example.com",
      phone: "+91 98765 43210",
      addressLine1: "Tower B, EcoSpace Business Park",
      addressLine2: "Outer Ring Road, Bellandur",
      city: "Bengaluru",
      state: "Karnataka",
      pincode: "560103",
      country: "India",
      isDefault: false,
    },
  ],
};

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export function AuthProvider({ children }: { children: React.ReactNode }) {
  const [user, setUser] = useState<UserProfile | null>(null);
  const [orders, setOrders] = useState<Order[]>([]);
  const { showToast } = useToast();

  useEffect(() => {
    try {
      const stored = localStorage.getItem("knotelle_customer_user");
      if (stored) {
        const parsed = JSON.parse(stored);
        if (parsed.avatar && parsed.avatar.includes("unsplash.com")) {
          parsed.avatar = "";
          localStorage.setItem("knotelle_customer_user", JSON.stringify(parsed));
        }
        setUser(parsed);
      }
    } catch {
      // ignore JSON parse error
    }
  }, []);

  const refetchOrders = useCallback(async () => {
    if (!user?.email) {
      setOrders([]);
      return;
    }
    const liveOrders = await fetchCustomerOrders(user.email);
    if (Array.isArray(liveOrders)) {
      setOrders(liveOrders);
    } else {
      setOrders([]);
    }
  }, [user?.email]);

  useEffect(() => {
    if (user?.email) {
      refetchOrders();
      const interval = setInterval(refetchOrders, 4000);
      const handleFocus = () => refetchOrders();
      window.addEventListener("focus", handleFocus);
      return () => {
        clearInterval(interval);
        window.removeEventListener("focus", handleFocus);
      };
    } else {
      setOrders([]);
    }
  }, [refetchOrders, user?.email]);

  const login = (email: string, userData?: Partial<UserProfile>, token?: string) => {
    const nameToUse = userData?.name || email.split("@")[0].replace(".", " ").replace(/(^\w|\s\w)/g, (m) => m.toUpperCase());
    const newUser: UserProfile = {
      name: nameToUse,
      email: email,
      phone: userData?.phone || "+91 98765 43210",
      avatar: userData?.avatar || "",
      addresses: userData?.addresses || [
        {
          id: "addr-1",
          fullName: nameToUse,
          email: email,
          phone: userData?.phone || "+91 98765 43210",
          addressLine1: "Flat 402, Rosewood Residency",
          addressLine2: "12th Main, 4th Cross, Indiranagar",
          city: "Bengaluru",
          state: "Karnataka",
          pincode: "560038",
          country: "India",
          isDefault: true,
        },
      ],
    };

    setUser(newUser);
    if (typeof window !== "undefined") {
      localStorage.setItem("knotelle_customer_user", JSON.stringify(newUser));
      if (token) {
        localStorage.setItem("knotelle_customer_token", token);
      }
    }
    showToast("Welcome! ✨", "You are now logged in to KNOTELLE.", "success");
  };

  const logout = () => {
    setUser(null);
    setOrders([]);
    if (typeof window !== "undefined") {
      localStorage.removeItem("knotelle_customer_user");
      localStorage.removeItem("knotelle_customer_token");
      localStorage.removeItem("knotelle_wishlist");
      localStorage.removeItem("knotelle_cart");
    }
    showToast("Logged out", "You have been signed out.", "info");
  };

  const updateProfile = (data: Partial<UserProfile>) => {
    if (user) {
      setUser({ ...user, ...data });
      showToast("Profile Updated 🌸", "Your details have been saved.", "success");
    }
  };

  const addAddress = (address: Omit<ShippingAddress, "id">) => {
    if (!user) return;
    const newId = `addr-${Date.now()}`;
    const newAddr = { ...address, id: newId, isDefault: user.addresses.length === 0 };
    setUser({ ...user, addresses: [...user.addresses, newAddr] });
    showToast("Address Saved", "New shipping address added.", "success");
  };

  const deleteAddress = (id: string) => {
    if (!user) return;
    setUser({ ...user, addresses: user.addresses.filter((a) => a.id !== id) });
    showToast("Address Removed", "Address deleted from your address book.", "info");
  };

  const setDefaultAddress = (id: string) => {
    if (!user) return;
    setUser({
      ...user,
      addresses: user.addresses.map((a) => ({
        ...a,
        isDefault: a.id === id,
      })),
    });
    showToast("Default Updated", "Default shipping address set.", "success");
  };

  const addOrder = (newOrder: Order) => {
    setOrders((prev) => [newOrder, ...prev]);
    setTimeout(() => {
      refetchOrders();
    }, 800);
  };

  return (
    <AuthContext.Provider
      value={{
        user,
        isLoggedIn: !!user,
        orders,
        login,
        logout,
        updateProfile,
        addAddress,
        deleteAddress,
        setDefaultAddress,
        addOrder,
        refetchOrders,
      }}
    >
      {children}
    </AuthContext.Provider>
  );
}

export function useAuth() {
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error("useAuth must be used within an AuthProvider");
  }
  return context;
}
