import React from "react";
import { INITIAL_ORDERS } from "@/data/orders";
import OrderDetailView from "./OrderDetailView";

interface OrderDetailPageProps {
  params: Promise<{
    id: string;
  }>;
}

export function generateStaticParams() {
  const ids = ["1", "2", "3", "4", "5", "placeholder"];
  if (INITIAL_ORDERS && INITIAL_ORDERS.length > 0) {
    INITIAL_ORDERS.forEach((order) => {
      if (order?.id && !ids.includes(order.id)) {
        ids.push(order.id);
      }
    });
  }
  return ids.map((id) => ({
    id,
  }));
}

export default async function OrderDetailPage({ params }: OrderDetailPageProps) {
  const { id } = await params;
  return <OrderDetailView id={id} />;
}
