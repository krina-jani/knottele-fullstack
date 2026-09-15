"use client";

import React, { useEffect, useState } from "react";
import Image from "next/image";
import Link from "next/link";
import {
  Heart,
  Sparkles,
  Flower2,
  Leaf,
  ArrowRight,
  ShieldCheck,
  Sun,
  Star,
  Award,
  Gem,
  Feather,
  Gift,
  Smile,
} from "lucide-react";
import { FlowerIcon } from "@/components/ui/BotanicalDecorations";
import { Breadcrumbs } from "@/components/ui/Breadcrumbs";
import {
  fetchAboutPageData,
  fetchHomepageMedia,
  normalizeImageUrl,
  AboutStoryData,
  CraftPillarItem,
  CraftPillarsData,
} from "@/lib/api";

const DEFAULT_STORY: AboutStoryData = {
  badge: "The KNOTELLE Story",
  tag_text: "The KNOTELLE Story",
  title: "Every Loop Tells a Story",
  subtitle: "Handcrafted slow-made warmth from Bengaluru",
  description:
    "In a world flooded with disposable factory goods, KNOTELLE was born from a yearning for slow-made warmth. What began as a tiny home workshop in Bengaluru has grown into a vibrant collective of women artisans who share a deep love for yarn, color harmony, and delicate floral silhouettes.",
  paragraph_1:
    "In a world flooded with disposable factory goods, KNOTELLE was born from a yearning for slow-made warmth. What began as a tiny home workshop in Bengaluru has grown into a vibrant collective of women artisans who share a deep love for yarn, color harmony, and delicate floral silhouettes.",
  paragraph_2:
    "When you order a bouquet of crochet roses, a customized bunny keychain, or a granny square tote, you are not simply purchasing an object. You are welcoming hours of human patience, intention, and joy into your home.",
  desktop_image:
    "https://images.unsplash.com/photo-1544816155-12df9643f363?q=80&w=1000&auto=format&fit=crop",
  image:
    "https://images.unsplash.com/photo-1544816155-12df9643f363?q=80&w=1000&auto=format&fit=crop",
  alt_text: "Artisan stitching crochet with wooden hook",
  floating_badge: {
    title: "100% Handcrafted",
    subtitle: "Never mass machine produced",
    icon: "Heart",
    is_active: true,
  },
  cta: {
    text: "Request a Custom Creation",
    url: "/custom-order",
    link: "/custom-order",
    is_active: true,
  },
  cta_text: "Request a Custom Creation",
  cta_link: "/custom-order",
  is_active: true,
};

const DEFAULT_PILLARS: CraftPillarsData = {
  title: "Our Craft Pillars",
  subtitle: "Guiding principles behind every stitch we make.",
  badge: "Artisan Standards",
  is_active: true,
  items: [
    {
      id: 1,
      title: "Natural Materials",
      description:
        "We use 100% pure milk cotton and mercerized organic fibers that are gentle on skin and kind to mother nature.",
      icon: "Leaf",
      icon_name: "Leaf",
      sort_order: 1,
      is_active: true,
    },
    {
      id: 2,
      title: "Bespoke Atelier",
      description:
        "No two requests are alike. We collaborate with you on colors, sizing, and details to make every piece unique.",
      icon: "Sparkles",
      icon_name: "Sparkles",
      sort_order: 2,
      is_active: true,
    },
    {
      id: 3,
      title: "Empowering Artisans",
      description:
        "We provide fair wages, flexible hours, and creative dignity to our women-led artisan crafting community in India.",
      icon: "Heart",
      icon_name: "Heart",
      sort_order: 3,
      is_active: true,
    },
  ],
};

function PillarIcon({
  name,
  className = "w-6 h-6",
  iconUrl,
}: {
  name?: string;
  className?: string;
  iconUrl?: string | null;
}) {
  if (iconUrl) {
    return (
      <div className={`relative ${className}`}>
        <Image
          src={normalizeImageUrl(iconUrl)}
          alt={name || "Craft Pillar Icon"}
          fill
          className="object-contain"
        />
      </div>
    );
  }

  const normalized = (name || "").trim().toLowerCase();

  switch (normalized) {
    case "sparkles":
    case "magic":
      return <Sparkles className={className} />;
    case "heart":
    case "love":
      return <Heart className={className} />;
    case "flower":
    case "flower2":
    case "bloom":
      return <Flower2 className={className} />;
    case "shield":
    case "shieldcheck":
    case "quality":
      return <ShieldCheck className={className} />;
    case "sun":
      return <Sun className={className} />;
    case "star":
      return <Star className={className} />;
    case "award":
      return <Award className={className} />;
    case "gem":
    case "diamond":
      return <Gem className={className} />;
    case "feather":
      return <Feather className={className} />;
    case "gift":
      return <Gift className={className} />;
    case "smile":
      return <Smile className={className} />;
    case "leaf":
    default:
      return <Leaf className={className} />;
  }
}

