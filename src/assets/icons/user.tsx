import * as React from "react";

const IconUser = ({ color = "#fff", className = "", size = 24, ...props }: any) => (
  <svg
    xmlns="http://www.w3.org/2000/svg"
    width={size}
    height={size + 1}
    fill="none"
    viewBox="0 0 24 25"
    className={className}
    {...props}
  >
    <circle
      cx="12"
      cy="8.809"
      r="4.675"
      stroke={color}
      strokeLinejoin="bevel"
      strokeWidth="0.708"
    />
    <path
      stroke={color}
      strokeLinejoin="round"
      strokeWidth="0.708"
      d="M12 13.484c-4.214 0-7.712 2.76-8.384 6.382-.128.692.455 1.268 1.159 1.268h14.45c.704 0 1.287-.576 1.159-1.268-.672-3.621-4.17-6.382-8.384-6.382Z"
    />
  </svg>
);

export default IconUser;