import * as React from "react";


const IconSearch = ({ color = "#fff", className = "", size = 25, ...props }: any) => (
  <svg
    xmlns="http://www.w3.org/2000/svg"
    width={size}
    height={size}
    fill="none"
    viewBox="0 0 25 25"
    className={className}
    {...props}
  >
    <circle
      cx="10.891"
      cy="10.857"
      r="6.222"
      stroke={color}
      strokeWidth="0.889"
    />
    <path
      stroke={color}
      strokeWidth="0.889"
      d="M14.89 10.857a4 4 0 0 0-4-4"
    />
    <path
      stroke={color}
      strokeLinejoin="round"
      strokeWidth="0.889"
      d="m20.668 20.634-5.333-5.333"
    />
  </svg>
);

export default IconSearch;