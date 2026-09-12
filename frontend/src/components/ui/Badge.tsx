import React from "react";
import { clsx } from "clsx";
import { twMerge } from "tailwind-merge";

interface BadgeProps {
  children: React.ReactNode;
  variant?: "rose" | "pink" | "gold" | "secondary" | "outline" | "success";
  className?: string;
}

export function Badge({ children, variant = "secondary", className }: BadgeProps) {
  const base = "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium tracking-wide";
  const variants = {
    rose: "bg-[#913638] text-white",
    pink: "bg-[#FCE9E5] text-[#913638] border border-[#E7D1CC]",
    gold: "bg-[#FDF6E9] text-[#C69A5A] border border-[#C69A5A]/30",
    secondary: "bg-[#FCE9E5] text-[#913638] border border-[#E7D1CC]",
    outline: "border border-[#E7D1CC] text-[#786864] bg-white/80",
    success: "bg-emerald-50 text-emerald-800 border border-emerald-200",
  };

  return <span className={twMerge(clsx(base, variants[variant], className))}>{children}</span>;
}
