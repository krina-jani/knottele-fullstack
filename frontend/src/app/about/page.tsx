import React from "react";
import { fetchAboutPageData } from "@/lib/api";
import { AboutPageView } from "./AboutPageView";

export default async function AboutPage() {
  const initialData = await fetchAboutPageData();
  return <AboutPageView initialData={initialData} />;
}
