/**
 * KNOTELLE API Client
 * Provides seamless connection between Next.js frontend and Laravel backend.
 */

import { Product } from "@/types/product";

export const getApiBaseUrl = () => {
  if (process.env.NEXT_PUBLIC_API_URL) {
    return process.env.NEXT_PUBLIC_API_URL;
  }
  if (typeof window !== "undefined") {
    if (window.location.port === "3000") {
      return "http://127.0.0.1:8000/api";
    }
    return "/api";
  }
  return "http://127.0.0.1:8000/api";
};

export interface ContactFormData {
  name: string;
  email: string;
  phone?: string;
  subject?: string;
  message: string;
}

export interface ContactSettingsData {
  studio_name?: string;
  studio_badge?: string;
  address_line_1: string;
  address_line_2?: string;
  phone: string;
  hours: string;
  email: string;
  response_time: string;
}

export interface ApiCategory {
  id: string;
  db_id?: number;
  name: string;
  slug: string;
  description: string;
  image: string;
  featured: boolean;
  priceFrom: string;
  itemCount: number;
}

export interface HomepageMedia {
  hero: {
    desktop: string;
    mobile: string;
    slides: Array<{
      id: number;
      title: string;
      tagline?: string;
      subtitle?: string;
      description?: string;
      primary_button_text?: string;
      primary_button_link?: string;
      secondary_button_text?: string;
      secondary_button_link?: string;
      cta_text?: string;
      cta_link?: string;
      secondary_cta_text?: string;
      secondary_cta_link?: string;
      tag_text?: string;
      alt: string;
      desktop: string;
      desktop_image?: string;
      mobile: string;
      mobile_image?: string;
      status?: boolean;
      is_active?: boolean;
      sort_order: number;
    }>;
  };
  categories: ApiCategory[];
  categorySection?: {
    tag_text?: string;
    title?: string;
    subtitle?: string;
    description?: string;
    is_active?: boolean;
  };
  customCrochet: {
    desktop: string;
    mobile: string;
    title?: string;
    subtitle?: string;
    description?: string;
    cta_text?: string;
    cta_link?: string;
    tag_text?: string;
    tag_active?: boolean;
    alt_text?: string;
    is_active?: boolean;
  };
  brandStory: {
    desktop: string;
    mobile: string;
    title?: string;
    subtitle?: string;
    description?: string;
    cta_text?: string;
    cta_link?: string;
    is_active?: boolean;
  };
  customOrder: {
    desktop: string | null;
    mobile: string | null;
    badge?: string;
    title_line1?: string;
    title_line2?: string;
    description?: string;
    cta_text?: string;
    cta_link?: string;
    is_active?: boolean;
  };
  newsletter: {
    bg: string | null;
    left: string | null;
    right: string | null;
    title?: string;
    subtitle?: string;
  };
  footer: {
    bg: string;
    image?: string;
    left?: string | null;
    right?: string | null;
    title?: string;
    subtitle?: string;
    tagline?: string;
    copyright_text?: string;
    heart_tagline?: string;
    social?: {
      instagram?: { url: string; is_active: boolean };
      facebook?: { url: string; is_active: boolean };
      pinterest?: { url: string; is_active: boolean };
      youtube?: { url: string; is_active: boolean };
    };
    column_1?: {
      title: string;
      links: Array<{ label?: string; name?: string; url?: string; href?: string; is_active?: boolean }>;
    };
    column_2?: {
      title: string;
      links: Array<{ label?: string; name?: string; url?: string; href?: string; is_active?: boolean }>;
    };
    column_3?: {
      title: string;
      phone?: string;
      phone_link?: string;
      email?: string;
      email_link?: string;
      address?: string;
      address_link?: string;
    };
    is_active?: boolean;
  };
  navbar?: {
    logo?: string;
    announcement?: {
      text: string;
      link?: string;
      is_active: boolean;
    };
    nav_links?: Array<{
      name: string;
      href: string;
      is_highlighted?: boolean;
      is_active?: boolean;
      sort_order?: number;
    }>;
    actions?: {
      show_search?: boolean;
      show_wishlist?: boolean;
      show_account?: boolean;
      show_cart?: boolean;
    };
    is_active?: boolean;
  };
  global: {
    logo: string;
    mobileLogo: string;
    favicon: string;
    defaultProduct: string;
    defaultCategory: string;
  };
  shop?: {
    banner?: {
      desktop?: string | null;
      mobile?: string | null;
      title?: string;
      subtitle?: string;
      tag_text?: string;
      cta_text?: string | null;
      cta_link?: string | null;
      is_active?: boolean;
    };
    promo?: {
      banner?: string | null;
      title?: string;
      subtitle?: string;
      cta_text?: string;
      cta_link?: string;
      is_active?: boolean;
    };
  };
  about?: {
    hero?: {
      image?: string;
      title?: string;
      subtitle?: string;
      description?: string;
      badge?: string;
      cta_text?: string;
      cta_link?: string;
      is_active?: boolean;
    };
    studio?: {
      image?: string | null;
      title?: string;
      subtitle?: string;
    };
  };
  contact?: {
    banner?: {
      image?: string | null;
      title?: string;
      subtitle?: string;
      tag_text?: string;
    };
    studio?: {
      image?: string | null;
      title?: string;
      subtitle?: string;
    };
  };
  blogReels?: {
    title?: string;
    subtitle?: string;
    tag_text?: string;
    cta_text?: string;
    cta_link?: string;
    is_active?: boolean;
    items: Array<{
      id: string;
      db_id?: number;
      title: string;
      subtitle: string;
      description?: string;
      caption?: string;
      content_type: "reel" | "video" | "blog_video" | string;
      category: string;
      thumbnail: string;
      mobile_thumbnail?: string;
      video_url: string;
      videoUrl?: string;
      duration: string;
      audio_name?: string;
      audio_track?: string;
      audioTrack?: string;
      likes: string | number;
      likes_count?: number;
      comments: string | number;
      comments_count?: number;
      views: string | number;
      views_count?: number;
      is_featured?: boolean;
      sort_order?: number;
      author?: {
        name: string;
        role: string;
        avatar: string;
      };
      tags?: string[];
    }>;
  };
  testimonials?: Array<{
    id: number | string;
    name: string;
    designation?: string;
    message: string;
    rating: number;
    image?: string;
    is_active?: boolean;
  }>;
  customOrderItems?: Array<{
    id: number;
    name: string;
    title?: string;
    subtitle?: string;
    sort_order?: number;
    is_active?: boolean;
  }>;
}

