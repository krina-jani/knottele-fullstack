import React from "react";
import Image from "next/image";
import Link from "next/link";
import { ArrowRight, Sparkles } from "lucide-react";
import { BotanicalFlourish, FlowerIcon, YarnBallDecor } from "@/components/ui/BotanicalDecorations";

export default function NotFound() {
  return (
    <div className="bg-[#FFF9F6] min-h-[80vh] flex items-center justify-center py-16 px-4">
      <div className="max-w-md w-full bg-white rounded-3xl border border-[#E7D1CC] p-8 sm:p-12 text-center shadow-boutique space-y-6 relative overflow-hidden">
        
        {/* Cute yarn animation icon */}
        <div className="relative w-20 h-20 sm:w-24 sm:h-24 mx-auto">
          <div className="w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-[#FCE9E5] border border-[#E7D1CC] flex items-center justify-center text-[#913638] shadow-sm">
            <YarnBallDecor className="w-10 h-10 sm:w-12 sm:h-12 text-[#913638]" />
          </div>
          <div className="absolute -top-1 -right-1 w-8 h-8 rounded-full bg-white border border-[#E7D1CC] flex items-center justify-center text-[#C69A5A] shadow-xs">
            <Sparkles className="w-4 h-4" />
          </div>
        </div>

        <div className="space-y-2">
          <span className="font-serif-luxury text-4xl font-bold text-[#913638] block">
            404
          </span>
          <h1 className="font-serif-luxury text-2xl sm:text-3xl font-bold text-[#2E211E]">
            Oops! This Stitch Went Missing.
          </h1>
          <p className="text-xs sm:text-sm text-[#786864] leading-relaxed">
            The page you&apos;re looking for doesn&apos;t seem to exist or has gently unraveled. Let&apos;s guide you back to our cozy boutique.
          </p>
        </div>

        <div className="flex justify-center">
          <BotanicalFlourish className="w-20 h-4 text-[#EFB8B0]" />
        </div>

        <div className="pt-2 flex flex-col sm:flex-row items-center justify-center gap-3">
          <Link
            href="/"
            className="w-full sm:w-auto px-7 py-3 rounded-full bg-[#913638] text-white text-xs font-semibold hover:bg-[#74292B] active:scale-[0.98] shadow-sm transition-all flex items-center justify-center gap-2 cursor-pointer"
          >
            <span>Back to Home</span>
            <ArrowRight className="w-4 h-4" />
          </Link>
          <Link
            href="/shop"
            className="w-full sm:w-auto px-6 py-3 rounded-full bg-white text-[#2E211E] border border-[#E7D1CC] hover:border-[#EFB8B0] text-xs font-semibold hover:bg-[#FCE9E5] hover:text-[#913638] active:scale-[0.98] transition-all cursor-pointer shadow-xs"
          >
            Browse Shop
          </Link>
        </div>

      </div>
    </div>
  );
}
