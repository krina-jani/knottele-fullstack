"use client";

import React, { useState } from "react";
import Image from "next/image";
import Link from "next/link";
import { useRouter } from "next/navigation";
import {
  CheckCircle2,
  ShieldCheck,
  CreditCard,
  QrCode,
  Truck,
  ArrowRight,
  ArrowLeft,
  ChevronDown,
  ChevronUp,
  Sparkles,
  Heart,
} from "lucide-react";
import confetti from "canvas-confetti";
import { useCart } from "@/context/CartContext";
import { useAuth } from "@/context/AuthContext";
import { useWishlist } from "@/context/WishlistContext";
import { Order, OrderStatus } from "@/types/order";
import { Breadcrumbs } from "@/components/ui/Breadcrumbs";
import { FlowerIcon } from "@/components/ui/BotanicalDecorations";
import { submitOrder, normalizeImageUrl } from "@/lib/api";

export default function CheckoutPage() {
  const router = useRouter();
  const { items, subtotal, shipping, discount, total, clearCart } = useCart();
  const { user, addOrder } = useAuth();
  const { toggleWishlist, isInWishlist } = useWishlist();

  const [activeStep, setActiveStep] = useState(1);
  const [isSummaryCollapsedMobile, setIsSummaryCollapsedMobile] = useState(true);

  // Form State: 01 Information
  const [fullName, setFullName] = useState(user?.name || "");
  const [email, setEmail] = useState(user?.email || "");
  const [phone, setPhone] = useState(user?.phone || "");

  // Form State: 02 Delivery
  const [addressLine1, setAddressLine1] = useState(
    user?.addresses[0]?.addressLine1 || "Flat 402, Rosewood Residency"
  );
  const [addressLine2, setAddressLine2] = useState(
    user?.addresses[0]?.addressLine2 || "12th Main, 4th Cross, Indiranagar"
  );
  const [city, setCity] = useState(user?.addresses[0]?.city || "Bengaluru");
  const [state, setState] = useState(user?.addresses[0]?.state || "Karnataka");
  const [pincode, setPincode] = useState(user?.addresses[0]?.pincode || "560038");

  // Form State: 03 Payment
  const [paymentMethod, setPaymentMethod] = useState<
    "UPI" | "Credit/Debit Card" | "Net Banking" | "Cash on Delivery"
  >("UPI");
  const [upiId, setUpiId] = useState("ananya@okaxis");

  const handlePlaceOrder = () => {
    // Generate order ID
    const randomNum = Math.floor(1000 + Math.random() * 9000);
    const orderNumber = `KNT-${randomNum}-IN`;

    const newOrder: Order = {
      id: `ord-${randomNum}`,
      orderNumber,
      orderDate: new Date().toLocaleDateString("en-GB", {
        day: "2-digit",
        month: "short",
        year: "numeric",
      }),
      estimatedDelivery: new Date(Date.now() + 6 * 24 * 60 * 60 * 1000).toLocaleDateString(
        "en-GB",
        { day: "2-digit", month: "short", year: "numeric" }
      ),
      items: items.length > 0 ? [...items] : [],
      shippingAddress: {
        fullName,
        email,
        phone,
        addressLine1,
        addressLine2,
        city,
        state,
        pincode,
        country: "India",
      },
      paymentMethod,
      paymentStatus: paymentMethod === "Cash on Delivery" ? "Pending" : "Paid",
      subtotal,
      shipping,
      discount,
      total,
      status: "Order Placed",
      timeline: [
        {
          status: "Order Placed",
          date: "Just now",
          description: "Order placed successfully. Assigned to artisan studio.",
          completed: true,
          current: true,
        },
        {
          status: "Order Confirmed",
          date: "Upcoming",
          description: "Natural cotton yarns prepared for crafting.",
          completed: false,
        },
        {
          status: "Crafting Your Order",
          date: "Upcoming",
          description: "Handcrafting your bespoke crochet items.",
          completed: false,
        },
        {
          status: "Packed",
          date: "Upcoming",
          description: "Wrapped with boutique lavender box and wax seal.",
          completed: false,
        },
        {
          status: "Shipped",
          date: "Upcoming",
          description: "Handed to premium courier express.",
          completed: false,
        },
        {
          status: "Out for Delivery",
          date: "Upcoming",
          description: "On its way to your doorstep.",
          completed: false,
        },
        {
          status: "Delivered",
          date: "Upcoming",
          description: "Handmade joy delivered with love.",
          completed: false,
        },
      ],
    };

    // Trigger celebratory confetti
    try {
      confetti({
        particleCount: 100,
        spread: 70,
        origin: { y: 0.6 },
        colors: ["#8F3032", "#F4C7C1", "#C69A5A", "#FDE9E5"],
      });
    } catch (e) {}

    // Submit order to Laravel backend API
    const orderItemsPayload = items.map((item) => {
      let variantId = 1;
      const numMatch = String(item.product?.id || "").match(/\d+/);
      if (numMatch) {
        const parsed = parseInt(numMatch[0], 10);
        if (parsed >= 1 && parsed <= 50) {
          variantId = parsed;
        }
      }
      return {
        variant_id: variantId,
        product_id: item.product?.id || item.productId,
        product_code: item.product?.id || item.productId,
        name: item.product?.name || "Handmade Product",
        unit_price: item.price,
        quantity: item.quantity || 1,
      };
    });

    submitOrder({
      items: orderItemsPayload.length > 0 ? orderItemsPayload : [{ variant_id: 1, quantity: 1, unit_price: subtotal }],
      shipping_address: {
        name: fullName,
        email: email,
        phone: phone.replace(/\D/g, "").slice(-10) || "9876543210",
        address_line_1: `${addressLine1}${addressLine2 ? ', ' + addressLine2 : ''}`,
        city: city,
        pin_code: pincode,
      },
      payment_method: paymentMethod === "Cash on Delivery" ? "cod" : "upi",
    })
      .then((res) => {
        if (res && res.success) {
          if (res.orderNumber) {
            newOrder.orderNumber = res.orderNumber;
          }
          if (res.orderId) {
            newOrder.id = res.orderId;
          }
        }
      })
      .catch((err) => console.warn("Background order sync notice:", err))
      .finally(() => {
        addOrder(newOrder);
        clearCart();
        const finalNum = newOrder.orderNumber;
        const finalId = newOrder.id;
        router.push(`/order-confirmation?orderNumber=${encodeURIComponent(finalNum)}&orderId=${encodeURIComponent(finalId)}`);
      });
  };

  return (
    <div className="bg-[#FFF9F6] min-h-screen py-8 lg:py-12">
      <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {/* Breadcrumb */}
        <Breadcrumbs
          items={[
            { label: "Cart", href: "/cart" },
            { label: "Distraction-Free Checkout" },
          ]}
          className="mb-6"
        />

        {/* Header */}
        <div className="flex items-center justify-between mb-8 pb-4 border-b border-[#E7D1CC]">
          <div className="flex items-center gap-3">
            <div className="w-10 h-10 rounded-full bg-[#FCE9E5] flex items-center justify-center text-[#913638]">
              <FlowerIcon className="w-5 h-5 text-[#913638]" />
            </div>
            <div>
              <h1 className="font-serif-luxury text-2xl sm:text-3xl font-bold text-[#2E211E]">
                Complete Your Order
              </h1>
              <p className="text-xs text-[#786864]">
                Safe 256-Bit Encrypted Boutique Checkout
              </p>
            </div>
          </div>
          <div className="hidden sm:flex items-center gap-2 text-xs text-[#786864]">
            <ShieldCheck className="w-4 h-4 text-emerald-800" />
            <span>100% Guaranteed Handmade</span>
          </div>
        </div>

        {/* Mobile Collapsible Order Summary Trigger */}
        <div className="lg:hidden mb-6 bg-white rounded-2xl border border-[#E7D1CC] p-4 shadow-xs">
          <button
            onClick={() => setIsSummaryCollapsedMobile(!isSummaryCollapsedMobile)}
            className="w-full flex items-center justify-between text-xs font-semibold text-[#2E211E] cursor-pointer"
          >
            <span className="flex items-center gap-1.5 text-[#913638]">
              <Sparkles className="w-4 h-4" />
              <span>
                {isSummaryCollapsedMobile ? "Show" : "Hide"} Order Summary (₹{total.toLocaleString("en-IN")})
              </span>
            </span>
            {isSummaryCollapsedMobile ? <ChevronDown className="w-4 h-4" /> : <ChevronUp className="w-4 h-4" />}
          </button>

          {!isSummaryCollapsedMobile && (
            <div className="mt-4 pt-4 border-t border-[#E7D1CC] space-y-3 text-xs animate-in fade-in">
              {items.map((item) => (
                <div key={item.id} className="flex justify-between items-center">
                  <span>{item.quantity}x {item.product.name}</span>
                  <span className="font-bold text-[#2E211E]">₹{(item.price * item.quantity).toLocaleString("en-IN")}</span>
                </div>
              ))}
              <div className="flex justify-between font-bold text-sm text-[#913638] pt-2 border-t border-[#E7D1CC]">
                <span>Total:</span>
                <span>₹{total.toLocaleString("en-IN")}</span>
              </div>
            </div>
          )}
        </div>

        {/* Main 2-Column Grid */}
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
          
          {/* Left Steps Form (Spans 7 cols on lg) */}
          <div className="lg:col-span-7 space-y-6">
            
            {/* STEP 01: Information */}
            <div className={`bg-white rounded-3xl border border-[#E7D1CC] p-6 shadow-boutique transition-all ${activeStep === 1 ? "ring-2 ring-[#913638]/20" : ""}`}>
              <div className="flex items-center justify-between pb-3 border-b border-[#E7D1CC]">
                <div className="flex items-center gap-2.5">
                  <span className={`w-7 h-7 rounded-full text-xs font-bold flex items-center justify-center ${activeStep > 1 ? "bg-[#FCE9E5] text-[#913638]" : "bg-[#913638] text-white"}`}>
                    {activeStep > 1 ? "✓" : "01"}
                  </span>
                  <h3 className="font-serif-luxury text-base font-bold text-[#2E211E]">
                    Customer Information
                  </h3>
                </div>
                {activeStep > 1 && (
                  <button
                    onClick={() => setActiveStep(1)}
                    className="text-xs font-semibold text-[#913638] hover:underline cursor-pointer"
                  >
                    Edit
                  </button>
                )}
              </div>

              {activeStep === 1 ? (
                <div className="pt-4 space-y-4 animate-in fade-in">
                  <div className="space-y-1">
                    <label className="text-xs font-semibold text-[#2E211E] block">
                      Full Name *
                    </label>
                    <input
                      type="text"
                      value={fullName}
                      onChange={(e) => setFullName(e.target.value)}
                      className="w-full px-3.5 py-2.5 rounded-xl bg-[#FFF9F6] border border-[#E7D1CC] text-xs text-[#2E211E] focus:outline-none focus:border-[#913638]"
                    />
                  </div>

                  <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div className="space-y-1">
                      <label className="text-xs font-semibold text-[#2E211E] block">
                        Email Address *
                      </label>
                      <input
                        type="email"
                        value={email}
                        onChange={(e) => setEmail(e.target.value)}
                        className="w-full px-3.5 py-2.5 rounded-xl bg-[#FFF9F6] border border-[#E7D1CC] text-xs text-[#2E211E] focus:outline-none focus:border-[#913638]"
                      />
                    </div>
                    <div className="space-y-1">
                      <label className="text-xs font-semibold text-[#2E211E] block">
                        Phone Number *
                      </label>
                      <input
                        type="tel"
                        value={phone}
                        onChange={(e) => setPhone(e.target.value)}
                        className="w-full px-3.5 py-2.5 rounded-xl bg-[#FFF9F6] border border-[#E7D1CC] text-xs text-[#2E211E] focus:outline-none focus:border-[#913638]"
                      />
                    </div>
                  </div>

                  <div className="pt-2 flex justify-end">
                    <button
                      type="button"
                      onClick={() => setActiveStep(2)}
                      className="px-6 py-2.5 rounded-full bg-[#913638] text-white text-xs font-semibold hover:bg-[#74292B] active:scale-[0.98] shadow-xs transition-all flex items-center gap-1.5 cursor-pointer"
                    >
                      <span>Continue to Delivery</span>
                      <ArrowRight className="w-3.5 h-3.5" />
                    </button>
                  </div>
                </div>
              ) : (
                <div className="pt-3 text-xs text-[#786864]">
                  <p className="font-semibold text-[#2E211E]">{fullName}</p>
                  <p>{email} &bull; {phone}</p>
                </div>
              )}
            </div>

            {/* STEP 02: Delivery Address */}
            <div className={`bg-white rounded-3xl border border-[#E7D1CC] p-6 shadow-boutique transition-all ${activeStep === 2 ? "ring-2 ring-[#913638]/20" : ""}`}>
              <div className="flex items-center justify-between pb-3 border-b border-[#E7D1CC]">
                <div className="flex items-center gap-2.5">
                  <span className={`w-7 h-7 rounded-full text-xs font-bold flex items-center justify-center ${activeStep > 2 ? "bg-[#FCE9E5] text-[#913638]" : activeStep === 2 ? "bg-[#913638] text-white" : "bg-[#FFF9F6] text-[#786864] border border-[#E7D1CC]"}`}>
                    {activeStep > 2 ? "✓" : "02"}
                  </span>
                  <h3 className="font-serif-luxury text-base font-bold text-[#2E211E]">
                    Shipping & Delivery Address
                  </h3>
                </div>
                {activeStep > 2 && (
                  <button
                    onClick={() => setActiveStep(2)}
                    className="text-xs font-semibold text-[#913638] hover:underline cursor-pointer"
                  >
                    Edit
                  </button>
                )}
              </div>

              {activeStep === 2 ? (
                <div className="pt-4 space-y-4 animate-in fade-in">
                  <div className="space-y-1">
                    <label className="text-xs font-semibold text-[#2E211E] block">
                      House / Flat / Building / Street *
                    </label>
                    <input
                      type="text"
                      value={addressLine1}
                      onChange={(e) => setAddressLine1(e.target.value)}
                      className="w-full px-3.5 py-2.5 rounded-xl bg-[#FFF9F6] border border-[#E7D1CC] text-xs text-[#2E211E] focus:outline-none focus:border-[#913638]"
                    />
                  </div>

                  <div className="space-y-1">
                    <label className="text-xs font-semibold text-[#2E211E] block">
                      Area / Landmark (Optional)
                    </label>
                    <input
                      type="text"
                      value={addressLine2}
                      onChange={(e) => setAddressLine2(e.target.value)}
                      className="w-full px-3.5 py-2.5 rounded-xl bg-[#FFF9F6] border border-[#E7D1CC] text-xs text-[#2E211E] focus:outline-none focus:border-[#913638]"
                    />
                  </div>

                  <div className="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div className="space-y-1">
                      <label className="text-xs font-semibold text-[#2E211E] block">
                        City *
                      </label>
                      <input
                        type="text"
                        value={city}
                        onChange={(e) => setCity(e.target.value)}
                        className="w-full px-3.5 py-2.5 rounded-xl bg-[#FFF9F6] border border-[#E7D1CC] text-xs text-[#2E211E] focus:outline-none focus:border-[#913638]"
                      />
                    </div>
                    <div className="space-y-1">
                      <label className="text-xs font-semibold text-[#2E211E] block">
                        State *
                      </label>
                      <input
                        type="text"
                        value={state}
                        onChange={(e) => setState(e.target.value)}
                        className="w-full px-3.5 py-2.5 rounded-xl bg-[#FFF9F6] border border-[#E7D1CC] text-xs text-[#2E211E] focus:outline-none focus:border-[#913638]"
                      />
                    </div>
                    <div className="space-y-1">
                      <label className="text-xs font-semibold text-[#2E211E] block">
                        Pincode *
                      </label>
                      <input
                        type="text"
                        value={pincode}
                        onChange={(e) => setPincode(e.target.value)}
                        className="w-full px-3.5 py-2.5 rounded-xl bg-[#FFF9F6] border border-[#E7D1CC] text-xs text-[#2E211E] focus:outline-none focus:border-[#913638]"
                      />
                    </div>
                  </div>

                  <div className="pt-2 flex justify-between">
                    <button
                      type="button"
                      onClick={() => setActiveStep(1)}
                      className="px-5 py-2 rounded-full bg-white text-[#2E211E] hover:bg-[#FCE9E5] hover:text-[#913638] border border-[#E7D1CC] hover:border-[#EFB8B0] text-xs font-semibold active:scale-[0.98] shadow-xs transition-all cursor-pointer"
                    >
                      Back
                    </button>
                    <button
                      type="button"
                      onClick={() => setActiveStep(3)}
                      className="px-6 py-2.5 rounded-full bg-[#913638] text-white text-xs font-semibold hover:bg-[#74292B] active:scale-[0.98] shadow-xs transition-all flex items-center gap-1.5 cursor-pointer"
                    >
                      <span>Continue to Payment</span>
                      <ArrowRight className="w-3.5 h-3.5" />
                    </button>
                  </div>
                </div>
              ) : activeStep > 2 ? (
                <div className="pt-3 text-xs text-[#786864]">
                  <p>{addressLine1}, {addressLine2}</p>
                  <p>{city}, {state} - {pincode}, India</p>
                </div>
              ) : null}
            </div>

            {/* STEP 03: Payment */}
            <div className={`bg-white rounded-3xl border border-[#E7D1CC] p-6 shadow-boutique transition-all ${activeStep === 3 ? "ring-2 ring-[#913638]/20" : ""}`}>
              <div className="flex items-center justify-between pb-3 border-b border-[#E7D1CC]">
                <div className="flex items-center gap-2.5">
                  <span className={`w-7 h-7 rounded-full text-xs font-bold flex items-center justify-center ${activeStep > 3 ? "bg-[#FCE9E5] text-[#913638]" : activeStep === 3 ? "bg-[#913638] text-white" : "bg-[#FFF9F6] text-[#786864] border border-[#E7D1CC]"}`}>
                    {activeStep > 3 ? "✓" : "03"}
                  </span>
                  <h3 className="font-serif-luxury text-base font-bold text-[#2E211E]">
                    Payment Method
                  </h3>
                </div>
                {activeStep > 3 && (
                  <button
                    onClick={() => setActiveStep(3)}
                    className="text-xs font-semibold text-[#913638] hover:underline cursor-pointer"
                  >
                    Edit
                  </button>
                )}
              </div>

              {activeStep === 3 ? (
                <div className="pt-4 space-y-4 animate-in fade-in">
                  
                  {/* Payment Options */}
                  <div className="space-y-3">
                    {/* UPI */}
                    <label className={`flex items-start gap-3 p-4 rounded-2xl border cursor-pointer transition-all ${paymentMethod === "UPI" ? "border-[#913638] bg-[#FCE9E5]/50 ring-1 ring-[#913638]" : "border-[#E7D1CC] hover:bg-[#FFF9F6]"}`}>
                      <input
                        type="radio"
                        name="payment"
                        checked={paymentMethod === "UPI"}
                        onChange={() => setPaymentMethod("UPI")}
                        className="mt-1 text-[#913638] focus:ring-[#913638]"
                      />
                      <div className="flex-1">
                        <div className="flex items-center justify-between">
                          <span className="text-xs font-bold text-[#2E211E] flex items-center gap-2">
                            <QrCode className="w-4 h-4 text-[#913638]" />
                            <span>Instant UPI (GPay, PhonePe, Paytm, QR)</span>
                          </span>
                          <span className="text-[10px] text-emerald-800 bg-emerald-100 px-2 py-0.5 rounded-full font-bold">
                            Fastest
                          </span>
                        </div>
                        <p className="text-[11px] text-[#786864] mt-0.5">
                          Pay instantly via any UPI App on mobile or desktop QR.
                        </p>
                        {paymentMethod === "UPI" && (
                          <div className="mt-3 pt-3 border-t border-[#E7D1CC]">
                            <input
                              type="text"
                              value={upiId}
                              onChange={(e) => setUpiId(e.target.value)}
                              placeholder="Enter UPI ID (e.g. yourname@okhdfcbank)"
                              className="w-full px-3.5 py-2 rounded-xl bg-white border border-[#E7D1CC] text-xs text-[#2E211E] focus:outline-none focus:border-[#913638]"
                            />
                          </div>
                        )}
                      </div>
                    </label>

                    {/* Cards */}
                    <label className={`flex items-start gap-3 p-4 rounded-2xl border cursor-pointer transition-all ${paymentMethod === "Credit/Debit Card" ? "border-[#913638] bg-[#FCE9E5]/50 ring-1 ring-[#913638]" : "border-[#E7D1CC] hover:bg-[#FFF9F6]"}`}>
                      <input
                        type="radio"
                        name="payment"
                        checked={paymentMethod === "Credit/Debit Card"}
                        onChange={() => setPaymentMethod("Credit/Debit Card")}
                        className="mt-1 text-[#913638] focus:ring-[#913638]"
                      />
                      <div className="flex-1">
                        <span className="text-xs font-bold text-[#2E211E] flex items-center gap-2">
                          <CreditCard className="w-4 h-4 text-[#913638]" />
                          <span>Credit / Debit Card / Net Banking</span>
                        </span>
                        <p className="text-[11px] text-[#786864] mt-0.5">
                          Visa, Mastercard, RuPay, Maestro & Net Banking.
                        </p>
                      </div>
                    </label>

                    {/* Cash on Delivery */}
                    <label className={`flex items-start gap-3 p-4 rounded-2xl border cursor-pointer transition-all ${paymentMethod === "Cash on Delivery" ? "border-[#913638] bg-[#FCE9E5]/50 ring-1 ring-[#913638]" : "border-[#E7D1CC] hover:bg-[#FFF9F6]"}`}>
                      <input
                        type="radio"
                        name="payment"
                        checked={paymentMethod === "Cash on Delivery"}
                        onChange={() => setPaymentMethod("Cash on Delivery")}
                        className="mt-1 text-[#913638] focus:ring-[#913638]"
                      />
                      <div className="flex-1">
                        <span className="text-xs font-bold text-[#2E211E] flex items-center gap-2">
                          <Truck className="w-4 h-4 text-[#913638]" />
                          <span>Cash on Delivery (COD)</span>
                        </span>
                        <p className="text-[11px] text-[#786864] mt-0.5">
                          Pay cash upon delivery to the courier agent.
                        </p>
                      </div>
                    </label>
                  </div>

                  <div className="pt-2 flex justify-between">
                    <button
                      type="button"
                      onClick={() => setActiveStep(2)}
                      className="px-5 py-2 rounded-full bg-white text-[#2E211E] hover:bg-[#FCE9E5] hover:text-[#913638] border border-[#E7D1CC] hover:border-[#EFB8B0] text-xs font-semibold active:scale-[0.98] shadow-xs transition-all cursor-pointer"
                    >
                      Back
                    </button>
                    <button
                      type="button"
                      onClick={() => setActiveStep(4)}
                      className="px-6 py-2.5 rounded-full bg-[#913638] text-white text-xs font-semibold hover:bg-[#74292B] active:scale-[0.98] shadow-xs transition-all flex items-center gap-1.5 cursor-pointer"
                    >
                      <span>Review Order</span>
                      <ArrowRight className="w-3.5 h-3.5" />
                    </button>
                  </div>
                </div>
              ) : activeStep > 3 ? (
                <div className="pt-3 text-xs text-[#786864]">
                  <p className="font-semibold text-[#2E211E]">{paymentMethod}</p>
                </div>
              ) : null}
            </div>

            {/* STEP 04: Review & Place Order */}
            {activeStep === 4 && (
              <div className="bg-white rounded-3xl border border-[#E7D1CC] p-6 shadow-boutique space-y-6 animate-in fade-in">
                <div className="flex items-center gap-2.5 pb-3 border-b border-[#E7D1CC]">
                  <span className="w-7 h-7 rounded-full text-xs font-bold bg-[#913638] text-white flex items-center justify-center">
                    04
                  </span>
                  <h3 className="font-serif-luxury text-base font-bold text-[#2E211E]">
                    Final Order Review
                  </h3>
                </div>

                <div className="p-4 rounded-2xl bg-[#FFF9F6] border border-[#E7D1CC] space-y-3 text-xs">
                  <div className="flex justify-between">
                    <span className="text-[#786864]">Recipient:</span>
                    <strong className="text-[#2E211E]">{fullName} ({phone})</strong>
                  </div>
                  <div className="flex justify-between">
                    <span className="text-[#786864]">Ship to:</span>
                    <span className="text-[#2E211E] text-right">{addressLine1}, {city}</span>
                  </div>
                  <div className="flex justify-between">
                    <span className="text-[#786864]">Payment Mode:</span>
                    <strong className="text-[#913638]">{paymentMethod}</strong>
                  </div>
                </div>

                <button
                  type="button"
                  onClick={handlePlaceOrder}
                  className="w-full py-4 px-8 rounded-full bg-[#913638] text-white text-sm font-bold tracking-wide hover:bg-[#74292B] active:scale-[0.98] shadow-xs hover:shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer"
                >
                  <Sparkles className="w-4 h-4 text-[#C69A5A]" />
                  <span>Place Order & Start Crafting →</span>
                </button>
              </div>
            )}

          </div>

          {/* Right Order Summary Box (Spans 5 cols on lg) */}
          <div className="lg:col-span-5 hidden lg:block sticky top-28">
            <div className="bg-white rounded-3xl border border-[#E7D1CC] p-6 sm:p-8 shadow-boutique space-y-6">
              <h3 className="font-serif-luxury text-lg font-bold text-[#2E211E] pb-3 border-b border-[#E7D1CC]">
                Order Items ({items.length})
              </h3>

              <div className="divide-y divide-[#E7D1CC]/60 max-h-72 overflow-y-auto pr-1">
                {items.map((item) => {
                  const isSaved = isInWishlist(item.product.id);
                  return (
                    <div key={item.id} className="py-3 first:pt-0 last:pb-0 flex items-center gap-3">
                      <div className="relative w-14 h-14 rounded-xl overflow-hidden bg-[#FFF9F6] shrink-0 border border-[#E7D1CC]">
                        <Image
                          src={normalizeImageUrl(item.product.main_image || item.product.images?.[0], "/images/products/bunny-keychain.jpg")}
                          alt={item.product.name}
                          fill
                          sizes="56px"
                          className="object-cover"
                        />
                        <span className="absolute top-0 right-0 bg-[#913638] text-white text-[10px] font-bold w-4 h-4 rounded-bl flex items-center justify-center">
                          {item.quantity}
                        </span>
                      </div>
                      <div className="flex-1 min-w-0">
                        <p className="text-xs font-bold text-[#2E211E] truncate">
                          {item.product.name}
                        </p>
                        {item.customization?.color && (
                          <p className="text-[10px] text-[#786864]">
                            {item.customization.color.name}
                          </p>
                        )}
                        <button
                          type="button"
                          onClick={() => toggleWishlist(item.product)}
                          className="mt-0.5 inline-flex items-center gap-1 text-[10px] font-semibold text-[#913638] hover:underline cursor-pointer"
                        >
                          <Heart className={`w-3 h-3 ${isSaved ? "fill-[#913638] text-[#913638]" : ""}`} />
                          <span>{isSaved ? "Remove Wishlist" : "Add Wishlist"}</span>
                        </button>
                      </div>
                      <span className="text-xs font-bold text-[#913638]">
                        ₹{(item.price * item.quantity).toLocaleString("en-IN")}
                      </span>
                    </div>
                  );
                })}
              </div>

              {/* Price Details */}
              <div className="space-y-2.5 pt-4 border-t border-[#E7D1CC] text-xs text-[#786864]">
                <div className="flex justify-between">
                  <span>Subtotal</span>
                  <span className="font-semibold text-[#2E211E]">
                    ₹{subtotal.toLocaleString("en-IN")}
                  </span>
                </div>
                {discount > 0 && (
                  <div className="flex justify-between text-[#913638]">
                    <span>Discount</span>
                    <span className="font-bold">-₹{discount.toLocaleString("en-IN")}</span>
                  </div>
                )}
                <div className="flex justify-between">
                  <span>Shipping</span>
                  <span>{shipping === 0 ? <strong className="text-emerald-800">FREE</strong> : `₹${shipping}`}</span>
                </div>
                <div className="flex justify-between text-base font-bold text-[#2E211E] pt-3 border-t border-[#E7D1CC]">
                  <span>Total Due</span>
                  <span className="text-[#913638] text-lg">
                    ₹{total.toLocaleString("en-IN")}
                  </span>
                </div>
              </div>
            </div>
          </div>

        </div>

      </div>
    </div>
  );
}
