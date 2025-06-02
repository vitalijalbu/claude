export function getBreadcrumbSchema(pages = []) {
    return {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      itemListElement: pages.map((page, index) => ({
        "@type": "ListItem",
        position: index + 1,
        item: {
          "@id": page.url,
          "@type": "WebPage",
          name: page.name,
        },
      })),
    };
  }
  