export default function AboutPage() {
  const [story, setStory] = useState<AboutStoryData>(DEFAULT_STORY);
  const [craftPillars, setCraftPillars] = useState<CraftPillarsData>(DEFAULT_PILLARS);
  const [isLoading, setIsLoading] = useState<boolean>(true);

  const loadData = async () => {
    try {
      // 1. Try dedicated about endpoint
      const aboutData = await fetchAboutPageData();
      if (aboutData) {
        if (aboutData.story) setStory(aboutData.story);
        if (aboutData.craftPillars) setCraftPillars(aboutData.craftPillars);
        setIsLoading(false);
        return;
      }

      // 2. Fallback to homepage media structured about payload
      const mediaData = await fetchHomepageMedia();
      if (mediaData && (mediaData as any).about) {
        const ab = (mediaData as any).about;
        if (ab.story) setStory(ab.story);
        if (ab.craftPillars) setCraftPillars(ab.craftPillars);
      }
    } catch (err) {
      console.warn("Failed to load dynamic about page data:", err);
    } finally {
      setIsLoading(false);
    }
  };

  useEffect(() => {
    loadData();

    // Listen for live updates broadcast from admin panel
    const handleBroadcast = () => {
      loadData();
    };

    if (typeof window !== "undefined") {
      window.addEventListener("knotelle_media_updated", handleBroadcast);
      window.addEventListener("storage", (e) => {
        if (e.key === "knotelle_media_sync") {
          loadData();
        }
      });
    }

    return () => {
      if (typeof window !== "undefined") {
        window.removeEventListener("knotelle_media_updated", handleBroadcast);
      }
    };
  }, []);

  const storyImage = normalizeImageUrl(
    story.desktop_image || story.image,
    DEFAULT_STORY.desktop_image!
  );

  const mobileStoryImage = story.mobile_image
    ? normalizeImageUrl(story.mobile_image)
    : storyImage;

  const floatingBadge = story.floating_badge || {
    title: "100% Handcrafted",
    subtitle: "Never mass machine produced",
    icon: "Heart",
    is_active: true,
  };

  const showFloatingBadge = floatingBadge.is_active !== false;

  const ctaButton = {
    text: story.cta?.text || story.cta_text || "Request a Custom Creation",
    link: story.cta?.link || story.cta?.url || story.cta_link || "/custom-order",
    is_active: story.cta?.is_active !== false,
  };

  const activePillars = (craftPillars.items || []).filter(
    (item) => item.is_active !== false
  );

  return (
    <div className="bg-[#FFF9F6] min-h-screen py-8 lg:py-12">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Breadcrumbs */}
        <Breadcrumbs items={[{ label: "About KNOTELLE" }]} className="mb-8" />

        {/* Story Section: 2 Columns */}
        {story.is_active !== false && (
          <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center mb-16 lg:mb-20">
            {/* Story Visual Frame */}
            <div className="lg:col-span-6 relative">
              <div className="relative aspect-[4/4.5] rounded-3xl overflow-hidden border-4 border-white shadow-xl bg-white group">
                <Image
                  src={storyImage}
                  alt={story.alt_text || story.title || "The KNOTELLE Story"}
                  fill
                  sizes="(max-width: 1024px) 100vw, 50vw"
                  className="object-cover transition-transform duration-500 group-hover:scale-103"
                  priority
                />
              </div>

              {/* Floating Handcrafted Badge */}
              {showFloatingBadge && (
                <div className="absolute -bottom-4 -right-4 bg-white/95 backdrop-blur-sm p-4 rounded-2xl border border-[#E7D1CC] shadow-lg flex items-center gap-3 animate-fadeIn">
                  <div className="w-10 h-10 rounded-xl bg-[#FCE9E5] text-[#913638] flex items-center justify-center font-bold">
                    <PillarIcon
                      name={floatingBadge.icon || "Heart"}
                      className="w-5 h-5 text-[#913638]"
                    />
                  </div>
                  <div>
                    <p className="text-xs font-bold text-[#2E211E]">
                      {floatingBadge.title || "100% Handcrafted"}
                    </p>
                    <p className="text-[10px] text-[#786864]">
                      {floatingBadge.subtitle || "Never mass machine produced"}
                    </p>
                  </div>
                </div>
              )}
            </div>

            {/* Story Narrative & Call To Action */}
            <div className="lg:col-span-6 space-y-6">
              <div className="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#FCE9E5] text-[#913638] text-xs font-semibold border border-[#E7D1CC]">
                <FlowerIcon className="w-3.5 h-3.5" />
                <span>{story.tag_text || story.badge || "The KNOTELLE Story"}</span>
              </div>

              <h1 className="font-serif-luxury text-3xl sm:text-4xl font-bold text-[#2E211E] tracking-tight">
                {story.title || "Every Loop Tells a Story"}
              </h1>

              {story.subtitle && (
                <p className="text-xs font-medium text-[#913638] tracking-wide -mt-2">
                  {story.subtitle}
                </p>
              )}

              <p className="text-sm text-[#786864] leading-relaxed">
                {story.paragraph_1 || story.description}
              </p>

              {(story.paragraph_2 || story.secondary_description) && (
                <p className="text-sm text-[#786864] leading-relaxed">
                  {story.paragraph_2 || story.secondary_description}
                </p>
              )}

              {ctaButton.is_active && ctaButton.text && (
                <div className="pt-2">
                  <Link
                    href={ctaButton.link}
                    className="inline-flex items-center gap-2 px-7 py-3.5 rounded-full bg-[#913638] text-white text-xs sm:text-sm font-semibold hover:bg-[#74292B] active:scale-[0.98] transition-all shadow-sm"
                  >
                    <span>{ctaButton.text}</span>
                    <ArrowRight className="w-3.5 h-3.5" />
                  </Link>
                </div>
              )}
            </div>
          </div>
        )}

        {/* Our Craft Pillars Dynamic Grid */}
        {craftPillars.is_active !== false && activePillars.length > 0 && (
          <div className="mb-16 lg:mb-20">
            <div className="text-center max-w-xl mx-auto mb-10">
              {craftPillars.badge && (
                <span className="inline-block px-3 py-1 mb-2.5 rounded-full text-xs font-bold bg-[#FCE9E5] text-[#913638] border border-[#E7D1CC]">
                  {craftPillars.badge}
                </span>
              )}
              <h2 className="font-serif-luxury text-3xl font-bold text-[#2E211E]">
                {craftPillars.title || "Our Craft Pillars"}
              </h2>
              {craftPillars.subtitle && (
                <p className="text-xs sm:text-sm text-[#786864] mt-1.5">
                  {craftPillars.subtitle}
                </p>
              )}
            </div>

            {/* Dynamic Grid: 1 col on mobile, 2 cols on tablet, 3-4 cols on desktop */}
            <div
              className={`grid grid-cols-1 ${
                activePillars.length === 2
                  ? "sm:grid-cols-2 max-w-3xl mx-auto"
                  : activePillars.length === 4
                  ? "sm:grid-cols-2 lg:grid-cols-4"
                  : activePillars.length > 4
                  ? "sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
                  : "md:grid-cols-3"
              } gap-6`}
            >
              {activePillars.map((pillar, idx) => (
                <div
                  key={pillar.id || idx}
                  className="p-6 sm:p-8 rounded-3xl bg-white border border-[#E7D1CC] shadow-boutique text-center space-y-3.5 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl flex flex-col justify-between"
                >
                  <div>
                    <div className="w-14 h-14 rounded-2xl bg-[#FCE9E5] border border-[#E7D1CC] flex items-center justify-center text-[#913638] mx-auto mb-4 shadow-2xs">
                      <PillarIcon
                        name={pillar.icon_name || pillar.icon || "Leaf"}
                        iconUrl={pillar.icon_url}
                        className="w-6 h-6 text-[#913638]"
                      />
                    </div>
                    <h3 className="font-serif-luxury text-xl font-bold text-[#2E211E]">
                      {pillar.title}
                    </h3>
                    <p className="text-xs text-[#786864] leading-relaxed mt-2">
                      {pillar.description}
                    </p>
                  </div>
                </div>
              ))}
            </div>
          </div>
        )}
      </div>
    </div>
  );
}
