"use client";

import React, { useState, useEffect, useRef, useMemo } from "react";
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
  Maximize2,
} from "lucide-react";
import { InstagramIcon } from "@/components/ui/BotanicalDecorations";
import { REEL_POSTS } from "@/data/blogReels";
import { fetchHomepageMedia, recordVideoView, normalizeImageUrl, getApiBaseUrl } from "@/lib/api";

function normalizeVideoUrl(url?: string | null): string {
  if (!url) return "";
  if (url.startsWith("http://") || url.startsWith("https://") || url.startsWith("data:") || url.startsWith("blob:")) {
    return url;
  }
  const clean = url.startsWith("/") ? url : `/${url}`;
  return `${getApiBaseUrl()}${clean}`;
}

import { useWebsiteMedia } from "@/context/MediaContext";

interface ReelItemType {
  id: string;
  db_id?: number;
  title: string;
  subtitle: string;
  description?: string;
  caption?: string;
  category: string;
  duration: string;
  views: string;
  likes: string;
  comments: string;
  thumbnail: string;
  mobile_thumbnail?: string;
  videoUrl?: string;
  video_url?: string;
  audioTrack: string;
  author: {
    name: string;
    role: string;
    avatar: string;
  };
  tags?: string[];
}

export function BlogReels() {
  const { media } = useWebsiteMedia();
  const br = media?.blogReels;

  const sectionMeta = useMemo(() => ({
    title: br?.title || "Behind the Stitches",
    subtitle: br?.subtitle || "Watch our artisans hand-craft each creation, styling guides, and cozy studio ASMR unboxings.",
    tag_text: br?.tag_text || "Studio Journal & Video Reels",
    cta_text: br?.cta_text || "Follow @knotelleindia",
    cta_link: br?.cta_link || "https://instagram.com/knotelleindia",
    is_active: br?.is_active !== false,
  }), [br]);

  const reelsList = useMemo<ReelItemType[]>(() => {
    if (br && Array.isArray(br.items)) {
      if (br.items.length === 0) return [];
      return br.items.map((item) => {
        const vUrl = normalizeVideoUrl(item.video_url || item.videoUrl);
        return {
          id: String(item.id),
          db_id: item.db_id,
          title: item.title,
          subtitle: item.subtitle || "",
          description: item.description || "",
          caption: item.caption || item.description || item.subtitle || item.title,
          category: item.category || "Studio ASMR",
          duration: item.duration || "00:48",
          views: String(item.views || "0"),
          likes: String(item.likes || "0"),
          comments: String(item.comments || "0"),
          thumbnail: normalizeImageUrl(item.thumbnail, "/images/homepage/middleimg.png"),
          mobile_thumbnail: item.mobile_thumbnail ? normalizeImageUrl(item.mobile_thumbnail) : undefined,
          videoUrl: vUrl,
          video_url: vUrl,
          audioTrack: item.audio_track || item.audioTrack || item.audio_name || "Original Audio",
          author: item.author || {
            name: "Krina Jani",
            role: "Lead Artisan",
            avatar: "https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200&auto=format&fit=crop",
          },
          tags: item.tags || ["#Crochet", "#Handmade", "#Knotelle"],
        };
      });
    }
    return REEL_POSTS;
  }, [br]);

  const [selectedReel, setSelectedReel] = useState<ReelItemType | null>(null);
  const [isPlaying, setIsPlaying] = useState<boolean>(true);
  const [isMuted, setIsMuted] = useState<boolean>(false);
  const [currentTime, setCurrentTime] = useState<number>(0);
  const [videoDuration, setVideoDuration] = useState<number>(0);
  const [likedReels, setLikedReels] = useState<Record<string, boolean>>({});
  const [savedReels, setSavedReels] = useState<Record<string, boolean>>({});

  const videoRef = useRef<HTMLVideoElement | null>(null);
  const recordedViewsRef = useRef<Record<string, boolean>>({});

  // Handle Play/Pause when video element or selected reel changes
  useEffect(() => {
    if (selectedReel && videoRef.current) {
      if (isPlaying) {
        videoRef.current.play().catch(() => {
          // Autoplay fallback (often muted required)
          if (videoRef.current) {
            videoRef.current.muted = true;
            setIsMuted(true);
            videoRef.current.play().catch(() => {});
          }
        });
      } else {
        videoRef.current.pause();
      }
    }
  }, [isPlaying, selectedReel]);

  // Video view increment trigger
  const handleOpenReel = (reel: ReelItemType) => {
    setSelectedReel(reel);
    setIsPlaying(true);
    setCurrentTime(0);

    // Record view in backend once per session per reel
    if (reel.id && !recordedViewsRef.current[reel.id]) {
      recordedViewsRef.current[reel.id] = true;
      recordVideoView(reel.db_id || reel.id);
    }
  };

  const handleCloseReel = () => {
    if (videoRef.current) {
      videoRef.current.pause();
    }
    setSelectedReel(null);
    setIsPlaying(false);
  };

  const togglePlayPause = () => {
    setIsPlaying((prev) => !prev);
  };

  const toggleMute = () => {
    if (videoRef.current) {
      videoRef.current.muted = !isMuted;
    }
    setIsMuted((prev) => !prev);
  };

  const toggleLike = (id: string, e?: React.MouseEvent) => {
    e?.stopPropagation();
    setLikedReels((prev) => ({ ...prev, [id]: !prev[id] }));
  };

  const toggleSave = (id: string, e?: React.MouseEvent) => {
    e?.stopPropagation();
    setSavedReels((prev) => ({ ...prev, [id]: !prev[id] }));
  };

  const handleNextReel = (e?: React.MouseEvent) => {
    e?.stopPropagation();
    if (!selectedReel || reelsList.length === 0) return;
    const currentIndex = reelsList.findIndex((r: ReelItemType) => r.id === selectedReel.id);
    const nextIndex = (currentIndex + 1) % reelsList.length;
    setSelectedReel(reelsList[nextIndex]);
    setIsPlaying(true);
    setCurrentTime(0);

    const nextId = reelsList[nextIndex].id;
    if (nextId && !recordedViewsRef.current[nextId]) {
      recordedViewsRef.current[nextId] = true;
      recordVideoView(reelsList[nextIndex].db_id || nextId);
    }
  };

  const handlePrevReel = (e?: React.MouseEvent) => {
    e?.stopPropagation();
    if (!selectedReel || reelsList.length === 0) return;
    const currentIndex = reelsList.findIndex((r: ReelItemType) => r.id === selectedReel.id);
    const prevIndex = (currentIndex - 1 + reelsList.length) % reelsList.length;
    setSelectedReel(reelsList[prevIndex]);
    setIsPlaying(true);
    setCurrentTime(0);

    const prevId = reelsList[prevIndex].id;
    if (prevId && !recordedViewsRef.current[prevId]) {
      recordedViewsRef.current[prevId] = true;
      recordVideoView(reelsList[prevIndex].db_id || prevId);
    }
  };

  const handleTimeUpdate = () => {
    if (videoRef.current) {
      setCurrentTime(videoRef.current.currentTime);
      setVideoDuration(videoRef.current.duration || 0);
    }
  };

  const handleSeek = (e: React.MouseEvent<HTMLDivElement>) => {
    e.stopPropagation();
    if (videoRef.current && videoDuration > 0) {
      const rect = e.currentTarget.getBoundingClientRect();
      const pos = (e.clientX - rect.left) / rect.width;
      videoRef.current.currentTime = pos * videoDuration;
    }
  };

  if (!sectionMeta.is_active) {
    return null;
  }

  return (
    <section className="py-14 sm:py-18 bg-[#FFF9F6] relative overflow-hidden" id="behind-the-stitches">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {/* Section Header */}
        <div className="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-10">
          <div>
            <div className="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#FCE9E5] text-[#913638] text-xs font-semibold mb-2.5">
              <InstagramIcon className="w-3.5 h-3.5" />
              <span>{sectionMeta.tag_text || "Studio Journal & Video Reels"}</span>
            </div>
            <h2 className="font-serif-luxury text-2xl sm:text-3xl lg:text-4xl font-bold text-[#2E211E]">
              {sectionMeta.title || "Behind the Stitches"}
            </h2>
            <p className="text-xs sm:text-sm text-[#786864] mt-1.5 max-w-xl">
              {sectionMeta.subtitle || "Watch our artisans hand-craft each creation, styling guides, and cozy studio ASMR unboxings."}
            </p>
          </div>

          {sectionMeta.cta_link && (
            <a
              href={sectionMeta.cta_link}
              target="_blank"
              rel="noopener noreferrer"
              className="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-white border border-[#E7D1CC] text-xs font-semibold text-[#913638] hover:bg-[#913638] hover:text-white shadow-xs transition-all w-fit active:scale-[0.98]"
            >
              <Sparkles className="w-3.5 h-3.5" />
              <span>{sectionMeta.cta_text || "Follow @knotelleindia"}</span>
            </a>
          )}
        </div>

        {/* Reel Video Cards Grid or Clean Empty State */}
        {reelsList.length > 0 ? (
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            {reelsList.map((reel: ReelItemType) => {
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

                    <div className="pt-2 border-t border-white/15 flex items-center justify-between text-white/90">
                      <div className="flex items-center gap-3 text-xs">
                        <button
                          type="button"
                          onClick={(e) => toggleLike(reel.id, e)}
                          className="flex items-center gap-1 hover:text-[#EFB8B0] transition-colors"
                        >
                          <Heart
                            className={`w-3.5 h-3.5 ${
                              isLiked ? "fill-red-500 text-red-500" : ""
                            }`}
                          />
                          <span className="text-[10px] font-medium">
                            {reel.likes}
                          </span>
                        </button>
                        <div className="flex items-center gap-1">
                          <MessageCircle className="w-3.5 h-3.5" />
                          <span className="text-[10px] font-medium">
                            {reel.comments}
                          </span>
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
        ) : (
          <div className="p-12 text-center rounded-3xl bg-[#FFF9F6] border border-dashed border-[#E7D1CC] text-[#786864]">
            <Sparkles className="w-8 h-8 mx-auto mb-2 text-[#913638] opacity-60" />
            <p className="font-serif-luxury font-bold text-lg text-[#3A211D]">Fresh Stitches Coming Soon</p>
            <p className="text-xs text-[#786864] mt-1">Our artisans are recording new behind-the-scenes reels. Check back shortly!</p>
          </div>
        )}

      </div>

      {/* Interactive Reel Real Video Player Modal */}
      {selectedReel && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-6 bg-black/85 backdrop-blur-md animate-fadeIn">
          {/* Backdrop Click to Close */}
          <div
            className="absolute inset-0"
            onClick={handleCloseReel}
            aria-hidden="true"
          />

          {/* Modal Container */}
          <div className="relative z-10 w-full max-w-sm sm:max-w-md aspect-[9/16] max-h-[92vh] bg-black rounded-3xl overflow-hidden shadow-2xl border border-white/20 flex flex-col justify-between p-5">
            
            {/* Real Video Element or Poster Fallback */}
            {selectedReel.videoUrl || selectedReel.video_url ? (
              <video
                ref={videoRef}
                src={selectedReel.videoUrl || selectedReel.video_url}
                poster={selectedReel.thumbnail}
                playsInline
                autoPlay
                loop
                muted={isMuted}
                onTimeUpdate={handleTimeUpdate}
                onClick={togglePlayPause}
                className="absolute inset-0 w-full h-full object-cover cursor-pointer"
              />
            ) : (
              <Image
                src={selectedReel.thumbnail}
                alt={selectedReel.title}
                fill
                className="object-cover"
                priority
              />
            )}

            {/* Gradient Overlay for Legibility */}
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
                  onClick={toggleMute}
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
              onClick={togglePlayPause}
              className={`relative z-20 self-center my-auto w-16 h-16 rounded-full bg-black/40 backdrop-blur-md border border-white/40 text-white flex items-center justify-center hover:scale-110 transition-all ${
                isPlaying ? "opacity-0 hover:opacity-100" : "opacity-100 scale-105"
              }`}
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
                <p className="text-xs text-white/90 leading-relaxed line-clamp-3">
                  {selectedReel.caption}
                </p>
                <div className="flex items-center gap-1.5 text-[10px] text-[#EFB8B0]">
                  <Music2 className="w-3 h-3 shrink-0" />
                  <span className="truncate">{selectedReel.audioTrack}</span>
                </div>
              </div>

              {/* Action Buttons Column */}
              <div className="flex flex-col items-center gap-3.5 text-white shrink-0">
                <button
                  onClick={(e) => toggleLike(selectedReel.id, e)}
                  className="flex flex-col items-center gap-1 group cursor-pointer"
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

                <button className="flex flex-col items-center gap-1 group cursor-pointer">
                  <div className="w-10 h-10 rounded-full bg-black/50 backdrop-blur-md flex items-center justify-center group-hover:bg-[#913638] transition-colors">
                    <MessageCircle className="w-5 h-5 text-white" />
                  </div>
                  <span className="text-[10px] font-semibold">
                    {selectedReel.comments}
                  </span>
                </button>

                <button
                  onClick={(e) => toggleSave(selectedReel.id, e)}
                  className="flex flex-col items-center gap-1 group cursor-pointer"
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

                <button 
                  onClick={() => {
                    if (navigator.share) {
                      navigator.share({
                        title: selectedReel.title,
                        text: selectedReel.subtitle,
                        url: window.location.href,
                      }).catch(() => {});
                    }
                  }}
                  className="flex flex-col items-center gap-1 group cursor-pointer"
                >
                  <div className="w-10 h-10 rounded-full bg-black/50 backdrop-blur-md flex items-center justify-center group-hover:bg-[#913638] transition-colors">
                    <Share2 className="w-5 h-5 text-white" />
                  </div>
                  <span className="text-[10px] font-semibold">Share</span>
                </button>
              </div>
            </div>

            {/* Interactive Video Timeline / Progress Bar */}
            <div 
              onClick={handleSeek}
              className="relative z-20 w-full bg-white/20 hover:bg-white/30 h-1.5 rounded-full overflow-hidden mt-3 cursor-pointer transition-all"
            >
              <div
                className="bg-[#EFB8B0] h-full rounded-full transition-all duration-100"
                style={{
                  width: videoDuration > 0 ? `${(currentTime / videoDuration) * 100}%` : isPlaying ? "65%" : "30%",
                }}
              />
            </div>

            {/* Left / Right Carousel Navigation Arrows */}
            <button
              onClick={handlePrevReel}
              className="absolute -left-12 sm:-left-16 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/20 hover:bg-white text-white hover:text-[#913638] backdrop-blur-md flex items-center justify-center transition-all hidden sm:flex cursor-pointer"
              aria-label="Previous reel"
            >
              <ChevronLeft className="w-6 h-6" />
            </button>
            <button
              onClick={handleNextReel}
              className="absolute -right-12 sm:-right-16 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/20 hover:bg-white text-white hover:text-[#913638] backdrop-blur-md flex items-center justify-center transition-all hidden sm:flex cursor-pointer"
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
