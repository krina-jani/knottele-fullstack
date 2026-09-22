import React from "react";
import { Metadata } from "next";
import { fetchContactPageData } from "@/lib/api";
import { ShippingPolicyView } from "./ShippingPolicyView";

export const metadata: Metadata = {
  title: "Shipping Policy | KNOTELLE",
  description:
    "Everything you need to know about how your KNOTELLE order reaches you. Order processing, artisan crafting timelines, courier shipping, and delivery instructions.",
};

export default async function ContactPage() {
  const initialData = await fetchContactPageData();
  return <ShippingPolicyView initialContactData={initialData} />;
}
