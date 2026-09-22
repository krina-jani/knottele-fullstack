"use client";

import React from "react";
import Link from "next/link";
import {
  RotateCcw,
  Video,
  AlertCircle,
  CheckCircle2,
  HelpCircle,
  ArrowRight,
  Sparkles,
  ShieldCheck,
  Clock,
  Camera,
  Mail,
  Phone,
  MessageCircle,
} from "lucide-react";
import { Breadcrumbs } from "@/components/ui/Breadcrumbs";
import { SupportHeader } from "@/components/support/SupportHeader";
import { BotanicalFlourish, FlowerIcon } from "@/components/ui/BotanicalDecorations";

export function ReturnRefundView() {
  return (
    <div className="min-h-screen bg-[#FFFDFB] text-[#2E211E] font-sans antialiased pb-20">
      {/* Top Breadcrumbs */}
      <div className="max-w-6xl mx-auto px-4 sm:px-6 pt-6 pb-2">
        <Breadcrumbs
          items={[
            { label: "Home", href: "/" },
            { label: "Customer Care", href: "/contact" },
            { label: "Return & Refund", href: "/contact/return-refund" },
          ]}
        />
      </div>

      {/* Unified Support Header Tabs */}
      <div className="max-w-6xl mx-auto px-4 sm:px-6 mt-2">
        <SupportHeader activeTab="returns" />
      </div>

      {/* Hero Intro */}
      <section className="max-w-4xl mx-auto px-4 sm:px-6 text-center mt-8 mb-12">
        <div className="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#FCE9E5] text-[#913638] text-xs font-semibold uppercase tracking-wider mb-3">
          <RotateCcw className="w-3.5 h-3.5" />
          <span>Care & Integrity</span>
        </div>
        <h1 className="font-serif-luxury text-3xl sm:text-4xl md:text-5xl font-bold text-[#2E211E] tracking-tight">
          Return & Refund Policy
        </h1>
        <p className="mt-3 text-sm sm:text-base text-[#786864] max-w-xl mx-auto leading-relaxed">
          We want you to love your KNOTELLE order. Here&apos;s everything you need to know about returns.
        </p>
        <BotanicalFlourish className="w-24 h-5 mx-auto text-[#913638]/40 mt-3" />
      </section>

      <main className="max-w-4xl mx-auto px-4 sm:px-6 space-y-10">
        
        {/* ========================================================= */}
        {/* 1. VISUALLY HIGHLIGHTED UNPACKING VIDEO REQUIREMENT       */}
        {/* ========================================================= */}
        <section
          id="unpacking-video"
          aria-labelledby="unpacking-heading"
          className="relative overflow-hidden rounded-3xl border-2 border-[#EFB8B0] bg-[#FFF5F3] p-6 sm:p-8 md:p-10 shadow-boutique transition-all"
        >
          {/* Subtle Decorative Background Accent */}
          <div className="absolute -right-8 -top-8 w-40 h-40 rounded-full bg-[#EFB8B0]/20 blur-2xl pointer-events-none" />
          <div className="absolute -left-8 -bottom-8 w-40 h-40 rounded-full bg-[#C69A5A]/10 blur-2xl pointer-events-none" />

          <div className="relative z-10 space-y-6">
            <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
              <div className="flex items-center gap-3">
                <div className="w-12 h-12 rounded-2xl bg-[#913638] text-white flex items-center justify-center shrink-0 shadow-md">
                  <Video className="w-6 h-6 animate-pulse" />
                </div>
                <div>
                  <div className="inline-flex items-center gap-1 text-[11px] font-bold text-[#913638] uppercase tracking-wider bg-white/80 px-2.5 py-0.5 rounded-full border border-[#EFB8B0]">
                    <AlertCircle className="w-3 h-3 text-[#913638]" />
                    <span>Mandatory Claim Requirement</span>
                  </div>
                  <h2
                    id="unpacking-heading"
                    className="font-serif-luxury text-xl sm:text-2xl font-bold text-[#2E211E] mt-1"
                  >
                    Unpacking Video — IMPORTANT
                  </h2>
                </div>
              </div>

              <span className="hidden sm:inline-flex text-xs font-semibold px-3 py-1 rounded-full bg-white text-[#913638] border border-[#EFB8B0] shadow-xs">
                Zero Cuts &bull; Single Take
              </span>
            </div>

            {/* Core Mandatory Requirement Text */}
            <div className="bg-white/90 backdrop-blur-sm rounded-2xl p-5 sm:p-6 border border-[#EFB8B0] space-y-3">
              <p className="text-base sm:text-lg font-bold text-[#913638] leading-snug">
                &ldquo;A clear, continuous unpacking video is mandatory for every return or damage claim.&rdquo;
              </p>
              
              {/* Highlighted Instruction Callout Box */}
              <div className="p-4 rounded-xl bg-[#FFF9F6] border-l-4 border-[#913638] text-xs sm:text-sm text-[#2E211E] font-medium leading-relaxed">
                <strong>Please record your package from the outside before opening it and continue recording until the product is completely visible.</strong>
              </div>
            </div>

            {/* 3 Simple Visual Checkpoints */}
            <div className="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-1">
              <div className="bg-white/80 rounded-2xl p-3.5 border border-[#EFB8B0]/60 flex items-start gap-2.5">
                <div className="w-6 h-6 rounded-full bg-[#FCE9E5] text-[#913638] text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">
                  1
                </div>
                <p className="text-xs text-[#523D35] leading-relaxed">
                  <strong>Show All Sides:</strong> Capture outer label, seal, and sealed packaging prior to unboxing.
                </p>
              </div>

              <div className="bg-white/80 rounded-2xl p-3.5 border border-[#EFB8B0]/60 flex items-start gap-2.5">
                <div className="w-6 h-6 rounded-full bg-[#FCE9E5] text-[#913638] text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">
                  2
                </div>
                <p className="text-xs text-[#523D35] leading-relaxed">
                  <strong>Continuous Cut:</strong> No pauses, edits, transitions, or camera obstruction while slicing open.
                </p>
              </div>

              <div className="bg-white/80 rounded-2xl p-3.5 border border-[#EFB8B0]/60 flex items-start gap-2.5">
                <div className="w-6 h-6 rounded-full bg-[#FCE9E5] text-[#913638] text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">
                  3
                </div>
                <p className="text-xs text-[#523D35] leading-relaxed">
                  <strong>Inspect Product:</strong> Unveil the crochet item fully to clearly show the claimed concern.
                </p>
              </div>
            </div>

            <div className="flex items-center gap-2 text-xs text-[#786864] pt-1">
              <Camera className="w-4 h-4 text-[#913638]" />
              <span>Smart tip: Keep the phone mounted or prop it up against a mug while opening the box.</span>
            </div>
          </div>
        </section>


        {/* ========================================================= */}
        {/* 2. POLICY SECTIONS: ELIGIBILITY, DAMAGED, REFUND, TERMS   */}
        {/* ========================================================= */}
        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">

          {/* Section: Return Eligibility */}
          <div className="bg-white rounded-3xl border border-[#E7D1CC] p-6 sm:p-7 shadow-boutique space-y-4">
            <div className="flex items-center gap-3">
              <div className="w-10 h-10 rounded-2xl bg-[#FCE9E5] text-[#913638] flex items-center justify-center shrink-0">
                <CheckCircle2 className="w-5 h-5" />
              </div>
              <h2 className="font-serif-luxury text-xl font-bold text-[#2E211E]">
                Return Eligibility
              </h2>
            </div>

            <p className="text-sm text-[#523D35] leading-relaxed">
              Returns are accepted only for eligible products and according to our return conditions.
            </p>

            <ul className="space-y-2 text-xs text-[#786864]">
              <li className="flex items-start gap-2">
                <span className="text-[#913638] font-bold">&bull;</span>
                <span>Items must be unused, unwashed, and in pristine original handmade condition.</span>
              </li>
              <li className="flex items-start gap-2">
                <span className="text-[#913638] font-bold">&bull;</span>
                <span>Original brand tags, ribbons, and custom boutique packaging must remain intact.</span>
              </li>
              <li className="flex items-start gap-2">
                <span className="text-[#913638] font-bold">&bull;</span>
                <span>Return requests must be initiated within <strong>48 hours</strong> of verified delivery.</span>
              </li>
              <li className="flex items-start gap-2">
                <span className="text-[#913638] font-bold">&bull;</span>
                <span>Personalized or custom bespoke crochet items made to custom dimensions are not eligible unless defective upon arrival.</span>
              </li>
            </ul>
          </div>

          {/* Section: Damaged or Wrong Product */}
          <div className="bg-white rounded-3xl border border-[#E7D1CC] p-6 sm:p-7 shadow-boutique space-y-4">
            <div className="flex items-center gap-3">
              <div className="w-10 h-10 rounded-2xl bg-[#FCE9E5] text-[#913638] flex items-center justify-center shrink-0">
                <AlertCircle className="w-5 h-5" />
              </div>
              <h2 className="font-serif-luxury text-xl font-bold text-[#2E211E]">
                Damaged or Wrong Product
              </h2>
            </div>

            <p className="text-sm text-[#523D35] leading-relaxed">
              If you receive a damaged, defective, or incorrect product, contact us with your order details and unpacking video.
            </p>

            <p className="text-xs text-[#786864] leading-relaxed">
              Our quality assurance team reviews all reports swiftly. Once the unpacking video is validated, we will offer you an expedited priority artisan replacement or a full refund without hassle.
            </p>

            <div className="pt-2">
              <Link
                href="/contact"
                className="inline-flex items-center gap-2 text-xs font-semibold text-[#913638] hover:underline"
              >
                <span>Report an issue with your order</span>
                <ArrowRight className="w-3.5 h-3.5" />
              </Link>
            </div>
          </div>

          {/* Section: Refund */}
          <div className="bg-white rounded-3xl border border-[#E7D1CC] p-6 sm:p-7 shadow-boutique space-y-4">
            <div className="flex items-center gap-3">
              <div className="w-10 h-10 rounded-2xl bg-[#FCE9E5] text-[#913638] flex items-center justify-center shrink-0">
                <Clock className="w-5 h-5" />
              </div>
              <h2 className="font-serif-luxury text-xl font-bold text-[#2E211E]">
                Refund
              </h2>
            </div>

            <p className="text-sm text-[#523D35] leading-relaxed">
              Approved refunds will be processed according to the applicable return/refund conditions.
            </p>

            <ul className="space-y-2 text-xs text-[#786864]">
              <li className="flex items-start gap-2">
                <span className="text-[#913638] font-bold">&bull;</span>
                <span><strong>Original Payment Method:</strong> Refunds credit back to the source bank account, card, or UPI ID.</span>
              </li>
              <li className="flex items-start gap-2">
                <span className="text-[#913638] font-bold">&bull;</span>
                <span><strong>Processing Timeline:</strong> Please allow <strong>5–7 business days</strong> for the bank settlement cycle.</span>
              </li>
              <li className="flex items-start gap-2">
                <span className="text-[#913638] font-bold">&bull;</span>
                <span>You will receive an official email confirmation with the refund reference ID immediately upon initiation.</span>
              </li>
            </ul>
          </div>

          {/* Section: Important Notice */}
          <div className="bg-white rounded-3xl border border-[#E7D1CC] p-6 sm:p-7 shadow-boutique space-y-4">
            <div className="flex items-center gap-3">
              <div className="w-10 h-10 rounded-2xl bg-[#FFF5F3] text-[#913638] border border-[#EFB8B0] flex items-center justify-center shrink-0">
                <ShieldCheck className="w-5 h-5" />
              </div>
              <h2 className="font-serif-luxury text-xl font-bold text-[#2E211E]">
                Important
              </h2>
            </div>

            <div className="p-4 rounded-2xl bg-[#FFF9F6] border border-[#E7D1CC] text-xs sm:text-sm text-[#2E211E] font-medium leading-relaxed">
              Returns without the required unpacking video may not be accepted.
            </div>

            <p className="text-xs text-[#786864] leading-relaxed">
              Due to the delicate, handcrafted nature of our crochet goods, video verification protects both your purchase and our artisan collective against transit mishandling claims.
            </p>
          </div>

        </div>

        {/* ========================================================= */}
        {/* 3. QUICK ASSISTANCE & FAQ PROMPT                          */}
        {/* ========================================================= */}
        <section className="rounded-3xl border border-[#E7D1CC] bg-[#FFF9F6] p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between gap-6">
          <div className="space-y-1 text-center sm:text-left">
            <h3 className="font-serif-luxury text-lg font-bold text-[#2E211E]">
              Have questions about your order or return status?
            </h3>
            <p className="text-xs text-[#786864]">
              Browse common questions or track your order progress directly in your account.
            </p>
          </div>

          <div className="flex flex-wrap items-center justify-center gap-3 shrink-0">
            <Link
              href="/contact/faq"
              className="px-5 py-2.5 rounded-full bg-white text-[#2E211E] border border-[#E7D1CC] hover:border-[#EFB8B0] hover:bg-[#FCE9E5] text-xs font-semibold transition-all inline-flex items-center gap-1.5 shadow-xs"
            >
              <HelpCircle className="w-4 h-4 text-[#913638]" />
              <span>Read Return FAQs</span>
            </Link>

            <Link
              href="/account/orders"
              className="px-5 py-2.5 rounded-full bg-[#913638] text-white text-xs font-semibold hover:bg-[#74292B] active:scale-[0.98] transition-all inline-flex items-center gap-1.5 shadow-sm"
            >
              <span>Track Your Order</span>
              <ArrowRight className="w-3.5 h-3.5" />
            </Link>
          </div>
        </section>

      </main>
    </div>
  );
}
