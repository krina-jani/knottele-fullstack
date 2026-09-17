"use client";

import React, { Suspense } from "react";
import { useSearchParams } from "next/navigation";
import { AuthCard } from "@/components/auth/AuthCard";

function SignupContent() {
  const searchParams = useSearchParams();
  const rawRedirect = searchParams.get("redirectTo") || "/account";
  const redirectTarget = rawRedirect.startsWith("/") && !rawRedirect.startsWith("//") ? rawRedirect : "/account";

  return (
    <div className="bg-[#FFF9F6] min-h-screen py-10 sm:py-16 flex items-center justify-center px-4">
      <AuthCard defaultMode="signup" onSuccessRedirect={redirectTarget} />
    </div>
  );
}

export default function SignupPage() {
  return (
    <Suspense
      fallback={
        <div className="bg-[#FFF9F6] min-h-screen py-16 flex items-center justify-center text-center">
          <p className="text-xs text-[#786864]">Loading registration...</p>
        </div>
      }
    >
      <SignupContent />
    </Suspense>
  );
}
