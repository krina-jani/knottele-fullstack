"use client";

import React, { Suspense } from "react";
import { useSearchParams } from "next/navigation";
import { AuthCard } from "@/components/auth/AuthCard";

function LoginContent() {
  const searchParams = useSearchParams();
  const rawRedirect = searchParams.get("redirectTo") || "/account";
  // Validate redirect to prevent open redirect vulnerabilities
  const redirectTarget = rawRedirect.startsWith("/") && !rawRedirect.startsWith("//") ? rawRedirect : "/account";

  return (
    <div className="bg-[#FFF9F6] min-h-screen py-10 sm:py-16 flex items-center justify-center px-4">
      <AuthCard defaultMode="login" onSuccessRedirect={redirectTarget} />
    </div>
  );
}

export default function LoginPage() {
  return (
    <Suspense
      fallback={
        <div className="bg-[#FFF9F6] min-h-screen py-16 flex items-center justify-center text-center">
          <p className="text-xs text-[#786864]">Loading login...</p>
        </div>
      }
    >
      <LoginContent />
    </Suspense>
  );
}
