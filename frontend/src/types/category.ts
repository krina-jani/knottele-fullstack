export interface Category {
  id: string;
  slug: string;
  name: string;
  priceFrom: string;
  image: string;
  itemCount: number;
  description: string;
  featured?: boolean;
}
