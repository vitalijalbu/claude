import React from "react";

export default function AuthLayout({ children }) {
  return (
    <div className="w-full relative bg-zinc-50">
      <div className="container">
        <div className="h-lvh max-w-sm m-auto justify-center flex items-center">
          <div className="w-full align-middle">
            <div className="w-full mb-10 text-center font-bold text-2xl">
              OnlyEscort Console
            </div>
            <div className="w-full">{children}</div>
          </div>
        </div>
      </div>
    </div>
  );
};
