export interface Testimonial {
  id: string;
  name: string;
  location: string;
  rating: number;
  productName: string;
  comment: string;
  date: string;
  avatar: string;
}

export const TESTIMONIALS: Testimonial[] = [
  {
    id: "t-1",
    name: "Aanya Verma",
    location: "Bengaluru, India",
    rating: 5,
    productName: "Rose Bouquet & Bunny Keychain",
    comment: "I ordered the custom rose bouquet for my sister's birthday and she cried tears of joy! The stitches are unimaginably neat, and the packaging smelled like gentle lavender. KNOTELLE is pure art.",
    date: "August 2026",
    avatar: "https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200&auto=format&fit=crop",
  },
  {
    id: "t-2",
    name: "Rhea Mukherjee",
    location: "Mumbai, India",
    rating: 5,
    productName: "Granny Square Bag",
    comment: "This bag is an absolute showstopper. Strangers stop me on Bandra streets asking where I got it. The lining is sturdy and the color harmony is so chic. Worth every single rupee!",
    date: "July 2026",
    avatar: "https://images.unsplash.com/photo-1517841905240-472988babdf9?q=80&w=200&auto=format&fit=crop",
  },
  {
    id: "t-3",
    name: "Tanvi Deshmukh",
    location: "Pune, India",
    rating: 5,
    productName: "Daisy Phone Cover (Custom)",
    comment: "Requested a custom size for my phone with a personalized initial charm. The team sent me yarn color previews beforehand. The craftsmanship is flawless and super protective.",
    date: "August 2026",
    avatar: "https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=200&auto=format&fit=crop",
  },
  {
    id: "t-4",
    name: "Isha Kapoor",
    location: "Delhi NCR, India",
    rating: 5,
    productName: "Classic Teddy Bear",
    comment: "Bought this for my newborn niece as an heirloom keepsake. The chenille yarn is so soft and gentle. You can feel the love poured into every single loop.",
    date: "September 2026",
    avatar: "https://images.unsplash.com/photo-1494790108377-be9c29b29330?q=80&w=200&auto=format&fit=crop",
  },
];
