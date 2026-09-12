import React from "react";
import { Star } from "lucide-react";

interface StarRatingProps {
  rating: number;
  maxRating?: number;
  size?: "sm" | "md" | "lg";
  showNumber?: boolean;
  reviewCount?: number;
  className?: string;
}

export function StarRating({
  rating,
  maxRating = 5,
  size = "sm",
  showNumber = false,
  reviewCount,
  className = "",
}: StarRatingProps) {
  const sizes = {
    sm: "w-3.5 h-3.5",
    md: "w-4 h-4",
    lg: "w-5 h-5",
  };

  return (
    <div className={`flex items-center gap-1.5 ${className}`}>
      <div className="flex items-center text-[#C69A5A]">
        {Array.from({ length: maxRating }).map((_, i) => {
          const filled = i < Math.floor(rating);
          const half = !filled && i < rating;
          return (
            <Star
              key={i}
              className={`${sizes[size]} ${
                filled
                  ? "fill-[#C69A5A] text-[#C69A5A]"
                  : half
                  ? "fill-[#C69A5A]/50 text-[#C69A5A]"
                  : "text-[#E8D4CF]"
              }`}
            />
          );
        })}
      </div>
      {showNumber && (
        <span className="text-xs font-semibold text-[#3A211D]">
          {rating.toFixed(1)}
        </span>
      )}
      {reviewCount !== undefined && (
        <span className="text-xs text-[#78635E]">({reviewCount})</span>
      )}
    </div>
  );
}
