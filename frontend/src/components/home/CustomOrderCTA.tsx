"use client";

import React, { useState, useEffect } from "react";
import Link from "next/link";
import { ArrowRight, Sparkles } from "lucide-react";
import { FlowerIcon, BotanicalFlourish } from "@/components/ui/BotanicalDecorations";

const ROTATING_TITLES = [
  {
    badge: "Bespoke Handcrafting",
    line1: "Your Idea.",
    line2: "Our Yarn.",
    desc: "From custom color palettes and personalized initials to unique floral bouquets and character plushies — let's create something made especially for you.",
  },
  {
    badge: "Handmade Just For You",
    line1: "Your Dream.",
    line2: "Our Craft.",
    desc: "Turn your favorite inspirations and vision into timeless, handcrafted cotton crochet keepsakes made with love.",
  },
  {
    badge: "Custom Artisan Creations",
    line1: "Your Wish.",
    line2: "Our Magic.",
    desc: "Bespoke floral bouquets, custom personalized plushies, and heirloom gifts tailored to your exact style.",
  },
  {
    badge: "Crafted With Care",
    line1: "Your Story.",
    line2: "Every Stitch.",
    desc: "Custom color matching, personalized embroidery tags, and bespoke crochet designs crafted exclusively for your moments.",
  },
];

export function CustomOrderCTA() {
  const [index, setIndex] = useState(0);
  const [isFading, setIsFading] = useState(false);

  // Rotate title every 2 seconds (2000ms) with smooth crossfade
  useEffect(() => {
    const timer = setInterval(() => {
      setIsFading(true);
      setTimeout(() => {
        setIndex((prev) => (prev + 1) % ROTATING_TITLES.length);
        setIsFading(false);
      }, 300);
    }, 2000);

    return () => clearInterval(timer);
  }, []);

  const current = ROTATING_TITLES[index];

  return (
    <section className="py-16 lg:py-20 bg-[#FFF9F6]">
      <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div className="relative rounded-[3rem] bg-gradient-to-b from-[#FCE9E5] via-[#FFF9F6] to-[#FCE9E5] border border-[#E7D1CC] p-6 sm:p-14 lg:p-16 shadow-boutique overflow-hidden">
          
          {/* Subtle floral accents */}
          <div className="absolute top-6 left-8 text-[#913638]/20 hidden sm:block">
            <FlowerIcon className="w-12 h-12" />
          </div>
          <div className="absolute bottom-6 right-8 text-[#913638]/20 hidden sm:block">
            <FlowerIcon className="w-12 h-12" />
          </div>

          <div className="max-w-2xl mx-auto space-y-6 relative z-10">
            {/* Badge */}
            <div className="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white border border-[#E7D1CC] text-xs font-semibold text-[#913638] shadow-2xs transition-all duration-300">
              <Sparkles className="w-3.5 h-3.5 text-[#C69A5A]" />
              <span className={`transition-opacity duration-300 ${isFading ? "opacity-0" : "opacity-100"}`}>
                {current.badge}
              </span>
            </div>

            {/* Rotating Heading */}
            <div className="space-y-2 min-h-[130px] sm:min-h-[150px] lg:min-h-[170px] flex flex-col justify-center">
              <h2
                className={`font-serif-luxury text-3xl sm:text-4xl lg:text-5xl font-bold text-[#2E211E] tracking-tight leading-[1.15] transition-all duration-300 ease-out transform ${
                  isFading
                    ? "opacity-0 -translate-y-2 scale-98"
                    : "opacity-100 translate-y-0 scale-100"
                }`}
              >
                {current.line1}
                <span className="block text-[#913638] italic">
                  {current.line2}
                </span>
              </h2>
              <div className="flex justify-center pt-1">
                <BotanicalFlourish className="w-24 h-5 text-[#EFB8B0]" />
              </div>
            </div>

            {/* Description */}
            <p
              className={`text-base sm:text-lg text-[#786864] leading-relaxed transition-opacity duration-300 min-h-[56px] ${
                isFading ? "opacity-0" : "opacity-100"
              }`}
            >
              {current.desc}
            </p>

            {/* Indicator Dots for the 2-second rotating titles */}
            <div className="flex justify-center items-center gap-2 pt-1">
              {ROTATING_TITLES.map((_, i) => (
                <button
                  key={i}
                  onClick={() => {
                    setIsFading(true);
                    setTimeout(() => {
                      setIndex(i);
                      setIsFading(false);
                    }, 200);
                  }}
                  className={`h-1.5 rounded-full transition-all duration-300 cursor-pointer ${
                    index === i
                      ? "w-6 bg-[#913638]"
                      : "w-1.5 bg-[#E7D1CC] hover:bg-[#EFB8B0]"
                  }`}
                  aria-label={`Go to slide ${i + 1}`}
                />
              ))}
            </div>

            {/* Action CTA Button */}
            <div className="pt-2">
              <Link
                href="/custom-order"
                className="inline-flex items-center gap-2 px-7 sm:px-8 py-3 sm:py-3.5 rounded-full bg-[#913638] text-white text-xs sm:text-sm font-semibold hover:bg-[#74292B] shadow-xs hover:shadow-boutique-hover transition-all active:scale-[0.98] group"
              >
                <span>Start a Custom Order</span>
                <ArrowRight className="w-4 h-4 group-hover:translate-x-1 transition-transform" />
              </Link>
            </div>
          </div>

        </div>
      </div>
    </section>
  );
}
