"use client";

import React, { useState } from "react";
import Link from "next/link";
import {
  HelpCircle,
  ChevronDown,
  Search,
  Package,
  RotateCcw,
  Truck,
  Video,
  ShoppingBag,
  ArrowRight,
  MessageCircle,
  Sparkles,
} from "lucide-react";
import { Breadcrumbs } from "@/components/ui/Breadcrumbs";
import { SupportHeader } from "@/components/support/SupportHeader";
import { BotanicalFlourish, FlowerIcon } from "@/components/ui/BotanicalDecorations";

interface FaqItem {
  id: string;
  category: "orders" | "tracking" | "returns" | "shipping";
  question: string;
  answer: string;
  actionText?: string;
  actionHref?: string;
  highlight?: boolean;
}

const FAQ_DATA: FaqItem[] = [
  {
    id: "place-order",
    category: "orders",
    question: "How can I place an order?",
    answer:
      "Choose your product, add it to your cart, and complete checkout.",
    actionText: "Explore Collection",
    actionHref: "/shop",
  },
  {
    id: "track-order",
    category: "tracking",
    question: "How can I track my order?",
    answer:
      "Go to Track Order from your account to view your order status.",
    actionText: "Track My Order",
    actionHref: "/account/orders",
  },
  {
    id: "return-order",
    category: "returns",
    question: "Can I return my order?",
    answer:
      "Eligible products can be returned according to our Return & Refund Policy.",
    actionText: "Read Return Policy",
    actionHref: "/contact/return-refund",
  },
  {
    id: "unpacking-video",
    category: "returns",
    question: "Is an unpacking video required?",
    answer:
      "Yes. A clear unpacking video is mandatory for return or damage claims.",
    actionText: "View Video Guidelines",
    actionHref: "/contact/return-refund#unpacking-video",
    highlight: true,
  },
  {
    id: "wrong-product",
    category: "returns",
    question: "What if I receive the wrong product?",
    answer:
      "Contact us with your order details and the required unpacking video.",
    actionText: "Contact Studio",
    actionHref: "/contact",
  },
  {
    id: "change-address",
    category: "shipping",
    question: "Can I change my delivery address?",
    answer:
      "Contact us as soon as possible. Changes may not be possible after dispatch.",
    actionText: "Reach Out Immediately",
    actionHref: "/contact",
  },
];

