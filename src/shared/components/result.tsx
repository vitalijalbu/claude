import React from "react";
import { Card, Divider } from "@heroui/react";
import clsx from "clsx";
import { Check, CircleAlert } from "lucide-react";

interface ResultProps {
  status?: "success" | "error";
  icon?: React.ReactNode;
  title: string;
  subtitle?: string;
  extra?: React.ReactNode;
}

const Result: React.FC<ResultProps> = ({
  status,
  icon,
  title,
  subtitle,
  extra,
}) => {
  const isSuccess = status === "success";
  const defaultIcon = isSuccess ? (
    <Check className="text-green-500" />
  ) : (
    <CircleAlert className="text-gray-300" />
  );

  // Use tailwind-merge to handle conditional class merging
  const cardClasses = clsx("flex flex-col items-center p-4");

  return (
    <div className={cardClasses}>
      <div className="mb-4">{icon || defaultIcon}</div>
      <h2 className="text-md font-semibold mb-2">{title}</h2>
      {subtitle && <p className="text-gray-600">{subtitle}</p>}
      <div className="my-4">{extra}</div>
    </div>
  );
};

export { Result };
