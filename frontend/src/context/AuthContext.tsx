"use client";

import React, { createContext, useContext, useState } from "react";
import { Order, ShippingAddress } from "@/types/order";
import { INITIAL_ORDERS } from "@/data/orders";
import { useToast } from "./ToastContext";

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
  login: (email: string) => void;
  logout: () => void;
  updateProfile: (data: Partial<UserProfile>) => void;
  addAddress: (address: Omit<ShippingAddress, "id">) => void;
  deleteAddress: (id: string) => void;
  setDefaultAddress: (id: string) => void;
  addOrder: (order: Order) => void;
}

const DEFAULT_USER: UserProfile = {
  name: "Ananya Sharma",
  email: "ananya.sharma@example.com",
  phone: "+91 98765 43210",
  avatar: "https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200&auto=format&fit=crop",
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
  const [user, setUser] = useState<UserProfile | null>(DEFAULT_USER);
  const [orders, setOrders] = useState<Order[]>(INITIAL_ORDERS);
  const { showToast } = useToast();

  const login = (email: string) => {
    setUser({
      ...DEFAULT_USER,
      email,
      name: email.split("@")[0].replace(".", " ").replace(/(^\w|\s\w)/g, (m) => m.toUpperCase()),
    });
    showToast("Welcome back! ✨", "You are now logged in to KNOTELLE.", "success");
  };

  const logout = () => {
    setUser(null);
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
