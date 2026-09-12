"use client";

import React, { useState } from "react";
import Link from "next/link";
import { useRouter } from "next/navigation";
import { FlowerIcon, KnotelleCrownLogo } from "@/components/ui/BotanicalDecorations";
import { useAuth } from "@/context/AuthContext";
import { ArrowRight, Sparkles } from "lucide-react";

export default function SignupPage() {
  const router = useRouter();
  const { login } = useAuth();

  const [firstName, setFirstName] = useState("");
  const [lastName, setLastName] = useState("");
  const [email, setEmail] = useState("");
  const [phone, setPhone] = useState("");
  const [password, setPassword] = useState("");
  const [confirmPassword, setConfirmPassword] = useState("");

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    login(email || "ananya.sharma@example.com");
    router.push("/account");
  };

  return (
    <div className="bg-[#FFF9F6] min-h-screen py-10 sm:py-16 flex items-center justify-center px-4">
      <div className="w-full max-w-lg bg-white rounded-3xl border border-[#E7D1CC] p-6 sm:p-10 shadow-boutique space-y-6">
        
        <div className="text-center space-y-2">
          <div className="flex justify-center mb-2">
            <KnotelleCrownLogo className="h-20 sm:h-24 w-auto" />
          </div>
          <h1 className="font-serif-luxury text-2xl sm:text-3xl font-bold text-[#2E211E]">
            Create Your Account
          </h1>
          <p className="text-xs text-[#786864] leading-relaxed">
            Join the KNOTELLE community & receive 10% off your first handmade order.
          </p>
        </div>

        <form onSubmit={handleSubmit} className="space-y-4">
          <div className="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
            <div className="space-y-1">
              <label className="text-xs font-semibold text-[#2E211E] block">
                First Name *
              </label>
              <input
                type="text"
                required
                value={firstName}
                onChange={(e) => setFirstName(e.target.value)}
                placeholder="Ananya"
                className="w-full px-4 py-2.5 rounded-xl bg-[#FFF9F6] border border-[#E7D1CC] text-xs text-[#2E211E] focus:outline-none focus:border-[#913638] transition-colors"
              />
            </div>
            <div className="space-y-1">
              <label className="text-xs font-semibold text-[#2E211E] block">
                Last Name *
              </label>
              <input
                type="text"
                required
                value={lastName}
                onChange={(e) => setLastName(e.target.value)}
                placeholder="Sharma"
                className="w-full px-4 py-2.5 rounded-xl bg-[#FFF9F6] border border-[#E7D1CC] text-xs text-[#2E211E] focus:outline-none focus:border-[#913638] transition-colors"
              />
            </div>
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
            <div className="space-y-1">
              <label className="text-xs font-semibold text-[#2E211E] block">
                Email Address *
              </label>
              <input
                type="email"
                required
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                placeholder="you@example.com"
                className="w-full px-4 py-2.5 rounded-xl bg-[#FFF9F6] border border-[#E7D1CC] text-xs text-[#2E211E] focus:outline-none focus:border-[#913638] transition-colors"
              />
            </div>
            <div className="space-y-1">
              <label className="text-xs font-semibold text-[#2E211E] block">
                Phone Number *
              </label>
              <div className="relative flex items-center rounded-xl bg-[#FFF9F6] border border-[#E7D1CC] focus-within:border-[#913638] focus-within:ring-1 focus-within:ring-[#913638] transition-all overflow-hidden">
                <div className="flex items-center gap-1.5 px-3.5 py-2.5 bg-[#FCE9E5] border-r border-[#E7D1CC] text-xs font-bold text-[#913638] shrink-0 select-none">
                  <span className="text-sm leading-none">🇮🇳</span>
                  <span>+91</span>
                </div>
                <input
                  type="tel"
                  required
                  inputMode="numeric"
                  pattern="[0-9]{10}"
                  maxLength={10}
                  value={phone}
                  onChange={(e) => setPhone(e.target.value.replace(/\D/g, "").slice(0, 10))}
                  placeholder="98765 43210"
                  className="w-full px-3.5 py-2.5 bg-transparent text-xs text-[#2E211E] placeholder-[#786864]/50 focus:outline-none"
                />
              </div>
            </div>
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
            <div className="space-y-1">
              <label className="text-xs font-semibold text-[#2E211E] block">
                Password *
              </label>
              <input
                type="password"
                required
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                placeholder="••••••••"
                className="w-full px-4 py-2.5 rounded-xl bg-[#FFF9F6] border border-[#E7D1CC] text-xs text-[#2E211E] focus:outline-none focus:border-[#913638] transition-colors"
              />
            </div>
            <div className="space-y-1">
              <label className="text-xs font-semibold text-[#2E211E] block">
                Confirm Password *
              </label>
              <input
                type="password"
                required
                value={confirmPassword}
                onChange={(e) => setConfirmPassword(e.target.value)}
                placeholder="••••••••"
                className="w-full px-4 py-2.5 rounded-xl bg-[#FFF9F6] border border-[#E7D1CC] text-xs text-[#2E211E] focus:outline-none focus:border-[#913638] transition-colors"
              />
            </div>
          </div>

          <div className="pt-2">
            <button
              type="submit"
              className="w-full py-3.5 rounded-full bg-[#913638] text-white text-xs sm:text-sm font-semibold hover:bg-[#74292B] active:scale-[0.98] shadow-sm hover:shadow-boutique-hover transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer"
            >
              <span>Create Account</span>
              <ArrowRight className="w-4 h-4" />
            </button>
          </div>
        </form>

        <p className="text-center text-xs text-[#786864]">
          Already have an account?{" "}
          <Link href="/login" className="text-[#913638] font-semibold hover:underline">
            Login here
          </Link>
        </p>

      </div>
    </div>
  );
}
