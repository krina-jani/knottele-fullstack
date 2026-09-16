"use client";

import React, { createContext, useContext, useState, useEffect, useCallback, useRef } from "react";
import { fetchHomepageMedia, HomepageMedia } from "@/lib/api";
import { useClientCache, setupAdminSyncListener } from "@/lib/cache";

const MEDIA_CACHE_KEY = "knotelle_cache_media";

interface MediaContextType {
  media: HomepageMedia | null;
  loading: boolean;
  lastUpdated: number;
  refetchMedia: () => Promise<void>;
}

const MediaContext = createContext<MediaContextType | undefined>(undefined);

export function MediaProvider({
  children,
  initialMedia = null,
}: {
  children: React.ReactNode;
  initialMedia?: HomepageMedia | null;
}) {
  const [cachedMedia, setCachedMedia] = useClientCache<HomepageMedia | null>(
    MEDIA_CACHE_KEY,
    initialMedia ?? null
  );

  const media = cachedMedia || initialMedia || null;
  const [loading, setLoading] = useState<boolean>(!media);
  const [lastUpdated, setLastUpdated] = useState<number>(Date.now());
  const isFetchingRef = useRef(false);

  const refetchMedia = useCallback(async () => {
    if (isFetchingRef.current) return;
    isFetchingRef.current = true;
    try {
      const data = await fetchHomepageMedia();
      if (data) {
        setCachedMedia(data);
        setLastUpdated(Date.now());
      }
    } catch (err) {
      console.warn("Live media sync notice:", err);
    } finally {
      isFetchingRef.current = false;
      setLoading(false);
    }
  }, [setCachedMedia]);

  useEffect(() => {
    // 1. Initial live fetch to ensure cache freshness
    refetchMedia();

    // 2. Setup unified real-time admin sync listeners (BroadcastChannel, storage event, visibility, focus)
    const cleanupListeners = setupAdminSyncListener(() => {
      refetchMedia();
    });

    // 3. Fallback background polling (every 18 seconds when visible)
    const pollTimer = setInterval(() => {
      if (document.visibilityState === "visible") {
        refetchMedia();
      }
    }, 18000);

    return () => {
      cleanupListeners();
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
