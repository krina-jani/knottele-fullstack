export interface ProductColor {
  name: string;
  hex: string;
}

export interface ProductReview {
  id: string;
  userName: string;
  rating: number;
  date: string;
  comment: string;
  verified: boolean;
  avatar?: string;
}

export interface Product {
  id: string;
  slug: string;
  name: string;
  category: string;
  categorySlug: string;
  price: number;
  comparePrice?: number;
  images: string[];
  description: string;
  shortDescription: string;
  materials?: string[];
  dimensions?: string;
  craftTime?: string;
  colors: ProductColor[];
  sizes?: string[];
  isCustomizable: boolean;
  isFeatured?: boolean;
  isBestSeller?: boolean;
  isNew?: boolean;
  rating: number;
  reviewCount: number;
  stock: number;
  tags: string[];
  reviews?: ProductReview[];
}
