import React from "react";
import { INITIAL_ORDERS } from "@/data/orders";
import OrderDetailView from "./OrderDetailView";

interface OrderDetailPageProps {
  params: Promise<{
    id: string;
  }>;
}

export function generateStaticParams() {
  return INITIAL_ORDERS.map((order) => ({
    id: order.id,
  }));
}

export default async function OrderDetailPage({ params }: OrderDetailPageProps) {
  const { id } = await params;
  return <OrderDetailView id={id} />;
}
