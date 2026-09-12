import React from "react";
import Link from "next/link";
import { clsx } from "clsx";
import { twMerge } from "tailwind-merge";

function cn(...inputs: (string | undefined | null | false)[]) {
  return twMerge(clsx(inputs));
}

export interface ButtonProps extends React.ButtonHTMLAttributes<HTMLButtonElement> {
  variant?: "primary" | "secondary" | "outline" | "ghost" | "gold";
  size?: "sm" | "md" | "lg";
  href?: string;
  icon?: React.ReactNode;
  iconPosition?: "left" | "right";
  isLoading?: boolean;
}

export const Button = React.forwardRef<HTMLButtonElement, ButtonProps>(
  (
    {
      children,
      className,
      variant = "primary",
      size = "md",
      href,
      icon,
      iconPosition = "right",
      isLoading = false,
      disabled,
      ...props
    },
    ref
  ) => {
    const baseStyles =
      "inline-flex items-center justify-center font-semibold rounded-full transition-all duration-250 active:scale-[0.98] disabled:opacity-50 disabled:pointer-events-none disabled:active:scale-100 tracking-normal cursor-pointer select-none";

    const variants = {
      primary:
        "bg-[#913638] text-white hover:bg-[#74292B] border border-transparent shadow-xs hover:shadow-boutique-hover active:bg-[#5E1F21]",
      secondary:
        "bg-white text-[#2E211E] hover:bg-[#FCE9E5] hover:text-[#913638] border border-[#E7D1CC] hover:border-[#EFB8B0] shadow-xs active:bg-[#FCE9E5]",
      outline:
        "border border-[#913638] text-[#913638] hover:bg-[#913638] hover:text-white bg-transparent active:bg-[#74292B]",
      ghost:
        "text-[#2E211E] hover:bg-[#FCE9E5] hover:text-[#913638] bg-transparent active:bg-[#FCE9E5]",
      gold:
        "bg-[#C89B61] text-white hover:bg-[#B38747] shadow-xs active:scale-[0.98]",
    };

    const sizes = {
      sm: "text-xs px-3.5 py-1.5 gap-1.5",
      md: "text-xs sm:text-sm px-5 sm:px-6 py-2.5 sm:py-3 gap-2",
      lg: "text-sm sm:text-base px-7 sm:px-8 py-3.5 sm:py-4 gap-2.5 font-bold",
    };

    const combinedClassName = cn(
      baseStyles,
      variants[variant],
      sizes[size],
      className
    );

    const content = (
      <>
        {isLoading && (
          <span className="w-4 h-4 border-2 border-current border-t-transparent rounded-full animate-spin mr-2" />
        )}
        {!isLoading && icon && iconPosition === "left" && <span className="shrink-0">{icon}</span>}
        <span>{children}</span>
        {!isLoading && icon && iconPosition === "right" && <span className="shrink-0">{icon}</span>}
      </>
    );

    if (href) {
      return (
        <Link href={href} className={combinedClassName}>
          {content}
        </Link>
      );
    }

    return (
      <button
        ref={ref}
        disabled={disabled || isLoading}
        className={combinedClassName}
        {...props}
      >
        {content}
      </button>
    );
  }
);

Button.displayName = "Button";
