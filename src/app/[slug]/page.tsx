import MediaText from '@/shared/sections/media-text'
import StyleCard from '@/shared/snippets/style-card'
import { Button } from '@heroui/react'
import Head from 'next/head'
import Link from 'next/link'

export default function Storia() {
  const heroContent = (
    <>
      <p>
        <strong>Stile è il primo event store d'Italia.</strong> Questo termine indica un concetto nuovo di boutique che articola 
        la sua offerta in base agli eventi, più o meno importanti, nella vita di una persona.
      </p>
      <p>
        Un matrimonio, un battesimo, una laurea sono tutti quanti con esigenze particolari da soddisfare 
        in termini di organizzazione e cura del particolare.
      </p>
      <p>
        Stile Event Store è una <strong>boutique</strong> che propone una selezione di ricercati brand artigianali, 
        ma è anche un luogo dove trovare, tutte riunite, figure professionali specializzate in ambiti differenti: dal 
        catering al wedding planner, dal cake design al marketing. L'obiettivo è mettere a fuoco lo stile particolare 
        dei clienti coinvolgendoli e infine personalizzare e creare il suo evento perfetto.
      </p>
    </>
  )

  const storyContent = (
    <>
      <p>
        L'attività nasce nel <strong>1985</strong> come "Bottega dei Fiori", una fiorista a conduzione familiare 
        che nel 2014 amplia il terreno di gioco.
      </p>
      <p>
        Nel tempo si aggiungono nuove sfere d'azione: finché l'etichettta di fioreria non è più sufficiente 
        a contenere l'ampiezza dell'offerta presente in boutique. Nel 2014 avviene una profonda 
        ristrutturazione aziendale che vede nascere il concetto di event store.
      </p>
      <p>
        Il punto vendita è studiato per rendere accogliente e apprezzabile l'esperienza d'acquisto. 
        Entrando si respira un'atmosfera rilassata e ricca di spunti creativi che ogni cliente può far 
        propri grazie all'aiuto di uno staff preparato e sempre disponibile.
      </p>
    </>
  )

  const styleCards = [
    {
      title: "Shabby Chic",
      description: "Raffinatezza e tonalità pastello impreziosite con accenti retrò da che vuol apparire come faco di un'antica grazia. Quello mood predilige i colori chiari: il beige, il bianco, il rosa e il grigio chiaro. Tutto richiama un'atmosfera idillica, luminosa, dove il tempo pare essersi fermato in un'epoca di quiete serenità. Chi lo scoglie? Rispecchia il gusto di persone romantiche, delicate, creative, abituate a riconoscere i dettagli che fanno la differenza. Chi lo scoglie è di solito elegante amante delle cose preziose.",
      images: Array(4).fill(null)
    },
    {
      title: "Vintage Rustic",
      description: "Natura e amore per la tradizione si mescolano facendo scaturire colore e armonia da un mix di splendide imperfezioni. Questo mood predilige i colori della terra come marrone e verde, il fuco delle candele, il profumo del legno naturale. Chi lo scoglie? Rimanda a un mondo semplice e genuino e sempre presente, per questo il vintage rustic incontra il gusto di persone che amano la natura, l'artigianalità e l'autenticità legate ai valori del passato.",
      images: Array(4).fill(null)
    },
    {
      title: "Minimal",
      description: "Essenzialità ed eleganza della forma sublimano gli spazi in un gioco perfetto di linee forti e geometrie moderne. Questo mood predilige i colori neutri come bianco, nero, grigio e beige. L'alternanza studiata di queste tonalità, unita alle forme schematiche, conferisce un senso di ordine e precisione assoluti. Chi lo scoglie? Questo mood rispecchia il gusto delle persone che amano l'ordine, la praticità e la concretezza e sono attratte dal design e da una bellezza punta e decisa.",
      images: Array(4).fill(null)
    }
  ]

  return (
    <>
      <Head>
        <title>La Storia - Stile Event Store</title>
        <meta name="description" content="Scopri la storia di Stile, il primo event store d'Italia" />
      </Head>

      <main className="min-h-screen">
        {/* Hero Section */}
        <MediaText
          title="La storia"
          content={heroContent}
          imageSrc="/images/placeholder.svg"
          imageAlt="Stile Event Store"
          imagePosition="right"
        />

        {/* Story Section */}
        <MediaText
          content={storyContent}
          imageSrc="/images/placeholder.svg"
          imageAlt="Bottega dei Fiori"
          imagePosition="left"
        />

        {/* CTA Button */}
        <section className="py-8 text-center">
          <Button as={Link}  href="/" variant="bordered">
            Esplora il nostro punto vendita
          </Button>
        </section>

        {/* Style Question Section */}
        <section className="py-16">
          <div className="container mx-auto px-4 text-center">
            <h2 className="text-4xl font-bold mb-4 text-gray-800">
              E TU DI CHE STILE SEI?
            </h2>
            <p className="text-xl italic text-gray-600 mb-12">
              Benessere a portata di mano con eleganza
            </p>
            <p className="text-gray-600 max-w-3xl mx-auto leading-relaxed">
              Grazie allo studio delle principali tendenze in fatto di arredo per interni, 
              abbiamo individuato alcuni stili, o per meglio dire mood, che funzionano 
              come chiavi di lettura per interpretare qualsiasi evento.
            </p>
            <p className="text-gray-600 max-w-3xl mx-auto leading-relaxed mt-4">
              I tre mood proposti da Stile Event Store sono: Minimal, Shabby Chic e Vintage Rustic.
            </p>
          </div>
        </section>

        {/* Style Cards Grid */}
        <section className="py-16">
          <div className="container mx-auto px-4">
            <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
              {styleCards.map((card, index) => (
                <StyleCard
                  key={index}
                  title={card.title}
                  description={card.description}
                  images={card.images}
                />
              ))}
            </div>
          </div>
        </section>
      </main>
    </>
  )
}