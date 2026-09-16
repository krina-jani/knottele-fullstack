"use client";

import React from "react";
import Image from "next/image";
import Link from "next/link";
import { ArrowRight } from "lucide-react";
import { HandmadePaperTag } from "@/components/ui/BotanicalDecorations";
import { useWebsiteMedia } from "@/context/MediaContext";

const DEFAULT_BANNER_IMG = "/images/homepage/middleimg.png";

export function CustomBanner() {
  const { media } = useWebsiteMedia();
  const cc = media?.customCrochet;

  const bannerImg = cc?.desktop || DEFAULT_BANNER_IMG;
  const bannerImgMobile = cc?.mobile || null;
  const title = cc?.title || "Custom Crochet";
  const subtitle = cc?.subtitle || "Just for You";
  const description = cc?.description || "Your imagination, our yarn. \nLet's create something special together.";
  const ctaText = cc?.cta_text || "Request Your Custom Order";
  const ctaLink = cc?.cta_link || "/custom-order";
  const tagText = cc?.tag_text || "Turn Your Ideas Into Handmade Reality";
  const tagActive = cc?.tag_active !== false;
  const altText = cc?.alt_text || "Custom Crochet Banner";
  const isActive = cc?.is_active !== false;

  if (!isActive) return null;

  return (
    <section className="py-6 sm:py-8 bg-[#FFF9F6]">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="relative rounded-3xl bg-[#FCE9E5] border border-[#E7D1CC] p-8 sm:p-10 lg:p-12 overflow-hidden shadow-boutique">
          
          <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center relative z-10">
            
            {/* Left Content (Spans 7 cols on lg) */}
            <div className="lg:col-span-7 space-y-4 text-center lg:text-left flex flex-col items-center lg:items-start">
              
              <h2 className="font-serif-luxury text-3xl sm:text-4xl lg:text-5xl font-bold text-[#2E211E] tracking-tight leading-[1.15]">
                {title} <br />
                {subtitle && (
                  <span className="text-[#913638] italic font-serif font-normal">
                    {subtitle}
                  </span>
                )}
              </h2>

              <p className="text-xs sm:text-sm text-[#786864] max-w-md leading-relaxed whitespace-pre-line">
                {description}
              </p>

              {ctaText && ctaLink && (
                <div className="pt-2">
                  <Link
                    href={ctaLink}
                    className="inline-flex items-center gap-2 px-6 sm:px-7 py-3 rounded-full bg-[#913638] text-white text-xs sm:text-sm font-semibold hover:bg-[#74292B] shadow-xs hover:shadow-boutique-hover transition-all group active:scale-[0.98]"
                  >
                    <span>{ctaText}</span>
                    <ArrowRight className="w-4 h-4 group-hover:translate-x-1 transition-transform" />
                  </Link>
                </div>
              )}
            </div>

            {/* Right Visual (Spans 5 cols on lg) */}
            <div className="lg:col-span-5 relative flex justify-center">
              <div className="relative w-full max-w-xs sm:max-w-sm aspect-[4/3] rounded-2xl overflow-hidden border-2 border-white shadow-md bg-white">
                <picture>
                  {bannerImgMobile && (
                    <source media="(max-width: 640px)" srcSet={bannerImgMobile} />
                  )}
                  <Image
                    src={bannerImg}
                    alt={altText || `${title} - ${subtitle}`}
                    fill
                    sizes="(max-width: 1024px) 80vw, 35vw"
                    className="object-cover"
                  />
                </picture>

                {/* Hanging Tag */}
                {tagActive && tagText && (
                  <div className="absolute bottom-2 right-2 z-10">
                    <HandmadePaperTag text={tagText} />
                  </div>
                )}
              </div>
            </div>

          </div>
        </div>
      </div>
    </section>
  );
}
