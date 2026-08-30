/* Company profiles — home + all profiles pages */
const PROFILES_PER_PAGE = 12;
const HOME_PROFILE_LIMIT = PROFILES_PER_PAGE;
const ALL_PROFILES_LIMIT = 100;

const COMPANY_BANNER_FILES = [
  'movie-1.jpg', 'movie-2.jpg', 'movie-3.jpg',
  'movie-4.jpg', 'movie-5.jpg', 'movie-6.jpg',
  'blog-1.jpg', 'blog-2.jpg', 'blog-3.jpg',
  'cat-real-estate.jpg', 'cat-health.jpg', 'cat-entertainment.jpg',
  'cat-education.jpg', 'cat-shopping.jpg', 'cat-automobile.jpg',
  'cat-food.jpg', 'cat-business.jpg', 'hero-banner.jpg'
];

function getCompanyBanner(company, index) {
  if (company && company.bannerUrl) {
    return company.bannerUrl;
  }
  var base = window.FRONT_ASSETS || 'front/assets/images';
  return base + '/' + COMPANY_BANNER_FILES[index % COMPANY_BANNER_FILES.length];
}

const COMPANY_LOGO_COLORS = ['#1A428A', '#F7941D', '#16a34a', '#7c3aed', '#0891b2', '#dc2626', '#ca8a04', '#059669'];

const STATIC_COMPANY_PROFILES = [
  { name: 'Fixmycars', category: 'Travel Agency', projects: 225, tasks: 197, city: 'Ahmedabad', verified: true, featured: true, addedDaysAgo: 2 },
  { name: 'Plasma Graphics', category: 'Packaging Service', projects: 164, tasks: 182, city: 'Mumbai', verified: true, featured: true, addedDaysAgo: 5 },
  { name: 'Devada Jewelry', category: 'Metal-crafts', projects: 352, tasks: 376, city: 'Madurai', verified: true, featured: true, addedDaysAgo: 1 },
  { name: 'Pradhan Mantri Jan Aushdhi', category: 'Medical Store', projects: 241, tasks: 204, city: 'Delhi', verified: true, featured: false, addedDaysAgo: 12 },
  { name: 'Sunrise Realty', category: 'Real Estate', projects: 89, tasks: 54, city: 'Ahmedabad', verified: true, addedDaysAgo: 45 },
  { name: 'HealthFirst Clinic', category: 'Health & Wellness', projects: 62, tasks: 38, city: 'Chennai', verified: false, addedDaysAgo: 8 },
  { name: 'CineMax Entertainment', category: 'Entertainment', projects: 115, tasks: 72, city: 'Mumbai', verified: true, addedDaysAgo: 22 },
  { name: 'BrightMinds Academy', category: 'Education', projects: 48, tasks: 31, city: 'Bangalore', verified: false, addedDaysAgo: 60 },
  { name: 'ShopEasy Mart', category: 'Shopping', projects: 203, tasks: 145, city: 'Delhi', verified: true, addedDaysAgo: 3 },
  { name: 'AutoCare Motors', category: 'Automobile', projects: 77, tasks: 49, city: 'Ahmedabad', verified: false, addedDaysAgo: 90 },
  { name: 'SpiceRoute Dining', category: 'Food & Dining', projects: 56, tasks: 33, city: 'Madurai', verified: true, addedDaysAgo: 18 },
  { name: 'BizGrow Consultants', category: 'Business Services', projects: 34, tasks: 22, city: 'Bangalore', verified: false, addedDaysAgo: 120 },
  { name: 'GreenBuild Contractors', category: 'Home Construction', projects: 91, tasks: 60, city: 'Delhi', verified: true, addedDaysAgo: 35 },
  { name: 'PixelSoft IT', category: 'Software Development', projects: 128, tasks: 95, city: 'Bangalore', verified: true, addedDaysAgo: 6 },
  { name: 'LegalEase Associates', category: 'Legal Services', projects: 29, tasks: 18, city: 'Chennai', verified: false, addedDaysAgo: 200 },
  { name: 'FitLife Gym', category: 'Fitness Center', projects: 44, tasks: 27, city: 'Mumbai', verified: false, addedDaysAgo: 14 },
  { name: 'TravelMate Tours', category: 'Tours & Travels', projects: 67, tasks: 41, city: 'Ahmedabad', verified: true, addedDaysAgo: 28 },
  { name: 'PrintPro Solutions', category: 'Printing Services', projects: 52, tasks: 36, city: 'Delhi', verified: false, addedDaysAgo: 75 },
  { name: 'AgroFresh Farms', category: 'Agriculture', projects: 38, tasks: 24, city: 'Madurai', verified: false, addedDaysAgo: 150 },
  { name: 'StyleHub Fashion', category: 'Fashion Retail', projects: 73, tasks: 48, city: 'Mumbai', verified: true, addedDaysAgo: 9 },
  { name: 'SafeGuard Security', category: 'Security Services', projects: 61, tasks: 39, city: 'Chennai', verified: false, addedDaysAgo: 40 },
  { name: 'CleanHome Services', category: 'Home Services', projects: 85, tasks: 58, city: 'Bangalore', verified: true, addedDaysAgo: 11 },
  { name: 'TechRepair Hub', category: 'Electronics Repair', projects: 47, tasks: 30, city: 'Ahmedabad', verified: false, addedDaysAgo: 55 },
  { name: 'PetCare Paradise', category: 'Pet Services', projects: 33, tasks: 19, city: 'Delhi', verified: false, addedDaysAgo: 100 },
  { name: 'EventCraft Planners', category: 'Event Management', projects: 58, tasks: 35, city: 'Mumbai', verified: true, addedDaysAgo: 7 },
  { name: 'MediPlus Pharmacy', category: 'Pharmacy', projects: 42, tasks: 26, city: 'Madurai', verified: false, addedDaysAgo: 65 },
  { name: 'UrbanDecor Interiors', category: 'Interior Design', projects: 69, tasks: 44, city: 'Bangalore', verified: true, addedDaysAgo: 20 },
  { name: 'SwiftLogistics', category: 'Logistics', projects: 96, tasks: 63, city: 'Chennai', verified: true, addedDaysAgo: 4 },
  { name: 'PhotoFrame Studio', category: 'Photography', projects: 51, tasks: 32, city: 'Ahmedabad', verified: false, addedDaysAgo: 180 },
  { name: 'EcoSolar Energy', category: 'Renewable Energy', projects: 74, tasks: 47, city: 'Delhi', verified: true, addedDaysAgo: 25 },
  { name: 'CraftBox Handmade', category: 'Handicrafts', projects: 39, tasks: 21, city: 'Madurai', verified: false, addedDaysAgo: 320 }
];

var COMPANY_PROFILES = (typeof window.COMPANY_PROFILES !== 'undefined')
  ? window.COMPANY_PROFILES
  : STATIC_COMPANY_PROFILES;

var HOME_PROFILES = COMPANY_PROFILES.slice(0, HOME_PROFILE_LIMIT);
var ALL_PROFILES_LIST = COMPANY_PROFILES.slice(0, Math.min(COMPANY_PROFILES.length, ALL_PROFILES_LIMIT));

function getCompanyInitials(name) {
  return name.split(/\s+/).map(w => w[0]).join('').slice(0, 2).toUpperCase();
}

