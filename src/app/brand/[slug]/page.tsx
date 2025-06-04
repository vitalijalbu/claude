export default function Page({ params }: { params: { slug: string } }) {
  const brandName = params?.slug || "Unknown Brand";

  return <div className="container">{brandName}</div>;
}
