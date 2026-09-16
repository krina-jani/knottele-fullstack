"use client";

import React from "react";
import Link from "next/link";
import { ArrowLeft } from "lucide-react";

export default function PrivacyPage() {
  return (
    <div className="bg-[#FFF9F6] min-h-screen py-12 px-4 sm:px-6 lg:px-8">
      <div className="max-w-3xl mx-auto bg-white rounded-3xl border border-[#E7D1CC] p-6 sm:p-10 shadow-boutique space-y-6">
        <Link href="/account" className="inline-flex items-center gap-2 text-xs font-semibold text-[#913638] hover:underline">
          <ArrowLeft className="w-4 h-4" />
          <span>Back to Account</span>
        </Link>
        <h1 className="font-serif-luxury text-3xl font-bold text-[#2E211E]">Privacy Policy</h1>
        <p className="text-xs text-[#786864] leading-relaxed">
          Your privacy is sacred to KNOTELLE. We process your personal details strictly to fulfill your orders, provide customer support, and personalize your boutique experience.
        </p>
        <div className="space-y-4 text-xs text-[#2E211E]">
          <h2 className="font-bold text-sm">1. Data Collection & Security</h2>
          <p className="text-[#786864]">We protect your email address, delivery locations, and account details using encryption and secure storage protocols.</p>
          <h2 className="font-bold text-sm">2. Data Usage</h2>
          <p className="text-[#786864]">Your data is never sold to third parties and is used exclusively for boutique notifications, order tracking, and account management.</p>
        </div>
      </div>
    </div>
  );
}