function formatAddedTime(days) {
  days = Math.max(0, Math.floor(Number(days) || 0));
  if (days === 0) return 'Added today';
  if (days === 1) return 'Added yesterday';
  if (days <= 7) return 'Added ' + days + ' days ago';
  if (days <= 30) return 'Added ' + Math.floor(days / 7) + ' week' + (Math.floor(days / 7) > 1 ? 's' : '') + ' ago';
  if (days <= 365) return 'Added ' + Math.floor(days / 30) + ' month' + (Math.floor(days / 30) > 1 ? 's' : '') + ' ago';
  return 'Added over a year ago';
}

function getActiveChips(group) {
  return Array.from(group.querySelectorAll('.chip.active')).map(c =>
    (c.dataset.value || c.textContent).trim().toLowerCase()
  );
}

function initFilterChipsLocal(container) {
  container.querySelectorAll('.filter-chips').forEach(group => {
    const isSingle = group.dataset.single === 'true';
    group.querySelectorAll('.chip').forEach(chip => {
      chip.addEventListener('click', () => {
        if (isSingle) {
          group.querySelectorAll('.chip').forEach(c => c.classList.remove('active'));
          chip.classList.add('active');
        } else {
          chip.classList.toggle('active');
        }
      });
    });
  });
}

function verificationBadge(status) {
  const tips = {
    verified: 'Documents approved by JustGoom. This profile is verified.',
    pending: 'Documents uploaded. Waiting for admin review.',
    unapproved: 'Documents were not approved. Please update and resubmit.',
    unverified: 'No company documents uploaded yet.',
  };
  const tip = tips[status] || tips.unverified;
  const escTip = String(tip).replace(/"/g, '&quot;');

  if (status === 'verified') {
    return '<span class="company-verified-badge" tabindex="0" data-tooltip="' + escTip + '"><i class="fa fa-check-circle" aria-hidden="true"></i> Verified</span>';
  }
  if (status === 'pending') {
    return '<span class="company-verified-badge is-pending" tabindex="0" data-tooltip="' + escTip + '"><i class="fa fa-hourglass-half" aria-hidden="true"></i> Pending</span>';
  }
  if (status === 'unapproved') {
    return '<span class="company-verified-badge is-unapproved" tabindex="0" data-tooltip="' + escTip + '"><i class="fa fa-times-circle" aria-hidden="true"></i> Unapproved</span>';
  }
  return '<span class="company-verified-badge is-unverified" tabindex="0" data-tooltip="' + escTip + '"><i class="fa fa-question-circle" aria-hidden="true"></i> Unverified</span>';
}

function renderCompanyCard(company, index) {
  const banner = getCompanyBanner(company, index);
  const logoColor = COMPANY_LOGO_COLORS[index % COMPANY_LOGO_COLORS.length];
  const name = company.name || 'Business';
  const category = company.category || 'Uncategorized';
  const city = company.city || 'N/A';
  const country = company.country || 'N/A';
  const initials = getCompanyInitials(name);
  const completionLevel = company.completionLevel || (company.featured ? 'complete' : 'low');
  const starClass = ' is-' + completionLevel;
  const completionPercent = Number(company.completionPercent ?? 0);
  const starTips = {
    complete: completionPercent + '% complete. Green star: 6 or more About sections added (Team, Services, Products, Projects, Documents, Videos, Blog, Ads).',
    medium: completionPercent + '% complete. Yellow star: 3 to 5 About sections added.',
    low: completionPercent + '% complete. Red star: 0 to 2 About sections added. Add more to raise your rating.',
  };
  const starTip = String(starTips[completionLevel] || starTips.low).replace(/"/g, '&quot;');
  const trendingTag = completionLevel === 'complete'
    ? '<span class="company-star-tag" data-tooltip="Trending: this profile has 6 or more About sections filled."><i class="fa fa-fire" aria-hidden="true"></i> Trending</span>'
    : '';
  const addedDays = company.addedDaysAgo ?? 0;
  const logoHtml = company.logoUrl
    ? `<div class="company-logo company-logo-image"><img src="${company.logoUrl}" alt="${name}"></div>`
    : `<div class="company-logo" style="background:${logoColor}">${initials}</div>`;

  const profileUrl = company.profileUrl || '#';
  const esc = function(value) {
    return String(value || '').toLowerCase().replace(/"/g, '&quot;');
  };
  const escAttr = function(value) {
    return String(value || '').replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;');
  };

  return `
    <article class="company-card company-card-clickable"
      role="link"
      tabindex="0"
      data-profile-url="${escAttr(profileUrl)}"
      data-company-name="${escAttr(name)}"
      data-company-category="${escAttr(category)}"
      data-company-city="${escAttr(city)}"
      data-company-country="${escAttr(country)}"
      data-company-logo="${escAttr(company.logoUrl || '')}"
      data-company-banner="${escAttr(banner)}"
      data-company-projects="${company.projects ?? 0}"
      data-company-services="${company.services ?? company.tasks ?? 0}"
      data-name="${esc(name)}"
      data-category="${esc(category)}"
      data-category-slug="${esc(company.categorySlug)}"
      data-subcategory="${esc(company.subCategory)}"
      data-subcategory-slug="${esc(company.subCategorySlug)}"
      data-locality="${esc(city)}"
      data-country="${esc(country)}"
      data-verified="${company.verified ? 'yes' : 'no'}"
      data-verification-status="${esc(company.verificationStatus || (company.verified ? 'verified' : 'unverified'))}"
      data-added-days="${addedDays}">
      <div class="company-card-banner">
        <img src="${banner}" alt="${escAttr(name)}">
        <div class="company-star-wrap${starClass}">
          <span class="company-star" tabindex="0" data-tooltip="${starTip}" aria-label="${starTip}"><i class="fa fa-star" aria-hidden="true"></i></span>
          ${trendingTag}
        </div>
        <div class="company-menu-wrap">
          <button type="button" class="company-menu-btn" aria-label="More options"><i class="fa fa-ellipsis-h"></i></button>
          <div class="company-menu-dropdown">
            <button type="button" class="js-download-profile-pdf">Download Profile PDF</button>
            <button type="button" class="js-share-profile-link">Share Profile Link</button>
          </div>
        </div>
      </div>
      <div class="company-card-body">
        ${logoHtml}
        <h3 class="company-name">${name}</h3>
        <p class="company-category">${category}</p>
        <p class="company-location"><i class="fa fa-map-marker" aria-hidden="true"></i> ${city}</p>
        ${verificationBadge(company.verificationStatus)}
        <span class="company-added-time"><i class="fa fa-clock-o" aria-hidden="true"></i> ${formatAddedTime(addedDays)}</span>
        <div class="company-stats">
          <div class="company-stat">
            <span class="company-stat-num">${company.projects ?? 0}</span>
            <span class="company-stat-label"><i class="fa fa-briefcase" aria-hidden="true"></i> Projects</span>
          </div>
          <div class="company-stat-divider"></div>
          <div class="company-stat">
            <span class="company-stat-num">${company.services ?? company.tasks ?? 0}</span>
            <span class="company-stat-label"><i class="fa fa-cogs" aria-hidden="true"></i> Services</span>
          </div>
        </div>
        <a href="${profileUrl}" class="btn btn-view-profile">View Profile</a>
      </div>
    </article>
  `;
}

function openCompanyProfile(card) {
  const url = card?.dataset?.profileUrl;
  if (url && url !== '#') {
    window.location.href = url;
  }
}

function getCardProfileData(card) {
  return {
    url: card?.dataset?.profileUrl || '',
    name: card?.dataset?.companyName || card?.dataset?.name || 'Business Profile',
    category: card?.dataset?.companyCategory || '',
    city: card?.dataset?.companyCity || '',
    country: card?.dataset?.companyCountry || '',
    logo: card?.dataset?.companyLogo || '',
    banner: card?.dataset?.companyBanner || '',
    projects: Number(card?.dataset?.companyProjects || 0),
    services: Number(card?.dataset?.companyServices || 0),
    verified: card?.dataset?.verified === 'yes'
  };
}

function shareCompanyProfileLink(card, btn) {
  const data = getCardProfileData(card);
  if (!data.url || data.url === '#') return;

  const markCopied = function() {
    if (!btn) return;
    const prev = btn.textContent;
    btn.textContent = 'Link Copied';
    setTimeout(function() { btn.textContent = prev; }, 1800);
  };

  const shareData = {
    title: data.name + ' — JustGoom',
    text: 'Check out ' + data.name + ' on JustGoom',
    url: data.url
  };

  if (navigator.share) {
    navigator.share(shareData).catch(function() {
      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(data.url).then(markCopied);
      }
    });
    return;
  }

  if (navigator.clipboard && navigator.clipboard.writeText) {
    navigator.clipboard.writeText(data.url).then(markCopied);
    return;
  }

  window.prompt('Copy profile link:', data.url);
}

function slugifyFilename(name) {
  return String(name || 'profile')
    .toLowerCase()
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/^-+|-+$/g, '')
    .slice(0, 60) || 'profile';
}

function getProfileInitials(name) {
  return String(name || 'JG')
    .split(/\s+/)
    .filter(Boolean)
    .slice(0, 2)
    .map(function(part) { return part.charAt(0).toUpperCase(); })
    .join('') || 'JG';
}

function loadPdfImage(src) {
  if (!src) return Promise.resolve(null);
  return new Promise(function(resolve) {
    var settled = false;
    var finish = function(img) {
      if (settled) return;
      settled = true;
      resolve(img);
    };
    var img = new Image();
    try {
      var abs = new URL(src, window.location.href);
      if (abs.origin !== window.location.origin) {
        img.crossOrigin = 'anonymous';
      }
    } catch (err) { /* ignore */ }
    img.onload = function() { finish(img); };
    img.onerror = function() { finish(null); };
    img.src = src;
    setTimeout(function() { finish(null); }, 2500);
  });
}

function roundRectPath(ctx, x, y, w, h, r) {
  var radius = Math.min(r, w / 2, h / 2);
  ctx.beginPath();
  ctx.moveTo(x + radius, y);
  ctx.arcTo(x + w, y, x + w, y + h, radius);
  ctx.arcTo(x + w, y + h, x, y + h, radius);
  ctx.arcTo(x, y + h, x, y, radius);
  ctx.arcTo(x, y, x + w, y, radius);
  ctx.closePath();
}

function wrapCanvasText(ctx, text, maxWidth) {
  var words = String(text || '').split(/\s+/).filter(Boolean);
  var lines = [];
  var current = '';
  words.forEach(function(word) {
    var next = current ? current + ' ' + word : word;
    if (ctx.measureText(next).width > maxWidth && current) {
      lines.push(current);
      current = word;
    } else {
      current = next;
    }
  });
  if (current) lines.push(current);
  return lines.length ? lines : [''];
}

function strToPdfBytes(str) {
  var bytes = new Uint8Array(str.length);
  for (var i = 0; i < str.length; i++) bytes[i] = str.charCodeAt(i) & 0xff;
  return bytes;
}

function concatPdfBytes(chunks) {
  var total = 0;
  chunks.forEach(function(chunk) { total += chunk.length; });
  var out = new Uint8Array(total);
  var offset = 0;
  chunks.forEach(function(chunk) {
    out.set(chunk, offset);
    offset += chunk.length;
  });
  return out;
}

function canvasToJpegBytes(canvas) {
  return new Promise(function(resolve, reject) {
    if (canvas.toBlob) {
      canvas.toBlob(function(blob) {
        if (!blob) {
          reject(new Error('JPEG export failed'));
          return;
        }
        blob.arrayBuffer().then(function(buf) {
          resolve(new Uint8Array(buf));
        }).catch(reject);
      }, 'image/jpeg', 0.95);
      return;
    }
    try {
      var dataUrl = canvas.toDataURL('image/jpeg', 0.95);
      var base64 = dataUrl.split(',')[1] || '';
      var binary = atob(base64);
      var bytes = new Uint8Array(binary.length);
      for (var i = 0; i < binary.length; i++) bytes[i] = binary.charCodeAt(i);
      resolve(bytes);
    } catch (err) {
      reject(err);
    }
  });
}

/** Build a real A4 PDF with the designed canvas JPEG (no CDN / jsPDF). */
function buildA4PdfFromJpeg(jpegBytes, pixelW, pixelH) {
  var pageW = 595.28;
  var pageH = 841.89;
  var content = 'q\n' + pageW + ' 0 0 ' + pageH + ' 0 0 cm\n/Im0 Do\nQ\n';

  var chunks = [];
  var offsets = [0];
  var pos = 0;

  function pushBytes(bytes) {
    chunks.push(bytes);
    pos += bytes.length;
  }

  function pushStr(str) {
    pushBytes(strToPdfBytes(str));
  }

  function startObj() {
    offsets.push(pos);
  }

  pushStr('%PDF-1.4\n');

  startObj();
  pushStr('1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n');

  startObj();
  pushStr('2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n');

  startObj();
  pushStr(
    '3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 ' + pageW + ' ' + pageH + ']'
    + ' /Contents 4 0 R /Resources << /XObject << /Im0 5 0 R >> >> >>\nendobj\n'
  );

  startObj();
  pushStr('4 0 obj\n<< /Length ' + content.length + ' >>\nstream\n' + content + '\nendstream\nendobj\n');

  startObj();
  pushStr(
    '5 0 obj\n<< /Type /XObject /Subtype /Image /Width ' + pixelW
    + ' /Height ' + pixelH
    + ' /ColorSpace /DeviceRGB /BitsPerComponent 8 /Filter /DCTDecode /Length '
    + jpegBytes.length + ' >>\nstream\n'
  );
  pushBytes(jpegBytes);
  pushStr('\nendstream\nendobj\n');

  var xrefStart = pos;
  pushStr('xref\n0 ' + offsets.length + '\n');
  pushStr('0000000000 65535 f \n');
  for (var i = 1; i < offsets.length; i++) {
    pushStr(String(offsets[i]).padStart(10, '0') + ' 00000 n \n');
  }
  pushStr('trailer\n<< /Size ' + offsets.length + ' /Root 1 0 R >>\n');
  pushStr('startxref\n' + xrefStart + '\n%%EOF');

  return new Blob([concatPdfBytes(chunks)], { type: 'application/pdf' });
}

function triggerBlobDownload(blob, filename) {
  var objectUrl = URL.createObjectURL(blob);
  var link = document.createElement('a');
  link.href = objectUrl;
  link.download = filename;
  link.rel = 'noopener';
  document.body.appendChild(link);
  link.click();
  link.remove();
  setTimeout(function() { URL.revokeObjectURL(objectUrl); }, 1500);
}

function drawProfilePdfCanvas(data, logoImg, bannerImg) {
  // A4 @ 96dpi — modern digital business profile sheet
  var W = 794;
  var H = 1123;
  var pad = 44;
  var contentW = W - pad * 2;
  var NAVY = '#0B2A5B';
  var BLUE = '#1A428A';
  var ACCENT = '#F7941D';
  var SOFT = '#F4F7FB';
  var MUTED = '#64748b';
  var INK = '#0f172a';

  var canvas = document.createElement('canvas');
  canvas.width = W * 2;
  canvas.height = H * 2;
  var ctx = canvas.getContext('2d');
  ctx.scale(2, 2);

  function drawLogo(x, y, size) {
    ctx.fillStyle = '#ffffff';
    roundRectPath(ctx, x - 5, y - 5, size + 10, size + 10, size * 0.28);
    ctx.fill();
    if (logoImg) {
      ctx.save();
      roundRectPath(ctx, x, y, size, size, size * 0.22);
      ctx.clip();
      ctx.drawImage(logoImg, x, y, size, size);
      ctx.restore();
    } else {
      var g = ctx.createLinearGradient(x, y, x + size, y + size);
      g.addColorStop(0, BLUE);
      g.addColorStop(1, NAVY);
      ctx.fillStyle = g;
      roundRectPath(ctx, x, y, size, size, size * 0.22);
      ctx.fill();
      ctx.fillStyle = '#ffffff';
      ctx.font = '800 ' + Math.round(size * 0.34) + 'px Arial,sans-serif';
      ctx.textAlign = 'center';
      ctx.textBaseline = 'middle';
      ctx.fillText(getProfileInitials(data.name), x + size / 2, y + size / 2);
      ctx.textAlign = 'left';
    }
  }

  // Page base
  ctx.fillStyle = SOFT;
  ctx.fillRect(0, 0, W, H);

  // ===== HERO =====
  var heroH = 340;
  if (bannerImg) {
    ctx.save();
    ctx.beginPath();
    ctx.rect(0, 0, W, heroH);
    ctx.clip();
    var bw = bannerImg.naturalWidth || bannerImg.width || 1;
    var bh = bannerImg.naturalHeight || bannerImg.height || 1;
    var scale = Math.max(W / bw, heroH / bh);
    var dw = bw * scale;
    var dh = bh * scale;
    ctx.drawImage(bannerImg, (W - dw) / 2, (heroH - dh) / 2, dw, dh);
    ctx.restore();
  } else {
    var heroGrad = ctx.createLinearGradient(0, 0, W, heroH);
    heroGrad.addColorStop(0, '#072047');
    heroGrad.addColorStop(0.55, BLUE);
    heroGrad.addColorStop(1, '#2457b0');
    ctx.fillStyle = heroGrad;
    ctx.fillRect(0, 0, W, heroH);
  }

  // Dark cinematic overlay
  var overlay = ctx.createLinearGradient(0, 0, 0, heroH);
  overlay.addColorStop(0, 'rgba(7,32,71,0.55)');
  overlay.addColorStop(0.45, 'rgba(7,32,71,0.35)');
  overlay.addColorStop(1, 'rgba(7,32,71,0.88)');
  ctx.fillStyle = overlay;
  ctx.fillRect(0, 0, W, heroH);

  // Soft accent glow
  ctx.fillStyle = 'rgba(247,148,29,0.16)';
  ctx.beginPath();
  ctx.arc(W - 40, 40, 140, 0, Math.PI * 2);
  ctx.fill();

  // Top brand row
  ctx.fillStyle = '#ffffff';
  ctx.font = '800 22px Arial,sans-serif';
  ctx.textBaseline = 'middle';
  ctx.fillText('Just', pad, 36);
  var justW = ctx.measureText('Just').width;
  ctx.fillStyle = ACCENT;
  ctx.fillText('Goom', pad + justW, 36);

  ctx.fillStyle = 'rgba(255,255,255,0.18)';
  roundRectPath(ctx, W - pad - 168, 22, 168, 28, 14);
  ctx.fill();
  ctx.fillStyle = '#ffffff';
  ctx.font = '700 11px Arial,sans-serif';
  ctx.textAlign = 'center';
  ctx.fillText(data.verified ? '★  VERIFIED PROFILE' : '●  BUSINESS PROFILE', W - pad - 84, 36);
  ctx.textAlign = 'left';

  // Hero company identity
  var logoSize = 88;
  var logoX = pad;
  var logoY = heroH - 148;
  drawLogo(logoX, logoY, logoSize);

  var titleX = logoX + logoSize + 20;
  var titleMax = W - titleX - pad;
  ctx.fillStyle = 'rgba(255,255,255,0.72)';
  ctx.font = '700 11px Arial,sans-serif';
  ctx.textBaseline = 'top';
  ctx.fillText((data.category || 'BUSINESS').toUpperCase(), titleX, logoY + 4);

  ctx.fillStyle = '#ffffff';
  ctx.font = '800 36px Arial,sans-serif';
  var nameLines = wrapCanvasText(ctx, data.name || 'Business Profile', titleMax).slice(0, 2);
  nameLines.forEach(function(line, i) {
    ctx.fillText(line, titleX, logoY + 24 + i * 40);
  });
  var nameH = nameLines.length * 40;

  ctx.fillStyle = ACCENT;
  ctx.fillRect(titleX, logoY + 24 + nameH + 4, 52, 4);

  var metaY = logoY + 24 + nameH + 18;
  ctx.font = '600 13px Arial,sans-serif';
  ctx.fillStyle = 'rgba(255,255,255,0.9)';
  var locationLabel = [data.city, data.country].filter(Boolean).join(' · ') || 'India';
  ctx.fillText(locationLabel, titleX, metaY);

  // Orange accent strip under hero
  ctx.fillStyle = ACCENT;
  ctx.fillRect(0, heroH, W, 6);

  // ===== BODY CARD =====
  var cardY = heroH + 22;
  var cardH = H - cardY - 64;
  ctx.fillStyle = '#ffffff';
  roundRectPath(ctx, pad, cardY, contentW, cardH, 20);
  ctx.fill();
  ctx.strokeStyle = '#e8eef6';
  ctx.lineWidth = 1.5;
  ctx.stroke();

  var innerX = pad + 24;
  var innerW = contentW - 48;
  var y = cardY + 22;

  ctx.fillStyle = ACCENT;
  ctx.font = '800 10px Arial,sans-serif';
  ctx.textBaseline = 'top';
  ctx.fillText('SNAPSHOT', innerX, y);
  ctx.fillStyle = NAVY;
  ctx.font = '800 20px Arial,sans-serif';
  ctx.fillText('Business at a glance', innerX, y + 16);
  y += 48;

  var metrics = [
    { num: String(data.projects || 0), label: 'Projects', tone: BLUE },
    { num: String(data.services || 0), label: 'Services', tone: ACCENT },
    { num: data.verified ? 'Verified' : 'Public', label: 'Status', tone: data.verified ? '#059669' : BLUE },
    { num: data.city || '—', label: 'Location', tone: NAVY }
  ];
  var gap = 10;
  var tileW = (innerW - gap * 3) / 4;
  var tileH = 78;
  metrics.forEach(function(m, i) {
    var tx = innerX + i * (tileW + gap);
    roundRectPath(ctx, tx, y, tileW, tileH, 14);
    ctx.fillStyle = SOFT;
    ctx.fill();
    ctx.fillStyle = m.tone;
    ctx.fillRect(tx, y, 4, tileH);
    ctx.fillStyle = NAVY;
    ctx.font = '800 22px Arial,sans-serif';
    ctx.textBaseline = 'top';
    ctx.fillText(wrapCanvasText(ctx, m.num, tileW - 20)[0], tx + 14, y + 16);
    ctx.fillStyle = MUTED;
    ctx.font = '700 10px Arial,sans-serif';
    ctx.fillText(m.label.toUpperCase(), tx + 14, y + 48);
  });
  y += tileH + 26;

  ctx.fillStyle = ACCENT;
  ctx.font = '800 10px Arial,sans-serif';
  ctx.fillText('DETAILS', innerX, y);
  ctx.fillStyle = NAVY;
  ctx.font = '800 20px Arial,sans-serif';
  ctx.fillText('Company information', innerX, y + 16);
  y += 48;

  var details = [
    { label: 'Company', value: data.name || '—' },
    { label: 'Category', value: data.category || '—' },
    { label: 'City', value: data.city || '—' },
    { label: 'Country', value: data.country || '—' },
    { label: 'Listing', value: data.verified ? 'Verified on JustGoom' : 'Public JustGoom listing' },
    { label: 'Network', value: 'JustGoom Business Directory' }
  ];
  var colW = (innerW - 14) / 2;
  var rowH = 54;
  details.forEach(function(item, i) {
    var col = i % 2;
    var row = Math.floor(i / 2);
    var dx = innerX + col * (colW + 14);
    var dy = y + row * (rowH + 10);
    roundRectPath(ctx, dx, dy, colW, rowH, 12);
    ctx.fillStyle = SOFT;
    ctx.fill();
    ctx.fillStyle = MUTED;
    ctx.font = '700 9px Arial,sans-serif';
    ctx.textBaseline = 'top';
    ctx.fillText(item.label.toUpperCase(), dx + 14, dy + 10);
    ctx.fillStyle = INK;
    ctx.font = '700 14px Arial,sans-serif';
    ctx.fillText(wrapCanvasText(ctx, item.value, colW - 28)[0], dx + 14, dy + 28);
  });
  y += 3 * (rowH + 10) + 4;

  var ctaH = 100;
  roundRectPath(ctx, innerX, y, innerW, ctaH, 16);
  var ctaGrad = ctx.createLinearGradient(innerX, y, innerX + innerW, y + ctaH);
  ctaGrad.addColorStop(0, NAVY);
  ctaGrad.addColorStop(1, BLUE);
  ctx.fillStyle = ctaGrad;
  ctx.fill();

  ctx.fillStyle = ACCENT;
  ctx.beginPath();
  ctx.moveTo(innerX + innerW - 64, y);
  ctx.lineTo(innerX + innerW, y);
  ctx.lineTo(innerX + innerW, y + 64);
  ctx.closePath();
  ctx.fill();

  ctx.fillStyle = 'rgba(255,255,255,0.7)';
  ctx.font = '800 10px Arial,sans-serif';
  ctx.textBaseline = 'top';
  ctx.fillText('OPEN FULL PROFILE', innerX + 22, y + 18);
  ctx.fillStyle = '#ffffff';
  ctx.font = '700 15px Arial,sans-serif';
  wrapCanvasText(ctx, data.url || '', innerW - 90).slice(0, 2).forEach(function(line, i) {
    ctx.fillText(line, innerX + 22, y + 38 + i * 20);
  });
  ctx.fillStyle = 'rgba(255,255,255,0.65)';
  ctx.font = '11px Arial,sans-serif';
  ctx.fillText('Share with clients, partners & teams', innerX + 22, y + 78);
  // ===== FOOTER =====
  var footerY = H - 52;
  ctx.fillStyle = NAVY;
  ctx.fillRect(0, footerY, W, 52);
  ctx.fillStyle = ACCENT;
  ctx.fillRect(0, footerY, W, 3);
  ctx.fillStyle = '#ffffff';
  ctx.font = '700 13px Arial,sans-serif';
  ctx.textBaseline = 'middle';
  ctx.fillText('Just Goom LLP', pad, footerY + 26);
  ctx.fillStyle = 'rgba(255,255,255,0.7)';
  ctx.font = '12px Arial,sans-serif';
  ctx.textAlign = 'right';
  ctx.fillText('Business directory profile · A4', W - pad, footerY + 26);
  ctx.textAlign = 'left';

  return canvas;
}

function downloadCompanyProfilePdf(card, btn) {
  const data = getCardProfileData(card);
  if (!data.url || data.url === '#') return;

  const prev = btn ? btn.textContent : '';
  const filename = slugifyFilename(data.name) + '-profile.pdf';
  if (btn) {
    btn.disabled = true;
    btn.textContent = 'Downloading…';
  }

  Promise.all([
    loadPdfImage(data.logo),
    loadPdfImage(data.banner)
  ])
    .then(function(images) {
      var logoImg = images[0];
      var bannerImg = images[1];
      var canvas;
      try {
        canvas = drawProfilePdfCanvas(data, logoImg, bannerImg);
        // If canvas is tainted, toBlob/toDataURL will fail — rebuild without images
        canvas.toDataURL('image/jpeg', 0.5);
      } catch (err) {
        canvas = drawProfilePdfCanvas(data, null, null);
      }

      return canvasToJpegBytes(canvas).then(function(jpegBytes) {
        var pdfBlob = buildA4PdfFromJpeg(jpegBytes, canvas.width, canvas.height);
        triggerBlobDownload(pdfBlob, filename);
      });
    })
    .catch(function(err) {
      console.warn('Profile PDF failed', err);
      alert('Unable to download designed PDF. Please try again.');
    })
    .finally(function() {
      if (btn) {
        btn.disabled = false;
        btn.textContent = prev || 'Download Profile PDF';
      }
    });
}

function bindCardInteractions(grid) {
  grid.querySelectorAll('.company-card-clickable').forEach(card => {
    card.addEventListener('click', (e) => {
      if (e.target.closest('.company-menu-wrap, .company-menu-dropdown, .btn-view-profile, .company-star, .company-star-tag, .company-verified-badge')) {
        return;
      }
      openCompanyProfile(card);
    });

    card.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        openCompanyProfile(card);
      }
    });
  });

  grid.querySelectorAll('.company-menu-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      const dropdown = btn.nextElementSibling;
      grid.querySelectorAll('.company-menu-dropdown.open').forEach(d => {
        if (d !== dropdown) d.classList.remove('open');
      });
      dropdown?.classList.toggle('open');
    });
  });

  grid.querySelectorAll('.js-share-profile-link').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      const card = btn.closest('.company-card');
      shareCompanyProfileLink(card, btn);
      btn.closest('.company-menu-dropdown')?.classList.remove('open');
    });
  });

  grid.querySelectorAll('.js-download-profile-pdf').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      const card = btn.closest('.company-card');
      downloadCompanyProfilePdf(card, btn);
      btn.closest('.company-menu-dropdown')?.classList.remove('open');
    });
  });

  document.addEventListener('click', () => {
    grid.querySelectorAll('.company-menu-dropdown.open').forEach(d => d.classList.remove('open'));
  });
}

