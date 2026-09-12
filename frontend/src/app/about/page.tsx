import React from "react";
import Image from "next/image";
import Link from "next/link";
import { Heart, Sparkles, Flower2, Leaf, ArrowRight, ShieldCheck } from "lucide-react";
import { FlowerIcon } from "@/components/ui/BotanicalDecorations";
import { Breadcrumbs } from "@/components/ui/Breadcrumbs";

export default function AboutPage() {
  return (
    <div className="bg-[#FFF9F6] min-h-screen py-8 lg:py-12">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {/* Breadcrumbs */}
        <Breadcrumbs items={[{ label: "About KNOTELLE" }]} className="mb-8" />

        {/* Story Section: 2 Columns */}
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center mb-16 lg:mb-20">
          <div className="lg:col-span-6 relative">
            <div className="relative aspect-[4/4.5] rounded-3xl overflow-hidden border-4 border-white shadow-xl bg-white">
              <Image
                src="https://images.unsplash.com/photo-1544816155-12df9643f363?q=80&w=1000&auto=format&fit=crop"
                alt="Artisan stitching crochet with wooden hook"
                fill
                sizes="(max-width: 1024px) 100vw, 50vw"
                className="object-cover"
              />
            </div>
            <div className="absolute -bottom-4 -right-4 bg-white p-4 rounded-2xl border border-[#E7D1CC] shadow-lg flex items-center gap-3">
              <Heart className="w-6 h-6 fill-[#913638] text-[#913638]" />
              <div>
                <p className="text-xs font-bold text-[#2E211E]">100% Handcrafted</p>
                <p className="text-[10px] text-[#786864]">Never mass machine produced</p>
              </div>
            </div>
          </div>

          <div className="lg:col-span-6 space-y-6">
            <div className="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#FCE9E5] text-[#913638] text-xs font-semibold border border-[#E7D1CC]">
              <FlowerIcon className="w-3.5 h-3.5" />
              <span>The KNOTELLE Story</span>
            </div>

            <h2 className="font-serif-luxury text-3xl sm:text-4xl font-bold text-[#2E211E]">
              Every Loop Tells a Story
            </h2>

            <p className="text-sm text-[#786864] leading-relaxed">
              In a world flooded with disposable factory goods, KNOTELLE was born from a yearning for slow-made warmth. What began as a tiny home workshop in Bengaluru has grown into a vibrant collective of women artisans who share a deep love for yarn, color harmony, and delicate floral silhouettes.
            </p>

            <p className="text-sm text-[#786864] leading-relaxed">
              When you order a bouquet of crochet roses, a customized bunny keychain, or a granny square tote, you are not simply purchasing an object. You are welcoming hours of human patience, intention, and joy into your home.
            </p>

            <div className="pt-2">
              <Link
                href="/custom-order"
                className="inline-flex items-center gap-2 px-7 py-3.5 rounded-full bg-[#913638] text-white text-xs sm:text-sm font-semibold hover:bg-[#74292B] active:scale-[0.98] transition-all shadow-sm"
              >
                <span>Request a Custom Creation</span>
                <ArrowRight className="w-3.5 h-3.5" />
              </Link>
            </div>
          </div>
        </div>

        {/* 3 Pillars of KNOTELLE */}
        <div className="mb-16 lg:mb-20">
          <div className="text-center max-w-xl mx-auto mb-10">
            <h2 className="font-serif-luxury text-3xl font-bold text-[#2E211E]">
              Our Craft Pillars
            </h2>
            <p className="text-xs sm:text-sm text-[#786864] mt-1">
              Guiding principles behind every stitch we make.
            </p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div className="p-6 sm:p-8 rounded-3xl bg-white border border-[#E7D1CC] shadow-boutique text-center space-y-3">
              <div className="w-12 h-12 rounded-2xl bg-[#FCE9E5] border border-[#E7D1CC] flex items-center justify-center text-[#913638] mx-auto">
                <Leaf className="w-6 h-6" />
              </div>
              <h3 className="font-serif-luxury text-xl font-bold text-[#2E211E]">
                Natural Materials
              </h3>
              <p className="text-xs text-[#786864] leading-relaxed">
                We use 100% pure milk cotton and mercerized organic fibers that are gentle on skin and kind to mother nature.
              </p>
            </div>

            <div className="p-6 sm:p-8 rounded-3xl bg-white border border-[#E7D1CC] shadow-boutique text-center space-y-3">
              <div className="w-12 h-12 rounded-2xl bg-[#FCE9E5] border border-[#E7D1CC] flex items-center justify-center text-[#913638] mx-auto">
                <Sparkles className="w-6 h-6 text-[#C69A5A]" />
              </div>
              <h3 className="font-serif-luxury text-xl font-bold text-[#2E211E]">
                Bespoke Atelier
              </h3>
              <p className="text-xs text-[#786864] leading-relaxed">
                No two requests are alike. We collaborate with you on colors, sizing, and details to make every piece unique.
              </p>
            </div>

            <div className="p-6 sm:p-8 rounded-3xl bg-white border border-[#E7D1CC] shadow-boutique text-center space-y-3">
              <div className="w-12 h-12 rounded-2xl bg-[#FCE9E5] border border-[#E7D1CC] flex items-center justify-center text-[#913638] mx-auto">
                <Heart className="w-6 h-6 fill-[#913638]" />
              </div>
              <h3 className="font-serif-luxury text-xl font-bold text-[#2E211E]">
                Empowering Artisans
              </h3>
              <p className="text-xs text-[#786864] leading-relaxed">
                We provide fair wages, flexible hours, and creative dignity to our women-led artisan crafting community in India.
              </p>
            </div>
          </div>
        </div>

      </div>
    </div>
  );
}
