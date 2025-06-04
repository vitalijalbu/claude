"use client";

import React from "react";

type PageHeaderProps = {
  title: string;
  subtitle?: string;
  className?: string;
  extra?: React.ReactNode;
};

export function PageHeader({
  title,
  subtitle,
  extra,
  className,
}: PageHeaderProps) {
  return (
    <section className={className}>
      <div className="text-center w-full mb-4">
        <div>
          <h1 className="text-4xl font-light text-center mb-12 text-gray-800">
            {title}
          </h1>
          {subtitle && (
            <p className="text-muted-foreground mt-1 text-md">{subtitle}</p>
          )}
        </div>
        {extra && <div className="flex items-center gap-2">{extra}</div>}
      </div>
    </section>
  );
}
