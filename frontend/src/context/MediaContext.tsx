"use client";

import React, { createContext, useContext, useState, useEffect, useCallback, useRef } from "react";
import { fetchHomepageMedia, HomepageMedia } from "@/lib/api";

interface MediaContextType {
  media: HomepageMedia | null;
  loading: boolean;
  lastUpdated: number;
  refetchMedia: () => Promise<void>;
}

const MediaContext = createContext<MediaContextType | undefined>(undefined);

export function MediaProvider({ children }: { children: React.ReactNode }) {
  const [media, setMedia] = useState<HomepageMedia | null>(null);
  const [loading, setLoading] = useState<boolean>(true);
  const [lastUpdated, setLastUpdated] = useState<number>(Date.now());
  const isFetchingRef = useRef(false);

  const refetchMedia = useCallback(async () => {
    if (isFetchingRef.current) return;
    isFetchingRef.current = true;
    try {
      const data = await fetchHomepageMedia();
      if (data) {
        setMedia(data);
        setLastUpdated(Date.now());
      }
    } catch (err) {
      console.warn("Live media sync notice:", err);
    } finally {
      isFetchingRef.current = false;
      setLoading(false);
    }
  }, []);

  useEffect(() => {
    // 1. Initial fetch on mount
    refetchMedia();

    // 2. Cross-tab BroadcastChannel listener (fires immediately when admin saves anything)
    let channel: BroadcastChannel | null = null;
    if (typeof window !== "undefined" && "BroadcastChannel" in window) {
      try {
        channel = new BroadcastChannel("knotelle_media_sync");
        channel.onmessage = (event) => {
          if (event?.data?.type === "MEDIA_UPDATED") {
            refetchMedia();
          }
        };
      } catch (err) {
        console.warn("BroadcastChannel initialization notice:", err);
      }
    }

    // 3. Storage event listener (fires across tabs on same origin when admin updates)
    const handleStorageChange = (e: StorageEvent) => {
      if (e.key === "knotelle_media_updated") {
        refetchMedia();
      }
    };
    window.addEventListener("storage", handleStorageChange);

    // 4. Window focus & tab visibility change (instant refresh when user switches tabs)
    const handleFocus = () => {
      refetchMedia();
    };
    const handleVisibilityChange = () => {
      if (document.visibilityState === "visible") {
        refetchMedia();
      }
    };
    window.addEventListener("focus", handleFocus);
    document.addEventListener("visibilitychange", handleVisibilityChange);

    // 5. Automatic background polling (guarantees cross-origin live updates within 4s without click)
    const pollTimer = setInterval(() => {
      refetchMedia();
    }, 4000);

    return () => {
      if (channel) {
        channel.close();
      }
      window.removeEventListener("storage", handleStorageChange);
      window.removeEventListener("focus", handleFocus);
      document.removeEventListener("visibilitychange", handleVisibilityChange);
      clearInterval(pollTimer);
    };
  }, [refetchMedia]);

  return (
    <MediaContext.Provider value={{ media, loading, lastUpdated, refetchMedia }}>
      {children}
    </MediaContext.Provider>
  );
}

export function useWebsiteMedia() {
  const context = useContext(MediaContext);
  if (!context) {
    throw new Error("useWebsiteMedia must be used within a MediaProvider");
  }
  return context;
}
