"use client";

import React from "react";
import { Button, Input, Checkbox, Link, Form } from "@heroui/react";
import { Eye, EyeClosed } from "lucide-react";

export default function Page() {
  const [isVisible, setIsVisible] = React.useState(false);

  const toggleVisibility = () => setIsVisible(!isVisible);

  const handleSubmit = (event: React.FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    console.log("handleSubmit");
  };

  return (
    <div className="flex h-full w-full items-center justify-center">
      <div className="flex w-full max-w-sm flex-col gap-4 rounded-large px-8 pb-10 pt-6">
        <p className="pb-4 text-left text-3xl font-semibold">
          Password dimenticata?
        </p>
        <Form
          className="flex flex-col gap-4"
          validationBehavior="native"
          onSubmit={handleSubmit}
        >
          <Input
            isRequired
            label="Email"
            labelPlacement="outside"
            name="email"
            placeholder="Inserisciemail"
            type="email"
            variant="bordered"
          />
          <Button className="w-full" color="primary" type="submit">
            Log In
          </Button>
        </Form>
        <p className="text-center">
          <Link href="/login">Torna al login</Link>
        </p>
      </div>
    </div>
  );
}
