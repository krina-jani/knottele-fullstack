"use client";

import React, { useState, useEffect, useMemo } from "react";
import Link from "next/link";
import { ArrowRight } from "lucide-react";
import { Product } from "@/types/product";
import { fetchProducts } from "@/lib/api";
import { ProductCard } from "@/components/ui/ProductCard";
import { useWebsiteMedia } from "@/context/MediaContext";
import { getLocalCache, setLocalCache, setupAdminSyncListener } from "@/lib/cache";

const PRODUCTS_CACHE_KEY = "knotelle_cache_products";

export function BestSellers() {
  const { media } = useWebsiteMedia();
  const [productsList, setProductsList] = useState<Product[]>([]);
  const [activeTab, setActiveTab] = useState<string>("all");
  const [startIndex, setStartIndex] = useState<number>(0);
  const [isFading, setIsFading] = useState<boolean>(false);

  useEffect(() => {
    // Safely populate from local cache after mount to prevent SSR hydration mismatch
    const cached = getLocalCache<Product[]>(PRODUCTS_CACHE_KEY, []);
    if (cached && cached.length > 0) {
      setProductsList(cached);
    }

    let isMounted = true;
    const loadProducts = () => {
      fetchProducts({ per_page: 100 })
        .then((res) => {
          if (isMounted && res.products && res.products.length > 0) {
            setProductsList(res.products);
            setLocalCache(PRODUCTS_CACHE_KEY, res.products);
          }
        })
        .catch((err) => console.warn("Live bestsellers fetch notice:", err));
    };

    loadProducts();
    const cleanupListeners = setupAdminSyncListener(loadProducts);

    return () => {
      isMounted = false;
      cleanupListeners();
    };
  }, []);

  const bestSellers = useMemo(() => {
    const list = productsList.filter((p) => p.isBestSeller || p.isFeatured);
    return list.length > 0 ? list : productsList;
  }, [productsList]);

  const categories = useMemo(() => {
    const defaultTabs = [{ id: "all", label: "All" }];
    if (media?.categories && media.categories.length > 0) {
      const dbTabs = media.categories.slice(0, 5).map((c) => ({
        id: c.slug,
        label: c.name,
      }));
      return [...defaultTabs, ...dbTabs];
    }
    return defaultTabs;
  }, [media?.categories]);

  const filteredProducts = useMemo(() => {
    if (activeTab === "all") return bestSellers;
    return bestSellers.filter(
      (p) => p.categorySlug === activeTab || p.category.toLowerCase().includes(activeTab.toLowerCase())
    );
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
