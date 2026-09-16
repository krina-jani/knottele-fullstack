"use client";

import React from "react";
import { AuthCard } from "@/components/auth/AuthCard";

export default function SignupPage() {
  return (
    <div className="bg-[#FFF9F6] min-h-screen py-10 sm:py-16 flex items-center justify-center px-4">
      <AuthCard defaultMode="signup" onSuccessRedirect="/account" />
    </div>
  );
}
