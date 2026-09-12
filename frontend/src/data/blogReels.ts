export interface ReelPost {
  id: string;
  title: string;
  subtitle: string;
  category: string;
  duration: string;
  views: string;
  likes: string;
  comments: string;
  author: {
    name: string;
    role: string;
    avatar: string;
  };
  audioTrack: string;
  thumbnail: string;
  videoUrl?: string; // Optional direct video URL or embed
  caption: string;
  tags: string[];
}

export const REEL_POSTS: ReelPost[] = [
  {
    id: "reel-1",
    title: "Crafting the Everlasting Sunflower",
    subtitle: "Watch the petal-by-petal stitch technique & stem wiring",
    category: "Studio ASMR",
    duration: "0:48",
    views: "38.4K",
    likes: "3.2K",
    comments: "184",
    author: {
      name: "Krina Jani",
      role: "Lead Artisan",
      avatar: "https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200&auto=format&fit=crop",
    },
    audioTrack: "Original Audio • Acoustic Morning",
    thumbnail: "https://images.unsplash.com/photo-1597848212624-a19eb35e2651?q=80&w=800&auto=format&fit=crop",
    caption: "Ever wonder how our sunflowers stand tall and bloom forever? Each petal is hand-stitched with double-crochet cotton and subtly wired for flexible styling. 🌻✨ #crochetflower #knotelle #handmadeart",
    tags: ["#Sunflower", "#CrochetArt", "#ArtisanStudio"],
  },
  {
    id: "reel-2",
    title: "5 Chic Ways to Style the Granny Bag",
    subtitle: "From brunch dates to weekend farmer markets",
    category: "Style Guide",
    duration: "0:56",
    views: "52.1K",
    likes: "4.8K",
    comments: "296",
    author: {
      name: "Aarohi Verma",
      role: "Boutique Stylist",
      avatar: "https://images.unsplash.com/photo-1494790108377-be9c29b29330?q=80&w=200&auto=format&fit=crop",
    },
    audioTrack: "Taylor Swift • Cardigan (Acoustic)",
    thumbnail: "https://images.unsplash.com/photo-1544816155-12df9643f363?q=80&w=800&auto=format&fit=crop",
    caption: "The Vintage Pastel Granny Square Bag is the ultimate versatile statement piece. Pair it with breezy linen, denim jackets, or summer sundresses! 👜🌿 #crochetbag #grannysquare #outfitideas",
    tags: ["#BoutiqueFashion", "#GrannySquare", "#OOTD"],
  },
  {
    id: "reel-3",
    title: "Aesthetic Boutique Packing Routine",
    subtitle: "Lavender mist, silk blush ribbons & wax seals",
    category: "Behind The Scenes",
    duration: "0:39",
    views: "74.8K",
    likes: "6.5K",
    comments: "412",
    author: {
      name: "KNOTELLE Atelier",
      role: "Packaging Team",
      avatar: "https://images.unsplash.com/photo-1517841905240-472988babdf9?q=80&w=200&auto=format&fit=crop",
    },
    audioTrack: "ASMR Studio Ambience • Gentle Rain",
    thumbnail: "https://images.unsplash.com/photo-1563241527-3004b7be0ffd?q=80&w=800&auto=format&fit=crop",
    caption: "Every single KNOTELLE order is wrapped like a gift to yourself or a loved one. Spritzed with French lavender and sealed with handwritten love notes. 💌🌸 #packagingasmr #smallbusiness #crochetlove",
    tags: ["#BoutiquePacking", "#Unboxing", "#MadeWithLove"],
  },
  {
    id: "reel-4",
    title: "Stitching the Soft Bunny Amigurumi",
    subtitle: "Secret loops for blush cheeks & gold clasp",
    category: "Masterclass",
    duration: "1:05",
    views: "41.6K",
    likes: "3.9K",
    comments: "220",
    author: {
      name: "Meera Sen",
      role: "Master Artisan",
      avatar: "https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=200&auto=format&fit=crop",
    },
    audioTrack: "Lofi Beats • Cozy Coffee",
    thumbnail: "https://images.unsplash.com/photo-1590736969955-71cc94801759?q=80&w=800&auto=format&fit=crop",
    caption: "Meet our most loved fluffy bunny charm! Watch how we sculpt the micro ears, embroider the soft nose, and assemble the gold key ring. 🐰✨ #amigurumi #crochetbunny #cutekeychains",
    tags: ["#Amigurumi", "#CuteKeychain", "#CrochetHacks"],
  },
];