export function normalizeImageUrl(url?: string | null, fallback = "/images/logo/Logo_1.png"): string {
  if (!url) return fallback;
  if (url.startsWith("http://") || url.startsWith("https://") || url.startsWith("data:")) {
    return url;
  }
  const clean = url.startsWith("/") ? url : `/${url}`;
  if (clean.startsWith("/images/") && typeof window !== "undefined" && !window.location.origin.includes(":8000")) {
    return `http://127.0.0.1:8000${clean}`;
  }
  return clean;
}

export interface ApiProduct {
  id: number | string;
  slug: string;
  name: string;
  short_description?: string;
  description?: string;
  main_image?: string;
  images?: Array<string | { id?: number; url: string; is_primary?: boolean }>;
  price: number;
  compare_price?: number | null;
  tax_rate?: number;
  discount_percent?: number;
  is_in_stock?: boolean;
  stock_quantity?: number;
  sku?: string;
  brand?: string | { id?: number; name: string } | null;
  category?: string | { id?: number; name: string; slug?: string } | null;
  category_id?: number;
  category_slug?: string | null;
  is_featured?: boolean;
  is_new?: boolean;
  is_bestseller?: boolean;
  rating?: number;
  review_count?: number;
  variants_count?: number;
  variants?: Array<{
    id: number;
    sku: string;
    price: number;
    compare_price?: number | null;
    stock_quantity?: number;
    size?: string;
    images?: Array<{ id?: number; url: string }>;
  }>;
  reviews_data?: Array<{
    id: number | string;
    user_name?: string;
    rating?: number;
    review?: string;
    created_at?: string;
  }>;
}

/**
 * Transforms an ApiProduct from Laravel into the standard frontend Product interface.
 */
