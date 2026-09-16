import React from "react";
import { fetchContactPageData } from "@/lib/api";
import { ContactPageView } from "./ContactPageView";

export default async function ContactPage() {
  const initialData = await fetchContactPageData();
  return <ContactPageView initialData={initialData} />;
}
