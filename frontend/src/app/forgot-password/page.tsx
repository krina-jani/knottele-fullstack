"use client";

import React, { useState } from "react";
import Link from "next/link";
import { useRouter } from "next/navigation";
import { Mail, Lock, Eye, EyeOff, ArrowLeft, ArrowRight, CheckCircle2 } from "lucide-react";
import { KnotelleCrownLogo } from "@/components/ui/BotanicalDecorations";
import { useToast } from "@/context/ToastContext";
import { forgotCustomerPassword, resetCustomerPassword } from "@/lib/api";

export default function ForgotPasswordPage() {
  const router = useRouter();
  const { showToast } = useToast();

  const [step, setStep] = useState<1 | 2>(1);
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [confirmPassword, setConfirmPassword] = useState("");
  const [showPassword, setShowPassword] = useState(false);
  const [showConfirmPassword, setShowConfirmPassword] = useState(false);

  const [loading, setLoading] = useState(false);
  const [errorMsg, setErrorMsg] = useState<string | null>(null);
  const [successMsg, setSuccessMsg] = useState<string | null>(null);

  const handleSendResetEmail = async (e: React.FormEvent) => {
    e.preventDefault();
    setErrorMsg(null);
    setSuccessMsg(null);

    const cleanEmail = email.trim().toLowerCase();
    if (!cleanEmail || !cleanEmail.includes("@")) {
      setErrorMsg("Please enter a valid registered email address.");
      return;
    }

    setLoading(true);
    try {
      const res = await forgotCustomerPassword(cleanEmail);
      if (res.success) {
        setSuccessMsg(res.message || "Email verified! You can now set a new password.");
        setStep(2);
      } else {
        setErrorMsg(res.message || "No account found with this email address.");
      }
    } catch {
      setErrorMsg("Connection error. Please try again.");
    } finally {
      setLoading(false);
    }
  };

  const handleUpdatePassword = async (e: React.FormEvent) => {
    e.preventDefault();
    setErrorMsg(null);

    if (!password || password.length < 6) {
      setErrorMsg("Password must be at least 6 characters.");
      return;
    }
    if (password !== confirmPassword) {
      setErrorMsg("Passwords do not match.");
      return;
    }

    setLoading(true);
    try {
      const res = await resetCustomerPassword({
        email: email.trim().toLowerCase(),
        password,
      });

      if (res.success) {
        showToast("Password Reset! ✨", "Your password has been updated successfully. Please log in.", "success");
        router.push("/login");
      } else {
        setErrorMsg(res.message || "Failed to update password.");
      }
    } catch {
      setErrorMsg("Connection error. Please try again.");
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="bg-[#FFF9F6] min-h-screen py-10 sm:py-16 flex items-center justify-center px-4">
      <div className="w-full max-w-md mx-auto bg-white rounded-3xl border border-[#E7D1CC] p-6 sm:p-10 shadow-boutique relative overflow-hidden">
        
        {/* Crown Logo Header */}
        <div className="text-center space-y-2 mb-6">
          <div className="flex justify-center mb-1">
            <KnotelleCrownLogo className="h-20 sm:h-24 w-auto" />
          </div>
          <h1 className="font-serif-luxury text-2xl sm:text-3xl font-bold text-[#2E211E] tracking-tight">
            Reset Password
          </h1>
          <p className="text-xs sm:text-sm text-[#786864]">
            {step === 1
              ? "Enter your registered email address to recover your account."
              : `Create a new password for ${email}`}
          </p>
        </div>

        {/* Error Alert Box */}
        {errorMsg && (
          <div className="mb-4 p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium flex items-center justify-between animate-in fade-in">
            <span>{errorMsg}</span>
            <button onClick={() => setErrorMsg(null)} className="text-rose-500 hover:text-rose-800 font-bold ml-2">
              ✕
            </button>
          </div>
        )}

        {/* Success Alert Box */}
        {successMsg && (
          <div className="mb-4 p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium flex items-center gap-2 animate-in fade-in">
            <CheckCircle2 className="w-4 h-4 text-emerald-600 shrink-0" />
            <span>{successMsg}</span>
          </div>
        )}

        {step === 1 ? (
          <form onSubmit={handleSendResetEmail} className="space-y-4">
            <div className="space-y-1">
              <label className="text-[11px] font-bold tracking-wider text-[#2E211E] uppercase block">
                REGISTERED EMAIL ADDRESS
              </label>
              <div className="relative">
                <input
                  type="email"
                  required
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  placeholder="Enter your registered email"
                  className="w-full pl-10 pr-4 py-2.5 rounded-xl bg-[#FFF9F6] border border-[#E7D1CC] text-xs text-[#2E211E] placeholder-[#786864]/50 focus:outline-none focus:border-[#913638] focus:ring-1 focus:ring-[#913638] transition-colors"
                />
                <Mail className="w-4 h-4 text-[#786864] absolute left-3.5 top-1/2 -translate-y-1/2" />
              </div>
            </div>

            <button
              type="submit"
              disabled={loading}
              className="w-full py-3.5 rounded-full bg-[#913638] text-white text-xs sm:text-sm font-semibold hover:bg-[#74292B] active:scale-[0.98] shadow-sm hover:shadow-boutique-hover transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer disabled:opacity-60 disabled:cursor-not-allowed"
            >
              {loading ? (
                <div className="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin" />
              ) : (
                <>
                  <span>Verify Email & Continue</span>
                  <ArrowRight className="w-4 h-4" />
                </>
              )}
            </button>
          </form>
        ) : (
          <form onSubmit={handleUpdatePassword} className="space-y-4">
            <div className="space-y-1">
              <label className="text-[11px] font-bold tracking-wider text-[#2E211E] uppercase block">
                NEW PASSWORD
              </label>
              <div className="relative">
                <input
                  type={showPassword ? "text" : "password"}
                  required
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                  placeholder="Enter new password (min. 6 chars)"
                  className="w-full pl-10 pr-10 py-2.5 rounded-xl bg-[#FFF9F6] border border-[#E7D1CC] text-xs text-[#2E211E] placeholder-[#786864]/50 focus:outline-none focus:border-[#913638] focus:ring-1 focus:ring-[#913638] transition-colors"
                />
                <Lock className="w-4 h-4 text-[#786864] absolute left-3.5 top-1/2 -translate-y-1/2" />
                <button
                  type="button"
                  onClick={() => setShowPassword(!showPassword)}
                  className="absolute right-3.5 top-1/2 -translate-y-1/2 text-[#786864] hover:text-[#913638] transition-colors"
                >
                  {showPassword ? <EyeOff className="w-4 h-4" /> : <Eye className="w-4 h-4" />}
                </button>
              </div>
            </div>

            <div className="space-y-1">
              <label className="text-[11px] font-bold tracking-wider text-[#2E211E] uppercase block">
                CONFIRM NEW PASSWORD
              </label>
              <div className="relative">
                <input
                  type={showConfirmPassword ? "text" : "password"}
                  required
                  value={confirmPassword}
                  onChange={(e) => setConfirmPassword(e.target.value)}
                  placeholder="Confirm new password"
                  className="w-full pl-10 pr-10 py-2.5 rounded-xl bg-[#FFF9F6] border border-[#E7D1CC] text-xs text-[#2E211E] placeholder-[#786864]/50 focus:outline-none focus:border-[#913638] focus:ring-1 focus:ring-[#913638] transition-colors"
                />
                <Lock className="w-4 h-4 text-[#786864] absolute left-3.5 top-1/2 -translate-y-1/2" />
                <button
                  type="button"
                  onClick={() => setShowConfirmPassword(!showConfirmPassword)}
                  className="absolute right-3.5 top-1/2 -translate-y-1/2 text-[#786864] hover:text-[#913638] transition-colors"
                >
                  {showConfirmPassword ? <EyeOff className="w-4 h-4" /> : <Eye className="w-4 h-4" />}
                </button>
              </div>
            </div>

            <button
              type="submit"
              disabled={loading}
              className="w-full py-3.5 rounded-full bg-[#913638] text-white text-xs sm:text-sm font-semibold hover:bg-[#74292B] active:scale-[0.98] shadow-sm hover:shadow-boutique-hover transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer disabled:opacity-60 disabled:cursor-not-allowed"
            >
              {loading ? (
                <div className="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin" />
              ) : (
                <>
                  <span>Update Password & Log In</span>
                  <ArrowRight className="w-4 h-4" />
                </>
              )}
            </button>
          </form>
        )}

        <div className="mt-6 pt-6 border-t border-[#E7D1CC] text-center">
          <Link
            href="/login"
            className="inline-flex items-center gap-1.5 text-xs font-bold text-[#913638] hover:underline uppercase tracking-wide"
          >
            <ArrowLeft className="w-3.5 h-3.5" />
            <span>Back to Login</span>
          </Link>
        </div>

      </div>
    </div>
  );
}
