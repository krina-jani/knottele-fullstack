"use client";

import React, { createContext, useContext, useState, useEffect, useCallback } from "react";
import { Product } from "@/types/product";
import { PRODUCTS } from "@/data/products";
import { useToast } from "./ToastContext";
import { useAuth } from "./AuthContext";
import {
  fetchCustomerWishlist,
  toggleCustomerWishlist,
  removeFromCustomerWishlist,
  syncCustomerWishlist,
  getFullPath,
} from "@/lib/api";

interface WishlistContextType {
  wishlistIds: string[];
  wishlistItems: Product[];
  toggleWishlist: (product: Product) => void;
  addToWishlist: (product: Product) => Promise<boolean>;
  removeFromWishlist: (productId: string) => void;
  isInWishlist: (productId: string) => boolean;
  wishlistCount: number;
  refetchWishlist: () => Promise<void>;
}

const WishlistContext = createContext<WishlistContextType | undefined>(undefined);

export function WishlistProvider({ children }: { children: React.ReactNode }) {
  const { isLoggedIn, user } = useAuth();
  const { showToast } = useToast();
  const [wishlistIds, setWishlistIds] = useState<string[]>([]);
  const [wishlistItems, setWishlistItems] = useState<Product[]>([]);
  const [isInitialized, setIsInitialized] = useState(false);

  const refetchWishlist = useCallback(async () => {
    if (isLoggedIn) {
      const live = await fetchCustomerWishlist();
      if (live) {
        setWishlistItems(live.products || []);
        setWishlistIds(live.product_ids || []);
        if (typeof window !== "undefined") {
          localStorage.setItem("knotelle_wishlist", JSON.stringify(live.product_ids || []));
        }
        return;
      }
    }
    
    // Fallback for logged out guests: check localStorage
    try {
      const savedIds = localStorage.getItem("knotelle_wishlist");
      if (savedIds) {
        const parsed = JSON.parse(savedIds);
        setWishlistIds(parsed);
        const filtered = PRODUCTS.filter((p) => parsed.includes(p.id) || parsed.includes(String((p as any).db_id || p.id)));
        setWishlistItems(filtered);
      } else {
        setWishlistIds([]);
        setWishlistItems([]);
      }
    } catch {
      setWishlistIds([]);
      setWishlistItems([]);
    }
  }, [isLoggedIn]);

  // Load wishlist when authentication state or user changes
  useEffect(() => {
    if (!isLoggedIn) {
      // For guests: load saved items from localStorage if present
      try {
        const savedIds = localStorage.getItem("knotelle_wishlist");
        if (savedIds) {
          const parsed = JSON.parse(savedIds);
          setWishlistIds(parsed);
          const filtered = PRODUCTS.filter((p) => parsed.includes(p.id) || parsed.includes(String((p as any).db_id || p.id)));
          setWishlistItems(filtered);
        } else {
          setWishlistIds([]);
          setWishlistItems([]);
        }
      } catch {
        setWishlistIds([]);
        setWishlistItems([]);
      }
    } else {
      if (typeof window !== "undefined") {
        const pending = localStorage.getItem("knotelle_pending_wishlist");
        if (pending) {
          localStorage.removeItem("knotelle_pending_wishlist");
          toggleCustomerWishlist(pending).then(() => {
            refetchWishlist();
          });
          setIsInitialized(true);
          return;
        }
      }
      refetchWishlist();
    }
    setIsInitialized(true);
  }, [isLoggedIn, user?.email, refetchWishlist]);

  const isInWishlist = (productId: string | number) => {
    const pStr = String(productId);
    const cleanStr = pStr.replace("prod-", "");
    return wishlistIds.some((id) => {
      const idStr = String(id);
      return idStr === pStr || idStr === cleanStr || idStr === `prod-${cleanStr}`;
    });
  };

  const addToWishlist = async (product: Product): Promise<boolean> => {
    const pId = String((product as any).db_id || product.id);
    const cleanId = pId.replace("prod-", "");

    if (!isLoggedIn) {
      if (!isInWishlist(pId)) {
        setWishlistIds((prev) => [...prev, pId, `prod-${cleanId}`]);
        setWishlistItems((prev) => [product, ...prev]);
        if (typeof window !== "undefined") {
          try {
            const saved = JSON.parse(localStorage.getItem("knotelle_wishlist") || "[]");
            if (!saved.includes(pId)) saved.push(pId);
            localStorage.setItem("knotelle_wishlist", JSON.stringify(saved));
            localStorage.setItem("knotelle_pending_wishlist", cleanId);
          } catch (e) {
            console.error(e);
          }
        }
      }
      return true;
    }

    if (!isInWishlist(pId)) {
      setWishlistIds((prev) => [...prev, pId, `prod-${cleanId}`]);
      setWishlistItems((prev) => [product, ...prev]);
      await toggleCustomerWishlist(cleanId);
      refetchWishlist();
    }
    return true;
  };

  const toggleWishlist = async (product: Product) => {
    const pId = String((product as any).db_id || product.id);

    if (!isLoggedIn) {
      showToast("Sign In Required 🌸", "Please log in to save items to your personal wishlist.", "info");
      if (typeof window !== "undefined") {
        localStorage.setItem("knotelle_pending_wishlist", pId);
        window.location.href = getFullPath(`/login?redirectTo=${encodeURIComponent(window.location.pathname)}`);
      }
      return;
    }

    const isCurrentlySaved = isInWishlist(pId);

    if (isCurrentlySaved) {
      // Optimistic update
      setWishlistIds((prev) => prev.filter((id) => String(id) !== pId && String(id) !== `prod-${pId}`));
      setWishlistItems((prev) => prev.filter((item) => String((item as any).db_id || item.id) !== pId));
      showToast("Removed from Wishlist", `${product.name} removed from your saved items.`, "info");

      await toggleCustomerWishlist(pId);
      refetchWishlist();
    } else {
      // Optimistic update
      setWishlistIds((prev) => [...prev, pId, `prod-${pId}`]);
      setWishlistItems((prev) => [product, ...prev]);
      showToast("Saved to Wishlist 💕", `${product.name} added to your favorites.`, "wishlist");

      await toggleCustomerWishlist(pId);
      refetchWishlist();
    }
  };

  const removeFromWishlist = async (productId: string | number) => {
    const pId = String(productId);
    const cleanId = pId.replace("prod-", "");
    
    setWishlistIds((prev) => prev.filter((id) => String(id) !== pId && String(id) !== cleanId));
    setWishlistItems((prev) => prev.filter((item) => String((item as any).db_id || item.id) !== cleanId && String(item.id) !== pId));

    if (isLoggedIn) {
      await removeFromCustomerWishlist(cleanId);
      refetchWishlist();
    } else if (typeof window !== "undefined") {
      const updated = wishlistIds.filter((id) => String(id) !== pId && String(id) !== cleanId);
      localStorage.setItem("knotelle_wishlist", JSON.stringify(updated));
    }
  };

  return (
    <WishlistContext.Provider
      value={{
        wishlistIds,
        wishlistItems,
        toggleWishlist,
        addToWishlist,
        removeFromWishlist,
        isInWishlist,
        wishlistCount: wishlistItems.length,
        refetchWishlist,
      }}
    >
      {children}
    </WishlistContext.Provider>
  );
}

export function useWishlist() {
  const context = useContext(WishlistContext);
  if (!context) {
    throw new Error("useWishlist must be used within a WishlistProvider");
  }
  return context;
}
