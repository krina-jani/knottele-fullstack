import React from "react";
import { Metadata } from "next";
import { FaqPageView } from "./FaqPageView";

export const metadata: Metadata = {
  title: "FAQ | KNOTELLE",
  description:
    "Quick answers to common questions about KNOTELLE orders. Placing orders, tracking shipments, returns & refunds, unpacking video guidelines, and delivery address changes.",
};

export default function FaqPage() {
  return <FaqPageView />;
}
