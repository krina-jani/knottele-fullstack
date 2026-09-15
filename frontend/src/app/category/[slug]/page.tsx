import React from "react";
import { CATEGORIES } from "@/data/categories";
import CategoryPageView from "./CategoryPageView";

interface CategoryPageProps {
  params: Promise<{
    slug: string;
  }>;
}

export function generateStaticParams() {
  return CATEGORIES.map((category) => ({
    slug: category.slug,
  }));
}

export default async function CategoryPage({ params }: CategoryPageProps) {
  const { slug } = await params;
  return <CategoryPageView slug={slug} />;
}
