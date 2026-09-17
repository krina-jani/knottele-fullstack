import type { Metadata } from "next";
import { Cormorant_Garamond, Plus_Jakarta_Sans, Caveat } from "next/font/google";
import "./globals.css";
import { ToastProvider } from "@/context/ToastContext";
import { CartProvider } from "@/context/CartContext";
import { WishlistProvider } from "@/context/WishlistContext";
import { AuthProvider } from "@/context/AuthContext";
import { MediaProvider } from "@/context/MediaContext";
import { AnnouncementBar } from "@/components/layout/AnnouncementBar";
import { Navbar } from "@/components/layout/Navbar";
import { Footer } from "@/components/layout/Footer";
import { CartDrawer } from "@/components/layout/CartDrawer";

const serifFont = Cormorant_Garamond({
  variable: "--font-serif",
  subsets: ["latin"],
  weight: ["400", "500", "600", "700"],
  display: "swap",
});

const sansFont = Plus_Jakarta_Sans({
  variable: "--font-sans",
  subsets: ["latin"],
  weight: ["300", "400", "500", "600", "700"],
  display: "swap",
});

const scriptFont = Caveat({
  variable: "--font-script",
  subsets: ["latin"],
  weight: ["400", "600", "700"],
  display: "swap",
});

export const metadata: Metadata = {
  title: "KNOTELLE | Customized Crochet Products & Handmade Boutique",
  description: "Handmade crochet products that add warmth to your everyday life. Custom bouquets, bags, soft toys, keychains, phone covers, and personalized gifts made with love.",
  keywords: ["crochet", "handmade", "custom crochet", "crochet bouquet", "amigurumi", "granny square bag", "crochet keychain", "knotelle"],
  openGraph: {
    title: "KNOTELLE | Little Stitches, Big Happiness",
    description: "Handmade crochet boutique specializing in customized creations, forever floral bouquets, and bespoke accessories.",
    url: "https://knotelle.com",
    siteName: "KNOTELLE",
    locale: "en_IN",
    type: "website",
  },
  icons: {
    icon: [
      { url: "/favicon.ico", sizes: "any" },
      { url: "/icon.png", type: "image/png" },
    ],
    shortcut: "/favicon.ico",
    apple: "/apple-icon.png",
  },
};

import { fetchHomepageMedia } from "@/lib/api";

export default async function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  const initialMedia = await fetchHomepageMedia();

  return (
    <html
      lang="en"
      suppressHydrationWarning
      className={`${serifFont.variable} ${sansFont.variable} ${scriptFont.variable} h-full antialiased`}
    >
      <head>
        <link rel="icon" href="/favicon.ico" sizes="any" />
        <link rel="icon" href="/icon.png" type="image/png" />
        <link rel="apple-touch-icon" href="/apple-icon.png" />
      </head>
      <body suppressHydrationWarning className="min-h-full flex flex-col bg-[#FFF8F5] text-[#2C201D] font-sans">
        <MediaProvider initialMedia={initialMedia}>
          <ToastProvider>
            <AuthProvider>
              <CartProvider>
                <WishlistProvider>
                  <AnnouncementBar />
                  <Navbar />
                  <main className="flex-1">{children}</main>
                  <Footer />
                  <CartDrawer />
                </WishlistProvider>
              </CartProvider>
            </AuthProvider>
          </ToastProvider>
        </MediaProvider>
      </body>
    </html>
  );
}