export function transformApiProductToProduct(apiProduct: ApiProduct): Product {
  const mainImage =
    apiProduct.main_image ||
    (apiProduct.images && apiProduct.images.length > 0
      ? typeof apiProduct.images[0] === "string"
        ? apiProduct.images[0]
        : apiProduct.images[0].url
      : "/images/logo/Logo_1.png");

  const allImages: string[] =
    apiProduct.images && apiProduct.images.length > 0
      ? apiProduct.images.map((img) => (typeof img === "string" ? img : img.url))
      : [mainImage];

  // Extract category name & slug
  const categoryName =
    typeof apiProduct.category === "string"
      ? apiProduct.category
      : apiProduct.category?.name || "Handmade";

  const categorySlug =
    apiProduct.category_slug ||
    (typeof apiProduct.category === "object" ? apiProduct.category?.slug : null) ||
    "all";

  // Build sizes from variants if available
  const sizes =
    apiProduct.variants && apiProduct.variants.length > 0
      ? Array.from(
          new Set(
            apiProduct.variants
              .map((v) => v.size || (v.price ? `Size (₹${v.price})` : "Standard"))
              .filter(Boolean)
          )
        )
      : ["Standard Size"];

  return {
    id: String(apiProduct.id),
    slug: apiProduct.slug,
    name: apiProduct.name,
    category: categoryName,
    categorySlug: categorySlug,
    price: Number(apiProduct.price) || 0,
    comparePrice: apiProduct.compare_price ? Number(apiProduct.compare_price) : undefined,
    images: allImages.length > 0 ? allImages : [mainImage],
    description: apiProduct.description || apiProduct.short_description || "",
    shortDescription: apiProduct.short_description || "",
    materials: [
      "100% Pure Natural Cotton",
      "Hypoallergenic Fiberfill",
      "Artisan Hand-stitched",
    ],
    dimensions: "Standard Artisan Craft Size",
    craftTime: "2-4 hours of meticulous handcrafting",
    colors: [
      { name: "Artisan Natural", hex: "#FFFDF9" },
      { name: "Blush Rose", hex: "#F8C8C8" },
      { name: "Sage Garden", hex: "#D8E2DC" },
      { name: "Dusty Terracotta", hex: "#E8B4B8" },
    ],
    sizes: sizes.length > 0 ? sizes : ["Standard Size"],
    isCustomizable: true,
    isFeatured: Boolean(apiProduct.is_featured),
    isBestSeller: Boolean(apiProduct.is_bestseller),
    isNew: Boolean(apiProduct.is_new),
    rating: apiProduct.rating ? Number(apiProduct.rating) : 4.9,
    reviewCount: apiProduct.review_count ? Number(apiProduct.review_count) : 16,
    stock: apiProduct.stock_quantity ?? 25,
    tags: [categorySlug, "handmade", "crochet", "boutique", "gift"],
    reviews:
      apiProduct.reviews_data && apiProduct.reviews_data.length > 0
        ? apiProduct.reviews_data.map((r) => ({
            id: String(r.id),
            userName: r.user_name || "Boutique Customer",
            rating: Number(r.rating || 5),
            date: r.created_at || "Recently",
            comment: r.review || "Exquisite craftsmanship, wonderful quality!",
            verified: true,
          }))
        : [
            {
              id: "rev-default",
              userName: "Verified Patron",
              rating: 5,
              date: "Recent",
              comment:
                "Exquisite craftsmanship, the stitches are neat and the yarn is delightfully soft!",
              verified: true,
            },
          ],
  };
}

/**
 * Fetch Categories from Laravel Backend API
 */
export async function fetchCategories(): Promise<ApiCategory[]> {
  try {
    const isBrowser = typeof window !== "undefined";
    const isDev = process.env.NODE_ENV === "development";
    const bypassCache = isBrowser || isDev;
    const url = bypassCache
      ? `${getApiBaseUrl()}/customer/categories?_t=${Date.now()}`
      : `${getApiBaseUrl()}/customer/categories`;

    const response = await fetch(url, {
      headers: { Accept: "application/json" },
      ...(bypassCache ? { cache: "no-store" as const } : { next: { revalidate: 60 } }),
    });
    const result = await response.json();
    if (result.status === "success" && Array.isArray(result.data)) {
      return result.data;
    }
    return [];
  } catch (error) {
    console.warn("Fetch categories network issue:", error);
    return [];
  }
}

/**
 * Fetch Products from Laravel Backend API with dynamic filters
 */
