import React from "react";
import { PRODUCTS } from "@/data/products";
import ProductDetailView from "./ProductDetailView";

interface ProductPageProps {
  params: Promise<{
    slug: string;
  }>;
}

export function generateStaticParams() {
  return PRODUCTS.map((product) => ({
    slug: product.slug,
  }));
}

export default async function ProductDetailPage({ params }: ProductPageProps) {
  const { slug } = await params;
  return <ProductDetailView slug={slug} />;
}
