/**
 * KNOTELLE Instant Local Cache & Real-Time Admin Sync Utility
 * 
 * Provides synchronous client-side retrieval of last-known admin & media data
 * using React 18/19 useSyncExternalStore to eliminate hydration mismatches while
 * preventing any flash of default hardcoded content on page refresh.
 */

import { useSyncExternalStore } from "react";

const inMemoryCache: Record<string, any> = {};

/**
 * Synchronously retrieves data from localStorage or memory cache.
 * Safe for SSR (returns fallback on server).
 */
export function getLocalCache<T>(key: string, fallback: T): T {
  if (typeof window === "undefined") {
    return fallback;
  }

  // 1. Check in-memory cache first
  if (inMemoryCache[key] !== undefined) {
    return inMemoryCache[key] as T;
  }

  // 2. Check localStorage
  try {
    const raw = localStorage.getItem(key);
    if (raw) {
      const parsed = JSON.parse(raw);
      inMemoryCache[key] = parsed;
      return parsed as T;
    }
  } catch (err) {
    // Ignore JSON parse errors or quota issues
  }

  return fallback;
}

/**
 * Stores data into both memory and localStorage, and dispatches a local event for instant updates.
 */
export function setLocalCache<T>(key: string, data: T): void {
  inMemoryCache[key] = data;

  if (typeof window === "undefined") {
    return;
  }

  try {
    localStorage.setItem(key, JSON.stringify(data));
    window.dispatchEvent(new CustomEvent("knotelle_cache_updated", { detail: { key } }));
  } catch (err) {
    // Ignore quota issues
  }
}

/**
 * React 18/19 hook for reading and subscribing to client local cache safely without hydration mismatches.
 */
export function useClientCache<T>(key: string, fallback: T): [T, (data: T) => void] {
  const subscribe = (onStoreChange: () => void) => {
    if (typeof window === "undefined") return () => {};
    const handleStorage = (e: StorageEvent) => {
      if (
        e.key === key ||
        e.key === "knotelle_media_updated" ||
        e.key === "knotelle_contact_updated" ||
        e.key === "knotelle_about_updated" ||
        e.key === "knotelle_media_sync"
      ) {
        onStoreChange();
      }
    };
    const handleCustom = (e: Event) => {
      const detail = (e as CustomEvent)?.detail;
      if (!detail || detail.key === key) {
        onStoreChange();
      }
    };
    window.addEventListener("storage", handleStorage);
    window.addEventListener("knotelle_cache_updated", handleCustom);
    return () => {
      window.removeEventListener("storage", handleStorage);
      window.removeEventListener("knotelle_cache_updated", handleCustom);
    };
  };

  const getSnapshot = () => getLocalCache<T>(key, fallback);
  const getServerSnapshot = () => fallback;

  const value = useSyncExternalStore(subscribe, getSnapshot, getServerSnapshot);

  const setValue = (data: T) => {
    setLocalCache(key, data);
  };

  return [value, setValue];
}

/**
 * Sets up unified real-time listeners across:
 * 1. BroadcastChannel ('knotelle_media_sync')
 * 2. Window Storage Events ('knotelle_media_updated')
 * 3. Tab focus & visibility change
 * 
 * Returns an unbind cleanup function.
 */
export function setupAdminSyncListener(onUpdate: () => void): () => void {
  if (typeof window === "undefined") {
    return () => {};
  }

  let channel: BroadcastChannel | null = null;
  if ("BroadcastChannel" in window) {
    try {
      channel = new BroadcastChannel("knotelle_media_sync");
      channel.onmessage = (event) => {
        const type = event?.data?.type || "";
        if (
          type === "MEDIA_UPDATED" ||
          type === "knotelle_media_updated" ||
          type === "knotelle_contact_updated" ||
          type === "knotelle_about_updated" ||
          type === "KNOTELLE_SYNC"
        ) {
          onUpdate();
        }
      };
    } catch (err) {
      console.warn("BroadcastChannel error:", err);
    }
  }

  const handleStorage = (e: StorageEvent) => {
    if (
      e.key === "knotelle_media_updated" ||
      e.key === "knotelle_media_sync" ||
      e.key?.startsWith("knotelle_cache_")
    ) {
      onUpdate();
    }
  };

  const handleVisibility = () => {
    if (document.visibilityState === "visible") {
      onUpdate();
    }
  };

  window.addEventListener("storage", handleStorage);
  window.addEventListener("focus", onUpdate);
  document.addEventListener("visibilitychange", handleVisibility);

  return () => {
    if (channel) {
      channel.close();
    }
    window.removeEventListener("storage", handleStorage);
    window.removeEventListener("focus", onUpdate);
    document.removeEventListener("visibilitychange", handleVisibility);
  };
}
