import React from "react";
import { PRODUCTS } from "@/data/products";
import { fetchProducts } from "@/lib/api";
import ProductDetailView from "./ProductDetailView";

export const dynamicParams = true;

interface ProductPageProps {
  params: Promise<{
    slug: string;
  }>;
}

export async function generateStaticParams() {
  try {
    const res = await fetchProducts({ per_page: 500 });
    const apiSlugs = res?.products?.map((p) => ({ slug: p.slug })) || [];
    const staticSlugs = PRODUCTS.map((product) => ({ slug: product.slug }));
    const merged = [...apiSlugs, ...staticSlugs];
    const uniqueSlugs = Array.from(new Set(merged.map((item) => item.slug)))
      .filter(Boolean)
      .map((slug) => ({ slug }));

    if (uniqueSlugs.length > 0) {
      return uniqueSlugs;
    }
  } catch (error) {
    console.warn("generateStaticParams for products warning:", error);
  }

  return PRODUCTS.map((product) => ({
    slug: product.slug,
  }));
}

export default async function ProductDetailPage({ params }: ProductPageProps) {
  const { slug } = await params;
  return <ProductDetailView slug={slug} />;
}

