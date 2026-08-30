/* Categories page — property listings with filters */
const PROPERTY_IMAGES = [
  'assets/images/blog-1.jpg', 'assets/images/blog-2.jpg', 'assets/images/blog-3.jpg',
  'assets/images/cat-real-estate.jpg', 'assets/images/movie-1.jpg', 'assets/images/movie-2.jpg',
  'assets/images/movie-3.jpg', 'assets/images/movie-4.jpg', 'assets/images/cat-health.jpg',
  'assets/images/hero-banner.jpg', 'assets/images/cat-education.jpg', 'assets/images/cat-shopping.jpg'
];

const PROPERTY_LISTINGS = [
  {
    title: '1500 sqft Plots & Land for Sale in Vilakkuthoon',
    locality: 'madurai', city: 'Vilakkuthoon, Madurai',
    type: 'plots/land', budget: '40-60', bhk: '', sale: 'new',
    price: 'Contact for Price', emi: '₹13,50,000 / Sq Feet',
    specs: [['Direction Facing', 'North West'], ['Boundary Wall', 'Yes'], ['Corner Plots', 'Yes'], ['Plot Area', '1500 Sq Feet']],
    desc: 'STAR LUXOR AVENUE — DTCP & RERA approved gated community site with 9 feet compound wall, 24 hours CCTV, security...',
    amenities: ['CCTV', 'Corporation water', 'Drainage Facility', 'Security'],
    posted: 'KALAISELVI · Mar 02, 2026', photos: 8, viewed: true
  },
  {
    title: 'Flat for Resale in Om Vihar',
    locality: 'delhi', city: 'Om Vihar, Delhi',
    type: 'apartments/flats', budget: '20-40', bhk: '2 bhk', sale: 'resale',
    price: 'Contact for Price', emi: '',
    specs: [['Config', '2 BHK'], ['Sale Type', 'Resale'], ['Age', '3-5 Years'], ['Floors', '4 Total']],
    desc: 'Well-maintained property purchased in 2022, offering modern living with clear ownership and registry documentation...',
    amenities: ['Corporation water', 'Intercom', 'Piped gas'],
    posted: 'SOURAV YADAV · Apr 01, 2026', photos: 4
  },
  {
    title: 'Independent House for Sale in Dwarka',
    locality: 'delhi', city: 'Dwarka, Delhi',
    type: 'houses/villas', budget: 'above-1cr', bhk: '2 bhk', sale: 'new',
    price: '1.50 Crore', emi: 'EMI starts at ₹1.19L / Month',
    specs: [['Config', '2 BHK'], ['Sale Type', 'New'], ['Possession', 'Immediate'], ['Floors', '4 Total']],
    desc: 'Independent 2 BHK house ready to occupy immediately, with a built-up area of 400 sq ft on a plot of 450 sq ft...',
    amenities: ['Corporation water', 'Drainage facility'],
    posted: 'Rajeev Rellan · Apr 22, 2026', photos: 3, viewed: true
  },
  {
    title: 'Independent House for Resale in Patel Nagar',
    locality: 'delhi', city: 'Patel Nagar, Delhi',
    type: 'houses/villas', budget: 'above-1cr', bhk: '3 bhk', sale: 'resale',
    price: '3.30 Crores', emi: 'EMI starts at ₹2.63L / Month',
    specs: [['Config', '3 BHK'], ['Sale Type', 'Resale'], ['Age', '5-10 Years'], ['Parking', '1 Car']],
    desc: '3 BHK independence house with modular kitchen. Near BLK hospital, metro station, market, and school...',
    amenities: ['Lifts', 'ATM', 'Park'],
    posted: 'Garima · May 07, 2026', photos: 13
  },
  {
    title: '2 BHK Flat for Sale in Anna Nagar',
    locality: 'chennai', city: 'Anna Nagar, Chennai',
    type: 'apartments/flats', budget: '40-60', bhk: '2 bhk', sale: 'new',
    price: '55 Lakhs', emi: 'EMI starts at ₹44K / Month',
    specs: [['Config', '2 BHK'], ['Sale Type', 'New'], ['Floor', '5th of 12'], ['Parking', '1 Car']],
    desc: 'Spacious 2 BHK apartment in prime Anna Nagar location with modular kitchen and covered parking...',
    amenities: ['Lift', 'Security', 'Power backup'],
    posted: 'Priya S · May 15, 2026', photos: 6
  },
  {
    title: 'Commercial Showroom in Ashram Road',
    locality: 'ahmedabad', city: 'Ashram Road, Ahmedabad',
    type: 'commercial', budget: 'above-1cr', bhk: '', sale: 'new',
    price: '2.10 Crore', emi: '',
    specs: [['Area', '1200 Sq Ft'], ['Type', 'Showroom'], ['Facing', 'Main Road'], ['Floor', 'Ground']],
    desc: 'Premium commercial showroom on Ashram Road with high footfall and excellent visibility...',
    amenities: ['Parking', 'Security', 'CCTV'],
    posted: 'Yogesh P · May 10, 2026', photos: 5
  },
  {
    title: '3 BHK Villa in Othakadai',
    locality: 'madurai', city: 'Othakadai, Madurai',
    type: 'houses/villas', budget: '60-80', bhk: '3 bhk', sale: 'new',
    price: '72 Lakhs', emi: 'EMI starts at ₹57K / Month',
    specs: [['Config', '3 BHK'], ['Plot', '5 Cent'], ['Sale Type', 'New'], ['Furnished', 'Semi']],
    desc: 'Gated community villa with 3 bedrooms, modular kitchen, and landscaped garden...',
    amenities: ['Gated community', 'CCTV', 'Drainage'],
    posted: 'KALAISELVI · Apr 28, 2026', photos: 10
  },
  {
    title: '1 BHK Flat for Resale in Andheri',
    locality: 'mumbai', city: 'Andheri, Mumbai',
    type: 'apartments/flats', budget: 'below-20', bhk: '1 bhk', sale: 'resale',
    price: '18 Lakhs', emi: '',
    specs: [['Config', '1 BHK'], ['Sale Type', 'Resale'], ['Age', '10+ Years'], ['Floor', '2nd of 7']],
    desc: 'Compact 1 BHK flat near metro station, ideal for singles or investment...',
    amenities: ['Lift', 'Water supply'],
    posted: 'Amit M · May 05, 2026', photos: 4
  },
  {
    title: 'Agricultural Land in Vilacheri',
    locality: 'madurai', city: 'Vilacheri, Madurai',
    type: 'plots/land', budget: '20-40', bhk: '', sale: 'new',
    price: '28 Lakhs', emi: '',
    specs: [['Area', '2 Acres'], ['Type', 'Agricultural'], ['Water', 'Borewell'], ['Road', '30 ft']],
    desc: 'Fertile agricultural land with borewell and road access, suitable for farming or future development...',
    amenities: ['Borewell', 'Road access'],
    posted: 'Murugan R · Apr 18, 2026', photos: 3
  },
  {
    title: '4 BHK Penthouse in Whitefield',
    locality: 'bangalore', city: 'Whitefield, Bangalore',
    type: 'apartments/flats', budget: 'above-1cr', bhk: '4+ bhk', sale: 'new',
    price: '1.85 Crore', emi: 'EMI starts at ₹1.47L / Month',
    specs: [['Config', '4+ BHK'], ['Sale Type', 'New'], ['Terrace', 'Private'], ['Parking', '2 Cars']],
    desc: 'Luxury penthouse with private terrace, smart home features, and club access...',
    amenities: ['Club house', 'Swimming pool', 'Gym', 'Security'],
    posted: 'Neha K · May 20, 2026', photos: 12, viewed: true
  }
];

