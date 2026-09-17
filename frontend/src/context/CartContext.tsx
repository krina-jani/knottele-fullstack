"use client";

import React, { createContext, useContext, useState, useEffect } from "react";
import { CartItem, CartItemCustomization } from "@/types/cart";
import { Product } from "@/types/product";
import { useToast } from "./ToastContext";
import { validateOfferCode } from "@/lib/api";

interface CartContextType {
  items: CartItem[];
  addItem: (product: Product, quantity?: number, customization?: CartItemCustomization) => void;
  removeItem: (cartItemId: string) => void;
  updateQuantity: (cartItemId: string, quantity: number) => void;
  clearCart: () => void;
  isCartOpen: boolean;
  setIsCartOpen: (open: boolean) => void;
  totalItemsCount: number;
  subtotal: number;
  shipping: number;
  discount: number;
  total: number;
  promoCode: string;
  applyPromoCode: (code: string) => Promise<boolean>;
  removePromoCode: () => void;
  freeShippingThreshold: number;
  amountToFreeShipping: number;
}

const CartContext = createContext<CartContextType | undefined>(undefined);

const FREE_SHIPPING_THRESHOLD = 999;
const STANDARD_SHIPPING_FEE = 99;

export function CartProvider({ children }: { children: React.ReactNode }) {
  const [items, setItems] = useState<CartItem[]>([]);
  const [isCartOpen, setIsCartOpen] = useState(false);
  const [promoCode, setPromoCode] = useState<string>("");
  const [discountPercent, setDiscountPercent] = useState<number>(0);
  const [isInitialized, setIsInitialized] = useState(false);
  const { showToast } = useToast();

  // Load from localStorage on client mount
  useEffect(() => {
    try {
      const savedCart = localStorage.getItem("knotelle_cart");
      if (savedCart) {
        setItems(JSON.parse(savedCart));
      }
      const savedPromo = localStorage.getItem("knotelle_promo");
      if (savedPromo) {
        const parsed = JSON.parse(savedPromo);
        setPromoCode(parsed.code);
        setDiscountPercent(parsed.discount);
      }
    } catch (e) {
      console.error("Failed to load cart from localStorage", e);
    } finally {
      setIsInitialized(true);
    }
  }, []);

  // Save to localStorage when items change
  useEffect(() => {
    if (!isInitialized) return;
    try {
      localStorage.setItem("knotelle_cart", JSON.stringify(items));
    } catch (e) {
      console.error("Failed to save cart to localStorage", e);
    }
  }, [items, isInitialized]);

  const generateCartItemId = (productId: string, customization?: CartItemCustomization) => {
    const colorStr = customization?.color?.name || "default";
    const sizeStr = customization?.size || "default";
    const textStr = customization?.customText || "";
    return `${productId}-${colorStr}-${sizeStr}-${textStr}`;
  };

  const addItem = (product: Product, quantity = 1, customization?: CartItemCustomization) => {
    const id = generateCartItemId(product.id, customization);
    
    setItems((prevItems) => {
      const existingItemIndex = prevItems.findIndex((item) => item.id === id);
      if (existingItemIndex > -1) {
        const updated = [...prevItems];
        updated[existingItemIndex].quantity += quantity;
        return updated;
      } else {
        return [
          ...prevItems,
          {
            id,
            productId: product.id,
            product,
            quantity,
            customization,
            price: product.price,
          },
        ];
      }
    });

    showToast("Added to Your Cart 🧺", `${quantity}x ${product.name} ready for you.`, "cart");
  };

  const removeItem = (cartItemId: string) => {
    setItems((prev) => prev.filter((item) => item.id !== cartItemId));
    showToast("Item Removed", "Your cart has been updated.", "info");
  };

  const updateQuantity = (cartItemId: string, quantity: number) => {
    if (quantity <= 0) {
      removeItem(cartItemId);
      return;
    }
    setItems((prev) =>
      prev.map((item) => (item.id === cartItemId ? { ...item, quantity } : item))
    );
  };

  const clearCart = () => {
    setItems([]);
    setPromoCode("");
    setDiscountPercent(0);
    localStorage.removeItem("knotelle_cart");
    localStorage.removeItem("knotelle_promo");
  };

  const applyPromoCode = async (code: string): Promise<boolean> => {
    const cleanCode = code.trim().toUpperCase();
    if (!cleanCode) return false;

    // Calculate current subtotal for validation
    const currentSubtotal = items.reduce((sum, item) => sum + item.price * item.quantity, 0);

    // 1. Try Backend API Validation
    try {
      const apiResult = await validateOfferCode(cleanCode, currentSubtotal);
      if (apiResult && apiResult.success) {
        setPromoCode(cleanCode);
        let pct = 10;
        if (apiResult.offer?.offer_type === "percentage" && apiResult.offer?.discount_value) {
          pct = Number(apiResult.offer.discount_value);
        } else if (apiResult.discount_amount && currentSubtotal > 0) {
          pct = Math.round((apiResult.discount_amount / currentSubtotal) * 100);
        }
        setDiscountPercent(pct);
        localStorage.setItem("knotelle_promo", JSON.stringify({ code: cleanCode, discount: pct }));
        showToast("Coupon Applied! 🎉", apiResult.message || `${cleanCode} discount applied.`, "success");
        return true;
      } else if (apiResult && apiResult.message && !apiResult.message.includes("Connection error")) {
        showToast("Coupon Error", apiResult.message, "error");
        return false;
      }
    } catch (err) {
      console.warn("Backend offer validation network fallback", err);
    }

    // 2. Client Fallback Coupons
    if (cleanCode === "KNOTELLE10" || cleanCode === "WELCOME10") {
      setPromoCode(cleanCode);
      setDiscountPercent(10);
      localStorage.setItem("knotelle_promo", JSON.stringify({ code: cleanCode, discount: 10 }));
      showToast("Coupon Applied! 🎉", "10% handmade love discount applied to your order.", "success");
      return true;
    } else if (cleanCode === "STITCH20") {
      setPromoCode(cleanCode);
      setDiscountPercent(20);
      localStorage.setItem("knotelle_promo", JSON.stringify({ code: cleanCode, discount: 20 }));
      showToast("Special Discount! ✨", "20% discount applied to your order.", "success");
      return true;
    } else {
      showToast("Invalid Coupon Code", "Please check the code and try again.", "error");
      return false;
    }
  };

  const removePromoCode = () => {
    setPromoCode("");
    setDiscountPercent(0);
    localStorage.removeItem("knotelle_promo");
    showToast("Coupon Removed", "Discount has been removed.", "info");
  };

  const totalItemsCount = items.reduce((sum, item) => sum + item.quantity, 0);
  const subtotal = items.reduce((sum, item) => sum + item.price * item.quantity, 0);
  const discount = Math.round((subtotal * discountPercent) / 100);
  const isFreeShipping = subtotal >= FREE_SHIPPING_THRESHOLD || subtotal === 0;
  const shipping = isFreeShipping ? 0 : STANDARD_SHIPPING_FEE;
  const total = Math.max(0, subtotal - discount + shipping);
  const amountToFreeShipping = Math.max(0, FREE_SHIPPING_THRESHOLD - subtotal);

  return (
    <CartContext.Provider
      value={{
        items,
        addItem,
        removeItem,
        updateQuantity,
        clearCart,
        isCartOpen,
        setIsCartOpen,
        totalItemsCount,
        subtotal,
        shipping,
        discount,
        total,
        promoCode,
        applyPromoCode,
        removePromoCode,
        freeShippingThreshold: FREE_SHIPPING_THRESHOLD,
        amountToFreeShipping,
      }}
    >
      {children}
    </CartContext.Provider>
  );
}

export function useCart() {
  const context = useContext(CartContext);
  if (!context) {
    throw new Error("useCart must be used within a CartProvider");
  }
  return context;
}
