"use client";

import React, { useState, useEffect } from "react";
import { useAuth } from "@/context/AuthContext";
import { updateCustomerProfile } from "@/lib/api";
import { User, Mail, Phone, Lock, CheckCircle2 } from "lucide-react";

export default function ProfilePage() {
  const { user, updateProfile } = useAuth();

  const nameParts = (user?.name || "").split(" ");
  const [firstName, setFirstName] = useState(nameParts[0] || "");
  const [lastName, setLastName] = useState(nameParts.slice(1).join(" ") || "");
  const [phone, setPhone] = useState(user?.phone?.replace(/\D/g, "").slice(-10) || (user as any)?.mobile?.replace(/\D/g, "").slice(-10) || "");
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState<{ type: "success" | "error"; text: string } | null>(null);

  useEffect(() => {
    if (!user) return;
    if (user.name) {
      const parts = user.name.trim().split(" ");
      setFirstName(parts[0] || "");
      setLastName(parts.slice(1).join(" ") || "");
    }
    const phoneVal = user.phone || (user as any).mobile || "";
    if (phoneVal) {
      setPhone(phoneVal.replace(/\D/g, "").slice(-10));
    }
  }, [user]);

  const handleSave = async (e: React.FormEvent) => {
    e.preventDefault();
    setMessage(null);

    const fullName = `${firstName} ${lastName}`.trim();
    if (!fullName) {
      setMessage({ type: "error", text: "Please enter your full name." });
      return;
    }

    const cleanPhone = phone.replace(/\D/g, "");
    if (!cleanPhone || cleanPhone.length !== 10) {
      setMessage({ type: "error", text: "Please enter a valid 10-digit contact number." });
      return;
    }

    setLoading(true);
    try {
      const formattedMobile = `+91 ${cleanPhone}`;
      const result = await updateCustomerProfile({
        email: user?.email || "",
        name: fullName,
        mobile: formattedMobile,
      });

      if (result.success) {
        updateProfile({
          name: fullName,
          phone: formattedMobile,
        });
        setMessage({ type: "success", text: "Profile updated successfully! ✨" });
      } else {
        setMessage({ type: "error", text: result.message || "Failed to update profile." });
      }
    } catch {
      setMessage({ type: "error", text: "Connection error. Please try again." });
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="bg-white rounded-3xl border border-[#E7D1CC] p-6 sm:p-8 shadow-boutique space-y-6 animate-in fade-in">
      <div className="pb-4 border-b border-[#E7D1CC]">
        <h1 className="font-serif-luxury text-2xl font-bold text-[#2E211E]">
          Profile Settings
        </h1>
        <p className="text-xs text-[#786864] mt-0.5">
          Update your name and contact number. Email address is read-only.
        </p>
      </div>

      {message && (
        <div
          className={`p-3.5 rounded-2xl text-xs font-semibold flex items-center gap-2 ${
            message.type === "success"
              ? "bg-emerald-50 text-emerald-800 border border-emerald-200"
              : "bg-rose-50 text-rose-800 border border-rose-200"
          }`}
        >
          {message.type === "success" && <CheckCircle2 className="w-4 h-4 text-emerald-600" />}
          <span>{message.text}</span>
        </div>
      )}

      <form onSubmit={handleSave} className="space-y-6">
        
        {/* Personal Details */}
        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div className="space-y-1.5">
            <label className="text-xs font-semibold text-[#2E211E] block">
              First Name *
            </label>
            <input
              type="text"
              required
              value={firstName}
              onChange={(e) => setFirstName(e.target.value)}
              className="w-full px-4 py-2.5 rounded-xl bg-[#FFF9F6] border border-[#E7D1CC] text-xs text-[#2E211E] focus:outline-none focus:border-[#913638] transition-colors"
            />
          </div>

          <div className="space-y-1.5">
            <label className="text-xs font-semibold text-[#2E211E] block">
              Last Name *
            </label>
            <input
              type="text"
              required
              value={lastName}
              onChange={(e) => setLastName(e.target.value)}
              className="w-full px-4 py-2.5 rounded-xl bg-[#FFF9F6] border border-[#E7D1CC] text-xs text-[#2E211E] focus:outline-none focus:border-[#913638] transition-colors"
            />
          </div>
        </div>

        {/* Email & Phone */}
        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div className="space-y-1.5">
            <div className="flex items-center justify-between">
              <label className="text-xs font-semibold text-[#786864] block">
                Email Address (Read-Only)
              </label>
              <span className="text-[10px] text-[#786864] italic">Cannot be edited</span>
            </div>
            <div className="relative">
              <input
                type="email"
                disabled
                value={user?.email || ""}
                className="w-full px-4 py-2.5 rounded-xl bg-gray-100 border border-gray-200 text-xs text-gray-500 cursor-not-allowed select-none"
              />
              <Lock className="w-3.5 h-3.5 text-gray-400 absolute right-3.5 top-1/2 -translate-y-1/2" />
            </div>
          </div>

          <div className="space-y-1.5">
            <label className="text-xs font-semibold text-[#2E211E] block">
              Phone Number (+91) *
            </label>
            <div className="relative flex items-center rounded-xl bg-[#FFF9F6] border border-[#E7D1CC] focus-within:border-[#913638] focus-within:ring-1 focus-within:ring-[#913638] transition-all overflow-hidden">
              <div className="flex items-center gap-1.5 px-3.5 py-2.5 bg-[#FCE9E5] border-r border-[#E7D1CC] text-xs font-bold text-[#913638] shrink-0 select-none">
                <Phone className="w-3.5 h-3.5 text-[#786864]" />
                <span>+91</span>
              </div>
              <input
                type="tel"
                required
                inputMode="numeric"
                maxLength={10}
                value={phone}
                onChange={(e) => setPhone(e.target.value.replace(/\D/g, "").slice(0, 10))}
                placeholder="Enter 10-digit mobile number"
                className="w-full px-3.5 py-2.5 bg-transparent text-xs text-[#2E211E] placeholder-[#786864]/50 focus:outline-none"
              />
            </div>
          </div>
        </div>

        {/* Save Button */}
        <div className="pt-2 flex justify-end">
          <button
            type="submit"
            disabled={loading}
            className="w-full sm:w-auto px-8 py-3 rounded-full bg-[#913638] text-white text-xs font-semibold hover:bg-[#74292B] active:scale-[0.98] shadow-sm transition-all flex items-center justify-center gap-1.5 cursor-pointer disabled:opacity-60"
          >
            {loading ? (
              <div className="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin" />
            ) : (
              <span>Save Changes</span>
            )}
          </button>
        </div>

      </form>
    </div>
  );
}
