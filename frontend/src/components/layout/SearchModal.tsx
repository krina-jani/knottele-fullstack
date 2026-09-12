"use client";

import React, { useState, useEffect, useRef } from "react";
import Image from "next/image";
import Link from "next/link";
import { useRouter } from "next/navigation";
import { Search, X, ArrowRight, Sparkles } from "lucide-react";
import { PRODUCTS } from "@/data/products";
import { CATEGORIES } from "@/data/categories";

interface SearchModalProps {
  isOpen: boolean;
  onClose: () => void;
}

export function SearchModal({ isOpen, onClose }: SearchModalProps) {
  const [query, setQuery] = useState("");
  const router = useRouter();
  const inputRef = useRef<HTMLInputElement>(null);

  useEffect(() => {
    if (isOpen) {
      setTimeout(() => inputRef.current?.focus(), 100);
      document.body.style.overflow = "hidden";
    } else {
      document.body.style.overflow = "unset";
    }
    return () => {
      document.body.style.overflow = "unset";
    };
  }, [isOpen]);

  if (!isOpen) return null;

  const filteredProducts = query.trim()
    ? PRODUCTS.filter(
        (p) =>
          p.name.toLowerCase().includes(query.toLowerCase()) ||
          p.category.toLowerCase().includes(query.toLowerCase()) ||
          p.tags.some((t) => t.toLowerCase().includes(query.toLowerCase())) ||
          p.description.toLowerCase().includes(query.toLowerCase())
      ).slice(0, 5)
    : [];

  const filteredCategories = query.trim()
    ? CATEGORIES.filter((c) =>
        c.name.toLowerCase().includes(query.toLowerCase())
      ).slice(0, 3)
    : [];

  const handleSearchSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (query.trim()) {
      router.push(`/search?q=${encodeURIComponent(query.trim())}`);
      onClose();
    }
  };

  const trendingTags = ["Rose Bouquet", "Cute Bunny", "Granny Square Bag", "Sunflower", "Phone Cover", "Bookmark"];

  return (
    <div className="fixed inset-0 z-50 flex items-start justify-center pt-16 sm:pt-24 px-4 bg-black/40 backdrop-blur-sm animate-in fade-in duration-200">
      <div
        className="w-full max-w-2xl bg-white rounded-3xl border border-[#E8D4CF] shadow-2xl overflow-hidden animate-in zoom-in-95 duration-200"
        onClick={(e) => e.stopPropagation()}
      >
        {/* Search Header */}
        <form
          onSubmit={handleSearchSubmit}
          className="relative flex items-center px-6 py-4 border-b border-[#E8D4CF]"
        >
          <Search className="w-5 h-5 text-[#8F3032] mr-3 shrink-0" />
          <input
            ref={inputRef}
            type="text"
            value={query}
            onChange={(e) => setQuery(e.target.value)}
            placeholder="Search crochet flowers, bags, plushies, keychains..."
            className="w-full text-base sm:text-lg text-[#3A211D] placeholder-[#78635E]/60 bg-transparent focus:outline-none"
          />
          {query && (
            <button
              type="button"
              onClick={() => setQuery("")}
              className="text-[#78635E] hover:text-[#3A211D] p-1.5 mr-2"
            >
              <X className="w-4 h-4" />
            </button>
          )}
          <button
            type="button"
            onClick={onClose}
            className="text-xs font-semibold text-[#8F3032] hover:bg-[#FDE9E5] px-3 py-1.5 rounded-full transition-colors"
          >
            ESC
          </button>
        </form>

        {/* Search Body */}
        <div className="p-6 max-h-[65vh] overflow-y-auto">
          {query.trim() ? (
            <div className="space-y-6">
              {/* Category Matches */}
              {filteredCategories.length > 0 && (
                <div>
                  <h4 className="text-xs uppercase font-semibold text-[#78635E] tracking-wider mb-2.5">
                    Categories
                  </h4>
                  <div className="flex flex-wrap gap-2">
                    {filteredCategories.map((cat) => (
                      <Link
                        key={cat.id}
                        href={`/category/${cat.slug}`}
                        onClick={onClose}
                        className="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#FFF8F5] border border-[#E8D4CF] text-xs font-medium text-[#3A211D] hover:bg-[#FDE9E5] hover:border-[#8F3032] transition-colors"
                      >
                        <span>{cat.name}</span>
                        <ArrowRight className="w-3 h-3 text-[#8F3032]" />
                      </Link>
                    ))}
                  </div>
                </div>
              )}

              {/* Product Matches */}
              {filteredProducts.length > 0 ? (
                <div>
                  <h4 className="text-xs uppercase font-semibold text-[#78635E] tracking-wider mb-3">
                    Matching Products ({filteredProducts.length})
                  </h4>
                  <div className="divide-y divide-[#E8D4CF]/60">
                    {filteredProducts.map((product) => (
                      <Link
                        key={product.id}
                        href={`/product/${product.slug}`}
                        onClick={onClose}
                        className="flex items-center gap-4 py-3 group hover:bg-[#FFF8F5] -mx-3 px-3 rounded-2xl transition-colors"
                      >
                        <div className="relative w-14 h-14 rounded-xl overflow-hidden bg-[#FFF8F5] shrink-0 border border-[#E8D4CF]">
                          <Image
                            src={product.images[0]}
                            alt={product.name}
                            fill
                            sizes="56px"
                            className="object-cover group-hover:scale-105 transition-transform"
                          />
                        </div>
                        <div className="flex-1 min-w-0">
                          <p className="text-sm font-semibold text-[#3A211D] group-hover:text-[#8F3032] transition-colors truncate">
                            {product.name}
                          </p>
                          <p className="text-xs text-[#78635E] mt-0.5">
                            {product.category}
                          </p>
                        </div>
                        <div className="text-right">
                          <p className="text-sm font-bold text-[#8F3032]">
                            ₹{product.price.toLocaleString("en-IN")}
                          </p>
                          <span className="text-[11px] text-[#78635E] group-hover:text-[#8F3032] transition-colors flex items-center justify-end gap-1">
                            View <ArrowRight className="w-3 h-3" />
                          </span>
                        </div>
                      </Link>
                    ))}
                  </div>

                  <div className="mt-4 pt-3 border-t border-[#E8D4CF] text-center">
                    <button
                      onClick={handleSearchSubmit}
                      className="text-xs font-semibold text-[#8F3032] hover:underline inline-flex items-center gap-1"
                    >
                      <span>View all results for &quot;{query}&quot;</span>
                      <ArrowRight className="w-3.5 h-3.5" />
                    </button>
                  </div>
                </div>
              ) : (
                <div className="py-8 text-center">
                  <p className="text-sm text-[#3A211D] font-medium">
                    No little stitch found for &quot;{query}&quot;
                  </p>
                  <p className="text-xs text-[#78635E] mt-1">
                    Try searching for &quot;bouquet&quot;, &quot;bunny&quot;, or request a custom order!
                  </p>
                  <Link
                    href="/custom-order"
                    onClick={onClose}
                    className="inline-flex items-center gap-1.5 mt-4 px-4 py-2 rounded-full bg-[#FDE9E5] text-[#8F3032] text-xs font-semibold hover:bg-[#F4C7C1] transition-colors"
                  >
                    <Sparkles className="w-3.5 h-3.5" />
                    <span>Create Custom Order</span>
                  </Link>
                </div>
              )}
            </div>
          ) : (
            <div>
              <h4 className="text-xs uppercase font-semibold text-[#78635E] tracking-wider mb-3">
                Trending Searches
              </h4>
              <div className="flex flex-wrap gap-2 mb-6">
                {trendingTags.map((tag) => (
                  <button
                    key={tag}
                    onClick={() => {
                      setQuery(tag);
                    }}
                    className="px-3.5 py-1.5 rounded-full bg-[#FFF8F5] border border-[#E8D4CF] text-xs font-medium text-[#3A211D] hover:bg-[#FDE9E5] hover:border-[#8F3032] transition-colors"
                  >
                    {tag}
                  </button>
                ))}
              </div>

              <div className="p-4 rounded-2xl bg-[#FFF8F5] border border-[#E8D4CF] flex items-center justify-between">
                <div>
                  <p className="text-xs font-semibold text-[#3A211D]">
                    Can&apos;t find what you&apos;re dreaming of?
                  </p>
                  <p className="text-[11px] text-[#78635E]">
                    We specialize in custom handmade creations tailored to you.
                  </p>
                </div>
                <Link
                  href="/custom-order"
                  onClick={onClose}
                  className="px-3.5 py-1.5 rounded-full bg-[#8F3032] text-white text-xs font-medium hover:bg-[#722628] transition-colors shrink-0 ml-3"
                >
                  Custom Order →
                </Link>
              </div>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
