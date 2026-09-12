"use client";

import React, { useState, useEffect, useMemo } from "react";
import Image from "next/image";
import { StarRating } from "@/components/ui/StarRating";

export interface TestimonialItem {
  id: string;
  name: string;
  avatar: string;
  rating: number;
  review: string;
  productName: string;
  productImage: string;
}

export function Testimonials() {
  const [startIndex, setStartIndex] = useState<number>(0);
  const [isFading, setIsFading] = useState<boolean>(false);

  const testimonials: TestimonialItem[] = useMemo(
    () => [
      {
        id: "t-1",
        name: "Priya Sharma",
        avatar: "https://images.unsplash.com/photo-1494790108377-be9c29b29330?q=80&w=200&auto=format&fit=crop",
        rating: 5,
        review: "Absolutely in love with my crochet bouquet! The quality and detailing are breathtaking. It sits on my desk and brightens every single day.",
        productName: "Bespoke Rose Bouquet",
        productImage: "/knotelle/images/products/rose-bouquet.jpg",
      },
      {
        id: "t-2",
        name: "Ananya Verma",
        avatar: "https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200&auto=format&fit=crop",
        rating: 5,
        review: "The bunny keychain is insanely cute! Perfect for gifting. Beautiful craftsmanship, soft milk cotton yarn, and a super sturdy gold clasp.",
        productName: "Cute Bunny Keychain",
        productImage: "/knotelle/images/products/bunny-keychain.jpg",
      },
      {
        id: "t-3",
        name: "Riya Patel",
        avatar: "https://images.unsplash.com/photo-1517841905240-472988babdf9?q=80&w=200&auto=format&fit=crop",
        rating: 5,
        review: "I ordered the vintage granny square tote and it turned out even more gorgeous in person. Sturdy straps and lovely artisan stitchwork!",
        productName: "Granny Square Bag",
        productImage: "/knotelle/images/products/granny-square-bag.jpg",
      },
      {
        id: "t-4",
        name: "Sneha Mukherjee",
        avatar: "https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=200&auto=format&fit=crop",
        rating: 5,
        review: "The sunflower stem looks forever fresh! The wired petals hold shape beautifully and the warm color tone is so cheerful.",
        productName: "Everlasting Sunflower",
        productImage: "/knotelle/images/products/sunflower-stem.jpg",
      },
      {
        id: "t-5",
        name: "Kavita Desai",
        avatar: "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=200&auto=format&fit=crop",
        rating: 5,
        review: "My morning coffee feels so aesthetic with this tulip mug cozy! It protects my hands and feels velvety soft to hold.",
        productName: "Tulip Blossom Mug Cozy",
        productImage: "/knotelle/images/products/tulip-mug-cozy.jpg",
      },
      {
        id: "t-6",
        name: "Tanvi Joshi",
        avatar: "https://images.unsplash.com/photo-1524504388940-b1c1722653e1?q=80&w=200&auto=format&fit=crop",
        rating: 5,
        review: "The daisy phone sleeve fits my phone snugly with soft velvet yarn protection. Loved the boutique packaging and handwritten note!",
        productName: "Daisy Phone Sleeve",
        productImage: "/knotelle/images/products/daisy-phone-cover.jpg",
      },
    ],
    []
  );

  // Auto-advance reviews every 2 seconds (2000ms) with smooth crossfade
  useEffect(() => {
    const timer = setInterval(() => {
      setIsFading(true);
      setTimeout(() => {
        setStartIndex((prev) => (prev + 1) % testimonials.length);
        setIsFading(false);
      }, 250);
    }, 2000);

    return () => clearInterval(timer);
  }, [testimonials.length]);

  // Circular slice of 3 visible cards
  const visibleTestimonials = useMemo(() => {
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

        {/* 6 Reviews Container with 2-second Auto-Rotation (Arrows Removed) */}
        <div className="relative max-w-5xl mx-auto">
          {/* 3 Visible Review Cards */}
          <div
            className={`grid grid-cols-1 md:grid-cols-3 gap-5 sm:gap-6 transition-all duration-300 ease-in-out ${
              isFading ? "opacity-30 scale-[0.98]" : "opacity-100 scale-100"
            }`}
          >
            {visibleTestimonials.map((item, idx) => (
              <div
                key={`${item.id}-${startIndex}-${idx}`}
                className="bg-white rounded-3xl border border-[#E7D1CC] p-6 shadow-boutique flex flex-col justify-between hover:shadow-md hover:border-[#EFB8B0] transition-all duration-200"
              >
                <div>
                  {/* Avatar, Name & Stars */}
                  <div className="flex items-center gap-3 mb-3">
                    <div className="relative w-10 h-10 rounded-full overflow-hidden border border-[#E7D1CC] shrink-0">
                      <Image
                        src={item.avatar}
                        alt={item.name}
                        fill
                        sizes="40px"
                        className="object-cover"
                      />
                    </div>
                    <div>
                      <h3 className="text-xs font-bold text-[#2E211E]">
                        {item.name}
                      </h3>
                      <StarRating rating={item.rating} size="sm" />
                    </div>
                  </div>

                  {/* Review Text */}
                  <p className="text-xs text-[#786864] leading-relaxed mb-4 italic min-h-[56px]">
                    &ldquo;{item.review}&rdquo;
                  </p>
                </div>

                {/* Bottom Product Info */}
                <div className="pt-3 border-t border-[#E7D1CC]/60 flex items-center gap-2.5">
                  <div className="relative w-8 h-8 rounded-lg overflow-hidden bg-[#FCE9E5] border border-[#E7D1CC] shrink-0">
                    <Image
                      src={item.productImage}
                      alt={item.productName}
                      fill
                      sizes="32px"
                      className="object-cover"
                    />
                  </div>
                  <span className="text-[11px] font-medium text-[#2E211E] truncate">
                    {item.productName}
                  </span>
                </div>
              </div>
            ))}
          </div>

          {/* Carousel Progress Dots */}
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
        </div>
      </div>
    </section>
  );
}
