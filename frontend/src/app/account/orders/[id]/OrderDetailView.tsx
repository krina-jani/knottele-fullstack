"use client";

import React from "react";
import Image from "next/image";
import Link from "next/link";
import { notFound } from "next/navigation";
import {
  Clock,
  ArrowLeft,
  Sparkles,
} from "lucide-react";
import { useAuth } from "@/context/AuthContext";

interface OrderDetailViewProps {
  id: string;
}

export default function OrderDetailView({ id }: OrderDetailViewProps) {
  const { orders } = useAuth();

  const order = orders.find((o) => o.id === id) || orders[0];

  if (!order) {
    notFound();
  }

  return (
    <div className="space-y-8 animate-in fade-in">
      
      {/* Back Link & Header */}
      <div className="bg-white rounded-3xl border border-[#E7D1CC] p-6 sm:p-8 shadow-boutique space-y-4">
        <Link
          href="/account/orders"
          className="inline-flex items-center gap-1.5 text-xs font-semibold text-[#913638] hover:underline"
        >
          <ArrowLeft className="w-3.5 h-3.5" />
          <span>Back to All Orders</span>
        </Link>

        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-2 border-t border-[#E7D1CC]">
          <div>
            <span className="text-[10px] uppercase font-bold text-[#786864] tracking-wider">
              Handmade Boutique Order
            </span>
            <h1 className="font-mono text-xl sm:text-2xl font-bold text-[#913638]">
              {order.orderNumber}
            </h1>
            <p className="text-xs text-[#786864] mt-0.5">
              Placed on {order.orderDate} &bull; Est. Delivery: {order.estimatedDelivery}
            </p>
          </div>

          <div className="text-left sm:text-right">
            <span className="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#FCE9E5] text-[#913638] border border-[#E7D1CC]">
              <Sparkles className="w-3.5 h-3.5 text-[#C69A5A]" />
              <span>{order.status}</span>
            </span>
            {order.trackingNumber && (
              <p className="text-[11px] text-[#786864] mt-1">
                Tracking: <strong className="font-mono text-[#2E211E]">{order.trackingNumber}</strong> ({order.courierName})
              </p>
            )}
          </div>
        </div>
      </div>

      {/* Handmade Progress Timeline */}
      <div className="bg-white rounded-3xl border border-[#E7D1CC] p-6 sm:p-8 shadow-boutique space-y-6">
        <div className="flex items-center justify-between border-b border-[#E7D1CC] pb-3">
          <div className="flex items-center gap-2">
            <Clock className="w-4 h-4 text-[#913638]" />
            <h2 className="font-serif-luxury text-lg font-bold text-[#2E211E]">
              Crafting & Delivery Journey
            </h2>
          </div>
          <span className="text-xs text-[#786864]">Live Status</span>
        </div>

        {/* Numbered & Icon Timeline */}
        <div className="relative pl-6 sm:pl-8 space-y-8 before:absolute before:left-2.5 sm:before:left-3.5 before:top-2 before:bottom-2 before:w-0.5 before:bg-[#E7D1CC]">
          {order.timeline.map((step, index) => {
            const isDone = step.completed;
            const isCurrent = step.current;
            return (
              <div key={index} className="relative group">
                {/* Step Circle Indicator */}
                <div
                  className={`absolute -left-6 sm:-left-8 top-0.5 w-6 h-6 sm:w-7 sm:h-7 rounded-full flex items-center justify-center text-xs font-bold transition-all ${
                    isCurrent
                      ? "bg-[#913638] text-white ring-4 ring-[#913638]/20"
                      : isDone
                      ? "bg-[#FCE9E5] text-[#913638] border border-[#E7D1CC]"
                      : "bg-[#FFF9F6] text-[#786864] border border-[#E7D1CC]"
                  }`}
                >
                  {isDone ? "✓" : index + 1}
                </div>

                <div className="space-y-0.5">
                  <div className="flex items-center gap-2">
                    <h3 className={`text-xs sm:text-sm font-bold ${isCurrent ? "text-[#913638]" : isDone ? "text-[#2E211E]" : "text-[#786864]"}`}>
                      {step.status}
                    </h3>
                    {isCurrent && (
                      <span className="text-[10px] font-semibold bg-[#913638] text-white px-2 py-0.5 rounded-full">
                        In Progress
                      </span>
                    )}
                  </div>
                  <p className="text-[11px] text-[#786864]">{step.description}</p>
                  <span className="text-[10px] text-[#786864]/80 font-mono block pt-0.5">
                    {step.date}
                  </span>
                </div>
              </div>
            );
          })}
        </div>
      </div>

      {/* Order Items & Breakdown */}
      <div className="grid grid-cols-1 md:grid-cols-12 gap-6">
        
        {/* Items List (Spans 7 cols) */}
        <div className="md:col-span-7 bg-white rounded-3xl border border-[#E7D1CC] p-6 shadow-boutique space-y-4">
          <h3 className="font-serif-luxury text-base font-bold text-[#2E211E] pb-3 border-b border-[#E7D1CC]">
            Items in this Package ({order.items.length})
          </h3>

          <div className="divide-y divide-[#E7D1CC]/60 space-y-3">
            {order.items.map((item) => (
              <div key={item.id} className="pt-3 first:pt-0 flex gap-4 items-center">
                <div className="relative w-16 h-16 rounded-xl overflow-hidden bg-[#FFF9F6] shrink-0 border border-[#E7D1CC]">
                  <Image
                    src={item.product.images[0]}
                    alt={item.product.name}
                    fill
                    sizes="64px"
                    className="object-cover"
                  />
                </div>
                <div className="flex-1 min-w-0">
                  <p className="text-xs font-bold text-[#2E211E] truncate">
                    {item.product.name}
                  </p>
                  {item.customization?.color && (
                    <p className="text-[10px] text-[#786864]">
                      Color: {item.customization.color.name}
                    </p>
                  )}
                  {item.customization?.customText && (
                    <p className="text-[10px] text-[#913638] font-semibold">
                      Tag: &ldquo;{item.customization.customText}&rdquo;
                    </p>
                  )}
                  <p className="text-[11px] text-[#786864] mt-0.5">
                    Qty: {item.quantity} &bull; ₹{item.price.toLocaleString("en-IN")} each
                  </p>
                </div>
                <span className="text-xs font-bold text-[#913638]">
                  ₹{(item.price * item.quantity).toLocaleString("en-IN")}
                </span>
              </div>
            ))}
          </div>
        </div>

        {/* Shipping & Payment (Spans 5 cols) */}
        <div className="md:col-span-5 space-y-6">
          
          <div className="bg-white rounded-3xl border border-[#E7D1CC] p-6 shadow-boutique space-y-3 text-xs">
            <h3 className="font-serif-luxury text-base font-bold text-[#2E211E] pb-2 border-b border-[#E7D1CC]">
              Delivery Address
            </h3>
            <p className="font-bold text-[#2E211E]">{order.shippingAddress.fullName}</p>
            <p className="text-[#786864] leading-relaxed">
              {order.shippingAddress.addressLine1}, {order.shippingAddress.addressLine2}
              <br />
              {order.shippingAddress.city}, {order.shippingAddress.state} - {order.shippingAddress.pincode}
            </p>
            <p className="text-[#786864] pt-1">
              Phone: {order.shippingAddress.phone}
            </p>
          </div>

          <div className="bg-white rounded-3xl border border-[#E7D1CC] p-6 shadow-boutique space-y-3 text-xs">
            <h3 className="font-serif-luxury text-base font-bold text-[#2E211E] pb-2 border-b border-[#E7D1CC]">
              Payment Summary
            </h3>
            <div className="space-y-1.5 text-[#786864]">
              <div className="flex justify-between">
                <span>Subtotal:</span>
                <span className="font-semibold text-[#2E211E]">₹{order.subtotal.toLocaleString("en-IN")}</span>
              </div>
              {order.discount > 0 && (
                <div className="flex justify-between text-[#913638]">
                  <span>Discount:</span>
                  <span className="font-bold">-₹{order.discount.toLocaleString("en-IN")}</span>
                </div>
              )}
              <div className="flex justify-between">
                <span>Shipping:</span>
                <span>{order.shipping === 0 ? "FREE" : `₹${order.shipping}`}</span>
              </div>
              <div className="flex justify-between text-sm font-bold text-[#2E211E] pt-2 border-t border-[#E7D1CC]">
                <span>Total Paid:</span>
                <span className="text-[#913638]">₹{order.total.toLocaleString("en-IN")}</span>
              </div>
              <p className="text-[10px] text-[#786864] pt-1">
                Mode: {order.paymentMethod} ({order.paymentStatus})
              </p>
            </div>
          </div>

        </div>

      </div>

    </div>
  );
}