function bindViewToggle(grid, viewGridBtn, viewListBtn) {
  viewGridBtn?.addEventListener('click', () => {
    grid.classList.remove('list-view');
    viewGridBtn.classList.add('active');
    viewListBtn?.classList.remove('active');
  });

  viewListBtn?.addEventListener('click', () => {
    grid.classList.add('list-view');
    viewListBtn.classList.add('active');
    viewGridBtn?.classList.remove('active');
  });
}

function initHomeCompanyProfiles() {
  const grid = document.getElementById('homeCompanyGrid');
  const searchInput = document.getElementById('homeCompanySearch');
  const countEl = document.getElementById('homeCompanyCount');
  const viewGridBtn = document.getElementById('homeViewGrid');
  const viewListBtn = document.getElementById('homeViewList');

  if (!grid) return;

  const subtitleEl = document.getElementById('homeCompanySubtitle');
  if (subtitleEl) {
    subtitleEl.textContent = `Browse verified business profiles — showing ${HOME_PROFILES.length} featured listings`;
  }

  grid.innerHTML = HOME_PROFILES.map(renderCompanyCard).join('');

  function updateCount() {
    const visible = grid.querySelectorAll('.company-card:not([style*="display: none"])').length;
    const total = COMPANY_PROFILES.length;
    if (countEl) countEl.textContent = `Showing ${visible} of ${total} company profiles`;
  }

  searchInput?.addEventListener('input', () => {
    const q = searchInput.value.trim().toLowerCase();
    grid.querySelectorAll('.company-card').forEach(card => {
      const name = card.dataset.name || '';
      const cat = card.dataset.category || '';
      const loc = card.dataset.locality || '';
      const match = !q || name.includes(q) || cat.includes(q) || loc.includes(q);
      card.style.display = match ? '' : 'none';
    });
    updateCount();
  });

  bindViewToggle(grid, viewGridBtn, viewListBtn);
  bindCardInteractions(grid);
  updateCount();

  document.getElementById('homeFiltersBtn')?.addEventListener('click', function() {
    var q = searchInput?.value.trim() || '';
    var base = (window.FRONT_ROUTES && window.FRONT_ROUTES.allProfiles) || '/all-profiles';
    var params = new URLSearchParams();
    if (q) params.set('q', q);
    window.location.href = params.toString() ? base + '?' + params.toString() : base;
  });
}

