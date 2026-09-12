import { Product, ProductColor } from "./product";

export interface CartItemCustomization {
  color?: ProductColor;
  size?: string;
  customText?: string;
  specialInstructions?: string;
}

export interface CartItem {
  id: string; // unique item id (composite of product id + color + size + custom text)
  productId: string;
  product: Product;
  quantity: number;
  customization?: CartItemCustomization;
  price: number;
}
