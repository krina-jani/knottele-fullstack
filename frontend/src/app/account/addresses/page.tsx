"use client";

import React, { useState } from "react";
import { Plus, MapPin, Check, Trash2, Edit3, X } from "lucide-react";
import { useAuth } from "@/context/AuthContext";
import { ShippingAddress } from "@/types/order";

export default function AddressesPage() {
  const { user, addAddress, deleteAddress, setDefaultAddress } = useAuth();
  const [isAddingNew, setIsAddingNew] = useState(false);

  // New Address Form State
  const [fullName, setFullName] = useState(user?.name || "Ananya Sharma");
  const [email, setEmail] = useState(user?.email || "ananya.sharma@example.com");
  const [phone, setPhone] = useState(user?.phone || "+91 98765 43210");
  const [addressLine1, setAddressLine1] = useState("");
  const [addressLine2, setAddressLine2] = useState("");
  const [city, setCity] = useState("");
  const [state, setState] = useState("");
  const [pincode, setPincode] = useState("");

  const handleCreateAddress = (e: React.FormEvent) => {
    e.preventDefault();
    if (!addressLine1 || !city || !state || !pincode) return;

    addAddress({
      fullName,
      email,
      phone,
      addressLine1,
      addressLine2,
      city,
      state,
      pincode,
      country: "India",
    });

    setIsAddingNew(false);
    setAddressLine1("");
    setAddressLine2("");
    setCity("");
    setState("");
    setPincode("");
  };

  return (
    <div className="bg-white rounded-3xl border border-[#E7D1CC] p-6 sm:p-8 shadow-boutique space-y-6 animate-in fade-in">
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-[#E7D1CC]">
        <div>
          <h1 className="font-serif-luxury text-2xl font-bold text-[#2E211E]">
            My Addresses
          </h1>
          <p className="text-xs text-[#786864] mt-0.5">
            Manage your delivery destinations for fast boutique dispatch.
          </p>
        </div>
        <button
          onClick={() => setIsAddingNew(!isAddingNew)}
          className="w-full sm:w-auto px-5 py-2 rounded-full bg-[#913638] text-white text-xs font-semibold hover:bg-[#74292B] active:scale-[0.98] transition-all flex items-center justify-center gap-1.5 shrink-0 cursor-pointer"
        >
          <Plus className="w-3.5 h-3.5" />
          <span>Add New Address</span>
        </button>
      </div>

      {/* Add New Address Modal / Drawer Card */}
      {isAddingNew && (
        <form
          onSubmit={handleCreateAddress}
          className="p-4 sm:p-6 rounded-2xl bg-[#FFF9F6] border border-[#E7D1CC] space-y-4 animate-in fade-in"
        >
          <div className="flex items-center justify-between pb-2 border-b border-[#E7D1CC]">
            <h3 className="font-serif-luxury text-base font-bold text-[#2E211E]">
              New Shipping Destination
            </h3>
            <button
              type="button"
              onClick={() => setIsAddingNew(false)}
              className="text-[#786864] hover:text-[#2E211E] cursor-pointer"
            >
              <X className="w-4 h-4" />
            </button>
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div className="space-y-1">
              <label className="text-xs font-semibold text-[#2E211E]">Full Name *</label>
              <input
                type="text"
                required
                value={fullName}
                onChange={(e) => setFullName(e.target.value)}
                className="w-full px-3 py-2 rounded-xl bg-white border border-[#E7D1CC] text-xs text-[#2E211E] focus:outline-none focus:border-[#913638] transition-colors"
              />
            </div>
            <div className="space-y-1">
              <label className="text-xs font-semibold text-[#2E211E]">Phone *</label>
              <div className="relative flex items-center rounded-xl bg-white border border-[#E7D1CC] focus-within:border-[#913638] focus-within:ring-1 focus-within:ring-[#913638] transition-all overflow-hidden">
                <div className="flex items-center gap-1 px-2.5 py-2 bg-[#FCE9E5] border-r border-[#E7D1CC] text-xs font-bold text-[#913638] shrink-0 select-none">
                  <span className="text-xs leading-none">🇮🇳</span>
                  <span>+91</span>
                </div>
                <input
                  type="tel"
                  required
                  inputMode="numeric"
                  pattern="[0-9]{10}"
                  maxLength={10}
                  value={phone.replace(/^\+91\s*/, "")}
                  onChange={(e) => setPhone(e.target.value.replace(/\D/g, "").slice(0, 10))}
                  placeholder="98765 43210"
                  className="w-full px-2.5 py-2 bg-transparent text-xs text-[#2E211E] placeholder-[#786864]/50 focus:outline-none"
                />
              </div>
            </div>
            <div className="space-y-1">
              <label className="text-xs font-semibold text-[#2E211E]">Email *</label>
              <input
                type="email"
                required
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                className="w-full px-3 py-2 rounded-xl bg-white border border-[#E7D1CC] text-xs text-[#2E211E] focus:outline-none focus:border-[#913638] transition-colors"
              />
            </div>
          </div>

          <div className="space-y-1">
            <label className="text-xs font-semibold text-[#2E211E]">Street Address *</label>
            <input
              type="text"
              required
              value={addressLine1}
              onChange={(e) => setAddressLine1(e.target.value)}
              placeholder="House/Flat number, building name, road"
              className="w-full px-3 py-2 rounded-xl bg-white border border-[#E7D1CC] text-xs text-[#2E211E] focus:outline-none focus:border-[#913638] transition-colors"
            />
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div className="space-y-1">
              <label className="text-xs font-semibold text-[#2E211E]">City *</label>
              <input
                type="text"
                required
                value={city}
                onChange={(e) => setCity(e.target.value)}
                className="w-full px-3 py-2 rounded-xl bg-white border border-[#E7D1CC] text-xs text-[#2E211E] focus:outline-none focus:border-[#913638] transition-colors"
              />
            </div>
            <div className="space-y-1">
              <label className="text-xs font-semibold text-[#2E211E]">State *</label>
              <input
                type="text"
                required
                value={state}
                onChange={(e) => setState(e.target.value)}
                className="w-full px-3 py-2 rounded-xl bg-white border border-[#E7D1CC] text-xs text-[#2E211E] focus:outline-none focus:border-[#913638] transition-colors"
              />
            </div>
            <div className="space-y-1">
              <label className="text-xs font-semibold text-[#2E211E]">Pincode *</label>
              <input
                type="text"
                required
                value={pincode}
                onChange={(e) => setPincode(e.target.value)}
                className="w-full px-3 py-2 rounded-xl bg-white border border-[#E7D1CC] text-xs text-[#2E211E] focus:outline-none focus:border-[#913638] transition-colors"
              />
            </div>
          </div>

          <div className="pt-2 flex flex-col sm:flex-row justify-end gap-2">
            <button
              type="button"
              onClick={() => setIsAddingNew(false)}
              className="w-full sm:w-auto px-5 py-2 rounded-full bg-white text-[#2E211E] border border-[#E7D1CC] hover:border-[#EFB8B0] text-xs font-semibold hover:bg-[#FCE9E5] hover:text-[#913638] active:scale-[0.98] transition-all cursor-pointer text-center"
            >
              Cancel
            </button>
            <button
              type="submit"
              className="w-full sm:w-auto px-6 py-2 rounded-full bg-[#913638] text-white text-xs font-semibold hover:bg-[#74292B] active:scale-[0.98] transition-all cursor-pointer text-center"
            >
              Save Address
            </button>
          </div>
        </form>
      )}

      {/* Address Cards Grid */}
      <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
        {user?.addresses.map((addr) => (
          <div
            key={addr.id}
            className={`p-5 rounded-2xl border transition-all flex flex-col justify-between ${
              addr.isDefault
                ? "border-[#913638] bg-[#FFF9F6] shadow-xs"
                : "border-[#E7D1CC] bg-white"
            }`}
          >
            <div className="space-y-2">
              <div className="flex items-center justify-between">
                <span className="text-xs font-bold text-[#2E211E]">
                  {addr.fullName}
                </span>
                {addr.isDefault && (
                  <span className="text-[10px] font-bold bg-[#913638] text-white px-2.5 py-0.5 rounded-full">
                    Default
                  </span>
                )}
              </div>
              <p className="text-xs text-[#786864] leading-relaxed">
                {addr.addressLine1}
                {addr.addressLine2 && `, ${addr.addressLine2}`}
                <br />
                {addr.city}, {addr.state} - {addr.pincode}
              </p>
              <p className="text-[11px] text-[#786864]">Phone: {addr.phone}</p>
            </div>

            {/* Actions */}
            <div className="pt-4 mt-3 border-t border-[#E7D1CC]/60 flex items-center justify-between text-xs">
              {!addr.isDefault ? (
                <button
                  onClick={() => setDefaultAddress(addr.id)}
                  className="text-xs font-semibold text-[#913638] hover:underline cursor-pointer"
                >
                  Set as Default
                </button>
              ) : (
                <span className="text-xs text-emerald-700 font-semibold flex items-center gap-1">
                  <Check className="w-3.5 h-3.5" />
                  <span>Default Address</span>
                </span>
              )}

              <div className="flex items-center gap-3">
                <button
                  onClick={() => deleteAddress(addr.id)}
                  className="text-[#786864] hover:text-[#913638] transition-colors cursor-pointer"
                  aria-label="Delete address"
                >
                  <Trash2 className="w-3.5 h-3.5" />
                </button>
              </div>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}
