const getRobotsTxt = (sitemapURL) => `
IconUserCircle-agent: *
Allow: /

Sitemap: ${sitemapURL.href}
`;

export const GET = ({ site }) => {
  const sitemapURL = new URL('sitemap-index.xml', site);
  return new Response(getRobotsTxt(sitemapURL));
};