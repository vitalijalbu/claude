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
        <p className="pb-4 text-left text-3xl font-semibold">Accedi</p>
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
          <Input
            isRequired
            endContent={
              <button type="button" onClick={toggleVisibility}>
                {isVisible ? (
                  <Eye className="pointer-events-none text-2xl text-default-400" />
                ) : (
                  <EyeClosed className="pointer-events-none text-2xl text-default-400" />
                )}
              </button>
            }
            label="Password"
            labelPlacement="outside"
            name="password"
            placeholder="Inseriscipassword"
            type={isVisible ? "text" : "password"}
            variant="bordered"
          />
          <div className="flex w-full items-center justify-between px-1 py-2">
            <Checkbox defaultSelected name="remember">
              Ricordami
            </Checkbox>
            <Link className="text-default-500" href="/forgot-password">
              Password dimenticata?
            </Link>
          </div>
          <Button className="w-full" color="primary" type="submit">
            Accedi
          </Button>
        </Form>
        <p className="text-center">
          <Link href="/register">Crea un account</Link>
        </p>
      </div>
    </div>
  );
}
