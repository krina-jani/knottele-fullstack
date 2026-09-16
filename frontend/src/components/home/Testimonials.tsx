"use client";

import React, { useState, useEffect, useMemo } from "react";
import { StarRating } from "@/components/ui/StarRating";
import { useWebsiteMedia } from "@/context/MediaContext";

export interface TestimonialItem {
  id: string;
  name: string;
  designation: string;
  rating: number;
  review: string;
}

const DEFAULT_TESTIMONIALS: TestimonialItem[] = [
  {
    id: "t-1",
    name: "Priya Sharma",
    designation: "Bengaluru, India",
    rating: 5,
    review: "Absolutely in love with my crochet bouquet! The quality and detailing are breathtaking. It sits on my desk and brightens every single day.",
  },
  {
    id: "t-2",
    name: "Ananya Verma",
    designation: "Delhi NCR, India",
    rating: 5,
    review: "The bunny keychain is insanely cute! Perfect for gifting. Beautiful craftsmanship, soft milk cotton yarn, and a super sturdy gold clasp.",
  },
  {
    id: "t-3",
    name: "Riya Patel",
    designation: "Ahmedabad, India",
    rating: 5,
    review: "I ordered the vintage granny square tote and it turned out even more gorgeous in person. Sturdy straps and lovely artisan stitchwork!",
  },
  {
    id: "t-4",
    name: "Sneha Mukherjee",
    designation: "Kolkata, India",
    rating: 5,
    review: "The sunflower stem looks forever fresh! The wired petals hold shape beautifully and the warm color tone is so cheerful.",
  },
  {
    id: "t-5",
    name: "Kavita Desai",
    designation: "Mumbai, India",
    rating: 5,
    review: "My morning coffee feels so aesthetic with this tulip mug cozy! It protects my hands and feels velvety soft to hold.",
  },
  {
    id: "t-6",
    name: "Tanvi Joshi",
    designation: "Pune, India",
    rating: 5,
    review: "The daisy phone sleeve fits my phone snugly with soft velvet yarn protection. Loved the boutique packaging and handwritten note!",
  },
];

export function Testimonials() {
  const { media } = useWebsiteMedia();
  const [startIndex, setStartIndex] = useState<number>(0);
  const [isFading, setIsFading] = useState<boolean>(false);

  const testimonials = useMemo<TestimonialItem[]>(() => {
    if (media?.testimonials && Array.isArray(media.testimonials) && media.testimonials.length > 0) {
      return media.testimonials.map((t: any) => ({
        id: `t-db-${t.id}`,
        name: t.name,
        designation: t.designation || "Verified Patron",
        rating: Number(t.rating) || 5,
        review: t.message,
      }));
    }
    return DEFAULT_TESTIMONIALS;
  }, [media?.testimonials]);

  // Auto-advance reviews every 2.5 seconds with smooth crossfade
  useEffect(() => {
    if (testimonials.length <= 3) return;
    const timer = setInterval(() => {
      setIsFading(true);
      setTimeout(() => {
        setStartIndex((prev) => (prev + 1) % testimonials.length);
        setIsFading(false);
      }, 250);
    }, 2500);

    return () => clearInterval(timer);
  }, [testimonials.length]);

  // Circular slice of 3 visible cards
  const visibleTestimonials = useMemo(() => {
    if (testimonials.length <= 3) return testimonials;
    const items = [];
    for (let i = 0; i < 3; i++) {
      items.push(testimonials[(startIndex + i) % testimonials.length]);
    }
    return items;
  }, [testimonials, startIndex]);

  return (
    <section className="py-14 sm:py-18 bg-[#FFF9F6] relative overflow-hidden">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Header */}
        <div className="text-center max-w-xl mx-auto mb-10">
          <h2 className="font-serif-luxury text-2xl sm:text-3xl lg:text-4xl font-bold text-[#2E211E]">
            What Our Customers Say
          </h2>
          <p className="text-xs sm:text-sm text-[#786864] mt-1">
            Real people. Real stories. Real happiness.
          </p>
        </div>

        {/* Reviews Container with Auto-Rotation */}
        <div className="relative max-w-5xl mx-auto">
          {/* 3 Visible Review Cards */}
          <div
            className={`grid grid-cols-1 md:grid-cols-3 gap-5 sm:gap-6 transition-all duration-300 ease-in-out ${
              isFading ? "opacity-30 scale-[0.98]" : "opacity-100 scale-100"
            }`}
          >
            {visibleTestimonials.map((item, idx) => {
              const initialLetter = (item.name || "K").trim().charAt(0).toUpperCase();

              return (
                <div
                  key={`${item.id}-${startIndex}-${idx}`}
                  className="bg-white rounded-3xl border border-[#E7D1CC] p-6 shadow-boutique flex flex-col justify-between hover:shadow-md hover:border-[#EFB8B0] transition-all duration-200 group"
                >
                  <div>
                    {/* Monogram Badge, Name & Stars */}
                    <div className="flex items-center gap-3 mb-3.5">
                      <div className="w-10 h-10 rounded-2xl bg-gradient-to-br from-[#FCE9E5] to-[#F7D8D3] border border-[#E7D1CC] text-[#913638] flex items-center justify-center font-bold text-sm shadow-2xs shrink-0">
                        {initialLetter}
                      </div>
                      <div className="truncate flex-1">
                        <div className="flex items-center justify-between gap-1">
                          <h3 className="text-xs sm:text-sm font-bold text-[#2E211E] truncate">
                            {item.name}
                          </h3>
                        </div>
                        <div className="flex items-center gap-2 mt-0.5">
                          <StarRating rating={item.rating} size="sm" />
                          <span className="text-[10px] text-[#A89895] truncate">
                            {item.designation || "Verified Patron"}
                          </span>
                        </div>
                      </div>
                    </div>

                    {/* Review Text */}
                    <p className="text-xs text-[#786864] leading-relaxed mb-4 italic min-h-[56px]">
                      &ldquo;{item.review}&rdquo;
                    </p>
                  </div>

                  {/* Bottom Verified Badge Pill */}
                  <div className="pt-3 border-t border-[#E7D1CC]/60 flex items-center justify-between">
                    <span className="inline-flex items-center gap-1.5 text-[10px] font-bold text-[#913638] bg-[#FCE9E5]/60 px-2.5 py-1 rounded-full border border-[#E7D1CC]/60">
                      <svg className="w-3 h-3 text-[#913638]" fill="currentColor" viewBox="0 0 20 20">
                        <path fillRule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clipRule="evenodd" />
                      </svg>
                      Verified Patron
                    </span>
                    <span className="text-[10px] text-[#A89895] font-semibold truncate max-w-[120px]">
                      {item.designation || "Handmade Keepsake"}
                    </span>
                  </div>
                </div>
              );
            })}
          </div>

          {/* Carousel Progress Dots */}
          {testimonials.length > 3 && (
            <div className="flex justify-center items-center gap-2 mt-8">
              {testimonials.map((_, i) => (
                <button
                  key={i}
                  onClick={() => {
                    setIsFading(true);
                    setTimeout(() => {
                      setStartIndex(i);
                      setIsFading(false);
                    }, 200);
                  }}
                  className={`h-1.5 rounded-full transition-all duration-300 cursor-pointer ${
                    startIndex === i
                      ? "w-6 bg-[#913638]"
                      : "w-2 bg-[#E7D1CC] hover:bg-[#EFB8B0]"
                  }`}
                  aria-label={`Go to customer review ${i + 1}`}
                />
              ))}
            </div>
          )}
        </div>
      </div>
    </section>
  );
}
