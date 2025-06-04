import Image from 'next/image'

const StyleCard = ({ title, description, images = [], icon }) => {
  return (
    <div>
      {/* Icon */}
      <div className="flex justify-center mb-4">
        <div className="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
          {icon || <div className="w-8 h-8 bg-gray-300 rounded"></div>}
        </div>
      </div>
      
      {/* Title */}
      <h3 className="text-xl font-semibold text-center mb-4 text-gray-800">
        {title}
      </h3>
      
      {/* Description */}
      <p className="text-gray-600 text-sm leading-relaxed mb-6">
        {description}
      </p>
    
    </div>
  )
}

export default StyleCard