function matchesTimeRange(days, range) {
  if (range === 'all') return true;
  if (range === 'today') return days === 0;
  if (range === 'week') return days <= 7;
  if (range === 'month') return days <= 30;
  if (range === 'year') return days <= 365;
  return true;
}

const CATEGORY_MENU_KEYWORDS = {
  restaurants: ['food', 'dining', 'restaurant'],
  hotels: ['travel', 'tours', 'hotel'],
  'beauty spa': ['fashion', 'style', 'beauty'],
  'home decor': ['interior', 'decor', 'home'],
  wedding: ['event', 'wedding'],
  education: ['education', 'academy'],
  rent: ['real estate', 'automobile', 'rent'],
  hospitals: ['health', 'pharmacy', 'clinic', 'medical'],
  contractors: ['construction', 'contractor'],
  pet: ['pet'],
  pg: ['home services'],
  estate: ['real estate'],
  dentists: ['health', 'pharmacy', 'medical'],
  gym: ['fitness', 'gym'],
  loans: ['business', 'consult'],
  event: ['event'],
  driving: ['automobile', 'travel', 'motor'],
  packers: ['logistics', 'moving'],
  courier: ['logistics', 'courier'],
  jewellery: ['jewelry', 'metal', 'crafts'],
  supermarkets: ['shopping', 'mart', 'retail'],
  'coffee shops': ['food', 'dining', 'cafe'],
  salons: ['beauty', 'fashion', 'spa'],
  tailors: ['fashion', 'retail'],
  laundry: ['home services', 'clean'],
  'ac repair': ['electronics', 'repair', 'tech'],
  plumbers: ['home construction', 'home services', 'contractor'],
  electricians: ['electronics', 'repair', 'construction'],
  painters: ['home construction', 'interior', 'contractor'],
  astrologers: ['business', 'consult'],
  insurance: ['business', 'legal', 'consult'],
  'chartered accountants': ['business', 'legal', 'consult'],
  'book stores': ['education', 'shopping'],
  'mobile shops': ['electronics', 'automobile', 'shopping'],
  'computer repair': ['electronics', 'software', 'tech'],
  furniture: ['interior', 'home', 'decor'],
  'hardware stores': ['home construction', 'shopping'],
  catering: ['food', 'dining', 'event']
};