function renderPropertyCard(property, index) {
  const img = PROPERTY_IMAGES[index % PROPERTY_IMAGES.length];
  const specsHtml = property.specs.map(([l, v]) =>
    `<span class="spec-item">${l} <strong>${v}</strong></span>`
  ).join('');
  const amenitiesHtml = property.amenities.map(a =>
    `<span class="amenity-tag">${a}</span>`
  ).join('') + (property.amenities.length > 3 ? '<span class="amenity-tag">+more</span>' : '');

  return `
    <article class="listing-card"
      data-type="${property.type}"
      data-locality="${property.locality}"
      data-budget="${property.budget}"
      data-bhk="${property.bhk}"
      data-sale="${property.sale}">
      <div class="listing-card-img">
        <img src="${img}" alt="${property.title}">
        ${property.viewed ? '<span class="viewed-badge">Viewed</span>' : ''}
        <span class="photo-count">📷 ${property.photos} Photos</span>
      </div>
      <div class="listing-card-body">
        <div class="listing-card-top">
          <div>
            <h2><a href="category-details.html">${property.title}</a></h2>
            <p class="listing-location">📍 ${property.city}</p>
          </div>
          <div class="listing-price">
            <div class="amount">${property.price}</div>
            ${property.emi ? `<div class="emi">${property.emi}</div>` : ''}
          </div>
        </div>
        <div class="listing-specs">${specsHtml}</div>
        <p class="listing-desc">${property.desc}</p>
        <div class="listing-amenities">${amenitiesHtml}</div>
        <div class="listing-card-footer">
          <span class="listing-posted">by ${property.posted}</span>
          <div class="listing-actions">
            <a href="category-details.html" class="btn btn-primary btn-sm">Contact Owner</a>
            <button type="button" class="btn btn-outline btn-sm">Chat</button>
          </div>
        </div>
      </div>
    </article>
  `;
}

