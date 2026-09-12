import { Order } from "@/types/order";
import { PRODUCTS } from "./products";

export const INITIAL_ORDERS: Order[] = [
  {
    id: "ord-8092",
    orderNumber: "KNT-8092-IN",
    orderDate: "08 Sep 2026",
    estimatedDelivery: "14 Sep 2026",
    items: [
      {
        id: "item-1",
        productId: "prod-3",
        product: PRODUCTS[2], // Rose Bouquet
        quantity: 1,
        price: 1000,
        customization: {
          color: { name: "Deep Crimson & Blush", hex: "#8F3032" },
          size: "Classic 5 Roses",
          customText: "For My Sweet Sister on Her Graduation 🎓",
        },
      },
      {
        id: "item-2",
        productId: "prod-1",
        product: PRODUCTS[0], // Cute Bunny Keychain
        quantity: 2,
        price: 150,
        customization: {
          color: { name: "Powder Pink", hex: "#F8C8C8" },
        },
      },
    ],
    shippingAddress: {
      fullName: "Ananya Sharma",
      email: "ananya.sharma@example.com",
      phone: "+91 98765 43210",
      addressLine1: "Flat 402, Rosewood Residency",
      addressLine2: "12th Main, 4th Cross, Indiranagar",
      city: "Bengaluru",
      state: "Karnataka",
      pincode: "560038",
      country: "India",
    },
    paymentMethod: "UPI",
    paymentStatus: "Paid",
    subtotal: 1300,
    shipping: 0,
    discount: 130, // 10% coupon
    total: 1170,
    status: "Crafting Your Order",
    trackingNumber: "DTDC-BLR-892301",
    courierName: "DTDC Premium Express",
    timeline: [
      {
        status: "Order Placed",
        date: "08 Sep 2026, 10:30 AM",
        description: "Order received and queued for artisan assignment.",
        completed: true,
      },
      {
        status: "Order Confirmed",
        date: "08 Sep 2026, 11:15 AM",
        description: "Payment verified and natural yarn skeins prepared.",
        completed: true,
      },
      {
        status: "Crafting Your Order",
        date: "09 Sep 2026, 09:00 AM",
        description: "Master artisan is hand-crocheting your customized roses with love.",
        completed: true,
        current: true,
      },
      {
        status: "Packed",
        date: "Expected 11 Sep 2026",
        description: "Boutique wrapping with lavender fragrance and wax seal.",
        completed: false,
      },
      {
        status: "Shipped",
        date: "Expected 12 Sep 2026",
        description: "Handed over to DTDC Premium courier partner.",
        completed: false,
      },
      {
        status: "Out for Delivery",
        date: "Expected 14 Sep 2026",
        description: "Courier agent out for delivery to your doorstep.",
        completed: false,
      },
      {
        status: "Delivered",
        date: "Expected 14 Sep 2026",
        description: "Handmade joy delivered to your hands.",
        completed: false,
      },
    ],
  },
  {
    id: "ord-7840",
    orderNumber: "KNT-7840-IN",
    orderDate: "15 Aug 2026",
    estimatedDelivery: "21 Aug 2026",
    items: [
      {
        id: "item-3",
        productId: "prod-6",
        product: PRODUCTS[5], // Granny Square Bag
        quantity: 1,
        price: 5000,
        customization: {
          color: { name: "Pastel Dream (Pink, Cream, Sage)", hex: "#FDE9E5" },
        },
      },
    ],
    shippingAddress: {
      fullName: "Ananya Sharma",
      email: "ananya.sharma@example.com",
      phone: "+91 98765 43210",
      addressLine1: "Flat 402, Rosewood Residency",
      addressLine2: "Indiranagar",
      city: "Bengaluru",
      state: "Karnataka",
      pincode: "560038",
      country: "India",
    },
    paymentMethod: "Credit/Debit Card",
    paymentStatus: "Paid",
    subtotal: 5000,
    shipping: 0,
    discount: 500,
    total: 4500,
    status: "Delivered",
    trackingNumber: "BLUEDART-592810",
    courierName: "Blue Dart Express",
    timeline: [
      {
        status: "Order Placed",
        date: "15 Aug 2026",
        description: "Order placed successfully.",
        completed: true,
      },
      {
        status: "Order Confirmed",
        date: "15 Aug 2026",
        description: "Order confirmed.",
        completed: true,
      },
      {
        status: "Crafting Your Order",
        date: "16 Aug 2026",
        description: "Handcrafting 24 granny squares.",
        completed: true,
      },
      {
        status: "Packed",
        date: "19 Aug 2026",
        description: "Carefully wrapped in luxury canvas gift dustbag.",
        completed: true,
      },
      {
        status: "Shipped",
        date: "19 Aug 2026",
        description: "Shipped via Blue Dart.",
        completed: true,
      },
      {
        status: "Out for Delivery",
        date: "21 Aug 2026",
        description: "Out for delivery.",
        completed: true,
      },
      {
        status: "Delivered",
        date: "21 Aug 2026",
        description: "Package delivered with love.",
        completed: true,
        current: true,
      },
    ],
  },
];
