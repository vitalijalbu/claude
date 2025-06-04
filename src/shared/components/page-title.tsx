"use client";
import React from "react";
import Link from "next/link";
import { Button, Skeleton } from "@heroui/react";

const PageTitle = (props: any) => {
  const { loading, backLink, title, subTitle, extra, children } = props;
  return (
    <div className="mb-6">
      <Skeleton paragraph={false} loading={loading}>
        <div className="page-heading mb-4">
          <div className="flex justify-between items-center mb-4">
            <div className="flex gap-2 items-center">
              {backLink && (
                <Link href={backLink}>
                  <Button type="text">Indietro</Button>
                </Link>
              )}
              <div>
                {title}
                {subTitle && <p className="text-gray-500">{subTitle}</p>}
              </div>
            </div>
            <div className="flex">{extra}</div>
          </div>

          {children && (
            <div className="mt-4">
              {React.Children.map(children, (child, index) => (
                <span key={index} className="page-actions_meta">
                  {child}
                </span>
              ))}
            </div>
          )}
        </div>
      </Skeleton>
    </div>
  );
};

export default PageTitle;
