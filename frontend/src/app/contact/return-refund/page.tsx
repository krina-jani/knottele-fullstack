import React from "react";
import { Metadata } from "next";
import { ReturnRefundView } from "./ReturnRefundView";

export const metadata: Metadata = {
  title: "Return & Refund | KNOTELLE",
  description:
    "We want you to love your KNOTELLE order. Here's everything you need to know about returns, mandatory unpacking video requirements, and refund processing.",
};

export default function ReturnRefundPage() {
  return <ReturnRefundView />;
}
