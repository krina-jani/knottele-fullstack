"use client";

import React, { useState } from "react";
import Link from "next/link";
import { useRouter } from "next/navigation";
import { Lock, Mail, ArrowRight, Sparkles } from "lucide-react";
import { FlowerIcon, BotanicalFlourish, KnotelleCrownLogo } from "@/components/ui/BotanicalDecorations";
import { useAuth } from "@/context/AuthContext";

export default function LoginPage() {
  const router = useRouter();
  const { login } = useAuth();
  const [email, setEmail] = useState("ananya.sharma@example.com");
  const [password, setPassword] = useState("knotelle123");
  const [rememberMe, setRememberMe] = useState(true);

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    login(email);
    router.push("/account");
  };

  return (
    <div className="bg-[#FFF9F6] min-h-screen py-10 sm:py-16 flex items-center justify-center px-4">
      <div className="w-full max-w-md bg-white rounded-3xl border border-[#E7D1CC] p-6 sm:p-10 shadow-boutique space-y-6 relative overflow-hidden">
        
        {/* Header */}
        <div className="text-center space-y-2">
          <div className="flex justify-center mb-2">
            <KnotelleCrownLogo className="h-20 sm:h-24 w-auto" />
          </div>
          <h1 className="font-serif-luxury text-2xl sm:text-3xl font-bold text-[#2E211E]">
            Welcome Back
          </h1>
          <p className="text-xs text-[#786864] leading-relaxed">
            Sign in to track orders, save wishlists, and request custom creations.
          </p>
        </div>

        {/* Form */}
        <form onSubmit={handleSubmit} className="space-y-4">
          <div className="space-y-1">
            <label className="text-xs font-semibold text-[#2E211E] block">
              Email Address
            </label>
            <div className="relative">
              <input
                type="email"
                required
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                placeholder="you@example.com"
                className="w-full pl-10 pr-4 py-2.5 rounded-xl bg-[#FFF9F6] border border-[#E7D1CC] text-xs text-[#2E211E] focus:outline-none focus:border-[#913638] transition-colors"
              />
              <Mail className="w-4 h-4 text-[#786864] absolute left-3.5 top-1/2 -translate-y-1/2" />
            </div>
          </div>

          <div className="space-y-1">
            <div className="flex items-center justify-between">
              <label className="text-xs font-semibold text-[#2E211E]">
                Password
              </label>
              <a href="#" className="text-[11px] text-[#913638] hover:underline">
                Forgot password?
              </a>
            </div>
            <div className="relative">
              <input
                type="password"
                required
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                placeholder="••••••••"
                className="w-full pl-10 pr-4 py-2.5 rounded-xl bg-[#FFF9F6] border border-[#E7D1CC] text-xs text-[#2E211E] focus:outline-none focus:border-[#913638] transition-colors"
              />
              <Lock className="w-4 h-4 text-[#786864] absolute left-3.5 top-1/2 -translate-y-1/2" />
            </div>
          </div>

          <div className="flex items-center">
            <label className="flex items-center gap-2 text-xs text-[#786864] cursor-pointer">
              <input
                type="checkbox"
                checked={rememberMe}
                onChange={(e) => setRememberMe(e.target.checked)}
                className="rounded border-[#E7D1CC] text-[#913638] focus:ring-[#913638]"
              />
              <span>Remember me on this device</span>
            </label>
          </div>

          <button
            type="submit"
            className="w-full py-3.5 rounded-full bg-[#913638] text-white text-xs sm:text-sm font-semibold hover:bg-[#74292B] active:scale-[0.98] shadow-sm hover:shadow-boutique-hover transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer"
          >
            <span>Login to KNOTELLE</span>
            <ArrowRight className="w-4 h-4" />
          </button>
        </form>

        {/* Divider */}
        <div className="flex items-center justify-center gap-3 text-xs text-[#786864]">
          <div className="h-px flex-1 bg-[#E7D1CC]" />
          <span>OR</span>
          <div className="h-px flex-1 bg-[#E7D1CC]" />
        </div>

        {/* Google Login */}
        <button
          onClick={() => {
            login("ananya.sharma@example.com");
            router.push("/account");
          }}
          className="w-full py-3 rounded-full bg-white text-[#2E211E] border border-[#E7D1CC] hover:border-[#EFB8B0] text-xs sm:text-sm font-semibold hover:bg-[#FCE9E5] hover:text-[#913638] active:scale-[0.98] transition-all duration-200 flex items-center justify-center gap-2.5 cursor-pointer shadow-xs"
        >
          <svg className="w-4 h-4" viewBox="0 0 24 24">
            <path
              fill="#4285F4"
              d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
            />
            <path
              fill="#34A853"
              d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
            />
            <path
              fill="#FBBC05"
              d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"
            />
            <path
              fill="#EA4335"
              d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"
            />
          </svg>
          <span>Continue with Google</span>
        </button>

        {/* Footer */}
        <p className="text-center text-xs text-[#786864]">
          Don&apos;t have an account?{" "}
          <Link href="/signup" className="text-[#913638] font-semibold hover:underline">
            Create one
          </Link>
        </p>

      </div>
    </div>
  );
}
