"use client";

import React, { useState } from "react";
import Link from "next/link";
import {
  Truck,
  Package,
  MapPin,
  AlertTriangle,
  CheckCircle2,
  Clock,
  Sparkles,
  Send,
  Phone,
  Mail,
  ShieldCheck,
  ArrowRight,
  Info,
  Loader2,
} from "lucide-react";
import { Breadcrumbs } from "@/components/ui/Breadcrumbs";
import { SupportHeader } from "@/components/support/SupportHeader";
import { BotanicalFlourish, FlowerIcon } from "@/components/ui/BotanicalDecorations";
import { useToast } from "@/context/ToastContext";
import { submitContactForm, ContactPageData } from "@/lib/api";

interface ShippingPolicyViewProps {
  initialContactData?: ContactPageData | null;
}

export function ShippingPolicyView({ initialContactData }: ShippingPolicyViewProps) {
  // Contact Form State
  const [formData, setFormData] = useState({
    name: "",
    email: "",
    subject: "Shipping Inquiry",
    message: "",
  });
  const [submitting, setSubmitting] = useState(false);
  const [submitted, setSubmitted] = useState(false);
  const { showToast } = useToast();

  const handleContactSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!formData.name.trim() || !formData.email.trim() || !formData.message.trim()) {
      showToast("Please fill in all required fields.", "error");
      return;
    }

    setSubmitting(true);
    try {
      const res = await submitContactForm(formData);
      if (res.success) {
        setSubmitted(true);
        showToast("Your message has been sent successfully!", "success");
        setFormData({ name: "", email: "", subject: "Shipping Inquiry", message: "" });
      } else {
        showToast(res.message || "Failed to send message. Please try again.", "error");
      }
    } catch {
      showToast("Failed to send message. Please try again.", "error");
    } finally {
      setSubmitting(false);
    }
  };

  // Quick Overview Flow
  const quickFlow = [
    { icon: Truck, title: "Processing", desc: "Handcrafted & packed with care", emoji: "🚚" },
    { icon: Package, title: "Shipping", desc: "Dispatched with tracking", emoji: "📦" },
    { icon: MapPin, title: "Delivery", desc: "Arrives at your doorstep", emoji: "📍" },
    { icon: AlertTriangle, title: "Delays", desc: "Occasional seasonal delays", emoji: "⚠️" },
  ];

  // 5 Step Timeline
  const journeySteps = [
    {
      step: 1,
      title: "Order Placed",
      desc: "Your order is received and queued in our artisanal studio.",
      time: "Day 0",
    },
    {
      step: 2,
      title: "Order Confirmed",
      desc: "Our team verifies all customization details and yarn supplies.",
      time: "Day 1",
    },
    {
      step: 3,
      title: "Crafting",
      desc: "Each item is meticulously crocheted by hand stitch by stitch.",
      time: "1–3 Days",
    },
    {
      step: 4,
      title: "Shipped",
      desc: "Carefully wrapped in eco-friendly packaging with live courier tracking.",
      time: "3–4 Days",
    },
    {
      step: 5,
      title: "Delivered",
      desc: "Delivered straight to your hands with love from India.",
      time: "4–7 Days",
    },
  ];

  return (
    <div className="min-h-screen bg-[#FFFDFB] text-[#2E211E] pt-6 pb-20">
      <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {/* Breadcrumbs */}
        <div className="mb-6">
          <Breadcrumbs
            items={[
              { label: "Home", href: "/" },
              { label: "Customer Care", href: "/contact" },
              { label: "Shipping Policy" },
            ]}
          />
        </div>

        {/* Unified Support Header with Tabs */}
        <SupportHeader
          currentTab="shipping"
          title="Shipping Policy"
          subtitle="Everything you need to know about how your KNOTELLE order reaches you."
        />

        {/* 1. Quick Flow Banner */}
        <div className="bg-white rounded-3xl border border-[#E7D1CC] p-5 sm:p-7 shadow-xs mb-8 sm:mb-12">
          <div className="text-center mb-6">
            <span className="text-[11px] font-bold uppercase tracking-wider text-[#913638] bg-[#FCE9E5] px-3 py-1 rounded-full border border-[#E7D1CC]">
              Order Journey at a Glance
            </span>
          </div>

          <div className="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 relative">
            {quickFlow.map((item, idx) => (
              <div
                key={idx}
                className="flex flex-col items-center text-center p-4 rounded-2xl bg-[#FFF9F6] border border-[#E7D1CC]/70 hover:border-[#EFB8B0] transition-all"
              >
                <span className="text-2xl sm:text-3xl mb-2">{item.emoji}</span>
                <h3 className="font-bold text-sm sm:text-base text-[#2E211E]">{item.title}</h3>
                <p className="text-[11px] sm:text-xs text-[#786864] mt-1 leading-snug">{item.desc}</p>
              </div>
            ))}
          </div>
        </div>

        {/* 2. Step-by-Step Timeline */}
        <div className="bg-white rounded-3xl border border-[#E7D1CC] p-6 sm:p-8 md:p-10 shadow-xs mb-8 sm:mb-12">
          <div className="flex items-center gap-2 mb-2">
            <Clock className="w-4 h-4 text-[#913638]" />
            <h2 className="font-serif-luxury text-xl sm:text-2xl font-bold text-[#2E211E]">
              Step-by-Step Timeline
            </h2>
          </div>
          <p className="text-xs sm:text-sm text-[#786864] mb-8">
            From the first knot to your doorstep — here is what happens after you place your order.
          </p>

          {/* Desktop Horizontal Timeline */}
          <div className="hidden lg:grid grid-cols-5 gap-3 relative before:absolute before:top-4 before:left-6 before:right-6 before:h-0.5 before:bg-[#E7D1CC] before:z-0">
            {journeySteps.map((step) => (
              <div key={step.step} className="relative z-10 flex flex-col items-center text-center px-1">
                <div className="w-8 h-8 rounded-full bg-[#913638] text-white flex items-center justify-center font-bold text-xs shadow-xs mb-3 ring-4 ring-white">
                  {step.step}
                </div>
                <span className="text-[10px] font-bold text-[#913638] uppercase tracking-wider mb-0.5">
                  {step.time}
                </span>
                <h4 className="font-bold text-xs sm:text-sm text-[#2E211E]">{step.title}</h4>
                <p className="text-[11px] text-[#786864] mt-1 leading-relaxed">{step.desc}</p>
              </div>
            ))}
          </div>

          {/* Mobile Vertical Timeline */}
          <div className="lg:hidden relative pl-8 space-y-6 before:absolute before:left-3 before:top-2 before:bottom-2 before:w-0.5 before:bg-[#E7D1CC]">
            {journeySteps.map((step) => (
              <div key={step.step} className="relative">
                <div className="absolute -left-8 top-0.5 w-6 h-6 rounded-full bg-[#913638] text-white flex items-center justify-center font-bold text-xs ring-4 ring-white">
                  {step.step}
                </div>
                <div className="space-y-0.5">
                  <div className="flex items-center gap-2">
                    <h4 className="font-bold text-sm text-[#2E211E]">{step.title}</h4>
                    <span className="text-[10px] font-bold text-[#913638] bg-[#FCE9E5] px-2 py-0.5 rounded-full border border-[#E7D1CC]">
                      {step.time}
                    </span>
                  </div>
                  <p className="text-xs text-[#786864] leading-relaxed">{step.desc}</p>
                </div>
              </div>
            ))}
          </div>
        </div>

        {/* 3. Detailed Policy Cards */}
        <div className="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6 mb-8 sm:mb-12">
          
          {/* Card 1: Order Processing */}
          <div className="bg-white rounded-3xl border border-[#E7D1CC] p-6 sm:p-7 shadow-xs hover:shadow-md transition-shadow">
            <div className="w-10 h-10 rounded-2xl bg-[#FCE9E5] text-[#913638] flex items-center justify-center mb-4">
              <Package className="w-5 h-5" />
            </div>
            <h3 className="font-serif-luxury text-lg font-bold text-[#2E211E] mb-2">
              Order Processing
            </h3>
            <p className="text-xs sm:text-sm text-[#5C4D49] leading-relaxed">
              Your order is carefully prepared and packed before dispatch. Because each KNOTELLE creation is hand-crafted with intricate yarn techniques, custom commissions may require an extra day for our artisans to ensure flawless quality.
            </p>
          </div>

          {/* Card 2: Shipping */}
          <div className="bg-white rounded-3xl border border-[#E7D1CC] p-6 sm:p-7 shadow-xs hover:shadow-md transition-shadow">
            <div className="w-10 h-10 rounded-2xl bg-[#FCE9E5] text-[#913638] flex items-center justify-center mb-4">
              <Truck className="w-5 h-5" />
            </div>
            <h3 className="font-serif-luxury text-lg font-bold text-[#2E211E] mb-2">
              Shipping & Live Tracking
            </h3>
            <p className="text-xs sm:text-sm text-[#5C4D49] leading-relaxed">
              Once your order is shipped, tracking details will be provided where applicable. You will receive an SMS and email notification with your tracking link so you can follow the journey of your package in real-time.
            </p>
          </div>

          {/* Card 3: Delivery */}
          <div className="bg-white rounded-3xl border border-[#E7D1CC] p-6 sm:p-7 shadow-xs hover:shadow-md transition-shadow">
            <div className="w-10 h-10 rounded-2xl bg-[#FCE9E5] text-[#913638] flex items-center justify-center mb-4">
              <MapPin className="w-5 h-5" />
            </div>
            <h3 className="font-serif-luxury text-lg font-bold text-[#2E211E] mb-2">
              Delivery Times
            </h3>
            <p className="text-xs sm:text-sm text-[#5C4D49] leading-relaxed">
              Delivery time may vary depending on your location and the product. Metro cities typically receive deliveries within 3–5 business days, while regional locations may take 5–7 business days post-dispatch.
            </p>
          </div>

          {/* Card 4: Delivery Delays */}
          <div className="bg-white rounded-3xl border border-[#E7D1CC] p-6 sm:p-7 shadow-xs hover:shadow-md transition-shadow">
            <div className="w-10 h-10 rounded-2xl bg-[#FFF5E6] text-[#B87333] flex items-center justify-center mb-4">
              <AlertTriangle className="w-5 h-5" />
            </div>
            <h3 className="font-serif-luxury text-lg font-bold text-[#2E211E] mb-2">
              Delivery Delays
            </h3>
            <p className="text-xs sm:text-sm text-[#5C4D49] leading-relaxed">
              Unexpected delays may occur due to courier services, weather, holidays, or other circumstances. If your package is delayed, our support team will actively coordinate with the logistics partner to expedite delivery.
            </p>
          </div>
        </div>

        {/* 4. Before Accepting Your Package (Highlighted Box) */}
        <div className="bg-gradient-to-br from-[#FFF9F6] to-[#FCE9E5]/40 rounded-3xl border border-[#EFB8B0] p-6 sm:p-8 shadow-xs mb-12 flex flex-col sm:flex-row items-start sm:items-center gap-5">
          <div className="w-12 h-12 rounded-2xl bg-white text-[#913638] shadow-xs flex items-center justify-center shrink-0 border border-[#E7D1CC]">
            <ShieldCheck className="w-6 h-6" />
          </div>
          <div className="space-y-1 flex-1">
            <h3 className="font-serif-luxury text-base sm:text-lg font-bold text-[#2E211E]">
              Before Accepting Your Package
            </h3>
            <p className="text-xs sm:text-sm text-[#5C4D49] leading-relaxed">
              Please check the package carefully when it arrives. If the outer carton is noticeably torn, tampered with, or unsealed, please do not accept delivery and immediately report it to us.
            </p>
          </div>
          <Link
            href="/contact/return-refund"
            className="px-5 py-2.5 rounded-full bg-[#913638] text-white text-xs font-semibold hover:bg-[#74292B] transition-colors shrink-0 inline-flex items-center gap-1.5 shadow-xs"
          >
            <span>Return Policy →</span>
          </Link>
        </div>

        {/* 5. Studio Assistance & Contact Form (Preserves Contact Us functionality) */}
        <div id="contact-studio" className="scroll-mt-24 pt-6 border-t border-[#E7D1CC]">
          <div className="text-center space-y-2 mb-8">
            <span className="text-[11px] font-bold uppercase tracking-wider text-[#913638] bg-[#FCE9E5] px-3 py-1 rounded-full border border-[#E7D1CC]">
              Need Help With Shipping?
            </span>
            <h2 className="font-serif-luxury text-2xl sm:text-3xl font-bold text-[#2E211E]">
              Reach Out to KNOTELLE Studio
            </h2>
            <p className="text-xs sm:text-sm text-[#786864] max-w-md mx-auto">
              Our artisan team is here to answer any questions about order status, custom deliveries, or special gifting requests.
            </p>
          </div>

          <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            {/* Contact Information & Channels */}
            <div className="lg:col-span-5 bg-white rounded-3xl border border-[#E7D1CC] p-6 sm:p-7 shadow-xs space-y-5">
              <h3 className="font-bold text-sm text-[#2E211E] uppercase tracking-wider border-b border-[#E7D1CC] pb-3">
                Atelier Coordinates
              </h3>

              <div className="space-y-4 text-xs sm:text-sm">
                <a
                  href="tel:+919773039243"
                  className="flex items-center gap-3 p-3 rounded-2xl bg-[#FFF9F6] border border-[#E7D1CC]/70 hover:border-[#EFB8B0] transition-all group"
                >
                  <div className="w-9 h-9 rounded-xl bg-[#FCE9E5] text-[#913638] flex items-center justify-center shrink-0">
                    <Phone className="w-4 h-4" />
                  </div>
                  <div>
                    <span className="text-[10px] text-[#786864] block font-medium">Direct Line</span>
                    <span className="font-bold text-[#2E211E] group-hover:text-[#913638] transition-colors">
                      +91 97730 39243
                    </span>
                  </div>
                </a>

                <a
                  href="mailto:support@knotelle.in"
                  className="flex items-center gap-3 p-3 rounded-2xl bg-[#FFF9F6] border border-[#E7D1CC]/70 hover:border-[#EFB8B0] transition-all group"
                >
                  <div className="w-9 h-9 rounded-xl bg-[#FCE9E5] text-[#913638] flex items-center justify-center shrink-0">
                    <Mail className="w-4 h-4" />
                  </div>
                  <div>
                    <span className="text-[10px] text-[#786864] block font-medium">Artisan Support</span>
                    <span className="font-bold text-[#2E211E] group-hover:text-[#913638] transition-colors">
                      support@knotelle.in
                    </span>
                  </div>
                </a>

                <div className="flex items-center gap-3 p-3 rounded-2xl bg-[#FFF9F6] border border-[#E7D1CC]/70">
                  <div className="w-9 h-9 rounded-xl bg-[#FCE9E5] text-[#913638] flex items-center justify-center shrink-0">
                    <MapPin className="w-4 h-4" />
                  </div>
                  <div>
                    <span className="text-[10px] text-[#786864] block font-medium">Studio Origin</span>
                    <span className="font-bold text-[#2E211E]">
                      Bengaluru, India (Pan-India Dispatch)
                    </span>
                  </div>
                </div>
              </div>

              <div className="pt-2">
                <Link
                  href="/account/orders"
                  className="w-full py-2.5 px-4 rounded-xl bg-[#FFF9F6] text-[#913638] border border-[#E7D1CC] hover:bg-[#FCE9E5] text-xs font-semibold flex items-center justify-center gap-2 transition-colors"
                >
                  <Package className="w-4 h-4" />
                  <span>Looking to Track an Existing Order? →</span>
                </Link>
              </div>
            </div>

            {/* Direct Message Form */}
            <div className="lg:col-span-7 bg-white rounded-3xl border border-[#E7D1CC] p-6 sm:p-8 shadow-xs">
              <h3 className="font-serif-luxury text-lg font-bold text-[#2E211E] mb-1">
                Send Us a Note
              </h3>
              <p className="text-xs text-[#786864] mb-5">
                We typically respond within a few hours on business days.
              </p>

              {submitted ? (
                <div className="p-6 rounded-2xl bg-[#FCE9E5]/50 border border-[#E7D1CC] text-center space-y-2">
                  <CheckCircle2 className="w-8 h-8 text-[#913638] mx-auto" />
                  <h4 className="font-bold text-sm text-[#2E211E]">Message Received!</h4>
                  <p className="text-xs text-[#786864]">
                    Thank you for reaching out. One of our artisans will be in touch with you shortly.
                  </p>
                  <button
                    onClick={() => setSubmitted(false)}
                    className="text-xs font-bold text-[#913638] hover:underline pt-2 inline-block"
                  >
                    Send another message
                  </button>
                </div>
              ) : (
                <form onSubmit={handleContactSubmit} className="space-y-4">
                  <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                      <label className="block text-xs font-semibold text-[#2E211E] mb-1">Your Name *</label>
                      <input
                        type="text"
                        required
                        value={formData.name}
                        onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                        placeholder="e.g. Ananya Sharma"
                        className="w-full px-3.5 py-2.5 rounded-xl border border-[#E7D1CC] focus:outline-none focus:ring-2 focus:ring-[#913638]/20 focus:border-[#913638] text-xs bg-[#FFFDFB]"
                      />
                    </div>
                    <div>
                      <label className="block text-xs font-semibold text-[#2E211E] mb-1">Email Address *</label>
                      <input
                        type="email"
                        required
                        value={formData.email}
                        onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                        placeholder="ananya@example.com"
                        className="w-full px-3.5 py-2.5 rounded-xl border border-[#E7D1CC] focus:outline-none focus:ring-2 focus:ring-[#913638]/20 focus:border-[#913638] text-xs bg-[#FFFDFB]"
                      />
                    </div>
                  </div>

                  <div>
                    <label className="block text-xs font-semibold text-[#2E211E] mb-1">Subject</label>
                    <input
                      type="text"
                      value={formData.subject}
                      onChange={(e) => setFormData({ ...formData, subject: e.target.value })}
                      placeholder="Shipping status, bespoke inquiry..."
                      className="w-full px-3.5 py-2.5 rounded-xl border border-[#E7D1CC] focus:outline-none focus:ring-2 focus:ring-[#913638]/20 focus:border-[#913638] text-xs bg-[#FFFDFB]"
                    />
                  </div>

                  <div>
                    <label className="block text-xs font-semibold text-[#2E211E] mb-1">Your Message *</label>
                    <textarea
                      required
                      rows={3}
                      value={formData.message}
                      onChange={(e) => setFormData({ ...formData, message: e.target.value })}
                      placeholder="Share your order ID, inquiry, or question..."
                      className="w-full px-3.5 py-2.5 rounded-xl border border-[#E7D1CC] focus:outline-none focus:ring-2 focus:ring-[#913638]/20 focus:border-[#913638] text-xs bg-[#FFFDFB] resize-none"
                    />
                  </div>

                  <button
                    type="submit"
                    disabled={submitting}
                    className="w-full py-2.5 px-6 rounded-full bg-[#913638] text-white text-xs font-semibold hover:bg-[#74292B] active:scale-[0.98] transition-all flex items-center justify-center gap-2 shadow-xs disabled:opacity-50"
                  >
                    {submitting ? (
                      <>
                        <Loader2 className="w-3.5 h-3.5 animate-spin" />
                        <span>Sending Message...</span>
                      </>
                    ) : (
                      <>
                        <Send className="w-3.5 h-3.5" />
                        <span>Send Message to Atelier</span>
                      </>
                    )}
                  </button>
                </form>
              )}
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
