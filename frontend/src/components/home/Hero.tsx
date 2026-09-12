"use client";

import React, { useState, useEffect } from "react";
import Image from "next/image";
import Link from "next/link";
import { ArrowRight } from "lucide-react";

const HERO_SLIDES = [
  {
    id: 1,
    titleLine1: "Little",
    titleLine2: "Stitches",
    titleHighlight: "Big Happiness",
    subtitle: "Handmade crochet products that add warmth to your everyday life.",
    bgImage: "/knotelle/images/hero/hero-enhanced.jpg",
    cardNote: "Good Things Are Handmade",
    scriptAccent: "Yarn Crafts Happiness",
  },
  {
    id: 2,
    titleLine1: "Everyday",
    titleLine2: "Elegance",
    titleHighlight: "Handcrafted Bags",
    subtitle: "Artisanal granny square bags & wearable creations woven with love.",
    bgImage: "/knotelle/images/hero/2image.png",
    cardNote: "Every Stitch Has A Story",
    scriptAccent: "Handmade Boutique Quality",
  },
  {
    id: 3,
    titleLine1: "Everlasting",
    titleLine2: "Blooms",
    titleHighlight: "Made With Love",
    subtitle: "Bespoke everlasting flower bouquets crafted to brighten every moment.",
    bgImage: "/knotelle/images/hero/3image.png",
    cardNote: "Crafted Just For You",
    scriptAccent: "Where Yarn Meets Art",
  },
  {
    id: 4,
    titleLine1: "Bespoke",
    titleLine2: "Creations",
    titleHighlight: "Custom Crafted",
    subtitle: "Personalized crochet treasures tailored specially for your memorable moments.",
    bgImage: "/knotelle/images/hero/4image.png",
    cardNote: "Pure Artisan Warmth",
    scriptAccent: "Customized With Love",
  },
];

export function Hero() {
  const [currentSlide, setCurrentSlide] = useState(0);

  // Auto-play every 2 seconds (2000ms) as requested
  useEffect(() => {
    const timer = setInterval(() => {
      setCurrentSlide((prev) => (prev + 1) % HERO_SLIDES.length);
    }, 2000);
    return () => clearInterval(timer);
  }, []);

  const slide = HERO_SLIDES[currentSlide];

  return (
    <section className="relative w-full overflow-hidden bg-[#FFF9F6] border-b border-[#E7D1CC]/60">
      
      {/* Edge-to-Edge Full-Width Panoramic Hero Container */}
      <div className="relative w-full min-h-[440px] sm:min-h-[520px] md:min-h-[580px] lg:min-h-[640px] xl:min-h-[700px] flex items-center">
        
        {/* Full-Width Background Slides with Smooth Crossfade */}
        {HERO_SLIDES.map((item, index) => (
          <div
            key={item.id}
            className={`absolute inset-0 z-0 w-full h-full transition-opacity duration-700 ease-in-out ${
              currentSlide === index ? "opacity-100 z-10" : "opacity-0 z-0 pointer-events-none"
            }`}
          >
            <Image
              src={item.bgImage}
              alt={item.titleHighlight}
              fill
              priority={index === 0}
              quality={100}
              sizes="100vw"
              className="object-cover object-right sm:object-center lg:object-right transform scale-100 transition-transform duration-3000"
            />
            {/* Soft Gradient Overlay for Text Readability */}
            <div className="absolute inset-0 bg-gradient-to-r from-[#FFF5F2]/95 via-[#FFF5F2]/85 sm:via-[#FFF5F2]/70 md:via-[#FFF5F2]/50 to-transparent w-full md:w-[65%] lg:w-[55%]" />
            <div className="absolute inset-0 bg-gradient-to-t from-[#FFF5F2]/60 via-transparent to-transparent sm:hidden" />
          </div>
        ))}

        {/* Top-Right Script Accent */}
        <div className="absolute top-4 right-10 sm:top-8 sm:right-16 lg:right-24 text-center hidden sm:block z-20 pointer-events-none select-none transition-all duration-500">
          <span className="font-script-boutique text-2xl sm:text-3xl lg:text-4xl font-bold text-[#913638] drop-shadow-xs">
            {slide.scriptAccent}
          </span>
          <span className="text-xs sm:text-sm text-[#913638] block mt-0.5">♡</span>
        </div>

        {/* Content Container (Constrained within Max-Width with edge padding for perfect typography) */}
        <div className="relative z-20 w-full max-w-7xl mx-auto px-4 sm:px-8 lg:px-12 py-10 sm:py-16">
          <div className="max-w-xl lg:max-w-2xl space-y-4 sm:space-y-6">
            
            {/* Main Headline */}
            <h1 className="font-serif-luxury text-2xl sm:text-4xl md:text-5xl lg:text-[64px] font-bold text-[#2E211E] leading-[1.08] tracking-tight transition-all duration-500">
              {slide.titleLine1} <br />
              {slide.titleLine2} <br />
              <span className="text-[#913638] italic font-serif font-normal">
                {slide.titleHighlight}
              </span>
            </h1>

            {/* Subtitle */}
            <p className="text-xs sm:text-sm md:text-base lg:text-lg text-[#786864] font-normal leading-relaxed max-w-md lg:max-w-lg transition-all duration-500">
              {slide.subtitle}
            </p>

            {/* Interactive Action Buttons */}
            <div className="flex flex-wrap items-center gap-2.5 sm:gap-4 pt-1 sm:pt-2">
              <Link
                href="/shop"
                className="px-5 sm:px-8 py-2.5 sm:py-3.5 rounded-full bg-[#913638] text-white text-xs sm:text-sm md:text-base font-semibold hover:bg-[#74292B] shadow-xs hover:shadow-boutique-hover transition-all flex items-center justify-center gap-2 group active:scale-[0.98]"
              >
                <span>Shop Now</span>
                <ArrowRight className="w-3.5 h-3.5 sm:w-4 sm:h-4 group-hover:translate-x-1 transition-transform" />
              </Link>
              
              <Link
                href="/shop"
                className="px-5 sm:px-8 py-2.5 sm:py-3.5 rounded-full bg-white/95 backdrop-blur-xs text-[#2E211E] border border-[#E7D1CC] hover:bg-[#FCE9E5] hover:text-[#913638] hover:border-[#EFB8B0] text-xs sm:text-sm md:text-base font-semibold shadow-xs transition-all flex items-center justify-center active:scale-[0.98]"
              >
                <span>Explore Collections</span>
              </Link>
            </div>

          </div>
        </div>

        {/* Carousel Indicator Dots */}
        <div className="absolute bottom-3 sm:bottom-6 left-1/2 -translate-x-1/2 z-20 flex items-center gap-2.5">
          {HERO_SLIDES.map((_, i) => (
            <button
              key={i}
              onClick={() => setCurrentSlide(i)}
              className={`h-2 rounded-full transition-all duration-500 cursor-pointer ${
                currentSlide === i ? "bg-[#913638] w-8 shadow-xs" : "bg-[#E7D1CC]/90 w-2.5 hover:bg-[#EFB8B0]"
              }`}
              aria-label={`Go to slide ${i + 1}`}
            />
          ))}
        </div>

      </div>
    </section>
  );
}



