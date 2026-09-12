import React from "react";
import Image from "next/image";
import Link from "next/link";
import { ArrowRight } from "lucide-react";
import { HandmadePaperTag } from "@/components/ui/BotanicalDecorations";

export function CustomBanner() {
  return (
    <section className="py-6 sm:py-8 bg-[#FFF9F6]">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="relative rounded-3xl bg-[#FCE9E5] border border-[#E7D1CC] p-8 sm:p-10 lg:p-12 overflow-hidden shadow-boutique">
          
          <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center relative z-10">
            
            {/* Left Content (Spans 7 cols on lg) */}
            <div className="lg:col-span-7 space-y-4 text-center lg:text-left flex flex-col items-center lg:items-start">
              
              <h2 className="font-serif-luxury text-3xl sm:text-4xl lg:text-5xl font-bold text-[#2E211E] tracking-tight leading-[1.15]">
                Custom Crochet <br />
                <span className="text-[#913638] italic font-serif font-normal">
                  Just for You
                </span>
              </h2>

              <p className="text-xs sm:text-sm text-[#786864] max-w-md leading-relaxed">
                Your imagination, our yarn. <br />
                Let&apos;s create something special together.
              </p>

              <div className="pt-2">
                <Link
                  href="/custom-order"
                  className="inline-flex items-center gap-2 px-6 sm:px-7 py-3 rounded-full bg-[#913638] text-white text-xs sm:text-sm font-semibold hover:bg-[#74292B] shadow-xs hover:shadow-boutique-hover transition-all group active:scale-[0.98]"
                >
                  <span>Request Your Custom Order</span>
                  <ArrowRight className="w-4 h-4 group-hover:translate-x-1 transition-transform" />
                </Link>
              </div>
            </div>

            {/* Right Visual (Spans 5 cols on lg) */}
            <div className="lg:col-span-5 relative flex justify-center">
              <div className="relative w-full max-w-xs sm:max-w-sm aspect-[4/3] rounded-2xl overflow-hidden border-2 border-white shadow-md bg-white">
                <Image
                  src="https://images.unsplash.com/photo-1588850561407-ed78c282e89b?q=80&w=800&auto=format&fit=crop"
                  alt="Custom Crochet Creations"
                  fill
                  sizes="(max-width: 1024px) 80vw, 35vw"
                  className="object-cover"
                />

                {/* Hanging Tag */}
                <div className="absolute bottom-2 right-2 z-10">
                  <HandmadePaperTag text="Turn Your Ideas Into Handmade Reality" />
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </section>
  );
}
