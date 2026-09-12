"use client";

import React, { useState, useEffect, useMemo } from "react";
import Link from "next/link";
import { ArrowRight } from "lucide-react";
import { PRODUCTS } from "@/data/products";
import { ProductCard } from "@/components/ui/ProductCard";

export function BestSellers() {
  const [activeTab, setActiveTab] = useState<string>("all");
  const [startIndex, setStartIndex] = useState<number>(0);
  const [isFading, setIsFading] = useState<boolean>(false);

  const bestSellers = useMemo(() => {
    return PRODUCTS.filter((p) => p.isBestSeller || p.isFeatured);
  }, []);

  const categories = [
    { id: "all", label: "All" },
    { id: "keychain", label: "Keychains" },
    { id: "flower", label: "Flowers" },
    { id: "soft-toys", label: "Soft Toys" },
    { id: "bags", label: "Bags" },
  ];

  const filteredProducts = useMemo(() => {
    if (activeTab === "all") return bestSellers;
    if (activeTab === "flower") {
      return bestSellers.filter((p) => p.categorySlug === "flower" || p.categorySlug === "bouquet");
    }
    if (activeTab === "bags") {
      return bestSellers.filter((p) => p.categorySlug === "bags" || p.categorySlug === "coin-purse");
    }
    return bestSellers.filter((p) => p.categorySlug === activeTab);
  }, [activeTab, bestSellers]);

  // Auto-advance products every 2 seconds (2000ms) with smooth transition
  useEffect(() => {
    if (filteredProducts.length <= 6) return;

    const timer = setInterval(() => {
      setIsFading(true);
      setTimeout(() => {
        setStartIndex((prev) => (prev + 1) % filteredProducts.length);
        setIsFading(false);
      }, 250);
    }, 2000);

    return () => clearInterval(timer);
  }, [filteredProducts.length]);

  const handleTabChange = (tabId: string) => {
    setActiveTab(tabId);
    setStartIndex(0);
  };

  // Circular slice of 6 products
  const visibleProducts = useMemo(() => {
    if (filteredProducts.length <= 6) return filteredProducts;
    const items = [];
    for (let i = 0; i < 6; i++) {
      items.push(filteredProducts[(startIndex + i) % filteredProducts.length]);
    }
    return items;
  }, [filteredProducts, startIndex]);

  return (
    <section className="py-12 sm:py-16 bg-[#FFF9F6] relative">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {/* Header & Tabs */}
        <div className="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
          <h2 className="font-serif-luxury text-2xl sm:text-3xl font-bold text-[#2E211E]">
            Best Sellers
          </h2>

          <div className="flex items-center gap-1.5 sm:gap-2 overflow-x-auto pb-1 no-scrollbar">
            {categories.map((cat) => (
              <button
                key={cat.id}
                onClick={() => handleTabChange(cat.id)}
                className={`px-3.5 sm:px-4 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-all cursor-pointer ${
                  activeTab === cat.id
                    ? "bg-[#913638] text-white shadow-xs"
                    : "bg-white text-[#2E211E] border border-[#E7D1CC] hover:bg-[#FCE9E5] hover:text-[#913638]"
                }`}
              >
                {cat.label}
              </button>
            ))}

            <Link
              href="/shop"
              className="text-xs font-semibold text-[#786864] hover:text-[#913638] flex items-center gap-1 pl-2 whitespace-nowrap"
            >
              <span>View All</span>
              <ArrowRight className="w-3 h-3" />
            </Link>
          </div>
        </div>

        {/* 6 Products Grid with Smooth 2-Second Rotation (Arrows Removed) */}
        <div className="relative">
          <div
            className={`grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4 transition-all duration-300 ease-in-out ${
              isFading ? "opacity-40 scale-[0.99]" : "opacity-100 scale-100"
            }`}
          >
            {visibleProducts.map((product, idx) => (
              <ProductCard key={`${product.id}-${startIndex}-${idx}`} product={product} priority={idx < 4} />
            ))}
          </div>

          {/* Carousel Progress Dots when more than 6 products */}
          {filteredProducts.length > 6 && (
            <div className="flex justify-center items-center gap-1.5 mt-6">
              {filteredProducts.map((_, i) => (
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
                      ? "w-5 bg-[#913638]"
                      : "w-1.5 bg-[#E7D1CC] hover:bg-[#EFB8B0]"
                  }`}
                  aria-label={`Go to product group ${i + 1}`}
                />
              ))}
            </div>
          )}
        </div>

      </div>
    </section>
  );
}
