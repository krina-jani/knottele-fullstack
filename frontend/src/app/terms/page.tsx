"use client";

import React from "react";
import Link from "next/link";
import { ArrowLeft } from "lucide-react";

export default function TermsPage() {
  return (
    <div className="bg-[#FFF9F6] min-h-screen py-12 px-4 sm:px-6 lg:px-8">
      <div className="max-w-3xl mx-auto bg-white rounded-3xl border border-[#E7D1CC] p-6 sm:p-10 shadow-boutique space-y-6">
        <Link href="/account" className="inline-flex items-center gap-2 text-xs font-semibold text-[#913638] hover:underline">
          <ArrowLeft className="w-4 h-4" />
          <span>Back to Account</span>
        </Link>
        <h1 className="font-serif-luxury text-3xl font-bold text-[#2E211E]">Terms & Conditions</h1>
        <p className="text-xs text-[#786864] leading-relaxed">
          Welcome to KNOTELLE. By accessing or using our handmade boutique services, ordering custom crochet products, or creating an account, you agree to comply with and be bound by these Terms & Conditions.
        </p>
        <div className="space-y-4 text-xs text-[#2E211E]">
          <h2 className="font-bold text-sm">1. Handmade Product Craftsmanship</h2>
          <p className="text-[#786864]">Each KNOTELLE creation is individually hand-stitched by skilled artisans using premium yarns. Minor variations in shade or stitch density reflect authentic craftsmanship.</p>
          <h2 className="font-bold text-sm">2. Orders & Custom Creations</h2>
          <p className="text-[#786864]">Custom crochet orders are crafted based on user specifications and require artisan preparation before shipment.</p>
        </div>
      </div>
    </div>
  );
}
