import { Metadata } from "next";

export const metadata: Metadata = {
  title: "Track Order | KNOTELLE",
  description:
    "Track your KNOTELLE handmade crochet order. View live crafting progress, courier shipping tracking, and delivery timelines.",
};

export default function TrackOrderLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return <>{children}</>;
}