function resolveCategoryFilter(slug) {
  if (!slug || slug === 'all') return null;

  slug = slug.toLowerCase();

  if (typeof CATEGORY_SECTORS !== 'undefined') {
    var sector = CATEGORY_SECTORS.find(function(s) { return s.slug === slug; });
    if (sector) {
      return { type: 'sector', sectorSlug: slug, label: sector.name };
    }

    for (var i = 0; i < CATEGORY_SECTORS.length; i++) {
      var s = CATEGORY_SECTORS[i];
      for (var j = 0; j < s.subs.length; j++) {
        if (s.subs[j].slug === slug) {
          return {
            type: 'sub',
            sectorSlug: s.slug,
            subSlug: slug,
            label: s.subs[j].name
          };
        }
      }
    }
  }

  return { type: 'unknown', sectorSlug: slug, label: slug.replace(/-/g, ' ') };
}

function getCategoryFilterLabel(slug) {
  var resolved = resolveCategoryFilter(slug);
  if (!resolved) return '';
  if (resolved.label) {
    return resolved.label.replace(/\b\w/g, function(c) { return c.toUpperCase(); });
  }
  return slug.replace(/-/g, ' ').replace(/\b\w/g, function(c) { return c.toUpperCase(); });
}

function populateSubCategoryOptions(sectorSlug, selectedSub) {
  var group = document.getElementById('subCategoryFilterGroup');
  var select = document.getElementById('profileSubCategorySelect');
  if (!group || !select) return;

  if (!sectorSlug || sectorSlug === 'all') {
    group.hidden = true;
    select.innerHTML = '<option value="all">All Subcategories</option>';
    select.value = 'all';
    return;
  }

  if (typeof CATEGORY_SECTORS === 'undefined') {
    group.hidden = true;
    return;
  }

  var sector = CATEGORY_SECTORS.find(function(s) { return s.slug === sectorSlug; });
  if (!sector || !sector.subs.length) {
    group.hidden = true;
    select.innerHTML = '<option value="all">All Subcategories</option>';
    select.value = 'all';
    return;
  }

  group.hidden = false;
  select.innerHTML = '<option value="all">All Subcategories</option>' +
    sector.subs.map(function(sub) {
      return '<option value="' + sub.slug + '">' + sub.name + '</option>';
    }).join('');
  select.value = (selectedSub && selectedSub !== 'all') ? selectedSub : 'all';
}

