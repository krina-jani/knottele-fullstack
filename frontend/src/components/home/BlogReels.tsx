"use client";

import React, { useState } from "react";
import Image from "next/image";
import {
  Play,
  Pause,
  Heart,
  MessageCircle,
  Share2,
  X,
  Volume2,
  VolumeX,
  Music2,
  Sparkles,
  ChevronLeft,
  ChevronRight,
  Bookmark,
} from "lucide-react";
import { InstagramIcon } from "@/components/ui/BotanicalDecorations";
import { REEL_POSTS, ReelPost } from "@/data/blogReels";

export function BlogReels() {
  const [selectedReel, setSelectedReel] = useState<ReelPost | null>(null);
  const [isPlaying, setIsPlaying] = useState<boolean>(true);
  const [isMuted, setIsMuted] = useState<boolean>(false);
  const [likedReels, setLikedReels] = useState<Record<string, boolean>>({});
  const [savedReels, setSavedReels] = useState<Record<string, boolean>>({});

  const handleOpenReel = (reel: ReelPost) => {
    setSelectedReel(reel);
    setIsPlaying(true);
  };

  const handleCloseReel = () => {
    setSelectedReel(null);
  };

  const toggleLike = (id: string, e?: React.MouseEvent) => {
    e?.stopPropagation();
    setLikedReels((prev) => ({ ...prev, [id]: !prev[id] }));
  };

  const toggleSave = (id: string, e?: React.MouseEvent) => {
    e?.stopPropagation();
    setSavedReels((prev) => ({ ...prev, [id]: !prev[id] }));
  };

  const handleNextReel = () => {
    if (!selectedReel) return;
    const currentIndex = REEL_POSTS.findIndex((r) => r.id === selectedReel.id);
    const nextIndex = (currentIndex + 1) % REEL_POSTS.length;
    setSelectedReel(REEL_POSTS[nextIndex]);
    setIsPlaying(true);
  };

  const handlePrevReel = () => {
    if (!selectedReel) return;
    const currentIndex = REEL_POSTS.findIndex((r) => r.id === selectedReel.id);
    const prevIndex = (currentIndex - 1 + REEL_POSTS.length) % REEL_POSTS.length;
    setSelectedReel(REEL_POSTS[prevIndex]);
    setIsPlaying(true);
  };

  return (
    <section className="py-14 sm:py-18 bg-[#FFF9F6] relative overflow-hidden">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {/* Section Header */}
        <div className="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-10">
          <div>
            <div className="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#FCE9E5] text-[#913638] text-xs font-semibold mb-2.5">
              <InstagramIcon className="w-3.5 h-3.5" />
              <span>Studio Journal & Video Reels</span>
            </div>
            <h2 className="font-serif-luxury text-2xl sm:text-3xl lg:text-4xl font-bold text-[#2E211E]">
              Behind the Stitches
            </h2>
            <p className="text-xs sm:text-sm text-[#786864] mt-1.5 max-w-xl">
              Watch our artisans hand-craft each creation, styling guides, and cozy studio ASMR unboxings.
            </p>
          </div>

          <a
            href="https://instagram.com/knotelleindia"
            target="_blank"
            rel="noopener noreferrer"
            className="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-white border border-[#E7D1CC] text-xs font-semibold text-[#913638] hover:bg-[#913638] hover:text-white shadow-xs transition-all w-fit active:scale-[0.98]"
          >
            <Sparkles className="w-3.5 h-3.5" />
            <span>Follow @knotelleindia</span>
          </a>
        </div>

        {/* 4 Reel Video Cards Grid */}
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
          {REEL_POSTS.map((reel) => {
            const isLiked = likedReels[reel.id];

            return (
              <div
                key={reel.id}
                onClick={() => handleOpenReel(reel)}
                className="group relative aspect-[9/15] rounded-3xl overflow-hidden bg-[#2E211E] border border-[#E7D1CC] shadow-boutique hover:shadow-xl hover:border-[#EFB8B0] transition-all duration-300 cursor-pointer flex flex-col justify-between p-4"
              >
                {/* Background Image Thumbnail */}
                <Image
                  src={reel.thumbnail}
                  alt={reel.title}
                  fill
                  sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 25vw"
                  className="object-cover group-hover:scale-108 transition-transform duration-700 opacity-90 group-hover:opacity-100"
                />

                {/* Dark Gradient Vignette for Legibility */}
                <div className="absolute inset-0 bg-gradient-to-t from-black/85 via-black/25 to-black/60 pointer-events-none" />

                {/* Top Bar: Category Pill & Duration */}
                <div className="relative z-10 flex items-center justify-between gap-2">
                  <span className="px-3 py-1 rounded-full bg-black/40 backdrop-blur-md text-white text-[11px] font-semibold border border-white/20">
                    {reel.category}
                  </span>
                  <div className="flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-black/40 backdrop-blur-md text-white text-[10px] font-medium border border-white/20">
                    <span className="w-1.5 h-1.5 rounded-full bg-[#EFB8B0] animate-pulse" />
                    <span>{reel.duration}</span>
                  </div>
                </div>

                {/* Center Hover Play Button */}
                <div className="relative z-10 self-center my-auto">
                  <div className="w-13 h-13 rounded-full bg-white/30 backdrop-blur-md border border-white/60 text-white flex items-center justify-center group-hover:scale-115 group-hover:bg-[#913638] group-hover:border-[#913638] transition-all duration-300 shadow-lg">
                    <Play className="w-5 h-5 fill-current ml-0.5" />
                  </div>
                </div>

                {/* Bottom Overlay: Title, Audio & Engagement */}
                <div className="relative z-10 space-y-2">
                  <h3 className="font-serif-luxury text-base font-bold text-white line-clamp-2 leading-snug drop-shadow-sm group-hover:text-[#EFB8B0] transition-colors">
                    {reel.title}
                  </h3>

                  <p className="text-[11px] text-white/80 line-clamp-1">
                    {reel.subtitle}
                  </p>

                  {/* Audio Track */}
                  <div className="flex items-center gap-1.5 text-[10px] text-white/70 truncate">
                    <Music2 className="w-3 h-3 text-[#EFB8B0] shrink-0" />
                    <span className="truncate">{reel.audioTrack}</span>
                  </div>

                  {/* Engagement Counts Bar */}
                  <div className="pt-2 border-t border-white/20 flex items-center justify-between text-white/90 text-xs">
                    <div className="flex items-center gap-3">
                      <button
                        onClick={(e) => toggleLike(reel.id, e)}
                        className="flex items-center gap-1 hover:text-[#EFB8B0] transition-colors"
                        aria-label="Like reel"
                      >
                        <Heart
                          className={`w-3.5 h-3.5 ${
                            isLiked ? "fill-[#EFB8B0] text-[#EFB8B0]" : "text-white"
                          }`}
                        />
                        <span className="text-[11px] font-medium">
                          {isLiked ? "Liked" : reel.likes}
                        </span>
                      </button>

                      <div className="flex items-center gap-1">
                        <MessageCircle className="w-3.5 h-3.5 text-white/80" />
                        <span className="text-[11px] font-medium">{reel.comments}</span>
                      </div>
                    </div>

                    <span className="text-[10px] text-white/70 font-medium">
                      {reel.views} views
                    </span>
                  </div>
                </div>
              </div>
            );
          })}
        </div>

      </div>

      {/* Interactive Reel Video Player Modal */}
      {selectedReel && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-6 bg-black/80 backdrop-blur-md animate-fadeIn">
          {/* Backdrop Click to Close */}
          <div
            className="absolute inset-0"
            onClick={handleCloseReel}
            aria-hidden="true"
          />

          {/* Modal Card */}
          <div className="relative z-10 w-full max-w-sm sm:max-w-md aspect-[9/16] max-h-[90vh] bg-black rounded-3xl overflow-hidden shadow-2xl border border-white/20 flex flex-col justify-between p-5">
            {/* Reel Video Thumbnail Simulation */}
            <Image
              src={selectedReel.thumbnail}
              alt={selectedReel.title}
              fill
              className="object-cover"
              priority
            />
            <div className="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-black/70 pointer-events-none" />

            {/* Top Modal Controls */}
            <div className="relative z-20 flex items-center justify-between">
              <div className="flex items-center gap-2">
                <div className="relative w-8 h-8 rounded-full overflow-hidden border border-white/40">
                  <Image
                    src={selectedReel.author.avatar}
                    alt={selectedReel.author.name}
                    fill
                    className="object-cover"
                  />
                </div>
                <div>
                  <h4 className="text-xs font-bold text-white leading-tight">
                    {selectedReel.author.name}
                  </h4>
                  <p className="text-[10px] text-white/70">
                    {selectedReel.author.role}
                  </p>
                </div>
              </div>

              <div className="flex items-center gap-2">
                <button
                  onClick={() => setIsMuted(!isMuted)}
                  className="w-8 h-8 rounded-full bg-black/50 backdrop-blur-md text-white flex items-center justify-center hover:bg-black/80 transition-colors"
                  aria-label="Toggle mute"
                >
                  {isMuted ? (
                    <VolumeX className="w-4 h-4" />
                  ) : (
                    <Volume2 className="w-4 h-4 text-[#EFB8B0]" />
                  )}
                </button>
                <button
                  onClick={handleCloseReel}
                  className="w-8 h-8 rounded-full bg-black/50 backdrop-blur-md text-white flex items-center justify-center hover:bg-black/80 transition-colors"
                  aria-label="Close modal"
                >
                  <X className="w-4 h-4" />
                </button>
              </div>
            </div>

            {/* Center Play/Pause Indicator & Tap Trigger */}
            <button
              onClick={() => setIsPlaying(!isPlaying)}
              className="relative z-20 self-center my-auto w-16 h-16 rounded-full bg-black/40 backdrop-blur-md border border-white/40 text-white flex items-center justify-center hover:scale-110 transition-transform"
              aria-label={isPlaying ? "Pause video" : "Play video"}
            >
              {isPlaying ? (
                <Pause className="w-6 h-6" />
              ) : (
                <Play className="w-6 h-6 fill-current ml-1" />
              )}
            </button>

            {/* Bottom Caption & Right Actions */}
            <div className="relative z-20 flex items-end justify-between gap-4">
              <div className="space-y-2 max-w-[78%]">
                <div className="flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-black/50 backdrop-blur-md text-white text-[10px] font-semibold w-fit border border-white/20">
                  <span>{selectedReel.category}</span>
                </div>
                <h3 className="text-sm font-bold text-white leading-snug">
                  {selectedReel.title}
                </h3>
                <p className="text-xs text-white/90 leading-relaxed">
                  {selectedReel.caption}
                </p>
                <div className="flex items-center gap-1.5 text-[10px] text-[#EFB8B0]">
                  <Music2 className="w-3 h-3" />
                  <span className="truncate">{selectedReel.audioTrack}</span>
                </div>
              </div>

              {/* Action Buttons Column */}
              <div className="flex flex-col items-center gap-4 text-white">
                <button
                  onClick={() => toggleLike(selectedReel.id)}
                  className="flex flex-col items-center gap-1 group"
                >
                  <div className="w-10 h-10 rounded-full bg-black/50 backdrop-blur-md flex items-center justify-center group-hover:bg-[#913638] transition-colors">
                    <Heart
                      className={`w-5 h-5 ${
                        likedReels[selectedReel.id]
                          ? "fill-[#EFB8B0] text-[#EFB8B0]"
                          : "text-white"
                      }`}
                    />
                  </div>
                  <span className="text-[10px] font-semibold">
                    {likedReels[selectedReel.id] ? "Liked" : selectedReel.likes}
                  </span>
                </button>

                <button className="flex flex-col items-center gap-1 group">
                  <div className="w-10 h-10 rounded-full bg-black/50 backdrop-blur-md flex items-center justify-center group-hover:bg-[#913638] transition-colors">
                    <MessageCircle className="w-5 h-5 text-white" />
                  </div>
                  <span className="text-[10px] font-semibold">
                    {selectedReel.comments}
                  </span>
                </button>

                <button
                  onClick={() => toggleSave(selectedReel.id)}
                  className="flex flex-col items-center gap-1 group"
                >
                  <div className="w-10 h-10 rounded-full bg-black/50 backdrop-blur-md flex items-center justify-center group-hover:bg-[#913638] transition-colors">
                    <Bookmark
                      className={`w-5 h-5 ${
                        savedReels[selectedReel.id]
                          ? "fill-[#EFB8B0] text-[#EFB8B0]"
                          : "text-white"
                      }`}
                    />
                  </div>
                  <span className="text-[10px] font-semibold">Save</span>
                </button>

                <button className="flex flex-col items-center gap-1 group">
                  <div className="w-10 h-10 rounded-full bg-black/50 backdrop-blur-md flex items-center justify-center group-hover:bg-[#913638] transition-colors">
                    <Share2 className="w-5 h-5 text-white" />
                  </div>
                  <span className="text-[10px] font-semibold">Share</span>
                </button>
              </div>
            </div>

            {/* Simulated Video Progress Bar */}
            <div className="relative z-20 w-full bg-white/20 h-1 rounded-full overflow-hidden mt-3">
              <div
                className={`bg-[#EFB8B0] h-full ${
                  isPlaying ? "w-3/4 animate-pulse" : "w-1/2"
                }`}
              />
            </div>

            {/* Left / Right Carousel Navigation Arrows */}
            <button
              onClick={handlePrevReel}
              className="absolute -left-12 sm:-left-16 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/20 hover:bg-white text-white hover:text-[#913638] backdrop-blur-md flex items-center justify-center transition-all hidden sm:flex"
              aria-label="Previous reel"
            >
              <ChevronLeft className="w-6 h-6" />
            </button>
            <button
              onClick={handleNextReel}
              className="absolute -right-12 sm:-right-16 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/20 hover:bg-white text-white hover:text-[#913638] backdrop-blur-md flex items-center justify-center transition-all hidden sm:flex"
              aria-label="Next reel"
            >
              <ChevronRight className="w-6 h-6" />
            </button>
          </div>
        </div>
      )}
    </section>
  );
}
