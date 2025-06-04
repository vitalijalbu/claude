import * as React from "react";

const IconHeart = ({ color = "#fff", className = "", size = 24, ...props }: any) => (
  <svg
    xmlns="http://www.w3.org/2000/svg"
    width={size}
    height={size + 1}
    fill="none"
    viewBox="0 0 24 25"
    className={className}
    {...props}
  >
    <path
      stroke={color}
      strokeLinejoin="round"
      strokeWidth="0.75"
      d="m12.002 20.734-7.7-7.47.01-.01a5.167 5.167 0 0 1 .285-7.172c2.027-1.967 5.255-2.059 7.394-.277l.011-.01.012.01c2.14-1.782 5.367-1.69 7.393.277a5.167 5.167 0 0 1 .285 7.171l.012.011z"
    />
    <path
      stroke={color}
      strokeLinejoin="round"
      strokeWidth="0.75"
      d="M18.303 9.935a2.7 2.7 0 0 0-2.7-2.7"
    />
  </svg>
);

export default IconHeart;