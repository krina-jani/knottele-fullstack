import React from "react";
import Image from "next/image";
import Link from "next/link";
import {
  KnotelleCrownLogo,
  InstagramIcon,
  FacebookIcon,
  PinterestIcon,
  YouTubeIcon,
} from "@/components/ui/BotanicalDecorations";
import { Phone, Mail, MapPin } from "lucide-react";

export function Footer() {
  return (
    <footer className="relative w-full overflow-hidden bg-[#FFF9F6] border-t border-[#E7D1CC]/70 pt-12 sm:pt-16 pb-8 text-[#2E211E]">
      
      {/* Full-Width Panoramic Footer Background Image */}
      <div className="absolute inset-0 z-0 w-full h-full">
        <Image
          src="/knotelle/images/categories/footer.png"
          alt="KNOTELLE Boutique Footer"
          fill
          quality={100}
          sizes="100vw"
          className="object-cover object-center lg:object-bottom pointer-events-none"
        />
        {/* Soft Contrast Gradient Overlays for Crystal-Clear Text Legibility */}
        <div className="absolute inset-0 bg-[#FFF9F6]/85 sm:bg-[#FFF9F6]/75 md:bg-[#FFF9F6]/60 lg:bg-[#FFF9F6]/40 pointer-events-none" />
        <div className="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-[#D6A79F]/30 to-transparent pointer-events-none" />
      </div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 relative z-10">
        
        {/* Main Columns Container */}
        <div className="flex flex-col lg:flex-row items-start justify-between gap-8 lg:gap-12 mb-12">
          
          {/* Left Block: Brand Identity + Socials */}
          <div className="flex flex-col items-start space-y-4 lg:pr-10 lg:border-r lg:border-[#E7D1CC]/90 shrink-0 w-full lg:w-auto">
            <Link href="/" className="inline-block group" aria-label="KNOTELLE Home">
              <KnotelleCrownLogo className="h-24 sm:h-28 w-auto drop-shadow-xs" />
            </Link>

            {/* Social Media Icons Row */}
            <div className="flex items-center gap-2.5 pt-1.5 text-[#2E211E]">
              <a
                href="https://instagram.com/knotelleindia"
                target="_blank"
                rel="noopener noreferrer"
                aria-label="Instagram"
                className="w-8.5 h-8.5 rounded-full bg-white/85 backdrop-blur-xs border border-[#E7D1CC] shadow-xs flex items-center justify-center text-[#2E211E] hover:bg-[#913638] hover:text-white hover:border-[#913638] hover:scale-108 transition-all active:scale-95"
              >
                <InstagramIcon className="w-4.5 h-4.5" />
              </a>
              <a
                href="https://facebook.com/knotelleindia"
                target="_blank"
                rel="noopener noreferrer"
                aria-label="Facebook"
                className="w-8.5 h-8.5 rounded-full bg-white/85 backdrop-blur-xs border border-[#E7D1CC] shadow-xs flex items-center justify-center text-[#2E211E] hover:bg-[#913638] hover:text-white hover:border-[#913638] hover:scale-108 transition-all active:scale-95"
              >
                <FacebookIcon className="w-4.5 h-4.5" />
              </a>
              <a
                href="https://pinterest.com/knotelleindia"
                target="_blank"
                rel="noopener noreferrer"
                aria-label="Pinterest"
                className="w-8.5 h-8.5 rounded-full bg-white/85 backdrop-blur-xs border border-[#E7D1CC] shadow-xs flex items-center justify-center text-[#2E211E] hover:bg-[#913638] hover:text-white hover:border-[#913638] hover:scale-108 transition-all active:scale-95"
              >
                <PinterestIcon className="w-4.5 h-4.5" />
              </a>
              <a
                href="https://youtube.com/@knotelleindia"
                target="_blank"
                rel="noopener noreferrer"
                aria-label="YouTube"
                className="w-8.5 h-8.5 rounded-full bg-white/85 backdrop-blur-xs border border-[#E7D1CC] shadow-xs flex items-center justify-center text-[#2E211E] hover:bg-[#913638] hover:text-white hover:border-[#913638] hover:scale-108 transition-all active:scale-95"
              >
                <YouTubeIcon className="w-4.5 h-4.5" />
              </a>
            </div>
          </div>

          {/* Right Columns Grid: Quick Links, Help, Contact */}
          <div className="grid grid-cols-1 sm:grid-cols-3 gap-8 sm:gap-10 lg:gap-16 w-full flex-1">
            
            {/* Column 1: Quick Links */}
            <div className="space-y-3.5">
              <h4 className="font-bold text-sm text-[#2E211E]">
                <span className="border-b-2 border-[#913638] pb-1 inline-block">
                  Quick Links
                </span>
              </h4>
              <ul className="space-y-2 text-xs sm:text-sm text-[#5C4D49] font-medium pt-1">
                <li>
                  <Link href="/" className="hover:text-[#913638] transition-colors">
                    Home
                  </Link>
                </li>
                <li>
                  <Link href="/shop" className="hover:text-[#913638] transition-colors">
                    Shop
                  </Link>
                </li>
                <li>
                  <Link href="/custom-order" className="hover:text-[#913638] transition-colors">
                    Custom Order
                  </Link>
                </li>
                <li>
                  <Link href="/about" className="hover:text-[#913638] transition-colors">
                    About
                  </Link>
                </li>
                <li>
                  <Link href="/contact" className="hover:text-[#913638] transition-colors">
                    Contact
                  </Link>
                </li>
              </ul>
            </div>

            {/* Column 2: Help */}
            <div className="space-y-3.5">
              <h4 className="font-bold text-sm text-[#2E211E]">
                <span className="border-b-2 border-[#913638] pb-1 inline-block">
                  Help
                </span>
              </h4>
              <ul className="space-y-2 text-xs sm:text-sm text-[#5C4D49] font-medium pt-1">
                <li>
                  <Link href="/contact" className="hover:text-[#913638] transition-colors">
                    Shipping Policy
                  </Link>
                </li>
                <li>
                  <Link href="/contact" className="hover:text-[#913638] transition-colors">
                    Return & Refund
                  </Link>
                </li>
                <li>
                  <Link href="/contact" className="hover:text-[#913638] transition-colors">
                    FAQ
                  </Link>
                </li>
                <li>
                  <Link href="/account/orders" className="hover:text-[#913638] transition-colors">
                    Track Order
                  </Link>
                </li>
              </ul>
            </div>

            {/* Column 3: Contact */}
            <div className="space-y-3.5">
              <h4 className="font-bold text-sm text-[#2E211E]">
                <span className="border-b-2 border-[#913638] pb-1 inline-block">
                  Contact
                </span>
              </h4>
              <div className="space-y-3 text-xs sm:text-sm text-[#5C4D49] font-medium pt-1">
                <p className="flex items-center gap-2.5">
                  <Phone className="w-4 h-4 text-[#2E211E] shrink-0" />
                  <a href="tel:+919773039243" className="hover:text-[#913638] transition-colors font-semibold text-[#2E211E]">
                    +91 97730 39243
                  </a>
                </p>
                <p className="flex items-center gap-2.5">
                  <Mail className="w-4 h-4 text-[#2E211E] shrink-0" />
                  <a href="mailto:support@knotelle.in" className="hover:text-[#913638] transition-colors font-semibold text-[#2E211E]">
                    support@knotelle.in
                  </a>
                </p>
                <p className="flex items-center gap-2.5">
                  <MapPin className="w-4 h-4 text-[#2E211E] shrink-0" />
                  <span className="font-semibold text-[#2E211E]">India</span>
                </p>
              </div>
            </div>

          </div>

        </div>

        {/* Bottom Bar matching screenshot */}
        <div className="pt-6 border-t border-[#E7D1CC]/80 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-[#5C4D49] font-medium">
          <p>© 2024 Knotelle. All rights reserved.</p>

          <p className="flex items-center gap-1.5">
            <span>Made with</span>
            <span className="text-[#913638] text-sm">♡</span>
            <span>for a kinder, cozier world.</span>
          </p>
        </div>

      </div>
    </footer>
  );
}