function syncProfilesFilterUrl(sectorSlug, subSlug) {
  var params = new URLSearchParams(window.location.search);
  params.delete('category');
  params.delete('subcategory');

  if (subSlug && subSlug !== 'all') {
    params.set('category', subSlug);
  } else if (sectorSlug && sectorSlug !== 'all') {
    params.set('category', sectorSlug);
  }

  var query = params.toString();
  window.history.replaceState({}, '', query ? window.location.pathname + '?' + query : window.location.pathname);
}

function getProfilesFilterSubtitle(sectorSlug, subSlug) {
  if (subSlug && subSlug !== 'all') {
    return 'Showing profiles for ' + getCategoryFilterLabel(subSlug);
  }
  if (sectorSlug && sectorSlug !== 'all') {
    return 'Showing profiles for ' + getCategoryFilterLabel(sectorSlug);
  }
  return 'Browse verified business profiles across all categories';
}

function cardMatchesSectorAndSub(card, sectorSlug, subSlug) {
  var cardCatSlug = (card.dataset.categorySlug || '').toLowerCase();
  var cardSubSlug = (card.dataset.subcategorySlug || '').toLowerCase();

  if (subSlug && subSlug !== 'all') {
    var selected = subSlug.toLowerCase();
    return cardSubSlug.split(',').map(function (s) { return s.trim(); }).indexOf(selected) !== -1;
  }
  if (sectorSlug && sectorSlug !== 'all') {
    return cardCatSlug === sectorSlug.toLowerCase();
  }
  return true;
}

