"use client";

import React, { useState } from "react";
import Link from "next/link";
import { usePathname } from "next/navigation";
import { Search, Heart, User, ShoppingBag, Menu, Sparkles } from "lucide-react";
import { KnotelleCrownLogo } from "@/components/ui/BotanicalDecorations";
import { useCart } from "@/context/CartContext";
import { useWishlist } from "@/context/WishlistContext";
import { SearchModal } from "./SearchModal";
import { MobileDrawer } from "./MobileDrawer";

export function Navbar() {
  const pathname = usePathname();
  const { totalItemsCount, setIsCartOpen } = useCart();
  const { wishlistCount } = useWishlist();
  const [isSearchOpen, setIsSearchOpen] = useState(false);
  const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);

  const navLinks = [
    { name: "Home", href: "/" },
    { name: "Shop", href: "/shop" },
    { name: "Custom Order", href: "/custom-order" },
    { name: "About", href: "/about" },
    { name: "Contact", href: "/contact" },
  ];

  return (
    <>
      <header className="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-[#E7D1CC] transition-all">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex items-center justify-between h-20">
            
            {/* Mobile: Hamburger Button */}
            <div className="flex items-center lg:hidden">
              <button
                onClick={() => setIsMobileMenuOpen(true)}
                className="p-2 rounded-full text-[#2E211E] hover:bg-[#FCE9E5] transition-colors"
                aria-label="Open mobile menu"
              >
                <Menu className="w-6 h-6" />
              </button>
            </div>

            {/* Left: KNOTELLE Logo */}
            <div className="flex items-center">
              <Link href="/" className="flex items-center group py-1" aria-label="KNOTELLE Home">
                <KnotelleCrownLogo className="h-14 sm:h-16 w-auto" />
              </Link>
            </div>

            {/* Center: Navigation Links */}
            <nav className="hidden lg:flex items-center gap-7 xl:gap-9">
              {navLinks.map((link) => {
                const isActive = pathname === link.href;
                const isCustomOrder = link.href === "/custom-order";

                if (isCustomOrder) {
                  return (
                    <Link
                      key={link.href}
                      href={link.href}
                      className={`inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-semibold tracking-wide transition-all shadow-xs active:scale-95 ${
                        isActive
                          ? "bg-[#913638] text-white ring-2 ring-[#913638]/25 shadow-sm"
                          : "bg-[#FCE9E5] text-[#913638] border border-[#E7D1CC] hover:bg-[#913638] hover:text-white hover:border-[#913638] hover:shadow-xs"
                      }`}
                    >
                      <Sparkles className="w-3.5 h-3.5" />
                      <span>{link.name}</span>
                    </Link>
                  );
                }

                return (
                  <Link
                    key={link.href}
                    href={link.href}
                    className={`relative text-sm font-medium tracking-wide py-2 transition-colors flex flex-col items-center ${
                      isActive
                        ? "text-[#913638] font-bold"
                        : "text-[#2E211E] hover:text-[#913638]"
                    }`}
                  >
                    <span>{link.name}</span>
                    {isActive && (
                      <span className="absolute bottom-0 w-6 h-0.5 bg-[#913638] rounded-full" />
                    )}
                  </Link>
                );
              })}
            </nav>

            {/* Right: Actions */}
            <div className="flex items-center gap-1 sm:gap-2">
              <button
                onClick={() => setIsSearchOpen(true)}
                className="p-2.5 rounded-full text-[#2E211E] hover:text-[#913638] hover:bg-[#FCE9E5] transition-colors"
                aria-label="Search store"
              >
                <Search className="w-5 h-5" />
              </button>

              <Link
                href="/account/wishlist"
                className="relative p-2.5 rounded-full text-[#2E211E] hover:text-[#913638] hover:bg-[#FCE9E5] transition-colors hidden sm:flex items-center justify-center"
                aria-label="View wishlist"
              >
                <Heart className="w-5 h-5" />
                {wishlistCount > 0 && (
                  <span className="absolute top-1 right-1 w-4 h-4 rounded-full bg-[#913638] text-white text-[9px] font-bold flex items-center justify-center">
                    {wishlistCount}
                  </span>
                )}
              </Link>

              <Link
                href="/account"
                className="p-2.5 rounded-full text-[#2E211E] hover:text-[#913638] hover:bg-[#FCE9E5] transition-colors hidden sm:flex items-center justify-center"
                aria-label="My Account"
              >
                <User className="w-5 h-5" />
              </Link>

              <button
                onClick={() => setIsCartOpen(true)}
                className="relative p-2.5 rounded-full text-[#2E211E] hover:text-[#913638] hover:bg-[#FCE9E5] transition-colors flex items-center justify-center"
                aria-label="Shopping Cart"
              >
                <ShoppingBag className="w-5 h-5" />
                <span className="absolute top-1 right-1 w-4 h-4 rounded-full bg-[#913638] text-white text-[9px] font-bold flex items-center justify-center">
                  {totalItemsCount}
                </span>
              </button>
            </div>

          </div>
        </div>
      </header>

      {/* Search Modal & Mobile Drawer */}
      <SearchModal
        isOpen={isSearchOpen}
        onClose={() => setIsSearchOpen(false)}
      />
      <MobileDrawer
        isOpen={isMobileMenuOpen}
        onClose={() => setIsMobileMenuOpen(false)}
        onOpenSearch={() => {
          setIsMobileMenuOpen(false);
          setIsSearchOpen(true);
        }}
      />
    </>
  );
}
