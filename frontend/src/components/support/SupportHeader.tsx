"use client";

import React from "react";
import Link from "next/link";
import { Truck, RotateCcw, HelpCircle, Package, MessageSquare } from "lucide-react";
import { BotanicalFlourish } from "@/components/ui/BotanicalDecorations";

export type SupportTab =
  | "shipping"
  | "return-refund"
  | "returns"
  | "faq"
  | "track-order"
  | "track"
  | "contact";

interface SupportHeaderProps {
  currentTab?: SupportTab | string;
  activeTab?: SupportTab | string;
  title?: string;
  subtitle?: string;
}

const SUPPORT_TABS = [
  {
    id: "shipping",
    label: "Shipping Policy",
    href: "/contact",
    icon: Truck,
  },
  {
    id: "return-refund",
    label: "Return & Refund",
    href: "/contact/return-refund",
    icon: RotateCcw,
  },
  {
    id: "faq",
    label: "FAQs",
    href: "/contact/faq",
    icon: HelpCircle,
  },
  {
    id: "track-order",
    label: "Track Order",
    href: "/account/orders",
    icon: Package,
  },
  {
    id: "contact",
    label: "Contact Studio",
    href: "/contact#contact-studio",
    icon: MessageSquare,
  },
];

export function SupportHeader({
  currentTab,
  activeTab,
  title,
  subtitle,
}: SupportHeaderProps) {
  const tabToUse = activeTab || currentTab || "shipping";
  const normalizedActive =
    tabToUse === "returns"
      ? "return-refund"
      : tabToUse === "track"
      ? "track-order"
      : tabToUse;
  return (
    <div className="w-full text-center space-y-4 mb-8 sm:mb-12">
      {/* Top Tagline / Category */}
      <div className="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#FCE9E5] border border-[#E7D1CC] text-[#913638] text-[11px] font-bold tracking-widest uppercase">
        <BotanicalFlourish className="w-3.5 h-3.5 text-[#913638]" />
        <span>KNOTELLE Customer Care</span>
        <BotanicalFlourish className="w-3.5 h-3.5 text-[#913638] transform scale-x-[-1]" />
      </div>

      {/* Main Page Title */}
      {title && (
        <h1 className="font-serif-luxury text-3xl sm:text-4xl md:text-5xl font-bold text-[#2E211E] tracking-tight">
          {title}
        </h1>
      )}

      {/* Subtitle / Intro */}
      {subtitle && (
        <p className="text-xs sm:text-sm text-[#786864] max-w-xl mx-auto leading-relaxed">
          {subtitle}
        </p>
      )}

      {/* Navigation Tabs Bar */}
      <div className="pt-4 flex justify-center">
        <div className="w-full max-w-2xl overflow-x-auto no-scrollbar py-1 px-2">
          <nav
            aria-label="Support Navigation"
            className="flex items-center justify-start sm:justify-center gap-1.5 sm:gap-2 p-1.5 bg-white/80 backdrop-blur-md rounded-2xl border border-[#E7D1CC] shadow-xs min-w-max mx-auto"
          >
            {SUPPORT_TABS.map((tab) => {
              const Icon = tab.icon;
              const isActive = normalizedActive === tab.id;

              return (
                <Link
                  key={tab.id}
                  href={tab.href}
                  className={`inline-flex items-center gap-2 px-3.5 sm:px-4 py-2 rounded-xl text-xs font-semibold transition-all select-none ${
                    isActive
                      ? "bg-[#913638] text-white shadow-xs font-bold"
                      : "text-[#5C4D49] hover:text-[#913638] hover:bg-[#FFF9F6]"
                  }`}
                >
                  <Icon className={`w-3.5 h-3.5 ${isActive ? "text-white" : "text-[#913638]"}`} />
                  <span>{tab.label}</span>
                </Link>
              );
            })}
          </nav>
        </div>
      </div>
    </div>
  );
}
