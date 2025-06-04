import Image from 'next/image'

const MediaText = ({ 
  title, 
  content, 
  imageSrc = "/images/placeholder.svg", 
  imageAlt = "Placeholder image",
  imagePosition = "right",
  backgroundColor = "transparent",
  className = ""
}) => {
  const isImageLeft = imagePosition === "left"
  
  return (
    <section className={`py-16 ${backgroundColor} ${className}`}>
      <div className="container mx-auto px-4">
        <div className={`flex flex-col lg:flex-row items-center gap-12 ${isImageLeft ? 'lg:flex-row' : 'lg:flex-row-reverse'}`}>
          {/* Text Content */}
          <div className="flex-1">
            {title && (
              <h2 className="text-3xl lg:text-4xl font-bold mb-6 text-gray-800">
                {title}
              </h2>
            )}
            <div className="text-gray-600 leading-relaxed space-y-4">
              {content}
            </div>
          </div>
          
          {/* Image */}
          <div className="flex-1">
            <div className="relative w-full h-80 lg:h-96 overflow-hidden">
              <Image
                src={imageSrc}
                alt={imageAlt}
                fill
                className="object-cover"
              />
            </div>
          </div>
        </div>
      </div>
    </section>
  )
}

export default MediaText