export async function fetchProducts(params?: {
  category_id?: number | string;
  category_slug?: string;
  search?: string;
  min_price?: number;
  max_price?: number;
  is_featured?: boolean;
  is_bestseller?: boolean;
  is_new?: boolean;
  sort_by?: string;
  per_page?: number;
  in_stock?: boolean;
}): Promise<{ products: Product[]; rawApiProducts?: ApiProduct[]; meta?: any; filters?: any }> {
  try {
    const isBrowser = typeof window !== "undefined";
    const isDev = process.env.NODE_ENV === "development";
    const bypassCache = isBrowser || isDev;

    const query = new URLSearchParams();
    if (params?.category_id) query.set("category_id", String(params.category_id));
    if (params?.search) query.set("search", params.search);
    if (params?.min_price !== undefined) query.set("min_price", String(params.min_price));
    if (params?.max_price !== undefined) query.set("max_price", String(params.max_price));
    if (params?.is_featured !== undefined) query.set("is_featured", String(params.is_featured));
    if (params?.is_bestseller !== undefined) query.set("is_bestseller", String(params.is_bestseller));
    if (params?.is_new !== undefined) query.set("is_new", String(params.is_new));
    if (params?.sort_by) query.set("sort_by", params.sort_by);
    if (params?.per_page) query.set("per_page", String(params.per_page));
    if (params?.in_stock) query.set("in_stock", "true");
    if (bypassCache) query.set("_t", String(Date.now()));

    const qs = query.toString();
    const url = `${getApiBaseUrl()}/customer/products${qs ? `?${qs}` : ""}`;

    const response = await fetch(url, {
      headers: { Accept: "application/json" },
      ...(bypassCache ? { cache: "no-store" as const } : { next: { revalidate: 30 } }),
    });

    const result = await response.json();
    if (result.success && result.data && Array.isArray(result.data.products)) {
      const transformed = result.data.products.map(transformApiProductToProduct);
      return {
        products: transformed,
        rawApiProducts: result.data.products,
        meta: result.data.meta,
        filters: result.data.filters,
      };
    }
    return { products: [] };
  } catch (error) {
    console.warn("Fetch products error:", error);
    return { products: [] };
  }
}

/**
 * Fetch Single Product by Slug from Laravel Backend API
 */
export async function fetchProductBySlug(slug: string): Promise<Product | null> {
  try {
    const isBrowser = typeof window !== "undefined";
    const isDev = process.env.NODE_ENV === "development";
    const bypassCache = isBrowser || isDev;
    const url = bypassCache
      ? `${getApiBaseUrl()}/customer/products/${encodeURIComponent(slug)}?_t=${Date.now()}`
      : `${getApiBaseUrl()}/customer/products/${encodeURIComponent(slug)}`;

    const response = await fetch(url, {
      headers: { Accept: "application/json" },
      ...(bypassCache ? { cache: "no-store" as const } : { next: { revalidate: 30 } }),
    });
    const result = await response.json();
    if (result.success && result.data) {
      return transformApiProductToProduct(result.data);
    }
    return null;
  } catch (error) {
    console.warn(`Fetch product by slug (${slug}) error:`, error);
    return null;
  }
}

/**
 * Fetch Related Products by Product ID
 */
export async function fetchRelatedProducts(productId: number | string): Promise<Product[]> {
  try {
    const isBrowser = typeof window !== "undefined";
    const isDev = process.env.NODE_ENV === "development";
    const bypassCache = isBrowser || isDev;
    const url = bypassCache
      ? `${getApiBaseUrl()}/customer/products/${productId}/related?_t=${Date.now()}`
      : `${getApiBaseUrl()}/customer/products/${productId}/related`;

    const response = await fetch(url, {
      headers: { Accept: "application/json" },
      ...(bypassCache ? { cache: "no-store" as const } : { next: { revalidate: 60 } }),
    });
    const result = await response.json();
    if (result.success && Array.isArray(result.data)) {
      return result.data.map(transformApiProductToProduct);
    }
    return [];
  } catch (error) {
    console.warn(`Fetch related products error:`, error);
    return [];
  }
}

/**
 * Fetch Home Page Data (Banners, Categories, Sections, Testimonials)
 */
export async function fetchHomeData(): Promise<any> {
  try {
    const isBrowser = typeof window !== "undefined";
    const isDev = process.env.NODE_ENV === "development";
    const bypassCache = isBrowser || isDev;
    const url = bypassCache
      ? `${getApiBaseUrl()}/customer/home?_t=${Date.now()}`
      : `${getApiBaseUrl()}/customer/home`;

    const response = await fetch(url, {
      headers: { Accept: "application/json" },
      ...(bypassCache ? { cache: "no-store" as const } : { next: { revalidate: 60 } }),
    });
    const result = await response.json();
    if (result.success && result.data) {
      return result.data;
    }
    return null;
  } catch (error) {
    console.warn("Fetch home data error:", error);
    return null;
  }
}

/**
 * Fetch Contact Page Settings from Laravel Backend API
 */
