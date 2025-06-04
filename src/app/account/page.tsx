"use client";
import Link from "next/link";
import { ProductCard } from "@/shared/snippets/product-card";
import { useList } from "@/hooks";
import SettingsPage from "@/shared/account/settings-form";
import { PageHeader } from "@/shared/components";

export default function Page() {
  return (
    <div className="flex min-h-screen flex-col">
      <PageHeader
        title="Impostazioni account"
        subtitle="Gestisci le impostazioni del tuo account"
      />
      <SettingsPage />
    </div>
  );
}
