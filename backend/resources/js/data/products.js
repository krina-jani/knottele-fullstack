export const products = [
  {
    id: "prod-1",
    name: "Chocolate Nutrition Powder",
    slug: "chocolate-nutrition-powder",
    category: "nutrition-powder",
    shortDescription: "Rich chocolate flavored nutrition powder for daily energy.",
    description: "Our Chocolate Nutrition Powder is crafted with premium ingredients to provide you with essential nutrients. Perfect for starting your day or as a post-workout recovery drink.",
    images: [
      "/images/chocolate.jpeg",
      "/images/chocolate-diffrent.jpeg",
      "/images/chocolate-diffrent2.jpeg",
      "/images/chocolate-powder-video.mp4"
    ],
    rating: 4.8,
    reviews: 125,
    bestseller: true,
    featured: true,
    variants: [
      { size: "8 Sachets", price: 299, stock: 50, sku: "CNP-08" },
      { size: "16 Sachets", price: 549, stock: 35, sku: "CNP-16" },
      { size: "32 Sachets", price: 999, stock: 20, sku: "CNP-32" }
    ],
    ingredients: "Cocoa powder, Whey protein, Vitamins blend, Minerals.",
    benefits: "Boosts energy, supports muscle recovery, delicious taste.",
  },
  {
    id: "prod-2",
    name: "Kesar Pista Nutrition Powder",
    slug: "kesar-pista-nutrition-powder",
    category: "nutrition-powder",
    shortDescription: "Traditional kesar pista flavor packed with nutrients.",
    description: "Experience the authentic taste of saffron and pistachios combined with essential vitamins and minerals for your daily nutritional needs.",
    images: [
      "/images/pista.jpeg",
      "/images/kesarpista-view.jpeg",
      "/images/kesarpista-view2.jpeg",
      "/images/kesar-pista-powder.mp4"
    ],
    rating: 4.9,
    reviews: 98,
    bestseller: false,
    featured: true,
    variants: [
      { size: "8 Sachets", price: 299, stock: 40, sku: "KPNP-08" },
      { size: "16 Sachets", price: 549, stock: 25, sku: "KPNP-16" },
      { size: "32 Sachets", price: 999, stock: 15, sku: "KPNP-32" }
    ],
    ingredients: "Saffron extract, Pistachio bits, Milk protein, Cardamom.",
    benefits: "Improves immunity, rich in antioxidants, traditional goodness.",
  },
  {
    id: "prod-3",
    name: "Dry Fruits with Seeds Mix",
    slug: "dry-fruits-with-seeds-mix",
    category: "dry-fruits-seeds",
    shortDescription: "Premium almonds, cashews mixed with healthy seeds.",
    description: "A perfect blend of high-quality dry fruits and roasted seeds to keep you energetic throughout the day. A guilt-free snacking option.",
    images: [
      "/images/dryfruits.jpeg",
      "/images/dry-fruits-view.jpeg",
      "/images/dry-fruits-view-2.jpeg",
      "/images/dryfruits-with-seeds-mix.mp4"
    ],
    rating: 4.7,
    reviews: 156,
    bestseller: true,
    featured: true,
    variants: [
      { size: "250g", price: 249, stock: 100, sku: "DFSM-250" },
      { size: "500g", price: 449, stock: 80, sku: "DFSM-500" },
      { size: "1kg", price: 849, stock: 40, sku: "DFSM-1KG" }
    ],
    ingredients: "Almonds, Cashews, Raisins, Pumpkin seeds, Sunflower seeds.",
    benefits: "Rich in healthy fats, good for heart, satisfying crunch.",
  },
  {
    id: "prod-4",
    name: "Roasted Seeds Mix",
    slug: "roasted-seeds-mix",
    category: "seeds-mix",
    shortDescription: "A crunchy mix of pumpkin, sunflower, flax, and chia seeds.",
    description: "Packed with omega-3 and fiber, our roasted seeds mix is the perfect topping for your salads, smoothies, or just as a healthy snack.",
    images: [
      "/images/rosted-seeds.jpeg",
      "/images/rosted-seeds-view.jpeg",
      "/images/rosted-seeds-view-2.jpeg",
      "/images/mix-seeds.mp4"
    ],
    rating: 4.6,
    reviews: 87,
    bestseller: false,
    featured: false,
    variants: [
      { size: "250g", price: 199, stock: 120, sku: "RSM-250" },
      { size: "500g", price: 349, stock: 90, sku: "RSM-500" }
    ],
    ingredients: "Pumpkin seeds, Sunflower seeds, Flax seeds, Chia seeds.",
    benefits: "High in fiber, excellent source of Omega-3, boosts digestion.",
  }
];