export function FaqPageView() {
  const [openIds, setOpenIds] = useState<string[]>(["place-order", "track-order"]);
  const [activeCategory, setActiveCategory] = useState<string>("all");
  const [searchQuery, setSearchQuery] = useState("");

  const toggleAccordion = (id: string) => {
    setOpenIds((prev) =>
      prev.includes(id) ? prev.filter((item) => item !== id) : [...prev, id]
    );
  };

  const filteredFaqs = FAQ_DATA.filter((faq) => {
    const matchesCategory =
      activeCategory === "all" || faq.category === activeCategory;
    const matchesSearch =
      faq.question.toLowerCase().includes(searchQuery.toLowerCase()) ||
      faq.answer.toLowerCase().includes(searchQuery.toLowerCase());
    return matchesCategory && matchesSearch;
  });

  return (
    <div className="min-h-screen bg-[#FFFDFB] text-[#2E211E] font-sans antialiased pb-20">
      {/* Breadcrumbs */}
      <div className="max-w-6xl mx-auto px-4 sm:px-6 pt-6 pb-2">
        <Breadcrumbs
          items={[
            { label: "Home", href: "/" },
            { label: "Customer Care", href: "/contact" },
            { label: "FAQ", href: "/contact/faq" },
          ]}
        />
      </div>

      {/* Unified Support Header Tabs */}
      <div className="max-w-6xl mx-auto px-4 sm:px-6 mt-2">
        <SupportHeader activeTab="faq" />
      </div>

      {/* Page Heading & Intro */}
      <section className="max-w-4xl mx-auto px-4 sm:px-6 text-center mt-8 mb-10">
        <div className="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#FCE9E5] text-[#913638] text-xs font-semibold uppercase tracking-wider mb-3">
          <HelpCircle className="w-3.5 h-3.5" />
          <span>Help Center</span>
        </div>
        <h1 className="font-serif-luxury text-3xl sm:text-4xl md:text-5xl font-bold text-[#2E211E] tracking-tight">
          Frequently Asked Questions
        </h1>
        <p className="mt-3 text-sm sm:text-base text-[#786864] max-w-xl mx-auto leading-relaxed">
          Quick answers to common questions about KNOTELLE orders.
        </p>
        <BotanicalFlourish className="w-24 h-5 mx-auto text-[#913638]/40 mt-3" />
      </section>

      <main className="max-w-3xl mx-auto px-4 sm:px-6 space-y-8">
        
        {/* Search & Category Filter Controls */}
        <div className="space-y-4">
          <div className="relative">
            <Search className="w-4 h-4 text-[#786864] absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none" />
            <input
              type="text"
              value={searchQuery}
              onChange={(e) => setSearchQuery(e.target.value)}
              placeholder="Search frequently asked questions..."
              className="w-full pl-11 pr-4 py-3 rounded-full bg-white border border-[#E7D1CC] text-xs sm:text-sm text-[#2E211E] placeholder:text-[#786864]/60 focus:outline-none focus:ring-2 focus:ring-[#913638]/20 focus:border-[#913638] shadow-boutique transition-all"
            />
          </div>

          <div className="flex flex-wrap items-center justify-center gap-2">
            {[
              { key: "all", label: "All Questions" },
              { key: "orders", label: "Ordering" },
              { key: "tracking", label: "Tracking" },
              { key: "returns", label: "Returns & Video" },
              { key: "shipping", label: "Shipping & Address" },
            ].map((tab) => (
              <button
                key={tab.key}
                type="button"
                onClick={() => setActiveCategory(tab.key)}
                className={`px-3.5 py-1.5 rounded-full text-xs font-medium transition-all ${
                  activeCategory === tab.key
                    ? "bg-[#913638] text-white shadow-xs font-semibold"
                    : "bg-white text-[#786864] border border-[#E7D1CC] hover:text-[#2E211E] hover:border-[#EFB8B0]"
                }`}
              >
                {tab.label}
              </button>
            ))}
          </div>
        </div>

        {/* Accordion List */}
        <div className="space-y-3" role="region" aria-label="Frequently Asked Questions Accordion">
          {filteredFaqs.length === 0 ? (
            <div className="bg-white rounded-3xl border border-[#E7D1CC] p-10 text-center space-y-3">
              <p className="text-sm font-semibold text-[#2E211E]">No matching answers found</p>
              <p className="text-xs text-[#786864]">
                Try adjusting your search terms or reach out to our studio team directly.
              </p>
              <button
                type="button"
                onClick={() => {
                  setSearchQuery("");
                  setActiveCategory("all");
                }}
                className="text-xs text-[#913638] font-semibold underline"
              >
                Reset Search
              </button>
            </div>
          ) : (
            filteredFaqs.map((faq) => {
              const isOpen = openIds.includes(faq.id);
              return (
                <div
                  key={faq.id}
                  className={`group rounded-2xl sm:rounded-3xl border transition-all duration-200 overflow-hidden ${
                    isOpen
                      ? "bg-white border-[#EFB8B0] shadow-boutique"
                      : "bg-white/80 border-[#E7D1CC] hover:border-[#EFB8B0] hover:bg-white"
                  } ${faq.highlight ? "ring-1 ring-[#EFB8B0]" : ""}`}
                >
                  <button
                    type="button"
                    onClick={() => toggleAccordion(faq.id)}
                    aria-expanded={isOpen}
                    aria-controls={`faq-answer-${faq.id}`}
                    id={`faq-btn-${faq.id}`}
                    className="w-full text-left p-5 sm:p-6 flex items-center justify-between gap-4 cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-[#913638]"
                  >
                    <div className="flex items-center gap-3">
                      <span className="w-7 h-7 rounded-full bg-[#FCE9E5] text-[#913638] flex items-center justify-center shrink-0">
                        <FlowerIcon className="w-3.5 h-3.5 text-[#913638]" />
                      </span>
                      <h2 className="font-serif-luxury text-base sm:text-lg font-bold text-[#2E211E] group-hover:text-[#913638] transition-colors">
                        {faq.question}
                      </h2>
                    </div>

                    <div
                      className={`w-7 h-7 rounded-full flex items-center justify-center shrink-0 border transition-transform duration-200 ${
                        isOpen
                          ? "rotate-180 bg-[#913638] text-white border-[#913638]"
                          : "bg-white text-[#786864] border-[#E7D1CC]"
                      }`}
                    >
                      <ChevronDown className="w-4 h-4" />
                    </div>
                  </button>

                  {isOpen && (
                    <div
                      id={`faq-answer-${faq.id}`}
                      role="region"
                      aria-labelledby={`faq-btn-${faq.id}`}
                      className="px-5 sm:px-6 pb-6 pt-1 text-[#523D35] text-xs sm:text-sm leading-relaxed border-t border-[#E7D1CC]/50 space-y-3 animate-in fade-in duration-150"
                    >
                      <p>{faq.answer}</p>

                      {faq.highlight && (
                        <div className="p-3.5 rounded-xl bg-[#FFF5F3] border border-[#EFB8B0] text-xs text-[#913638] flex items-start gap-2">
                          <Video className="w-4 h-4 shrink-0 mt-0.5" />
                          <span>
                            <strong>Notice:</strong> Please record your package from the outside before opening it and continue recording until the product is completely visible.
                          </span>
                        </div>
                      )}

                      {faq.actionHref && faq.actionText && (
                        <div className="pt-1">
                          <Link
                            href={faq.actionHref}
                            className="inline-flex items-center gap-1.5 text-xs font-semibold text-[#913638] hover:underline"
                          >
                            <span>{faq.actionText}</span>
                            <ArrowRight className="w-3.5 h-3.5" />
                          </Link>
                        </div>
                      )}
                    </div>
                  )}
                </div>
              );
            })
          )}
        </div>

        {/* Studio Direct Help Banner */}
        <section className="rounded-3xl border border-[#E7D1CC] bg-[#FFF9F6] p-6 sm:p-8 text-center space-y-4 shadow-xs">
          <div className="w-10 h-10 rounded-full bg-[#FCE9E5] text-[#913638] flex items-center justify-center mx-auto">
            <MessageCircle className="w-5 h-5" />
          </div>
          <div>
            <h3 className="font-serif-luxury text-lg font-bold text-[#2E211E]">
              Still have a question?
            </h3>
            <p className="text-xs text-[#786864] mt-1 max-w-md mx-auto">
              Our artisan customer care team is here to assist you with order customizations, shipping updates, and care inquiries.
            </p>
          </div>

          <div className="pt-1">
            <Link
              href="/contact"
              className="px-6 py-2.5 rounded-full bg-[#913638] text-white text-xs font-semibold hover:bg-[#74292B] active:scale-[0.98] transition-all inline-flex items-center gap-2 shadow-sm"
            >
              <span>Contact Studio Team</span>
              <ArrowRight className="w-3.5 h-3.5" />
            </Link>
          </div>
        </section>

      </main>
    </div>
  );
}
