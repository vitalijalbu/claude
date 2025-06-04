"use client";

import React from "react";
import { Form, Button, Input, Checkbox, Link } from "@heroui/react";
import { Eye, EyeClosed } from "lucide-react";

export default function Page() {
  const [isVisible, setIsVisible] = React.useState(false);
  const [isConfirmVisible, setIsConfirmVisible] = React.useState(false);

  const toggleVisibility = () => setIsVisible(!isVisible);
  const toggleConfirmVisibility = () => setIsConfirmVisible(!isConfirmVisible);

  const handleSubmit = (event: React.FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    console.log("handleSubmit");
  };

  return (
    <div className="flex h-full w-full items-center justify-center">
      <div className="flex w-full max-w-sm flex-col gap-4 rounded-large px-8 pb-10 pt-6">
        <p className="pb-4 text-left text-3xl font-semibold">Crea un account</p>
        <Form
          className="flex flex-col gap-4"
          validationBehavior="native"
          onSubmit={handleSubmit}
        >
          <Input
            isRequired
            label="Nome"
            labelPlacement="outside"
            name="first_name"
            placeholder="Inserisci nome"
            type="text"
            variant="bordered"
          />
          <Input
            isRequired
            label="Email"
            labelPlacement="outside"
            name="email"
            placeholder="Inserisci la tua email"
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
            label="Confirm Password"
            labelPlacement="outside"
            name="confirmPassword"
            placeholder="Confirm your password"
            type={isConfirmVisible ? "text" : "password"}
            variant="bordered"
          />
          <Checkbox isRequired className="py-4">
            I agree with the&nbsp;
            <Link className="relative z-[1]" href="#">
              Terms
            </Link>
            &nbsp; and&nbsp;
            <Link className="relative z-[1]" href="#">
              Privacy Policy
            </Link>
          </Checkbox>
          <Button color="primary" type="submit">
            Registrati
          </Button>
        </Form>
        <p className="text-center">
          <Link href="/login">Hai già un account? Accedi</Link>
        </p>
      </div>
    </div>
  );
}
