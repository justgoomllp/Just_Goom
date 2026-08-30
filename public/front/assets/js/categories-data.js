/* B2B category sectors — Globy-style structure */
if (typeof window.CATEGORY_SECTORS === 'undefined') {
window.CATEGORY_SECTORS = [
  {
    name: 'Agriculture',
    slug: 'agriculture',
    icon: '🌾',
    subs: [
      { name: 'Agricultural Machinery', slug: 'agricultural-machinery' },
      { name: 'Agrochemicals', slug: 'agrochemicals' },
      { name: 'Animal Feed', slug: 'animal-feed' },
      { name: 'Cocoa Products', slug: 'cocoa-products' },
      { name: 'Coffee & Tea', slug: 'coffee-tea' },
      { name: 'Fertilizers', slug: 'fertilizers' },
      { name: 'Fruits', slug: 'fruits' },
      { name: 'Grains & Cereals', slug: 'grains-cereals' },
      { name: 'Meat & Seafood', slug: 'meat-seafood' },
      { name: 'Seeds & Plants', slug: 'seeds-plants' },
      { name: 'Vegetables', slug: 'vegetables' }
    ]
  },
  {
    name: 'Apparel',
    slug: 'apparel',
    icon: '👔',
    subs: [
      { name: 'Accessories', slug: 'accessories' },
      { name: 'Baby Clothing', slug: 'baby-clothing' },
      { name: "Children's Clothing", slug: 'childrens-clothing' },
      { name: "Men's Clothing", slug: 'mens-clothing' },
      { name: 'Sportswear', slug: 'sportswear' },
      { name: "Women's Clothing", slug: 'womens-clothing' },
      { name: 'Workwear', slug: 'workwear' },
      { name: 'Tailors', slug: 'tailors' }
    ]
  },
  {
    name: 'Beauty & Personal Care',
    slug: 'beauty-personal-care',
    icon: '💄',
    subs: [
      { name: 'Baby Care', slug: 'baby-care' },
      { name: 'Beauty Supplies', slug: 'beauty-supplies' },
      { name: 'Beauty Spa', slug: 'beauty-spa' },
      { name: 'Salons', slug: 'salons' },
      { name: 'Men Care', slug: 'men-care' },
      { name: 'Women Care', slug: 'women-care' },
      { name: 'Salon Equipment', slug: 'salon-equipment' }
    ]
  },
  {
    name: 'Chemicals',
    slug: 'chemicals',
    icon: '⚗️',
    subs: [
      { name: 'Adhesives & Sealants', slug: 'adhesives-sealants' },
      { name: 'Agrochemicals', slug: 'agrochemicals' },
      { name: 'Coatings & Paints', slug: 'coatings-paints' },
      { name: 'Food Additives', slug: 'food-additives' },
      { name: 'Industrial Chemicals', slug: 'industrial-chemicals' },
      { name: 'Organic Intermediates', slug: 'organic-intermediates' },
      { name: 'Pharmaceutical Additives', slug: 'pharmaceutical-additives' },
      { name: 'Solvents', slug: 'solvents' }
    ]
  },
  {
    name: 'Construction',
    slug: 'construction',
    icon: '🏗️',
    subs: [
      { name: 'Building Materials', slug: 'building-materials' },
      { name: 'Contractors', slug: 'contractors' },
      { name: 'Doors & Windows', slug: 'doors-windows' },
      { name: 'Flooring & Accessories', slug: 'flooring' },
      { name: 'Kitchens & Bathrooms', slug: 'kitchens-bathrooms' },
      { name: 'Landscaping Stone', slug: 'landscaping' },
      { name: 'Real Estate', slug: 'estate' },
      { name: 'Waterproofing Materials', slug: 'waterproofing' },
      { name: 'Wood & Timber', slug: 'wood-timber' }
    ]
  },
  {
    name: 'Consumer Electronics',
    slug: 'consumer-electronics',
    icon: '📱',
    subs: [
      { name: 'Cables & Adapters', slug: 'cables-adapters' },
      { name: 'Camera & Accessories', slug: 'camera' },
      { name: 'Computer Hardware', slug: 'computer-hardware' },
      { name: 'Laptops & Accessories', slug: 'laptops' },
      { name: 'Mobile Phone Shops', slug: 'mobile-shops' },
      { name: 'Smart Electronics', slug: 'smart-electronics' },
      { name: 'TV & Audio', slug: 'tv-audio' },
      { name: 'Computer Repair', slug: 'computer-repair' }
    ]
  },
  {
    name: 'Electrical Equipment',
    slug: 'electrical-equipment',
    icon: '⚡',
    subs: [
      { name: 'Batteries', slug: 'batteries' },
      { name: 'Connectors & Terminals', slug: 'connectors' },
      { name: 'Electrical Instruments', slug: 'electrical-instruments' },
      { name: 'Generators', slug: 'generators' },
      { name: 'Industrial Controls', slug: 'industrial-controls' },
      { name: 'Power Supplies', slug: 'power-supplies' },
      { name: 'Electricians', slug: 'electricians' },
      { name: 'Wiring & Cables', slug: 'wiring-cables' }
    ]
  },
  {
    name: 'Food & Beverage',
    slug: 'food-beverage',
    icon: '🍽️',
    subs: [
      { name: 'Restaurants', slug: 'restaurants' },
      { name: 'Coffee Shops', slug: 'coffee-shops' },
      { name: 'Catering Services', slug: 'catering' },
      { name: 'Supermarkets', slug: 'supermarkets' },
      { name: 'Food & Dining', slug: 'food' },
      { name: 'Packaged Foods', slug: 'packaged-foods' },
      { name: 'Beverages', slug: 'beverages' }
    ]
  },
  {
    name: 'Health & Medical',
    slug: 'health-medical',
    icon: '🏥',
    subs: [
      { name: 'Hospitals', slug: 'hospitals' },
      { name: 'Dentists', slug: 'dentists' },
      { name: 'Pharmacy', slug: 'pharmacy' },
      { name: 'Health & Wellness', slug: 'health' },
      { name: 'Medical Equipment', slug: 'medical-equipment' },
      { name: 'Diagnostic Labs', slug: 'diagnostic-labs' },
      { name: 'Clinics', slug: 'clinics' }
    ]
  },
  {
    name: 'Education',
    slug: 'education',
    icon: '🎓',
    subs: [
      { name: 'Schools & Colleges', slug: 'schools' },
      { name: 'Coaching Centers', slug: 'coaching' },
      { name: 'Driving Schools', slug: 'driving' },
      { name: 'Book Stores', slug: 'book-stores' },
      { name: 'Training Institutes', slug: 'training' },
      { name: 'Online Education', slug: 'online-education' }
    ]
  },
  {
    name: 'Automotive',
    slug: 'automotive',
    icon: '🚗',
    subs: [
      { name: 'Automobile Dealers', slug: 'automobile' },
      { name: 'Auto Repair', slug: 'auto-repair' },
      { name: 'Spare Parts', slug: 'spare-parts' },
      { name: 'Car Wash', slug: 'car-wash' },
      { name: 'Tyres & Wheels', slug: 'tyres' },
      { name: 'Two Wheeler', slug: 'two-wheeler' }
    ]
  },
  {
    name: 'Business Services',
    slug: 'business-services',
    icon: '💼',
    subs: [
      { name: 'Chartered Accountants', slug: 'chartered-accountants' },
      { name: 'Legal Services', slug: 'legal' },
      { name: 'Insurance', slug: 'insurance' },
      { name: 'Consulting', slug: 'consulting' },
      { name: 'IT Services', slug: 'it-services' },
      { name: 'Marketing Agencies', slug: 'marketing' },
      { name: 'Loans & Finance', slug: 'loans' }
    ]
  },
  {
    name: 'Hospitality & Travel',
    slug: 'hospitality-travel',
    icon: '🏨',
    subs: [
      { name: 'Hotels', slug: 'hotels' },
      { name: 'PG & Hostels', slug: 'pg' },
      { name: 'Travel Agency', slug: 'travel' },
      { name: 'Tours & Travels', slug: 'tours' },
      { name: 'Resorts', slug: 'resorts' },
      { name: 'Rent & Hire', slug: 'rent' }
    ]
  },
  {
    name: 'Home & Furniture',
    slug: 'home-furniture',
    icon: '🛋️',
    subs: [
      { name: 'Home Decor', slug: 'home-decor' },
      { name: 'Furniture', slug: 'furniture' },
      { name: 'Hardware Stores', slug: 'hardware-stores' },
      { name: 'Interior Design', slug: 'interior-design' },
      { name: 'Kitchen Appliances', slug: 'kitchen-appliances' },
      { name: 'Lighting', slug: 'lighting' }
    ]
  },
  {
    name: 'Logistics & Transport',
    slug: 'logistics-transport',
    icon: '🚚',
    subs: [
      { name: 'Courier Service', slug: 'courier' },
      { name: 'Packers & Movers', slug: 'packers' },
      { name: 'Freight & Shipping', slug: 'freight' },
      { name: 'Warehousing', slug: 'warehousing' },
      { name: 'Cold Storage', slug: 'cold-storage' },
      { name: 'Fleet Management', slug: 'fleet' }
    ]
  },
  {
    name: 'Entertainment & Events',
    slug: 'entertainment-events',
    icon: '🎉',
    subs: [
      { name: 'Event Organisers', slug: 'event' },
      { name: 'Wedding Planning', slug: 'wedding' },
      { name: 'Photography', slug: 'photography' },
      { name: 'Cinemas', slug: 'cinemas' },
      { name: 'Party Venues', slug: 'party-venues' },
      { name: 'DJ & Music', slug: 'dj-music' }
    ]
  },
  {
    name: 'Sports & Fitness',
    slug: 'sports-fitness',
    icon: '💪',
    subs: [
      { name: 'Gym & Fitness', slug: 'gym' },
      { name: 'Sports Equipment', slug: 'sports-equipment' },
      { name: 'Yoga Studios', slug: 'yoga' },
      { name: 'Swimming Pools', slug: 'swimming' },
      { name: 'Sports Coaching', slug: 'sports-coaching' }
    ]
  },
  {
    name: 'Industrial Engineering',
    slug: 'industrial-engineering',
    icon: '⚙️',
    subs: [
      { name: 'Machinery & Equipment', slug: 'machinery' },
      { name: 'Industrial Supplies', slug: 'industrial-supplies' },
      { name: 'Tools & Hardware', slug: 'tools' },
      { name: 'Manufacturing', slug: 'manufacturing' },
      { name: 'Metal Fabrication', slug: 'metal-fabrication' },
      { name: 'Packaging Machinery', slug: 'packaging-machinery' }
    ]
  },
  {
    name: 'Packaging & Printing',
    slug: 'packaging-printing',
    icon: '📦',
    subs: [
      { name: 'Packaging Service', slug: 'packaging' },
      { name: 'Printing Services', slug: 'printing' },
      { name: 'Labels & Stickers', slug: 'labels' },
      { name: 'Paper Products', slug: 'paper-products' },
      { name: 'Flex & Signage', slug: 'signage' }
    ]
  },
  {
    name: 'Home Services',
    slug: 'home-services',
    icon: '🔧',
    subs: [
      { name: 'Plumbers', slug: 'plumbers' },
      { name: 'Electricians', slug: 'electricians' },
      { name: 'AC Repair', slug: 'ac-repair' },
      { name: 'Painters', slug: 'painters' },
      { name: 'Pest Control', slug: 'pest-control' },
      { name: 'Laundry', slug: 'laundry' },
      { name: 'Cleaning Services', slug: 'cleaning' }
    ]
  },
  {
    name: 'Pets & Animals',
    slug: 'pets-animals',
    icon: '🐾',
    subs: [
      { name: 'Pet Shops', slug: 'pet' },
      { name: 'Veterinary Clinics', slug: 'veterinary' },
      { name: 'Pet Grooming', slug: 'pet-grooming' },
      { name: 'Pet Food & Supplies', slug: 'pet-supplies' }
    ]
  },
  {
    name: 'Jewellery & Gifts',
    slug: 'jewellery-gifts',
    icon: '💎',
    subs: [
      { name: 'Jewellery', slug: 'jewellery' },
      { name: 'Gold & Silver', slug: 'gold-silver' },
      { name: 'Gift Shops', slug: 'gift-shops' },
      { name: 'Watches', slug: 'watches' }
    ]
  },
  {
    name: 'Security & Safety',
    slug: 'security-safety',
    icon: '🛡️',
    subs: [
      { name: 'Security Services', slug: 'security' },
      { name: 'CCTV & Surveillance', slug: 'cctv' },
      { name: 'Fire Safety', slug: 'fire-safety' },
      { name: 'Safety Equipment', slug: 'safety-equipment' }
    ]
  },
  {
    name: 'Environment & Waste',
    slug: 'environment-waste',
    icon: '♻️',
    subs: [
      { name: 'Waste Management', slug: 'waste-management' },
      { name: 'Recycling', slug: 'recycling' },
      { name: 'Water Treatment', slug: 'water-treatment' },
      { name: 'Solar Energy', slug: 'solar-energy' },
      { name: 'Environmental Services', slug: 'environmental' }
    ]
  }
];

}
var CATEGORY_SECTORS = window.CATEGORY_SECTORS;

function slugifyCategory(text) {
  return String(text).toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
}

function getCategoryProfileUrl(slug) {
  var base = (window.FRONT_ROUTES && window.FRONT_ROUTES.allProfiles) || '/all-profiles';
  return base + (base.indexOf('?') >= 0 ? '&' : '?') + 'category=' + encodeURIComponent(slug);
}

function getTotalSubcategoryCount() {
  return CATEGORY_SECTORS.reduce(function(sum, s) { return sum + s.subs.length; }, 0);
}
