import React from "react";
import Link from "next/link";
import { Result } from "@/shared/components/result";
import { Button } from "@heroui/react";
import { AlertCircle, ArrowLeft } from "lucide-react";

const NotFound = () => {
  return (
    <div className="h-screen flex flex-col justify-center items-center">
      <Result
        icon={<AlertCircle className="m-auto" color="rgba(0, 0, 0, 0.45)" />}
        title="404 Not Found"
        subTitle="Whoops! That page doesn’t exist."
      />
      <div className="mt-4 text-center">
        <Button as={Link} type="default" href="/home" icon={<ArrowLeft />}>
          Torna alla home
        </Button>
      </div>
    </div>
  );
};

export default NotFound;
