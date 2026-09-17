import React from "react";
import { INITIAL_ORDERS } from "@/data/orders";
import OrderDetailView from "./OrderDetailView";

interface OrderDetailPageProps {
  params: Promise<{
    id: string;
  }>;
}

export function generateStaticParams() {
  if (INITIAL_ORDERS.length > 0) {
    return INITIAL_ORDERS.map((order) => ({
      id: order.id,
    }));
  }
  return [{ id: "placeholder" }];
}

export default async function OrderDetailPage({ params }: OrderDetailPageProps) {
  const { id } = await params;
  return <OrderDetailView id={id} />;
}