function setActiveCategoryChip(sidebar, slug) {
  var categoryGroup = sidebar?.querySelector('[data-filter-group="category"]');
  if (!categoryGroup) return;

  var chipSlug = 'all';
  if (slug && slug !== 'all') {
    var resolved = resolveCategoryFilter(slug);
    chipSlug = resolved ? resolved.sectorSlug : slug;
  }

  var matched = false;
  categoryGroup.querySelectorAll('.chip').forEach(function(chip) {
    var isActive = chip.dataset.value === chipSlug;
    if (isActive) matched = true;
    chip.classList.toggle('active', isActive);
  });

  if (!matched) {
    categoryGroup.querySelectorAll('.chip').forEach(function(chip) {
      chip.classList.toggle('active', chip.dataset.value === 'all');
    });
  }
}

function initCategoryCompanyProfiles() {
  const grid = document.getElementById('categoryCompanyGrid');
  const searchInput = document.getElementById('categoryCompanySearch');
  const countEl = document.getElementById('categoryCompanyCount');
  const viewGridBtn = document.getElementById('categoryViewGrid');
  const viewListBtn = document.getElementById('categoryViewList');
  const filtersPanel = document.getElementById('categoryFiltersPanel');
  const filtersToggle = document.getElementById('categoryFiltersToggle');
  const sidebar = document.getElementById('profileFilters');
  const timeSelect = document.getElementById('categoryTimeFilterSelect');
  const subCategorySelect = document.getElementById('profileSubCategorySelect');
  const subtitleEl = document.getElementById('profilesSubtitle');
  const emptyEl = document.getElementById('profilesEmpty');
  const paginationEl = document.getElementById('profilesPagination');
  const pageInfoEl = document.getElementById('profilesPageInfo');

  if (!grid) return;

  grid.innerHTML = ALL_PROFILES_LIST.map(renderCompanyCard).join('');
  bindCardInteractions(grid);
  bindViewToggle(grid, viewGridBtn, viewListBtn);

  if (sidebar) initFilterChipsLocal(sidebar);

  let timeRange = 'all';
  let currentPage = 1;
  const urlParams = new URLSearchParams(window.location.search);
  const urlCategory = urlParams.get('category');
  const urlSubCategory = urlParams.get('subcategory');
  const urlSearch = (urlParams.get('q') || '').trim();
  const urlCity = (urlParams.get('city') || '').trim();
  const urlCountry = (urlParams.get('country') || '').trim();
  let activeSectorFilter = 'all';
  let activeSubCategoryFilter = 'all';
  let urlCityFilter = '';
  let urlCountryFilter = urlCountry ? urlCountry.toLowerCase() : '';

  if (urlCategory) {
    var resolvedUrlCategory = resolveCategoryFilter(urlCategory);
    if (resolvedUrlCategory && resolvedUrlCategory.type === 'sub') {
      activeSectorFilter = resolvedUrlCategory.sectorSlug;
      activeSubCategoryFilter = resolvedUrlCategory.subSlug;
    } else {
      activeSectorFilter = urlCategory;
    }
  }
  if (urlSubCategory) {
    activeSubCategoryFilter = urlSubCategory;
    if (activeSectorFilter === 'all') {
      var resolvedSub = resolveCategoryFilter(urlSubCategory);
      if (resolvedSub && resolvedSub.sectorSlug) {
        activeSectorFilter = resolvedSub.sectorSlug;
      }
    }
  }

  if (urlSearch && searchInput) {
    searchInput.value = urlSearch;
  }

  if (urlCity && sidebar) {
    const localitySelect = sidebar.querySelector('[data-filter="locality"]');
    const cityLower = urlCity.toLowerCase();
    const matchedOption = localitySelect
      ? Array.from(localitySelect.options).find(function(option) {
          return option.value.toLowerCase() === cityLower
            || option.textContent.trim().toLowerCase() === cityLower;
        })
      : null;

    if (matchedOption) {
      localitySelect.value = matchedOption.value;
    } else {
      urlCityFilter = cityLower;
    }
  }

  if (sidebar && activeSectorFilter !== 'all') {
    setActiveCategoryChip(sidebar, activeSectorFilter);
    populateSubCategoryOptions(activeSectorFilter, activeSubCategoryFilter);
  }

  if (subtitleEl) {
    if (urlCategory || urlSubCategory || activeSectorFilter !== 'all') {
      subtitleEl.textContent = getProfilesFilterSubtitle(activeSectorFilter, activeSubCategoryFilter);
    } else if (urlSearch || urlCity || urlCountry) {
      const parts = [];
      if (urlSearch) parts.push('"' + urlSearch + '"');
      if (urlCountry) parts.push('in ' + urlCountry);
      else if (urlCity) parts.push('in ' + urlCity);
      subtitleEl.textContent = 'Search results for ' + parts.join(' ');
    }
  }

  function cardMatchesFilters(card) {
    const q = searchInput?.value.trim().toLowerCase() || '';
    const locality = sidebar?.querySelector('[data-filter="locality"]')?.value.toLowerCase() || '';
    const categoryGroup = sidebar?.querySelector('[data-filter-group="category"]');
    const verifiedGroup = sidebar?.querySelector('[data-filter-group="verified"]');

    let sectorSlug = activeSectorFilter;
    let subSlug = activeSubCategoryFilter;

    if (categoryGroup) {
      const chips = getActiveChips(categoryGroup).filter(function(v) { return v !== 'all'; });
      if (chips.length) {
        sectorSlug = chips[0];
      }
    }

    if (subCategorySelect) {
      var subGroup = document.getElementById('subCategoryFilterGroup');
      if (subGroup && !subGroup.hidden) {
        const selectedSub = (subCategorySelect.value || 'all').toLowerCase();
        subSlug = selectedSub;
      }
    }

    const verified = verifiedGroup ? getActiveChips(verifiedGroup).filter(function(v) { return v !== 'all'; }) : [];
    const name = card.dataset.name || '';
    const cat = card.dataset.category || '';
    const sub = card.dataset.subcategory || '';
    const loc = card.dataset.locality || '';
    const country = card.dataset.country || '';
    const ver = card.dataset.verified || '';
    const days = parseInt(card.dataset.addedDays || '0', 10);

    if (q && !name.includes(q) && !cat.includes(q) && !sub.includes(q) && !loc.includes(q) && !country.includes(q)) return false;
    if (!cardMatchesSectorAndSub(card, sectorSlug, subSlug)) return false;
    if (locality && locality !== 'all cities' && loc !== locality && !loc.includes(locality)) return false;
    if (urlCityFilter && !loc.includes(urlCityFilter)) return false;
    if (urlCountryFilter && country !== urlCountryFilter && !country.includes(urlCountryFilter)) return false;
    if (verified.indexOf('verified only') !== -1 && ver !== 'yes') return false;
    if (!matchesTimeRange(days, timeRange)) return false;
    return true;
  }

  function getMatchedCards() {
    return Array.from(grid.querySelectorAll('.company-card')).filter(cardMatchesFilters);
  }

  function renderPagination(totalPages) {
    if (!paginationEl) return;
    if (totalPages <= 1) {
      paginationEl.innerHTML = '';
      paginationEl.hidden = true;
      return;
    }
    paginationEl.hidden = false;

    var html = '';
    html += '<button type="button" class="pagination-btn" data-page="prev" aria-label="Previous page"' +
      (currentPage === 1 ? ' disabled' : '') + '>‹</button>';

    for (var p = 1; p <= totalPages; p++) {
      if (totalPages > 7 && p > 2 && p < totalPages - 1 && Math.abs(p - currentPage) > 1) {
        if (p === 3 || p === totalPages - 2) html += '<span class="pagination-ellipsis">…</span>';
        continue;
      }
      html += '<button type="button" class="pagination-btn' + (p === currentPage ? ' active' : '') +
        '" data-page="' + p + '">' + p + '</button>';
    }

    html += '<button type="button" class="pagination-btn" data-page="next" aria-label="Next page"' +
      (currentPage === totalPages ? ' disabled' : '') + '>›</button>';

    paginationEl.innerHTML = html;

    paginationEl.querySelectorAll('.pagination-btn').forEach(function(btn) {
      btn.addEventListener('click', function() {
        if (btn.disabled) return;
        var page = btn.dataset.page;
        if (page === 'prev') currentPage--;
        else if (page === 'next') currentPage++;
        else currentPage = parseInt(page, 10);
        applyFilters(false);
        grid.scrollIntoView({ behavior: 'smooth', block: 'start' });
      });
    });
  }

  function applyFilters(resetPage) {
    if (resetPage !== false) currentPage = 1;

    var matched = getMatchedCards();
    var totalPages = Math.max(1, Math.ceil(matched.length / PROFILES_PER_PAGE));
    if (currentPage > totalPages) currentPage = totalPages;

    var start = (currentPage - 1) * PROFILES_PER_PAGE;
    var end = start + PROFILES_PER_PAGE;

    grid.querySelectorAll('.company-card').forEach(function(card) {
      card.style.display = 'none';
    });

    matched.slice(start, end).forEach(function(card) {
      card.style.display = '';
    });

    if (countEl) {
      if (matched.length === 0) {
        countEl.textContent = 'No profiles found';
      } else {
        countEl.textContent = 'Showing ' + (start + 1) + '–' + Math.min(end, matched.length) +
          ' of ' + matched.length + ' profiles';
      }
    }

    if (pageInfoEl) {
      pageInfoEl.textContent = matched.length > 0
        ? 'Page ' + currentPage + ' of ' + totalPages + ' · ' + PROFILES_PER_PAGE + ' per page · 3 per row'
        : '';
    }

    if (emptyEl) {
      emptyEl.hidden = matched.length > 0;
      grid.hidden = matched.length === 0;
    }

    renderPagination(totalPages);
  }

  searchInput?.addEventListener('input', applyFilters);

  filtersToggle?.addEventListener('click', () => {
    const sidebarEl = document.querySelector('.profiles-sidebar');
    const overlay = document.getElementById('profilesFilterOverlay');
    sidebarEl?.classList.toggle('mobile-open');
    if (overlay) overlay.hidden = !sidebarEl?.classList.contains('mobile-open');
    if (filtersPanel) {
      const open = filtersPanel.hasAttribute('hidden');
      if (open) filtersPanel.removeAttribute('hidden');
      else filtersPanel.setAttribute('hidden', '');
    }
  });

  document.getElementById('profilesFilterOverlay')?.addEventListener('click', () => {
    document.querySelector('.profiles-sidebar')?.classList.remove('mobile-open');
    document.getElementById('profilesFilterOverlay').hidden = true;
  });

  timeSelect?.addEventListener('change', function() {
    timeRange = timeSelect.value || 'all';
    applyFilters();
  });

  sidebar?.querySelector('.filter-reset')?.addEventListener('click', resetProfileFilters);
  document.querySelector('.profiles-sidebar-reset')?.addEventListener('click', resetProfileFilters);

  function resetProfileFilters() {
    const filterRoot = document.getElementById('profileFilters');
    if (!filterRoot) return;
    filterRoot.querySelectorAll('.filter-select').forEach(s => { s.selectedIndex = 0; });
    filterRoot.querySelectorAll('.filter-chips').forEach(group => {
      group.querySelectorAll('.chip').forEach((c, i) => c.classList.toggle('active', i === 0));
    });
    if (timeSelect) timeSelect.value = 'all';
    timeRange = 'all';
    activeSectorFilter = 'all';
    activeSubCategoryFilter = 'all';
    populateSubCategoryOptions('all', 'all');
    urlCityFilter = '';
    if (searchInput) searchInput.value = '';
    if (subtitleEl) subtitleEl.textContent = 'Browse verified business profiles across all categories';
    if (window.location.search) {
      window.history.replaceState({}, '', window.location.pathname);
    }
    applyFilters();
  }

  sidebar?.querySelector('.btn-apply-filters')?.addEventListener('click', applyFilters);
  sidebar?.querySelector('[data-filter="locality"]')?.addEventListener('change', function() {
    urlCityFilter = '';
    applyFilters();
  });
  sidebar?.querySelectorAll('[data-filter-group="category"] .chip').forEach(chip => {
    chip.addEventListener('click', function() {
      activeSectorFilter = chip.dataset.value || 'all';
      activeSubCategoryFilter = 'all';
      populateSubCategoryOptions(activeSectorFilter, 'all');
      syncProfilesFilterUrl(activeSectorFilter, activeSubCategoryFilter);
      if (subtitleEl) {
        subtitleEl.textContent = getProfilesFilterSubtitle(activeSectorFilter, activeSubCategoryFilter);
      }
      applyFilters();
    });
  });

  subCategorySelect?.addEventListener('change', function() {
    activeSubCategoryFilter = subCategorySelect.value || 'all';
    if (activeSubCategoryFilter !== 'all' && activeSectorFilter === 'all' && typeof CATEGORY_SECTORS !== 'undefined') {
      var resolved = resolveCategoryFilter(activeSubCategoryFilter);
      if (resolved && resolved.sectorSlug) {
        activeSectorFilter = resolved.sectorSlug;
        setActiveCategoryChip(sidebar, activeSectorFilter);
      }
    }
    syncProfilesFilterUrl(activeSectorFilter, activeSubCategoryFilter);
    if (subtitleEl) {
      subtitleEl.textContent = getProfilesFilterSubtitle(activeSectorFilter, activeSubCategoryFilter);
    }
    applyFilters();
  });

  sidebar?.querySelectorAll('[data-filter-group="verified"] .chip').forEach(chip => {
    chip.addEventListener('click', function() {
      applyFilters();
    });
  });

  applyFilters();
}

document.addEventListener('DOMContentLoaded', () => {
  initHomeCompanyProfiles();
  initCategoryCompanyProfiles();
});
