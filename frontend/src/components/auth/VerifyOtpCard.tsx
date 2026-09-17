"use client";

import React, { useState, useEffect, useRef } from "react";
import Link from "next/link";
import { useRouter } from "next/navigation";
import { ArrowRight, Mail, RefreshCw, CheckCircle2, AlertCircle, ArrowLeft } from "lucide-react";
import { KnotelleCrownLogo, BotanicalFlourish } from "@/components/ui/BotanicalDecorations";
import { useAuth } from "@/context/AuthContext";
import { useToast } from "@/context/ToastContext";
import { verifyCustomerOtp, resendCustomerOtp, sanitizeRedirectUrl } from "@/lib/api";

interface VerifyOtpCardProps {
  email: string;
  onSuccessRedirect?: string;
}

export function VerifyOtpCard({ email, onSuccessRedirect = "/account" }: VerifyOtpCardProps) {
  const router = useRouter();
  const { login } = useAuth();
  const { showToast } = useToast();

  const [digits, setDigits] = useState<string[]>(Array(6).fill(""));
  const [loading, setLoading] = useState(false);
  const [resending, setResending] = useState(false);
  const [errorMsg, setErrorMsg] = useState<string | null>(null);
  const [cooldown, setCooldown] = useState(60);

  const inputRefs = useRef<(HTMLInputElement | null)[]>([]);

  // Focus first digit box on mount
  useEffect(() => {
    if (inputRefs.current[0]) {
      inputRefs.current[0].focus();
    }
  }, []);

  // Countdown timer for Resend OTP
  useEffect(() => {
    if (cooldown <= 0) return;
    const timer = setInterval(() => {
      setCooldown((prev) => prev - 1);
    }, 1000);
    return () => clearInterval(timer);
  }, [cooldown]);

  const handleChange = (index: number, value: string) => {
    // Only accept numeric digits
    const cleanValue = value.replace(/\D/g, "");
    if (!cleanValue && value !== "") return;

    const newDigits = [...digits];
    
    if (cleanValue.length > 1) {
      // Handle multi-character paste or input
      const pasted = cleanValue.slice(0, 6).split("");
      pasted.forEach((char, i) => {
        if (i < 6) newDigits[i] = char;
      });
      setDigits(newDigits);
      const nextIndex = Math.min(pasted.length, 5);
      if (inputRefs.current[nextIndex]) {
        inputRefs.current[nextIndex]?.focus();
      }
      return;
    }

    newDigits[index] = cleanValue;
    setDigits(newDigits);
    setErrorMsg(null);

    // Auto-advance focus
    if (cleanValue && index < 5) {
      inputRefs.current[index + 1]?.focus();
    }
  };

  const handleKeyDown = (index: number, e: React.KeyboardEvent<HTMLInputElement>) => {
    if (e.key === "Backspace") {
      if (!digits[index] && index > 0) {
        // Move to previous input on backspace if current is empty
        inputRefs.current[index - 1]?.focus();
      }
    } else if (e.key === "ArrowLeft" && index > 0) {
      inputRefs.current[index - 1]?.focus();
    } else if (e.key === "ArrowRight" && index < 5) {
      inputRefs.current[index + 1]?.focus();
    }
  };

  const handlePaste = (e: React.ClipboardEvent) => {
    e.preventDefault();
    const pastedData = e.clipboardData.getData("text").replace(/\D/g, "").slice(0, 6);
    if (!pastedData) return;

    const newDigits = Array(6).fill("");
    pastedData.split("").forEach((char, i) => {
      newDigits[i] = char;
    });
    setDigits(newDigits);
    setErrorMsg(null);

    const targetIndex = Math.min(pastedData.length, 5);
    inputRefs.current[targetIndex]?.focus();
  };

  const handleVerify = async (e?: React.FormEvent) => {
    if (e) e.preventDefault();
    setErrorMsg(null);

    const otpCode = digits.join("");
    if (otpCode.length !== 6) {
      setErrorMsg("Please enter all 6 digits of your verification code.");
      return;
    }

    setLoading(true);
    try {
      const result = await verifyCustomerOtp({
        email: email.trim().toLowerCase(),
        otp: otpCode,
      });

      if (result.success && result.token && result.user) {
        showToast("Email Verified! 🌸", "Welcome to KNOTELLE.", "success");
        login(email.trim().toLowerCase(), result.user, result.token);
        
        const finalRedirect = sanitizeRedirectUrl(onSuccessRedirect);
        router.push(finalRedirect);
      } else {
        setErrorMsg(result.message || "Invalid verification code. Please try again.");
        showToast("Verification Error", result.message || "Invalid code.", "error");
      }
    } catch (err: any) {
      setErrorMsg("Connection error. Please try again.");
    } finally {
      setLoading(false);
    }
  };

  const handleResend = async () => {
    if (cooldown > 0 || resending) return;
    setErrorMsg(null);
    setResending(true);

    try {
      const result = await resendCustomerOtp({ email: email.trim().toLowerCase() });
      if (result.success) {
        showToast("Code Resent! 🌸", "A new 6-digit code has been sent to your email.", "success");
        setCooldown(60);
        setDigits(Array(6).fill(""));
        if (inputRefs.current[0]) inputRefs.current[0].focus();
      } else {
        if (result.cooldown_remaining) {
          setCooldown(result.cooldown_remaining);
        }
        setErrorMsg(result.message || "Failed to resend verification code.");
        showToast("Resend Error", result.message || "Failed to resend code.", "error");
      }
    } catch (err) {
      setErrorMsg("Connection error. Please try again.");
    } finally {
      setResending(false);
    }
  };

  return (
    <div className="w-full max-w-md bg-white rounded-3xl border border-[#E7D1CC] shadow-xl p-6 sm:p-10 relative overflow-hidden">
      {/* Top Botanical Header Decoration */}
      <div className="text-center space-y-3 mb-6">
        <div className="inline-flex p-3 rounded-full bg-[#FFF9F6] border border-[#FCE9E5] text-[#913638] shadow-xs">
          <Mail className="w-6 h-6" />
        </div>
        <h1 className="text-2xl sm:text-3xl font-serif font-bold text-[#2E211E] tracking-tight">
          Verify Your Email
        </h1>
        <p className="text-xs sm:text-sm text-[#786864] leading-relaxed max-w-xs mx-auto">
          We&apos;ve sent a 6-digit verification code to
        </p>
        <div className="inline-block bg-[#FFF9F6] border border-[#E7D1CC] rounded-xl px-3.5 py-1.5 text-xs font-bold text-[#913638] break-all">
          {email || "your email address"}
        </div>
      </div>

      {/* ERROR ALERT */}
      {errorMsg && (
        <div className="mb-6 p-3.5 rounded-2xl bg-[#FDF4F2] border border-[#F8D7DA] text-xs text-[#721C24] flex items-center gap-2.5 animate-fadeIn">
          <AlertCircle className="w-4 h-4 text-[#913638] shrink-0" />
          <span>{errorMsg}</span>
        </div>
      )}

      {/* OTP INPUT FORM */}
      <form onSubmit={handleVerify} className="space-y-6">
        <div className="flex items-center justify-between gap-2 sm:gap-2.5 my-4" onPaste={handlePaste}>
          {digits.map((digit, idx) => (
            <input
              key={idx}
              ref={(el) => {
                inputRefs.current[idx] = el;
              }}
              type="text"
              inputMode="numeric"
              maxLength={1}
              value={digit}
              onChange={(e) => handleChange(idx, e.target.value)}
              onKeyDown={(e) => handleKeyDown(idx, e)}
              className="w-10 h-12 sm:w-12 sm:h-14 text-center text-lg sm:text-xl font-bold font-mono text-[#2E211E] bg-[#FFF9F6] border-2 rounded-2xl focus:outline-none transition-all duration-200 border-[#E7D1CC] focus:border-[#913638] focus:ring-2 focus:ring-[#913638]/20 shadow-xs"
              placeholder="•"
            />
          ))}
        </div>

        {/* VERIFY BUTTON */}
        <button
          type="submit"
          disabled={loading || digits.join("").length !== 6}
          className="w-full py-3.5 rounded-full bg-[#913638] text-white text-xs sm:text-sm font-semibold hover:bg-[#74292B] active:scale-[0.98] shadow-sm transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer disabled:opacity-60 disabled:cursor-not-allowed"
        >
          {loading ? (
            <div className="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin" />
          ) : (
            <>
              <span>Verify Code</span>
              <ArrowRight className="w-4 h-4" />
            </>
          )}
        </button>
      </form>

      {/* RESEND OTP & BACK TO SIGNUP */}
      <div className="mt-8 pt-6 border-t border-[#E7D1CC] text-center space-y-4">
        <div>
          {cooldown > 0 ? (
            <p className="text-xs text-[#786864] font-medium flex items-center justify-center gap-1.5">
              <span>Resend available in</span>
              <span className="font-bold text-[#913638] font-mono">{cooldown}s</span>
            </p>
          ) : (
            <button
              type="button"
              onClick={handleResend}
              disabled={resending}
              className="inline-flex items-center gap-1.5 text-xs font-bold text-[#913638] hover:underline uppercase tracking-wide cursor-pointer disabled:opacity-50"
            >
              <RefreshCw className={`w-3.5 h-3.5 ${resending ? "animate-spin" : ""}`} />
              <span>{resending ? "Sending..." : "Resend Code"}</span>
            </button>
          )}
        </div>

        <div>
          <Link
            href={`/signup${onSuccessRedirect && onSuccessRedirect !== "/account" ? `?redirectTo=${encodeURIComponent(onSuccessRedirect)}` : ""}`}
            className="inline-flex items-center gap-1 text-xs text-[#786864] hover:text-[#913638] font-semibold transition-colors"
          >
            <ArrowLeft className="w-3.5 h-3.5" />
            <span>Wrong email? Change details</span>
          </Link>
        </div>
      </div>
    </div>
  );
}
