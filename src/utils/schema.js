const BASE_URL = "https://it.onlyescort.vip";
const ORGANIZATION = {
  "@id": `${BASE_URL}/#organization`,
  "@type": "Organization",
  "name": "OnlyEscort",
  "logo": "/images/logo.svg",
  "url": BASE_URL,
  "slogan": "Il 1° sito con annunci e profili di Escort",
  "description": "OnlyEscort è il primo sito che permette agli utenti di trovare le migliori escort da incontrare"
};

/**
 * Genera lo schema per la Home Page
 */
export function generateSchemaHome() {
  return {
    "@context": "https://schema.org",
    "@graph": [ORGANIZATION]
  };
}

/**
 * Genera lo schema per una pagina Profile/PDP
 * @param {Object} data - Dati del profilo
 */
export function generateSchemaProfile(data) {
  const {
    pathname,
    id,
    name,
    location,
    telephone,
    images = [],
    rating = {},
    priceRange = "€€",
    title
  } = data;

  // Genera breadcrumbs automaticamente
  const breadcrumbs = generateBreadcrumbsFromPath(pathname, { title });
  
  // Costruisci l'array delle immagini
  const imageObjects = images.map(imageUrl => ({
    "@type": "ImageObject",
    "contentUrl": imageUrl
  }));

  // Schema del profilo
  const profileSchema = {
    "@context": "https://schema.org",
    "@graph": [
      {
        "@id": `${BASE_URL}/profilo/${id}`,
        "@type": "AdultEntertainment",
        "name": name,
        "currenciesAccepted": "EUR",
        "paymentAccepted": "Cash",
        "priceRange": priceRange,
        "location": location,
        "telephone": telephone,
        "image": imageObjects
      }
    ]
  };

  // Aggiungi rating se disponibile
  if (rating.reviewCount && rating.ratingValue) {
    profileSchema["@graph"][0].aggregateRating = {
      "@type": "AggregateRating",
      "itemReviewed": {
        "@id": `${BASE_URL}/profilo/${id}`
      },
      "reviewCount": rating.reviewCount,
      "ratingCount": rating.ratingCount || rating.reviewCount,
      "ratingValue": rating.ratingValue.toString()
    };
  }

  // Schema breadcrumbs
  const breadcrumbSchema = {
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": breadcrumbs.map((item, index) => ({
      "@type": "ListItem",
      "position": index + 1,
      "item": {
        "@id": item.url,
        "@type": "webpage",
        "name": item.name
      }
    }))
  };

  return [breadcrumbSchema, profileSchema];
}

/**
 * Genera lo schema per una pagina Annuncio
 * @param {Object} data - Dati dell'annuncio
 */
export function generateSchemaAnnuncio(data) {
  const {
    pathname,
    id,
    headline,
    description,
    location,
    telephone,
    images = [],
    price,
    validThrough,
    title
  } = data;

  // Genera breadcrumbs automaticamente
  const breadcrumbs = generateBreadcrumbsFromPath(pathname, { title });
  
  // Costruisci l'array delle immagini
  const imageObjects = images.map(imageUrl => ({
    "@type": "ImageObject",
    "contentUrl": imageUrl
  }));

  // Schema dell'annuncio
  const adSchema = {
    "@context": "https://schema.org",
    "@graph": [
      {
        "@id": `${BASE_URL}/annuncio/${id}`,
        "@type": "Service",
        "name": headline,
        "description": description,
        "provider": {
          "@type": "Person",
          "telephone": telephone,
          "address": {
            "@type": "PostalAddress",
            "addressLocality": location
          }
        },
        "image": imageObjects,
        "offers": {
          "@type": "Offer",
          "priceCurrency": "EUR",
          "price": price,
          "validThrough": validThrough,
          "availability": "https://schema.org/InStock"
        }
      }
    ]
  };

  // Schema breadcrumbs
  const breadcrumbSchema = {
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": breadcrumbs.map((item, index) => ({
      "@type": "ListItem",
      "position": index + 1,
      "item": {
        "@id": item.url,
        "@type": "webpage",
        "name": item.name
      }
    }))
  };

  return [breadcrumbSchema, adSchema];
}