function getActiveChips(group) {
  return Array.from(group.querySelectorAll('.chip.active')).map(c =>
    (c.dataset.value || c.textContent).trim().toLowerCase()
  );
}

function initCategoriesListings() {
  const grid = document.getElementById('categoryListings');
  const sidebar = document.getElementById('categoryFilters');
  const countEl = document.getElementById('categoryCount');

  if (!grid || !sidebar) return;

  grid.innerHTML = PROPERTY_LISTINGS.map(renderPropertyCard).join('');

  initFilterChips(sidebar);

  sidebar.querySelectorAll('.filter-chips[data-single="true"] .chip').forEach(chip => {
    chip.addEventListener('click', () => {
      const group = chip.closest('.filter-chips');
      group.querySelectorAll('.chip').forEach(c => c.classList.remove('active'));
      chip.classList.add('active');
      applyCategoryFilters();
    });
  });

  function applyCategoryFilters() {
    const typeGroup = sidebar.querySelector('[data-filter-group="type"]');
    const bhkGroup = sidebar.querySelector('[data-filter-group="bhk"]');
    const saleGroup = sidebar.querySelector('[data-filter-group="sale"]');

    const types = typeGroup ? getActiveChips(typeGroup) : [];
    const bhkList = bhkGroup ? getActiveChips(bhkGroup) : [];
    const saleList = saleGroup ? getActiveChips(saleGroup) : [];

    const locality = sidebar.querySelector('[data-filter="locality"]')?.value.toLowerCase() || '';
    const budget = sidebar.querySelector('[data-filter="budget"]')?.value || '';

    let visible = 0;

    grid.querySelectorAll('.listing-card').forEach(card => {
      let show = true;

      if (types.length && !types.some(t => card.dataset.type === t)) show = false;
      if (show && locality && locality !== 'all localities' && card.dataset.locality !== locality) show = false;
      if (show && budget && budget !== 'any budget' && card.dataset.budget !== budget) show = false;
      if (show && bhkList.length && card.dataset.bhk && !bhkList.includes(card.dataset.bhk)) show = false;
      if (show && saleList.length && !saleList.includes(card.dataset.sale)) show = false;

      card.style.display = show ? '' : 'none';
      if (show) visible++;
    });

    if (countEl) {
      countEl.innerHTML = `Showing <strong>${visible}</strong> of <strong>${PROPERTY_LISTINGS.length}</strong> properties`;
    }
  }

  sidebar.querySelector('.filter-reset')?.addEventListener('click', () => {
    sidebar.querySelectorAll('.filter-select').forEach(s => { s.selectedIndex = 0; });
    sidebar.querySelectorAll('.filter-chips').forEach(group => {
      group.querySelectorAll('.chip').forEach((c, i) => c.classList.toggle('active', i === 0));
    });
    setTimeout(applyCategoryFilters, 0);
  });

  sidebar.querySelector('.btn-apply-filters')?.addEventListener('click', applyCategoryFilters);
  sidebar.querySelector('[data-filter="locality"]')?.addEventListener('change', applyCategoryFilters);
  sidebar.querySelector('[data-filter="budget"]')?.addEventListener('change', applyCategoryFilters);

  sidebar.querySelectorAll('.filter-chips:not([data-single="true"]) .chip').forEach(chip => {
    chip.addEventListener('click', () => setTimeout(applyCategoryFilters, 0));
  });

  applyCategoryFilters();
}

document.addEventListener('DOMContentLoaded', initCategoriesListings);
