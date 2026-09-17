"use client";

import React, { Suspense } from "react";
import { useSearchParams } from "next/navigation";
import { VerifyOtpCard } from "@/components/auth/VerifyOtpCard";
import { sanitizeRedirectUrl } from "@/lib/api";

function VerifyOtpContent() {
  const searchParams = useSearchParams();
  const email = searchParams.get("email") || "";
  const rawRedirect = searchParams.get("redirectTo");
  const redirectTarget = sanitizeRedirectUrl(rawRedirect);

  return (
    <div className="bg-[#FFF9F6] min-h-screen py-10 sm:py-16 flex items-center justify-center px-4">
      <VerifyOtpCard email={email} onSuccessRedirect={redirectTarget} />
    </div>
  );
}

export default function VerifyOtpPage() {
  return (
    <Suspense
      fallback={
        <div className="bg-[#FFF9F6] min-h-screen py-16 flex items-center justify-center text-center">
          <p className="text-xs text-[#786864]">Loading verification screen...</p>
        </div>
      }
    >
      <VerifyOtpContent />
    </Suspense>
  );
}
