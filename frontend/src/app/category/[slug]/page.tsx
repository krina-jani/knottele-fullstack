import React from "react";
import { fetchCategories } from "@/lib/api";
import { CATEGORIES } from "@/data/categories";
import CategoryPageView from "./CategoryPageView";

export const dynamicParams = false;

interface CategoryPageProps {
  params: Promise<{
    slug: string;
  }>;
}

export async function generateStaticParams() {
  try {
    const cats = await fetchCategories();
    if (cats && cats.length > 0) {
      return cats.map((c) => ({ slug: c.slug }));
    }
  } catch (e) {}
  return CATEGORIES.map((category) => ({
    slug: category.slug,
  }));
}

export default async function CategoryPage({ params }: CategoryPageProps) {
  const { slug } = await params;
  return <CategoryPageView slug={slug} />;
}
