import * as React from "react";

const IconCart = ({ color = "#fff", className = "", size, ...props }: any) => (
  <svg
    xmlns="http://www.w3.org/2000/svg"
    width={size || "25"}
    height={size || "25"}
    fill="none"
    viewBox="0 0 25 25"
    className={className}
    {...props}
  >
    <path
      stroke={color}
      strokeLinejoin="round"
      strokeWidth="0.75"
      d="M6.334 7.635h14l-2 7.5h-10zM6.334 7.635l-.5-2h-2.5"
    />
    <circle
      cx="8.834"
      cy="19.135"
      r="1.5"
      stroke={color}
      strokeLinejoin="round"
      strokeWidth="0.75"
    />
    <circle
      cx="17.834"
      cy="19.135"
      r="1.5"
      stroke={color}
      strokeLinejoin="round"
      strokeWidth="0.75"
    />
  </svg>
);

export default IconCart;