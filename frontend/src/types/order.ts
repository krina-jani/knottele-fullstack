import { CartItem } from "./cart";

export type OrderStatus =
  | "Order Placed"
  | "Order Confirmed"
  | "Crafting Your Order"
  | "Packed"
  | "Shipped"
  | "Out for Delivery"
  | "Delivered"
  | "Cancelled";

export interface OrderTimelineEvent {
  status: OrderStatus;
  date: string;
  description: string;
  completed: boolean;
  current?: boolean;
}

export interface ShippingAddress {
  fullName: string;
  email: string;
  phone: string;
  addressLine1: string;
  addressLine2?: string;
  city: string;
  state: string;
  pincode: string;
  country: string;
}

export interface Order {
  id: string;
  orderNumber: string;
  orderDate: string;
  estimatedDelivery: string;
  items: CartItem[];
  shippingAddress: ShippingAddress;
  paymentMethod: "Razorpay Online Payment" | "Razorpay" | "UPI" | "Credit/Debit Card" | "Net Banking" | "Cash on Delivery" | string;
  paymentStatus: "Paid" | "Pending" | "Failed";
  subtotal: number;
  shipping: number;
  discount: number;
  total: number;
  status: OrderStatus;
  timeline: OrderTimelineEvent[];
  trackingNumber?: string;
  courierName?: string;
}
