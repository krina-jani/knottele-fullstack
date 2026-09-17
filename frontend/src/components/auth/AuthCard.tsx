"use client";

import React, { useState } from "react";
import Link from "next/link";
import { useRouter } from "next/navigation";
import { Eye, EyeOff, Lock, Mail, Phone, User as UserIcon, ArrowRight, Check } from "lucide-react";
import { KnotelleCrownLogo, BotanicalFlourish } from "@/components/ui/BotanicalDecorations";
import { useAuth } from "@/context/AuthContext";
import { useToast } from "@/context/ToastContext";
import { registerCustomer, loginCustomer } from "@/lib/api";

interface AuthCardProps {
  defaultMode?: "signup" | "login";
  onSuccessRedirect?: string;
}

export function AuthCard({ defaultMode = "signup", onSuccessRedirect = "/account" }: AuthCardProps) {
  const router = useRouter();
  const { login } = useAuth();
  const { showToast } = useToast();

  const [mode, setMode] = useState<"signup" | "login">(defaultMode);
  const [loading, setLoading] = useState(false);
  const [errorMsg, setErrorMsg] = useState<string | null>(null);

  // Form Fields - Sign Up & Log In
  const [fullName, setFullName] = useState("");
  const [email, setEmail] = useState("");
  const [mobile, setMobile] = useState("");
  const [password, setPassword] = useState("");
  const [confirmPassword, setConfirmPassword] = useState("");
  const [showPassword, setShowPassword] = useState(false);
  const [showConfirmPassword, setShowConfirmPassword] = useState(false);
  const [rememberMe, setRememberMe] = useState(true);
  const [agreeTerms, setAgreeTerms] = useState(false);

  // Switch between Sign Up and Log In
  const switchMode = (newMode: "signup" | "login") => {
    setMode(newMode);
    setErrorMsg(null);
  };

  // Submit Handler
  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setErrorMsg(null);

    if (mode === "signup") {
      // Validation
      if (!fullName.trim()) {
        setErrorMsg("Please enter your full name.");
        return;
      }
      if (!email.trim()) {
        setErrorMsg("Please enter a valid email address.");
        return;
      }
      const cleanedMobile = mobile.replace(/\D/g, "");
      if (!cleanedMobile) {
        setErrorMsg("Please enter your 10-digit mobile number.");
        return;
      }
      if (cleanedMobile.length !== 10) {
        setErrorMsg("Mobile number must be exactly 10 digits.");
        return;
      }
      if (!password) {
        setErrorMsg("Please enter a password.");
        return;
      }
      if (password.length < 6) {
        setErrorMsg("Password must be at least 6 characters.");
        return;
      }
      if (password !== confirmPassword) {
        setErrorMsg("Passwords do not match.");
        return;
      }
      if (!agreeTerms) {
        setErrorMsg("You must agree to the Terms & Conditions and Privacy Policy.");
        return;
      }

      setLoading(true);
      try {
        const formattedMobile = `+91 ${cleanedMobile}`;
        const result = await registerCustomer({
          name: fullName.trim(),
          email: email.trim().toLowerCase(),
          mobile: formattedMobile,
          password: password,
        });

        if (result.success) {
          showToast("Account Created! 🌸", "Welcome to the Knotelle Crochet community.", "success");
          login(email.trim().toLowerCase(), result.user || { name: fullName, email, mobile: formattedMobile }, result.token);
          router.push(onSuccessRedirect);
        } else {
          setErrorMsg(result.message || "Failed to create account. Please try again.");
          showToast("Registration Error", result.message || "Failed to create account.", "error");
        }
      } catch (err: any) {
        setErrorMsg("Connection error. Please try again.");
      } finally {
        setLoading(false);
      }
    } else {
      // Log In
      if (!email.trim()) {
        setErrorMsg("Please enter your email address or mobile number.");
        return;
      }
      if (!password) {
        setErrorMsg("Please enter your password.");
        return;
      }

      setLoading(true);
      try {
        const result = await loginCustomer({
          email: email.trim().toLowerCase(),
          password: password,
        });

        if (result.success) {
          showToast("Welcome Back! ✨", "Successfully logged into your account.", "success");
          login(email.trim().toLowerCase(), result.user, result.token);
          router.push(onSuccessRedirect);
        } else {
          setErrorMsg(result.message || "Invalid email/phone or password.");
          showToast("Login Failed", result.message || "Invalid credentials.", "error");
        }
      } catch (err: any) {
        setErrorMsg("Connection error. Please try again.");
      } finally {
        setLoading(false);
      }
    }
  };

  return (
    <div className="w-full max-w-md mx-auto bg-white rounded-3xl border border-[#E7D1CC] p-6 sm:p-10 shadow-boutique relative overflow-hidden transition-all duration-300">
      
      {/* Crown Logo Header */}
      <div className="text-center space-y-2 mb-6">
        <div className="flex justify-center mb-1">
          <KnotelleCrownLogo className="h-20 sm:h-24 w-auto" />
        </div>

        {mode === "signup" ? (
          <div>
            <h1 className="font-serif-luxury text-2xl sm:text-3xl font-bold text-[#2E211E] tracking-tight">
              Create Account
            </h1>
            <p className="text-xs sm:text-sm text-[#786864] mt-1">
              Join our Crochet community today!
            </p>
          </div>
        ) : (
          <div>
            <h1 className="font-serif-luxury text-2xl sm:text-3xl font-bold text-[#2E211E] tracking-tight">
              Welcome Back
            </h1>
            <p className="text-xs sm:text-sm text-[#786864] mt-1">
              Good to see you again! Please enter your details.
            </p>
          </div>
        )}
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

      {/* Auth Form */}
      <form onSubmit={handleSubmit} className="space-y-4">
        
        {/* SIGN UP FORM FIELDS */}
        {mode === "signup" && (
          <>
            {/* FULL NAME */}
            <div className="space-y-1">
              <label className="text-[11px] font-bold tracking-wider text-[#2E211E] uppercase block">
                FULL NAME
              </label>
              <div className="relative">
                <input
                  type="text"
                  required
                  value={fullName}
                  onChange={(e) => setFullName(e.target.value)}
                  placeholder="Enter your full name"
                  className="w-full pl-10 pr-4 py-2.5 rounded-xl bg-[#FFF9F6] border border-[#E7D1CC] text-xs text-[#2E211E] placeholder-[#786864]/50 focus:outline-none focus:border-[#913638] focus:ring-1 focus:ring-[#913638] transition-colors"
                />
                <UserIcon className="w-4 h-4 text-[#786864] absolute left-3.5 top-1/2 -translate-y-1/2" />
              </div>
            </div>

            {/* EMAIL ADDRESS */}
            <div className="space-y-1">
              <label className="text-[11px] font-bold tracking-wider text-[#2E211E] uppercase block">
                EMAIL ADDRESS
              </label>
              <div className="relative">
                <input
                  type="email"
                  required
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  placeholder="Enter your email address"
                  className="w-full pl-10 pr-4 py-2.5 rounded-xl bg-[#FFF9F6] border border-[#E7D1CC] text-xs text-[#2E211E] placeholder-[#786864]/50 focus:outline-none focus:border-[#913638] focus:ring-1 focus:ring-[#913638] transition-colors"
                />
                <Mail className="w-4 h-4 text-[#786864] absolute left-3.5 top-1/2 -translate-y-1/2" />
              </div>
            </div>

            {/* MOBILE NUMBER (+91) */}
            <div className="space-y-1">
              <label className="text-[11px] font-bold tracking-wider text-[#2E211E] uppercase block">
                MOBILE NUMBER
              </label>
              <div className="relative flex items-center">
                <div className="absolute left-3.5 top-1/2 -translate-y-1/2 flex items-center gap-1 text-xs font-bold text-[#913638] pointer-events-none select-none">
                  <Phone className="w-3.5 h-3.5 text-[#786864]" />
                  <span>+91</span>
                  <span className="text-[#E7D1CC] ml-0.5">|</span>
                </div>
                <input
                  type="tel"
                  required
                  maxLength={10}
                  value={mobile}
                  onChange={(e) => {
                    const val = e.target.value.replace(/\D/g, "");
                    if (val.length <= 10) setMobile(val);
                  }}
                  placeholder="Enter 10-digit mobile number"
                  className="w-full pl-20 pr-4 py-2.5 rounded-xl bg-[#FFF9F6] border border-[#E7D1CC] text-xs font-medium text-[#2E211E] placeholder-[#786864]/50 focus:outline-none focus:border-[#913638] focus:ring-1 focus:ring-[#913638] transition-colors"
                />
              </div>
            </div>

            {/* PASSWORD */}
            <div className="space-y-1">
              <label className="text-[11px] font-bold tracking-wider text-[#2E211E] uppercase block">
                PASSWORD
              </label>
              <div className="relative">
                <input
                  type={showPassword ? "text" : "password"}
                  required
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                  placeholder="Enter your password"
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

            {/* CONFIRM PASSWORD */}
            <div className="space-y-1">
              <label className="text-[11px] font-bold tracking-wider text-[#2E211E] uppercase block">
                CONFIRM PASSWORD
              </label>
              <div className="relative">
                <input
                  type={showConfirmPassword ? "text" : "password"}
                  required
                  value={confirmPassword}
                  onChange={(e) => setConfirmPassword(e.target.value)}
                  placeholder="Confirm your password"
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

            {/* TERMS & PRIVACY CHECKBOX */}
            <div className="pt-1">
              <label className="flex items-start gap-2 text-xs text-[#786864] cursor-pointer select-none">
                <input
                  type="checkbox"
                  checked={agreeTerms}
                  onChange={(e) => setAgreeTerms(e.target.checked)}
                  className="mt-0.5 rounded border-[#E7D1CC] text-[#913638] focus:ring-[#913638] accent-[#913638]"
                />
                <span className="leading-relaxed">
                  I agree to the{" "}
                  <Link href="/terms" className="text-[#913638] font-semibold hover:underline">
                    Terms & Conditions
                  </Link>{" "}
                  and{" "}
                  <Link href="/privacy" className="text-[#913638] font-semibold hover:underline">
                    Privacy Policy
                  </Link>
                </span>
              </label>
            </div>
          </>
        )}

        {/* LOG IN FORM FIELDS */}
        {mode === "login" && (
          <>
            {/* EMAIL OR MOBILE NUMBER */}
            <div className="space-y-1">
              <label className="text-[11px] font-bold tracking-wider text-[#2E211E] uppercase block">
                EMAIL ADDRESS OR MOBILE NUMBER
              </label>
              <div className="relative">
                <input
                  type="text"
                  required
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  placeholder="Enter your email or 10-digit mobile number"
                  className="w-full pl-10 pr-4 py-2.5 rounded-xl bg-[#FFF9F6] border border-[#E7D1CC] text-xs text-[#2E211E] placeholder-[#786864]/50 focus:outline-none focus:border-[#913638] focus:ring-1 focus:ring-[#913638] transition-colors"
                />
                <Mail className="w-4 h-4 text-[#786864] absolute left-3.5 top-1/2 -translate-y-1/2" />
              </div>
            </div>

            {/* PASSWORD */}
            <div className="space-y-1">
              <label className="text-[11px] font-bold tracking-wider text-[#2E211E] uppercase block">
                PASSWORD
              </label>
              <div className="relative">
                <input
                  type={showPassword ? "text" : "password"}
                  required
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                  placeholder="Enter your password"
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

            {/* REMEMBER ME & FORGOT PASSWORD */}
            <div className="flex items-center justify-between pt-1">
              <label className="flex items-center gap-2 text-xs text-[#786864] cursor-pointer select-none">
                <input
                  type="checkbox"
                  checked={rememberMe}
                  onChange={(e) => setRememberMe(e.target.checked)}
                  className="rounded border-[#E7D1CC] text-[#913638] focus:ring-[#913638] accent-[#913638]"
                />
                <span>Remember me</span>
              </label>

              <Link href="/forgot-password" className="text-xs text-[#913638] font-semibold hover:underline">
                Forgot password?
              </Link>
            </div>
          </>
        )}

        {/* SUBMIT BUTTON */}
        <div className="pt-2">
          <button
            type="submit"
            disabled={loading}
            className="w-full py-3.5 rounded-full bg-[#913638] text-white text-xs sm:text-sm font-semibold hover:bg-[#74292B] active:scale-[0.98] shadow-sm hover:shadow-boutique-hover transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer disabled:opacity-60 disabled:cursor-not-allowed"
          >
            {loading ? (
              <div className="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin" />
            ) : mode === "signup" ? (
              <>
                <span>Sign Up</span>
                <ArrowRight className="w-4 h-4" />
              </>
            ) : (
              <>
                <span>Log In</span>
                <ArrowRight className="w-4 h-4" />
              </>
            )}
          </button>
        </div>
      </form>

      {/* TOGGLE BOTTOM FOOTER */}
      <div className="mt-6 pt-6 border-t border-[#E7D1CC] text-center">
        {mode === "signup" ? (
          <p className="text-xs text-[#786864] font-semibold tracking-wide">
            ALREADY HAVE AN ACCOUNT?{" "}
            <button
              type="button"
              onClick={() => switchMode("login")}
              className="text-[#913638] font-bold hover:underline uppercase ml-1 cursor-pointer"
            >
              LOG IN
            </button>
          </p>
        ) : (
          <p className="text-xs text-[#786864] font-semibold tracking-wide">
            DON&apos;T HAVE AN ACCOUNT?{" "}
            <button
              type="button"
              onClick={() => switchMode("signup")}
              className="text-[#913638] font-bold hover:underline uppercase ml-1 cursor-pointer"
            >
              SIGN UP
            </button>
          </p>
        )}
      </div>

    </div>
  );
}
