"use client";

import React from "react";
import Image from "next/image";
import Link from "next/link";
import { ArrowRight, Heart, Sparkles, Leaf, Smile } from "lucide-react";

export function BrandStory() {
  const storyFeatures = [
    { title: "Handmade with Love", icon: Heart },
    { title: "Premium Yarn Quality", icon: Sparkles },
    { title: "100% Pure Natural Cotton", icon: Leaf },
    { title: "Happiness Guaranteed", icon: Smile },
  ];

  return (
    <section className="relative w-full overflow-hidden bg-[#FCE9E5] border-y border-[#E7D1CC]/70 py-12 sm:py-16 lg:py-20">
      
      {/* Full-Width Background Image Layer */}
      <div className="absolute inset-0 z-0 w-full h-full">
        <Image
          src="/knotelle/images/homepage/middleimg.png"
          alt="KNOTELLE Artisanal Crochet Craftsmanship"
          fill
          quality={100}
          sizes="100vw"
          className="object-cover object-center lg:object-right"
        />
        {/* Soft Multi-Layer Gradient Overlays for High-Contrast Text Legibility */}
        <div className="absolute inset-0 bg-gradient-to-r from-[#FFF5F2]/95 via-[#FFF5F2]/85 sm:via-[#FFF5F2]/60 to-transparent w-full md:w-[60%]" />
        <div className="absolute inset-0 bg-gradient-to-l from-[#FFF5F2]/90 via-[#FFF5F2]/60 to-transparent w-full md:w-[45%] ml-auto hidden lg:block" />
        <div className="absolute inset-0 bg-gradient-to-t from-[#FFF5F2]/70 via-transparent to-transparent sm:hidden" />
      </div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-center justify-between">
          
          {/* Left Column: Heading, Subtitle & CTA (Spans 6 cols on lg) */}
          <div className="lg:col-span-6 space-y-4 sm:space-y-5 text-center lg:text-left flex flex-col items-center lg:items-start max-w-lg">
            <h2 className="font-serif-luxury text-3xl sm:text-4xl lg:text-5xl font-bold text-[#2E211E] leading-[1.12] tracking-tight">
              Every Stitch <br />
              <span className="text-[#913638] italic font-serif font-normal">
                Has a Story
              </span>
            </h2>

            <p className="text-xs sm:text-sm md:text-base text-[#786864] leading-relaxed">
              More than just crochet, we create memories, happiness and a little bit of magic.
            </p>

            <div className="pt-2">
              <Link
                href="/about"
                className="inline-flex items-center gap-2 px-6 sm:px-7 py-3 rounded-full bg-[#913638] text-white text-xs sm:text-sm font-semibold hover:bg-[#74292B] shadow-xs hover:shadow-boutique-hover transition-all active:scale-[0.98]"
              >
                <span>Read Our Story</span>
                <ArrowRight className="w-4 h-4" />
              </Link>
            </div>
          </div>

          {/* Right Column: 4 Feature Items (Spans 5 cols on lg, aligned to the right) */}
          <div className="lg:col-span-5 lg:col-start-8 space-y-3.5 sm:space-y-4">
            {storyFeatures.map((feat, idx) => {
              const Icon = feat.icon;
              return (
                <div
                  key={idx}
                  className="flex items-center gap-3.5 px-4 py-2.5 sm:py-3 rounded-2xl bg-white/90 backdrop-blur-md border border-[#E7D1CC]/80 shadow-xs hover:shadow-md hover:border-[#913638]/40 transition-all"
                >
                  <div className="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-[#FFF9F6] border border-[#E7D1CC] flex items-center justify-center text-[#913638] shrink-0 shadow-2xs">
                    <Icon className="w-4 h-4 sm:w-4.5 sm:h-4.5" />
                  </div>
                  <span className="text-xs sm:text-sm font-semibold text-[#2E211E]">
                    {feat.title}
                  </span>
                </div>
              );
            })}
          </div>

        </div>
      </div>
    </section>
  );
}

