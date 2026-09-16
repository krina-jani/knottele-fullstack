"use client";

import React from "react";
import Link from "next/link";
import Image from "next/image";
import { Package, Clock, CheckCircle2, Heart, ArrowRight, Sparkles } from "lucide-react";
import { useAuth } from "@/context/AuthContext";
import { useWishlist } from "@/context/WishlistContext";
import { FlowerIcon } from "@/components/ui/BotanicalDecorations";

export default function AccountDashboardPage() {
  const { user, orders } = useAuth();
  const { wishlistCount } = useWishlist();

  const totalOrders = orders.length;
  const pendingOrders = orders.filter((o) => o.status !== "Delivered" && o.status !== "Cancelled").length;
  const deliveredOrders = orders.filter((o) => o.status === "Delivered").length;

  const stats = [
    { title: "Total Orders", value: totalOrders, icon: Package, href: "/account/orders" },
    { title: "In Crafting / Transit", value: pendingOrders, icon: Clock, href: "/account/orders" },
    { title: "Delivered With Love", value: deliveredOrders, icon: CheckCircle2, href: "/account/orders" },
    { title: "Saved in Wishlist", value: wishlistCount, icon: Heart, href: "/account/wishlist" },
  ];

  return (
    <div className="space-y-8 animate-in fade-in">
      
      {/* Welcome Banner */}
      <div className="bg-white rounded-3xl border border-[#E7D1CC] p-6 sm:p-8 shadow-boutique flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
          <div className="flex items-center gap-2 text-xs font-semibold text-[#913638] mb-1">
            <FlowerIcon className="w-4 h-4 text-[#913638]" />
            <span>KNOTELLE Member Atelier</span>
          </div>
          <h1 className="font-serif-luxury text-2xl sm:text-3xl font-bold text-[#2E211E]">
            Welcome back, {user?.name ? user.name.split(" ")[0] : "Member"}!
          </h1>
          <p className="text-xs sm:text-sm text-[#786864] mt-1">
            Manage your bespoke crochet orders, track artisan progress, and saved favorites.
          </p>
        </div>

        <Link
          href="/shop"
          className="w-full sm:w-auto px-6 py-2.5 rounded-full bg-[#913638] text-white text-xs font-semibold hover:bg-[#74292B] active:scale-[0.98] shadow-sm transition-all text-center shrink-0"
        >
          Explore Shop →
        </Link>
      </div>

      {/* 4 Stat Cards */}
      <div className="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
        {stats.map((stat, idx) => {
          const Icon = stat.icon;
          return (
            <Link
              key={idx}
              href={stat.href}
              className="bg-white rounded-3xl border border-[#E7D1CC] p-4 sm:p-5 shadow-boutique hover:shadow-boutique-hover transition-all group block"
            >
              <div className="w-10 h-10 rounded-2xl bg-[#FCE9E5] border border-[#E7D1CC] flex items-center justify-center text-[#913638] mb-3 group-hover:bg-[#913638] group-hover:text-white transition-colors">
                <Icon className="w-5 h-5" />
              </div>
              <p className="font-serif-luxury text-2xl sm:text-3xl font-bold text-[#2E211E]">
                {stat.value}
              </p>
              <p className="text-xs text-[#786864] mt-0.5 group-hover:text-[#913638] transition-colors">
                {stat.title}
              </p>
            </Link>
          );
        })}
      </div>

      {/* Recent Orders Section */}
      <div className="bg-white rounded-3xl border border-[#E7D1CC] p-6 sm:p-8 shadow-boutique space-y-6">
        <div className="flex items-center justify-between pb-4 border-b border-[#E7D1CC]">
          <h2 className="font-serif-luxury text-xl font-bold text-[#2E211E]">
            Recent Orders
          </h2>
          <Link
            href="/account/orders"
            className="text-xs font-semibold text-[#913638] hover:underline flex items-center gap-1"
          >
            <span>View All</span>
            <ArrowRight className="w-3.5 h-3.5" />
          </Link>
        </div>

        {orders.length > 0 ? (
          <div className="space-y-4">
            {orders.slice(0, 2).map((order) => (
              <div
                key={order.id}
                className="p-4 sm:p-5 rounded-2xl bg-[#FFF9F6] border border-[#E7D1CC] flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4"
              >
                <div className="space-y-1">
                  <div className="flex items-center gap-2">
                    <span className="text-xs font-bold text-[#2E211E]">
                      {order.orderNumber}
                    </span>
                    <span className="text-[11px] px-2.5 py-0.5 rounded-full bg-[#FCE9E5] text-[#913638] font-semibold border border-[#E7D1CC]">
                      {order.status}
                    </span>
                  </div>
                  <p className="text-xs text-[#786864]">
                    Placed on {order.orderDate} &bull; {order.items.length} handmade item(s)
                  </p>
                  <p className="text-xs font-bold text-[#913638] pt-1">
                    Total: ₹{order.total.toLocaleString("en-IN")}
                  </p>
                </div>

                <Link
                  href={`/account/orders/${order.id}`}
                  className="w-full sm:w-auto px-5 py-2 rounded-full bg-white text-[#2E211E] border border-[#E7D1CC] hover:border-[#EFB8B0] text-xs font-semibold hover:bg-[#FCE9E5] hover:text-[#913638] active:scale-[0.98] transition-all text-center"
                >
                  Track Order Timeline →
                </Link>
              </div>
            ))}
          </div>
        ) : (
          <p className="text-xs text-[#786864] py-4">No recent orders found.</p>
        )}
      </div>

    </div>
  );
}
