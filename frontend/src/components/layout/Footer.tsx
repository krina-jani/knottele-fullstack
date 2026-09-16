"use client";

import React, { useState, useEffect } from "react";
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
import { useWebsiteMedia } from "@/context/MediaContext";

const DEFAULT_FOOTER_BG = "/images/categories/footer.png";

export function Footer() {
  const { media } = useWebsiteMedia();
  const [footerBg, setFooterBg] = useState(DEFAULT_FOOTER_BG);

  useEffect(() => {
    const bg = media?.footer?.bg || media?.footer?.image;
    if (bg) {
      setFooterBg(bg);
    }
  }, [media?.footer?.bg, media?.footer?.image]);

  const f = media?.footer;

  // Social Links
  const instagram = f?.social?.instagram || { url: "https://instagram.com/knotelleindia", is_active: true };
  const facebook = f?.social?.facebook || { url: "https://facebook.com/knotelleindia", is_active: true };
  const pinterest = f?.social?.pinterest || { url: "https://pinterest.com/knotelleindia", is_active: true };
  const youtube = f?.social?.youtube || { url: "https://youtube.com/@knotelleindia", is_active: true };

  // Columns & Links
  const col1Title = f?.column_1?.title || "Quick Links";
  const col1Links = (f?.column_1?.links && f.column_1.links.length > 0)
    ? f.column_1.links.filter((l) => l.is_active !== false)
    : [
        { label: "Home", url: "/" },
        { label: "Shop", url: "/shop" },
        { label: "Custom Order", url: "/custom-order" },
        { label: "About", url: "/about" },
        { label: "Contact", url: "/contact" },
      ];

  const col2Title = f?.column_2?.title || "Help";
  const col2Links = (f?.column_2?.links && f.column_2.links.length > 0)
    ? f.column_2.links.filter((l) => l.is_active !== false)
    : [
        { label: "Shipping Policy", url: "/contact" },
        { label: "Return & Refund", url: "/contact" },
        { label: "FAQ", url: "/contact" },
        { label: "Track Order", url: "/account/orders" },
      ];

  const col3Title = f?.column_3?.title || "Contact";
  const contactPhone = f?.column_3?.phone || "+91 97730 39243";
  const contactPhoneLink = f?.column_3?.phone_link || `tel:${contactPhone.replace(/\s+/g, "")}`;
  const contactEmail = f?.column_3?.email || "support@knotelle.in";
  const contactEmailLink = f?.column_3?.email_link || `mailto:${contactEmail}`;
  const contactAddress = f?.column_3?.address || "India";

  // Bottom Bar
  const currentYear = new Date().getFullYear();
  const rawCopyright = f?.copyright_text || "© {year} Knotelle. All rights reserved.";
  const copyrightText = rawCopyright.includes("{year}")
    ? rawCopyright.replace("{year}", currentYear.toString())
    : rawCopyright;

  const heartTagline = f?.heart_tagline || "Made with ♡ for a kinder, cozier world.";

  return (
    <footer className="relative w-full overflow-hidden bg-[#FFF9F6] border-t border-[#E7D1CC]/70 pt-12 sm:pt-16 pb-8 text-[#2E211E]">
      
      {/* Full-Width Panoramic Footer Background Image */}
      <div className="absolute inset-0 z-0 w-full h-full">
        <Image
          src={footerBg}
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
              {instagram.is_active !== false && instagram.url && (
                <a
                  href={instagram.url}
                  target="_blank"
                  rel="noopener noreferrer"
                  aria-label="Instagram"
                  className="w-8.5 h-8.5 rounded-full bg-white/85 backdrop-blur-xs border border-[#E7D1CC] shadow-xs flex items-center justify-center text-[#2E211E] hover:bg-[#913638] hover:text-white hover:border-[#913638] hover:scale-108 transition-all active:scale-95"
                >
                  <InstagramIcon className="w-4.5 h-4.5" />
                </a>
              )}
              {facebook.is_active !== false && facebook.url && (
                <a
                  href={facebook.url}
                  target="_blank"
                  rel="noopener noreferrer"
                  aria-label="Facebook"
                  className="w-8.5 h-8.5 rounded-full bg-white/85 backdrop-blur-xs border border-[#E7D1CC] shadow-xs flex items-center justify-center text-[#2E211E] hover:bg-[#913638] hover:text-white hover:border-[#913638] hover:scale-108 transition-all active:scale-95"
                >
                  <FacebookIcon className="w-4.5 h-4.5" />
                </a>
              )}
              {pinterest.is_active !== false && pinterest.url && (
                <a
                  href={pinterest.url}
                  target="_blank"
                  rel="noopener noreferrer"
                  aria-label="Pinterest"
                  className="w-8.5 h-8.5 rounded-full bg-white/85 backdrop-blur-xs border border-[#E7D1CC] shadow-xs flex items-center justify-center text-[#2E211E] hover:bg-[#913638] hover:text-white hover:border-[#913638] hover:scale-108 transition-all active:scale-95"
                >
                  <PinterestIcon className="w-4.5 h-4.5" />
                </a>
              )}
              {youtube.is_active !== false && youtube.url && (
                <a
                  href={youtube.url}
                  target="_blank"
                  rel="noopener noreferrer"
                  aria-label="YouTube"
                  className="w-8.5 h-8.5 rounded-full bg-white/85 backdrop-blur-xs border border-[#E7D1CC] shadow-xs flex items-center justify-center text-[#2E211E] hover:bg-[#913638] hover:text-white hover:border-[#913638] hover:scale-108 transition-all active:scale-95"
                >
                  <YouTubeIcon className="w-4.5 h-4.5" />
                </a>
              )}
            </div>
          </div>

          {/* Right Columns Grid: Quick Links, Help, Contact */}
          <div className="grid grid-cols-1 sm:grid-cols-3 gap-8 sm:gap-10 lg:gap-16 w-full flex-1">
            
            {/* Column 1: Quick Links */}
            <div className="space-y-3.5">
              <h4 className="font-bold text-sm text-[#2E211E]">
                <span className="border-b-2 border-[#913638] pb-1 inline-block">
                  {col1Title}
                </span>
              </h4>
              <ul className="space-y-2 text-xs sm:text-sm text-[#5C4D49] font-medium pt-1">
                {col1Links.map((link, idx) => {
                  const href = link.url || link.href || "/";
                  const label = link.label || link.name || "Link";
                  return (
                    <li key={idx}>
                      <Link href={href} prefetch={true} className="hover:text-[#913638] transition-colors">
                        {label}
                      </Link>
                    </li>
                  );
                })}
              </ul>
            </div>

            {/* Column 2: Help */}
            <div className="space-y-3.5">
              <h4 className="font-bold text-sm text-[#2E211E]">
                <span className="border-b-2 border-[#913638] pb-1 inline-block">
                  {col2Title}
                </span>
              </h4>
              <ul className="space-y-2 text-xs sm:text-sm text-[#5C4D49] font-medium pt-1">
                {col2Links.map((link, idx) => {
                  const href = link.url || link.href || "/contact";
                  const label = link.label || link.name || "Help";
                  return (
                    <li key={idx}>
                      <Link href={href} prefetch={true} className="hover:text-[#913638] transition-colors">
                        {label}
                      </Link>
                    </li>
                  );
                })}
              </ul>
            </div>

            {/* Column 3: Contact */}
            <div className="space-y-3.5">
              <h4 className="font-bold text-sm text-[#2E211E]">
                <span className="border-b-2 border-[#913638] pb-1 inline-block">
                  {col3Title}
                </span>
              </h4>
              <div className="space-y-3 text-xs sm:text-sm text-[#5C4D49] font-medium pt-1">
                <p className="flex items-center gap-2.5">
                  <Phone className="w-4 h-4 text-[#2E211E] shrink-0" />
                  <a href={contactPhoneLink} className="hover:text-[#913638] transition-colors font-semibold text-[#2E211E]">
                    {contactPhone}
                  </a>
                </p>
                <p className="flex items-center gap-2.5">
                  <Mail className="w-4 h-4 text-[#2E211E] shrink-0" />
                  <a href={contactEmailLink} className="hover:text-[#913638] transition-colors font-semibold text-[#2E211E]">
                    {contactEmail}
                  </a>
                </p>
                <p className="flex items-center gap-2.5">
                  <MapPin className="w-4 h-4 text-[#2E211E] shrink-0" />
                  <span className="font-semibold text-[#2E211E]">{contactAddress}</span>
                </p>
              </div>
            </div>

          </div>

        </div>

        {/* Bottom Bar */}
        <div className="pt-6 border-t border-[#E7D1CC]/80 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-[#5C4D49] font-medium">
          <p>{copyrightText}</p>

          <p className="flex items-center gap-1.5">
            <span>{heartTagline}</span>
          </p>
        </div>

      </div>
    </footer>
  );
}



