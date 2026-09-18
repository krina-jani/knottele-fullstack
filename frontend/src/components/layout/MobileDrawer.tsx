"use client";

import React, { useEffect } from "react";
import Link from "next/link";
import { usePathname } from "next/navigation";
import { X, Heart, ShoppingBag, User, Sparkles, LogOut, ChevronRight, Phone, Mail } from "lucide-react";
import { KnotelleCrownLogo } from "@/components/ui/BotanicalDecorations";
import { useWishlist } from "@/context/WishlistContext";
import { useCart } from "@/context/CartContext";
import { useWebsiteMedia } from "@/context/MediaContext";
import { useAuth } from "@/context/AuthContext";
import { normalizeInternalLink } from "@/lib/api";

interface MobileDrawerProps {
  isOpen: boolean;
  onClose: () => void;
  onOpenSearch: () => void;
}

export function MobileDrawer({ isOpen, onClose, onOpenSearch }: MobileDrawerProps) {
  const pathname = usePathname();
  const { media } = useWebsiteMedia();
  const { wishlistCount } = useWishlist();
  const { totalItemsCount } = useCart();
  const { isLoggedIn, user, logout } = useAuth();

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

  const footer = media?.footer;

  // Contact support info
  const contactPhone = footer?.column_3?.phone || "+91 97730 39243";
  const contactPhoneLink = footer?.column_3?.phone_link || `tel:${contactPhone.replace(/\s+/g, "")}`;
  const contactEmail = footer?.column_3?.email || "support@knotelle.in";
  const contactEmailLink = footer?.column_3?.email_link || `mailto:${contactEmail}`;

  const menuItems = [
    { name: "Home", href: "/knottele" },
    { name: "Shop", href: "/knottele/shop" },
    { name: "Custom Order", href: "/knottele/custom-order" },
    { name: "About", href: "/knottele/about" },
    { name: "Contact", href: "/knottele/contact" },
  ];

  return (
    <div className="fixed inset-0 z-50 overflow-hidden">
      {/* Backdrop */}
      <div
        className="absolute inset-0 bg-black/50 backdrop-blur-xs transition-opacity animate-in fade-in duration-300"
        onClick={onClose}
      />

      {/* Slide Drawer from Right */}
      <div className="fixed inset-y-0 right-0 max-w-full flex pl-10">
        <div className="w-screen max-w-sm bg-white border-l border-[#E7D1CC] shadow-2xl flex flex-col animate-in slide-in-from-right duration-300">
          
          {/* Header Bar */}
          <div className="p-6 border-b border-[#E7D1CC]/70 flex items-center justify-between bg-white">
            <h2 className="font-sans font-bold text-xl tracking-wider text-[#2E211E] uppercase">
              MENU
            </h2>
            <button
              onClick={onClose}
              className="p-2 rounded-full text-[#2E211E] hover:text-[#913638] hover:bg-[#FCE9E5] transition-colors cursor-pointer"
              aria-label="Close menu"
            >
              <X className="w-6 h-6" />
            </button>
          </div>

          {/* Quick Action Shortcuts (Wishlist & Cart) */}
          <div className="grid grid-cols-2 gap-3 px-6 py-4 bg-[#FFF9F6] border-b border-[#E7D1CC]/60">
            <Link
              href={normalizeInternalLink("/account/wishlist")}
              onClick={onClose}
              className="flex items-center justify-center gap-2 p-2.5 rounded-xl bg-white border border-[#E7D1CC] text-xs font-semibold text-[#2E211E] hover:bg-[#FCE9E5] hover:text-[#913638] transition-colors relative"
            >
              <Heart className="w-4 h-4 text-[#913638]" />
              <span>Wishlist</span>
              {wishlistCount > 0 && (
                <span className="w-4 h-4 rounded-full bg-[#913638] text-white text-[9px] font-bold flex items-center justify-center">
                  {wishlistCount}
                </span>
              )}
            </Link>

            <Link
              href={normalizeInternalLink("/cart")}
              onClick={onClose}
              className="flex items-center justify-center gap-2 p-2.5 rounded-xl bg-white border border-[#E7D1CC] text-xs font-semibold text-[#2E211E] hover:bg-[#FCE9E5] hover:text-[#913638] transition-colors relative"
            >
              <ShoppingBag className="w-4 h-4 text-[#913638]" />
              <span>Cart</span>
              {totalItemsCount > 0 && (
                <span className="w-4 h-4 rounded-full bg-[#913638] text-white text-[9px] font-bold flex items-center justify-center">
                  {totalItemsCount}
                </span>
              )}
            </Link>
          </div>

          {/* Main Navigation Links */}
          <div className="flex-1 overflow-y-auto px-6 py-6 space-y-6">
            <nav className="flex flex-col space-y-4">
              {menuItems.map((item) => {
                const href = normalizeInternalLink(item.href);
                const isActive = pathname === href || pathname === item.href || (href === "/knottele" && (pathname === "/" || pathname === "/knottele"));
                return (
                  <Link
                    key={item.name}
                    href={href}
                    prefetch={true}
                    onClick={onClose}
                    className={`text-lg font-medium tracking-wide transition-all py-1 flex items-center justify-between ${
                      isActive
                        ? "text-[#913638] font-bold"
                        : "text-[#2E211E] hover:text-[#913638]"
                    }`}
                  >
                    <span>{item.name}</span>
                    <ChevronRight className="w-4 h-4 text-[#786864]/50" />
                  </Link>
                );
              })}
            </nav>

            {/* Divider Line */}
            <div className="pt-2 border-t border-[#E7D1CC]/80" />

            {/* User Auth Section */}
            <div className="flex flex-col space-y-3">
              {isLoggedIn ? (
                <>
                  <div className="p-3.5 rounded-2xl bg-[#FCE9E5]/60 border border-[#E7D1CC] mb-1">
                    <p className="text-[11px] uppercase tracking-wider font-bold text-[#913638]">
                      Logged in as
                    </p>
                    <p className="text-sm font-bold text-[#2E211E] truncate">
                      {user?.name || user?.email}
                    </p>
                  </div>

                  <Link
                    href={normalizeInternalLink("/account")}
                    onClick={onClose}
                    className="text-base font-medium text-[#2E211E] hover:text-[#913638] transition-colors py-1 flex items-center gap-2.5"
                  >
                    <User className="w-4 h-4 text-[#913638]" />
                    <span>My Account</span>
                  </Link>

                  <Link
                    href={normalizeInternalLink("/account/orders")}
                    onClick={onClose}
                    className="text-base font-medium text-[#2E211E] hover:text-[#913638] transition-colors py-1 flex items-center gap-2.5"
                  >
                    <ShoppingBag className="w-4 h-4 text-[#913638]" />
                    <span>My Orders</span>
                  </Link>

                  <button
                    onClick={() => {
                      logout();
                      onClose();
                    }}
                    className="text-base font-medium text-[#913638] hover:text-[#74292B] transition-colors py-1 flex items-center gap-2.5 text-left cursor-pointer"
                  >
                    <LogOut className="w-4 h-4" />
                    <span>Sign Out</span>
                  </button>
                </>
              ) : (
                <>
                  <Link
                    href={normalizeInternalLink("/login")}
                    onClick={onClose}
                    className="text-lg font-medium text-[#2E211E] hover:text-[#913638] transition-colors py-1 flex items-center justify-between"
                  >
                    <span>Login</span>
                    <ChevronRight className="w-4 h-4 text-[#786864]/50" />
                  </Link>

                  <Link
                    href={normalizeInternalLink("/signup")}
                    onClick={onClose}
                    className="text-lg font-medium text-[#2E211E] hover:text-[#913638] transition-colors py-1 flex items-center justify-between"
                  >
                    <span>Register</span>
                    <ChevronRight className="w-4 h-4 text-[#786864]/50" />
                  </Link>
                </>
              )}
            </div>

            {/* Custom Creation CTA */}
            <div className="pt-4">
              <div className="p-4 rounded-2xl bg-gradient-to-br from-[#FCE9E5] to-[#EFB8B0]/40 border border-[#E7D1CC] text-center">
                <Sparkles className="w-5 h-5 text-[#913638] mx-auto mb-1.5" />
                <h4 className="font-serif-luxury text-xs font-bold text-[#2E211E]">
                  Handcrafted Custom Orders
                </h4>
                <p className="text-[10px] text-[#786864] mt-0.5 mb-2.5">
                  Have a specific design in mind? Work with our master artisans.
                </p>
                <Link
                  href="/custom-order"
                  onClick={onClose}
                  className="inline-block w-full py-2 px-3 rounded-full bg-[#913638] text-white text-xs font-semibold hover:bg-[#74292B] active:scale-[0.98] transition-all text-center cursor-pointer shadow-xs"
                >
                  Request Custom Order →
                </Link>
              </div>
            </div>

          </div>

          {/* Bottom Footer Contact */}
          <div className="p-5 border-t border-[#E7D1CC] bg-[#FFF9F6] space-y-1.5 text-xs text-[#786864]">
            <a
              href={contactPhoneLink}
              className="flex items-center gap-2 hover:text-[#913638] transition-colors"
            >
              <Phone className="w-3.5 h-3.5 text-[#913638]" />
              <span>{contactPhone}</span>
            </a>
            <a
              href={contactEmailLink}
              className="flex items-center gap-2 hover:text-[#913638] transition-colors"
            >
              <Mail className="w-3.5 h-3.5 text-[#913638]" />
              <span>{contactEmail}</span>
            </a>
          </div>

        </div>
      </div>
    </div>
  );
}
