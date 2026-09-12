export interface CustomOrderRequest {
  id: string;
  category: string;
  itemType: string;
  colors: string[];
  size?: string;
  dimensions?: string;
  personalizationText?: string;
  notes: string;
  referenceImage?: string;
  budgetRange: string;
  urgency: "Standard (7-10 days)" | "Express (3-5 days)" | "Gift Rush (2-3 days)";
  customerName: string;
  customerEmail: string;
  customerPhone: string;
  createdAt: string;
  status: "Received" | "Under Review" | "Yarn Sourced" | "In Crafting" | "Completed";
  estimatedQuote?: number;
}