export async function fetchContactSettings(): Promise<ContactSettingsData | null> {
  try {
    const response = await fetch(`${getApiBaseUrl()}/customer/contact/settings?t=${Date.now()}`, {
      headers: {
        Accept: "application/json",
      },
      cache: "no-store",
    });

    if (!response.ok) return null;
    const result = await response.json();
    if (result.status === "success" && result.data) {
      return result.data as ContactSettingsData;
    }
    return null;
  } catch (error) {
    console.warn("Error fetching contact settings:", error);
    return null;
  }
}

/**
 * Submit Contact Form to Laravel Backend API
 */
export async function submitContactForm(
  data: ContactFormData
): Promise<{ success: boolean; message: string }> {
  try {
    const response = await fetch(`${getApiBaseUrl()}/customer/contact`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
      body: JSON.stringify({
        name: data.name,
        email: data.email,
        phone: data.phone ? data.phone.replace(/\D/g, "").slice(-10) : undefined,
        subject: data.subject || undefined,
        message: data.message,
      }),
    });

    const result = await response.json();
    if (response.ok && (result.status === "success" || result.success === true)) {
      return { success: true, message: result.message || "Message sent successfully." };
    }
    return { success: false, message: result.message || "Failed to submit message." };
  } catch (error) {
    console.warn("Contact API network issue:", error);
    return { success: false, message: "Network error. Please try again." };
  }
}

/**
 * Submit Order to Laravel Backend API
 */
export async function submitOrder(
  orderPayload: Record<string, unknown>
): Promise<{ success: boolean; orderId?: string; orderNumber?: string; message?: string }> {
  try {
    const response = await fetch(`${getApiBaseUrl()}/customer/orders`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
      body: JSON.stringify(orderPayload),
    });
    const result = await response.json();
    if (response.ok && result.success) {
      return {
        success: true,
        orderId: result.data?.order_id,
        orderNumber: result.data?.order_number,
        message: result.message,
      };
    }
    return { success: false, message: result.message || "Order placement failed." };
  } catch (error) {
    console.warn("Order submit error:", error);
    return { success: false, message: "Connection error" };
  }
}

/**
 * Fetch Structured Homepage & Global Media (Always Live in Browser, Static-friendly on Server)
 */
export async function fetchHomepageMedia(): Promise<HomepageMedia | null> {
  try {
    const isBrowser = typeof window !== "undefined";
    const isDev = process.env.NODE_ENV === "development";
    const bypassCache = isBrowser || isDev;
    const url = bypassCache
      ? `${getApiBaseUrl()}/customer/media?_t=${Date.now()}`
      : `${getApiBaseUrl()}/customer/media`;

    const response = await fetch(url, {
      headers: {
        Accept: "application/json",
      },
      ...(bypassCache ? { cache: "no-store" as const } : {}),
    });
    const result = await response.json();
    if (result.success && result.data) {
      return result.data as HomepageMedia;
    }
    return null;
  } catch (error) {
    console.warn("Fetch homepage media error:", error);
    return null;
  }
}

export interface AboutStoryData {
  id?: number | null;
  badge?: string;
  tag_text?: string;
  title?: string;
  tagline?: string;
  subtitle?: string;
  description?: string;
  paragraph_1?: string;
  paragraph_2?: string;
  secondary_description?: string;
  desktop_image?: string;
  image?: string;
  mobile_image?: string | null;
  alt_text?: string;
  floating_badge?: {
    title: string;
    subtitle: string;
    icon: string;
    is_active: boolean;
  };
  cta?: {
    text: string;
    url: string;
    link?: string;
    is_active: boolean;
  };
  cta_text?: string;
  cta_link?: string;
  is_active?: boolean;
}

export interface CraftPillarItem {
  id: number | string;
  title: string;
  description: string;
  icon?: string;
  icon_name?: string;
  icon_type?: "preset" | "svg" | "image" | "custom" | string;
  icon_url?: string | null;
  sort_order?: number;
  is_active?: boolean;
}

export interface CraftPillarsData {
  title?: string;
  subtitle?: string;
  badge?: string;
  is_active?: boolean;
  items: CraftPillarItem[];
}

export interface AboutPageData {
  story: AboutStoryData;
  hero?: AboutStoryData;
  craftPillars: CraftPillarsData;
  pillars?: CraftPillarsData;
}

/**
 * Fetch Structured Dedicated About Page Data (Always Live in Browser & Dev Mode, Static-friendly on Build)
 */
