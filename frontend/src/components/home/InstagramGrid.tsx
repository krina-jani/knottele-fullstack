import React from "react";
import Image from "next/image";
import { INSTAGRAM_POSTS } from "@/data/instagram";

export function InstagramGrid() {
  return (
    <section className="py-12 sm:py-16 bg-[#FFF9F6]">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {/* Header */}
        <div className="text-center max-w-xl mx-auto mb-8">
          <h2 className="font-serif-luxury text-2xl sm:text-3xl lg:text-4xl font-bold text-[#2E211E]">
            Made With Love
          </h2>
          <p className="text-xs sm:text-sm text-[#786864] mt-1">
            Follow our journey on Instagram{" "}
            <a
              href="https://instagram.com/knotelleindia"
              target="_blank"
              rel="noopener noreferrer"
              className="text-[#913638] font-semibold hover:underline"
            >
              @knotelleindia
            </a>
          </p>
        </div>

        {/* 8-Photo Horizontal Grid (8 on desktop/xl, 4 on tablet, 2 on mobile) */}
        <div className="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-3 sm:gap-3.5">
          {INSTAGRAM_POSTS.map((post) => (
            <a
              key={post.id}
              href={post.link}
              target="_blank"
              rel="noopener noreferrer"
              className="group relative aspect-square rounded-2xl overflow-hidden bg-white border border-[#E7D1CC] shadow-xs hover:shadow-boutique-hover block transition-all"
            >
              <Image
                src={post.image}
                alt="KNOTELLE Instagram Creation"
                fill
                sizes="(max-width: 640px) 50vw, (max-width: 1024px) 25vw, 12vw"
                className="object-cover group-hover:scale-108 transition-transform duration-500"
              />
            </a>
          ))}
        </div>

      </div>
    </section>
  );
}
