import { AccountSidebar } from "@/shared/account/sidebar";

export default function AccountLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <div className="container py-4">
      <div className="block lg:grid lg:grid-cols-[320px_1fr] gap-6 items-start">
        <aside className="block mb-6 lg:mb-0">
          <AccountSidebar />
        </aside>
        <div>{children}</div>
      </div>
    </div>
  );
}