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
  db_id?: number;
  slug: string;
  name: string;
  category: string;
  categorySlug: string;
  price: number;
  comparePrice?: number;
  compareAtPrice?: number;
  images: string[];
  main_image?: string;
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
  inStock?: boolean;
  tags: string[];
  reviews?: ProductReview[];
}