export async function fetchAboutPageData(): Promise<AboutPageData | null> {
  try {
    const isBrowser = typeof window !== "undefined";
    const isDev = process.env.NODE_ENV === "development";
    const bypassCache = isBrowser || isDev;
    const url = bypassCache
      ? `${getApiBaseUrl()}/customer/about?_t=${Date.now()}`
      : `${getApiBaseUrl()}/customer/about`;

    const response = await fetch(url, {
      headers: {
        Accept: "application/json",
      },
      ...(bypassCache ? { cache: "no-store" as const } : {}),
    });
    const result = await response.json();
    if (result.success && result.data) {
      return result.data as AboutPageData;
    }
    return null;
  } catch (error) {
    console.warn("Fetch about page data error:", error);
    return null;
  }
}

/**
 * Record a View on Video / Reel Playback
 */
export async function recordVideoView(id: number | string): Promise<void> {
  try {
    await fetch(`${getApiBaseUrl()}/customer/media/video/${id}/view`, {
      method: "POST",
      headers: {
        Accept: "application/json",
      },
    });
  } catch (err) {
    // Non-critical metric
  }
}

export interface ContactIntroData {
  id?: number | null;
  badge?: string;
  tag_text?: string;
  title: string;
  tagline?: string;
  subtitle?: string;
  description?: string;
  image?: string;
  desktop_image?: string;
  mobile_image?: string;
  alt_text?: string;
  cta?: {
    text: string;
    link?: string;
    url?: string;
  };
  cta_text?: string;
  cta_link?: string;
  is_active?: boolean;
}

export interface ContactDetailItem {
  id: number | string;
  title: string;
  value: string;
  address_line_2?: string;
  link?: string;
  cta_link?: string;
  icon?: string;
  icon_name?: string;
  sort_order?: number;
  is_active?: boolean;
}

export interface ContactInfoSectionData {
  badge?: string;
  tag_text?: string;
  title: string;
  subtitle?: string;
  custom_order_box?: {
    title: string;
    text: string;
    link: string;
    is_active: boolean;
  };
  custom_order_box_title?: string;
  custom_order_box_text?: string;
  custom_order_box_link?: string;
  custom_order_box_active?: boolean;
  is_active?: boolean;
  items: ContactDetailItem[];
}

export interface ContactFormFieldConfig {
  key: "name" | "email" | "phone" | "subject" | "message" | string;
  label: string;
  placeholder?: string;
  required?: boolean;
  is_active?: boolean;
}

export interface ContactFormSettingsData {
  badge?: string;
  tag_text?: string;
  title: string;
  subtitle?: string;
  submit_btn_text?: string;
  cta_text?: string;
  success_title?: string;
  success_message?: string;
  error_message?: string;
  fields?: ContactFormFieldConfig[];
  is_active?: boolean;
}

export interface ContactFaqItem {
  id: number | string;
  question: string;
  answer: string;
  title?: string;
  description?: string;
  sort_order?: number;
  is_active?: boolean;
}

export interface ContactFaqsSectionData {
  badge?: string;
  tag_text?: string;
  title: string;
  subtitle?: string;
  is_active?: boolean;
  items: ContactFaqItem[];
}

export interface ContactPageData {
  intro: ContactIntroData;
  info: ContactInfoSectionData;
  form: ContactFormSettingsData;
  faqs: ContactFaqsSectionData;
}

/**
 * Fetch Structured Dedicated Contact Page Data (Always Live in Browser & Dev Mode, Static-friendly on Build)
 */
export async function fetchContactPageData(): Promise<ContactPageData | null> {
  try {
    const isBrowser = typeof window !== "undefined";
    const isDev = process.env.NODE_ENV === "development";
    const bypassCache = isBrowser || isDev;
    const url = bypassCache
      ? `${getApiBaseUrl()}/customer/contact?_t=${Date.now()}`
      : `${getApiBaseUrl()}/customer/contact`;

    const response = await fetch(url, {
      headers: {
        Accept: "application/json",
      },
      ...(bypassCache ? { cache: "no-store" as const } : {}),
    });
    const result = await response.json();
    if (result.success && result.data) {
      const d = result.data;
      return {
        intro: d.intro,
        info: d.info || d.contactDetails || d.contactInfo || d.details,
        form: d.form || d.formSettings,
        faqs: d.faqs,
      } as ContactPageData;
    }
    return null;
  } catch (error) {
    console.warn("Fetch contact page data error:", error);
    return null;
  }
}
