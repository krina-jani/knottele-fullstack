"use client";

import React, { useState } from "react";
import { ArrowRight, CheckCircle2 } from "lucide-react";
import { YarnHeartArt } from "@/components/ui/BotanicalDecorations";
import { useToast } from "@/context/ToastContext";
import { useWebsiteMedia } from "@/context/MediaContext";

export function Newsletter() {
  const { media } = useWebsiteMedia();
  const [email, setEmail] = useState("");
  const [isSubscribed, setIsSubscribed] = useState(false);
  const { showToast } = useToast();

  const title = media?.newsletter?.title || "Join Our Creative Journey";
  const description = (media?.newsletter as any)?.description || media?.newsletter?.subtitle || "Get updates on new products, offers and handmade stories.";
  const promoCode = (media?.newsletter as any)?.tag_text || "KNOTELLE10";
  const buttonText = (media?.newsletter as any)?.cta_text || "Subscribe";

  const handleSubscribe = (e: React.FormEvent) => {
    e.preventDefault();
    if (email.trim()) {
      setIsSubscribed(true);
      showToast("Subscribed! 💌", `Thank you for joining our creative family. Use code ${promoCode} for 10% off!`, "success");
      setEmail("");
    }
  };

  if ((media?.newsletter as any)?.is_active === false) return null;

  return (
    <section className="py-6 sm:py-8 bg-[#FFF9F6]">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="rounded-2xl sm:rounded-3xl bg-[#FCE9E5] border border-[#E7D1CC]/80 p-5 sm:p-7 lg:py-6 lg:px-10 shadow-xs">
          <div className="grid grid-cols-1 lg:grid-cols-12 gap-5 lg:gap-6 items-center">
            
            {/* Left: Heading & Description (Spans 4.5 cols on lg) */}
            <div className="lg:col-span-5 text-center lg:text-left space-y-1">
              <h2 className="font-serif-luxury text-2xl sm:text-3xl font-bold text-[#74292B] tracking-tight">
                {title}
              </h2>
              <p className="text-xs sm:text-sm text-[#786864]">
                {description}
              </p>
            </div>

            {/* Center: Seamless Input + Button Form (Spans 4.5 cols on lg) */}
            <div className="lg:col-span-4 flex justify-center lg:justify-start">
              {isSubscribed ? (
                <div className="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white text-[#913638] text-xs font-semibold border border-[#E7D1CC] shadow-xs">
                  <CheckCircle2 className="w-4 h-4 text-[#913638]" />
                  <span>Welcome to the KNOTELLE family!</span>
                </div>
              ) : (
                <form onSubmit={handleSubscribe} className="flex flex-col sm:flex-row items-stretch sm:items-center w-full max-w-md shadow-xs rounded-2xl sm:rounded-full overflow-hidden border border-[#E7D1CC] bg-white p-1 sm:p-0">
                  <input
                    type="email"
                    required
                    value={email}
                    onChange={(e) => setEmail(e.target.value)}
                    placeholder="Your email address"
                    className="flex-1 px-4 py-2.5 bg-transparent text-xs sm:text-sm text-[#2E211E] placeholder-[#786864]/60 focus:outline-none"
                  />
                  <button
                    type="submit"
                    className="px-5 sm:px-6 py-2.5 bg-[#913638] text-white text-xs sm:text-sm font-semibold hover:bg-[#74292B] rounded-full sm:rounded-none transition-colors shrink-0 flex items-center justify-center gap-1.5 active:scale-[0.98] cursor-pointer shadow-xs"
                  >
                    <span>Subscribe</span>
                    <ArrowRight className="w-3.5 h-3.5" />
                  </button>
                </form>
              )}
            </div>

            {/* Right: Yarn Heart Art ("A Kinder, Cozier World. ♡") (Spans 3 cols on lg) */}
            <div className="lg:col-span-3 flex items-center justify-center lg:justify-end gap-3 text-center lg:text-right pt-2 lg:pt-0">
              <YarnHeartArt className="w-12 h-12 text-[#EFB8B0] shrink-0" />
              <div className="text-left">
                <p className="font-script-boutique text-base sm:text-lg font-bold text-[#913638] leading-tight">
                  A Kinder,<br />Cozier World.
                </p>
                <span className="text-xs text-[#913638] block">♡</span>
              </div>
            </div>

          </div>
        </div>
      </div>
    </section>
  );
}