/**
 * Genera lo schema per pagine generiche (città, categorie, ecc.)
 * @param {Object} data - Dati della pagina
 */
export function generateSchemaGeneric(data) {
  const { pathname, title } = data;

  // Genera breadcrumbs automaticamente
  const breadcrumbs = generateBreadcrumbsFromPath(pathname, { title });
  
  return {
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": breadcrumbs.map((item, index) => ({
      "@type": "ListItem",
      "position": index + 1,
      "item": {
        "@id": item.url,
        "@type": "webpage",
        "name": item.name
      }
    }))
  };
}

/**
 * Genera lo schema per pagine di listing (es. escort per città)
 * @param {Object} data - Dati della pagina di listing
 */
export function generateSchemaListing(data) {
  const {
    pathname,
    title,
    description,
    location,
    totalResults,
    items = []
  } = data;

  // Genera breadcrumbs automaticamente
  const breadcrumbs = generateBreadcrumbsFromPath(pathname, { title });
  
  // Schema della pagina listing
  const listingSchema = {
    "@context": "https://schema.org",
    "@graph": [
      {
        "@type": "CollectionPage",
        "name": title,
        "description": description,
        "about": {
          "@type": "Place",
          "name": location
        },
        "numberOfItems": totalResults,
        "mainEntity": {
          "@type": "ItemList",
          "numberOfItems": totalResults,
          "itemListElement": items.map((item, index) => ({
            "@type": "ListItem",
            "position": index + 1,
            "item": {
              "@type": "AdultEntertainment",
              "@id": `${BASE_URL}/profilo/${item.id}`,
              "name": item.name,
              "location": item.location
            }
          }))
        }
      }
    ]
  };

  // Schema breadcrumbs
  const breadcrumbSchema = {
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": breadcrumbs.map((item, index) => ({
      "@type": "ListItem",
      "position": index + 1,
      "item": {
        "@id": item.url,
        "@type": "webpage",
        "name": item.name
      }
    }))
  };

  return [breadcrumbSchema, listingSchema];
}

/**
 * Genera automaticamente i breadcrumbs da un pathname
 * @param {string} pathname - Il path della pagina corrente
 * @param {Object} pageData - Dati aggiuntivi della pagina (es. titolo custom)
 */
function generateBreadcrumbsFromPath(pathname, pageData = {}) {
  const breadcrumbs = [
    { name: "OnlyEscort", url: BASE_URL }
  ];
  
  // Rimuovi slash iniziale e finale, poi splitta
  const pathSegments = pathname.replace(/^\/|\/$/g, '').split('/').filter(Boolean);
  
  let currentPath = BASE_URL;
  
  pathSegments.forEach((segment, index) => {
    currentPath += `/${segment}`;
    
    // Logica per determinare il nome del breadcrumb
    let segmentName = segment;
    
    // Personalizza i nomi dei segmenti comuni
    const segmentNames = {
      'escort': 'Escort',
      'profilo': 'Profili',
      'profili': 'Profili',
      'annuncio': 'Annunci',
      'annunci': 'Annunci',
      'citta': 'Città',
      'recensioni': 'Recensioni',
      'milano': 'Milano',
      'roma': 'Roma',
      'torino': 'Torino',
      'napoli': 'Napoli',
      'firenze': 'Firenze'
    };
    
    if (segmentNames[segment.toLowerCase()]) {
      segmentName = segmentNames[segment.toLowerCase()];
    } else if (index === pathSegments.length - 1 && pageData.title) {
      // Usa il titolo della pagina per l'ultimo segmento se disponibile
      segmentName = pageData.title;
    } else {
      // Capitalizza la prima lettera e sostituisci i trattini
      segmentName = segment.replace(/-/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
    }
    
    breadcrumbs.push({
      name: segmentName,
      url: currentPath
    });
  });
  
  return breadcrumbs;
}