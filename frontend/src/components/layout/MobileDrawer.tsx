"use client";

import React, { useEffect } from "react";
import Link from "next/link";
import { usePathname } from "next/navigation";
import { X, Heart, ShoppingBag, User, Sparkles, ChevronRight, Phone, Mail } from "lucide-react";
import { CATEGORIES } from "@/data/categories";
import { FlowerIcon, KnotelleCrownLogo } from "@/components/ui/BotanicalDecorations";
import { useWishlist } from "@/context/WishlistContext";
import { useCart } from "@/context/CartContext";

interface MobileDrawerProps {
  isOpen: boolean;
  onClose: () => void;
  onOpenSearch: () => void;
}

export function MobileDrawer({ isOpen, onClose, onOpenSearch }: MobileDrawerProps) {
  const pathname = usePathname();
  const { wishlistCount } = useWishlist();
  const { totalItemsCount } = useCart();

  useEffect(() => {
    if (isOpen) {
      document.body.style.overflow = "hidden";
    } else {
      document.body.style.overflow = "unset";
    }
    return () => {
      document.body.style.overflow = "unset";
    };
  }, [isOpen]);

  if (!isOpen) return null;

  const mainNav = [
    { name: "Home", href: "/" },
    { name: "Shop All", href: "/shop" },
    { name: "Custom Order", href: "/custom-order", highlight: true },
    { name: "About Story", href: "/about" },
    { name: "Contact Us", href: "/contact" },
  ];

  return (
    <div className="fixed inset-0 z-50 overflow-hidden lg:hidden">
      {/* Backdrop */}
      <div
        className="absolute inset-0 bg-black/40 backdrop-blur-xs transition-opacity animate-in fade-in duration-300"
        onClick={onClose}
      />

      <div className="fixed inset-y-0 left-0 max-w-full flex pr-12">
        <div className="w-screen max-w-sm bg-[#FFF9F6] border-r border-[#E7D1CC] shadow-2xl flex flex-col animate-in slide-in-from-left duration-300">
          {/* Top Brand Header */}
          <div className="p-5 border-b border-[#E7D1CC] flex items-center justify-between bg-white">
            <Link
              href="/"
              onClick={onClose}
              className="flex items-center gap-2 group"
            >
              <KnotelleCrownLogo className="h-12 sm:h-14 w-auto" />
            </Link>
            <button
              onClick={onClose}
              className="p-2 rounded-full text-[#786864] hover:text-[#2E211E] hover:bg-[#FCE9E5] transition-colors cursor-pointer"
              aria-label="Close menu"
            >
              <X className="w-5 h-5" />
            </button>
          </div>

          {/* Quick Action Pills */}
          <div className="grid grid-cols-3 gap-2 p-4 bg-white border-b border-[#E7D1CC]">
            <Link
              href="/account/wishlist"
              onClick={onClose}
              className="flex flex-col items-center justify-center p-2.5 rounded-2xl bg-[#FFF9F6] border border-[#E7D1CC] text-xs text-[#2E211E] hover:bg-[#FCE9E5] hover:text-[#913638] transition-colors relative"
            >
              <Heart className="w-4 h-4 text-[#913638] mb-1" />
              <span>Wishlist</span>
              {wishlistCount > 0 && (
                <span className="absolute top-1.5 right-2 w-4 h-4 rounded-full bg-[#913638] text-white text-[9px] font-bold flex items-center justify-center">
                  {wishlistCount}
                </span>
              )}
            </Link>

            <Link
              href="/account"
              onClick={onClose}
              className="flex flex-col items-center justify-center p-2.5 rounded-2xl bg-[#FFF9F6] border border-[#E7D1CC] text-xs text-[#2E211E] hover:bg-[#FCE9E5] hover:text-[#913638] transition-colors"
            >
              <User className="w-4 h-4 text-[#913638] mb-1" />
              <span>Account</span>
            </Link>

            <Link
              href="/cart"
              onClick={onClose}
              className="flex flex-col items-center justify-center p-2.5 rounded-2xl bg-[#FFF9F6] border border-[#E7D1CC] text-xs text-[#2E211E] hover:bg-[#FCE9E5] hover:text-[#913638] transition-colors relative"
            >
              <ShoppingBag className="w-4 h-4 text-[#913638] mb-1" />
              <span>Cart</span>
              {totalItemsCount > 0 && (
                <span className="absolute top-1.5 right-2 w-4 h-4 rounded-full bg-[#913638] text-white text-[9px] font-bold flex items-center justify-center">
                  {totalItemsCount}
                </span>
              )}
            </Link>
          </div>

          {/* Nav Links Body */}
          <div className="flex-1 overflow-y-auto p-5 space-y-6">
            {/* Primary Nav */}
            <div className="space-y-1">
              {mainNav.map((item) => {
                const isActive = pathname === item.href;
                return (
                  <Link
                    key={item.href}
                    href={item.href}
                    onClick={onClose}
                    className={`flex items-center justify-between px-4 py-3 rounded-2xl text-sm font-medium transition-colors ${
                      item.highlight
                        ? "bg-[#FCE9E5] text-[#913638] border border-[#E7D1CC] font-semibold"
                        : isActive
                        ? "bg-[#913638] text-white"
                        : "text-[#2E211E] hover:bg-white"
                    }`}
                  >
                    <span className="flex items-center gap-2">
                      {item.highlight && <Sparkles className="w-4 h-4" />}
                      <span>{item.name}</span>
                    </span>
                    <ChevronRight className={`w-4 h-4 ${isActive ? "text-white" : "text-[#786864]"}`} />
                  </Link>
                );
              })}
            </div>

            {/* Explore Categories */}
            <div>
              <p className="text-xs uppercase font-bold text-[#786864] tracking-wider mb-3 px-1">
                Shop By Category
              </p>
              <div className="grid grid-cols-2 gap-2">
                {CATEGORIES.slice(0, 8).map((cat) => (
                  <Link
                    key={cat.id}
                    href={`/category/${cat.slug}`}
                    onClick={onClose}
                    className="p-3 rounded-xl bg-white border border-[#E7D1CC]/70 text-xs font-medium text-[#2E211E] hover:border-[#913638] hover:text-[#913638] transition-colors"
                  >
                    {cat.name}
                  </Link>
                ))}
              </div>
            </div>

            {/* Custom Order Box */}
            <div className="p-4 rounded-2xl bg-gradient-to-br from-[#FCE9E5] to-[#EFB8B0]/40 border border-[#E7D1CC] text-center">
              <Sparkles className="w-5 h-5 text-[#913638] mx-auto mb-1.5" />
              <h4 className="font-serif-luxury text-sm font-bold text-[#2E211E]">
                Have a Custom Idea?
              </h4>
              <p className="text-[11px] text-[#786864] mt-0.5 mb-3">
                Your imagination, our yarn. We craft custom dreams.
              </p>
              <Link
                href="/custom-order"
                onClick={onClose}
                className="inline-block w-full py-2.5 px-4 rounded-full bg-[#913638] text-white text-xs font-semibold hover:bg-[#74292B] active:scale-[0.98] shadow-xs transition-all text-center cursor-pointer"
              >
                Start Custom Order →
              </Link>
            </div>

            {/* Contact Support */}
            <div className="pt-2 border-t border-[#E7D1CC] space-y-2 text-xs text-[#786864]">
              <a
                href="tel:+919773039243"
                className="flex items-center gap-2 hover:text-[#913638] transition-colors"
              >
                <Phone className="w-3.5 h-3.5 text-[#913638]" />
                <span>+91 97730 39243</span>
              </a>
              <a
                href="mailto:support@knotelle.in"
                className="flex items-center gap-2 hover:text-[#913638] transition-colors"
              >
                <Mail className="w-3.5 h-3.5 text-[#913638]" />
                <span>support@knotelle.in</span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
