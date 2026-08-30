@extends('front.layouts.app')

@section('title', 'About Us — Just Goom LLP')
@section('meta_description', 'Learn about Just Goom LLP — India\'s B2B business discovery platform connecting buyers with verified suppliers across India.')
@section('body_attrs', 'class="about-page" data-page="about"')

@section('content')
<!-- Page Hero -->
  <section class="page-hero about-hero">
    <div class="container">
      <nav class="breadcrumb">
        <a href="{{ route('front.home') }}">Home</a>
        <span class="breadcrumb-sep">›</span>
        <span>About Us</span>
      </nav>
      <h1>About Just Goom LLP</h1>
      <p>Global B2B business discovery platform — connecting buyers with verified suppliers, service providers, and local businesses.</p>
    </div>
    <div class="pixel-deco orange"><span></span><span></span><span></span><span></span></div>
  </section>

  <!-- Stats -->
  <div class="stats-bar about-stats">
    <div class="container stats-grid">
      <div class="stat-item">
        <div class="stat-num">30+</div>
        <div class="stat-label">Verified Businesses</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">24+</div>
        <div class="stat-label">Business Sectors</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">50+</div>
        <div class="stat-label">Cities Covered</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">10K+</div>
        <div class="stat-label">Monthly Inquiries</div>
      </div>
    </div>
  </div>

  <!-- About Intro -->
  <section class="section about-intro-section">
    <div class="container about-intro">
      <div class="about-intro-text">
        <span class="about-tag">Who We Are</span>
        <h2>Partnership & <span>Innovation</span> at Our Core</h2>
        <p>
          <strong>Just Goom LLP</strong> is a B2B business discovery platform built on trust, partnership, and technology. Our logo — featuring interlocking hands and circuit patterns — represents our commitment to connecting buyers with verified businesses through innovative digital solutions.
        </p>
        <p>
          From agriculture and construction to health, education, and business services — we help buyers discover trusted suppliers across 24+ sectors and empower MSMEs to list their profiles and grow online.
        </p>
        <p>
          Based in Ahmedabad, Gujarat, we serve communities across India with a mission to simplify B2B discovery and help businesses reach the right audience.
        </p>
        <div class="about-intro-actions">
          <a href="{{ route('front.all-profiles') }}" class="btn btn-primary">Browse Profiles →</a>
          <a href="{{ route('front.register') }}" class="btn btn-outline-primary">List Your Business</a>
        </div>
      </div>
      <div class="about-logo-box">
        <img src="{{ asset('front/assets/images/justgoom-logo.png') }}" alt="Just Goom LLP">
        <h3>Just Goom LLP</h3>
        <p>Partnership · Technology · Trust</p>
        <ul class="about-logo-tags">
          <li>B2B Marketplace</li>
          <li>Verified Listings</li>
          <li>Global Reach</li>
        </ul>
      </div>
    </div>
  </section>

  <!-- Mission & Vision -->
  <section class="section section-alt about-mission-section">
    <div class="container">
      <div class="about-mission-grid">
        <div class="about-mission-card">
          <div class="about-mission-icon">🎯</div>
          <h3>Our Mission</h3>
          <p>To make B2B business discovery simple, transparent, and accessible — helping buyers find verified suppliers and enabling businesses of all sizes to grow their reach online.</p>
        </div>
        <div class="about-mission-card">
          <div class="about-mission-icon">👁️</div>
          <h3>Our Vision</h3>
          <p>To become India's most trusted B2B platform where every business — from local MSMEs to established brands — can connect, list, and thrive in the digital marketplace.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- What We Offer -->
  <section class="section about-offer-section">
    <div class="container">
      <div class="section-header text-center about-section-head">
        <div>
          <h2 class="section-title">What We Offer</h2>
          <p class="section-subtitle">Everything you need to discover and grow in the B2B marketplace</p>
        </div>
      </div>
      <div class="about-offer-grid">
        <div class="about-offer-card">
          <div class="about-offer-icon">🏢</div>
          <h3>B2B Marketplace</h3>
          <p>Browse verified business profiles across 24+ sectors — from agriculture to electronics.</p>
        </div>
        <div class="about-offer-card">
          <div class="about-offer-icon">✓</div>
          <h3>Verified Profiles</h3>
          <p>Trust badges and verification status help buyers connect with reliable businesses.</p>
        </div>
        <div class="about-offer-card">
          <div class="about-offer-icon">🔍</div>
          <h3>Smart Search</h3>
          <p>Find businesses by category, city, name, or service with powerful filters.</p>
        </div>
        <div class="about-offer-card">
          <div class="about-offer-icon">📝</div>
          <h3>Free Listing</h3>
          <p>Register your business profile for free and start receiving buyer inquiries.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Profile Journey -->
  <section class="profile-flow-section about-journey">
    <div class="container">
      <div class="profile-flow-header">
        <div>
          <h2 class="section-title">How It Works for Businesses</h2>
          <p class="section-subtitle">Register → Get Listed → Receive Inquiries</p>
        </div>
      </div>
      <div class="profile-flow-track active">
        <div class="profile-flow-steps">
          <div class="profile-flow-step">
            <div class="profile-flow-step-badge">Step 1</div>
            <div class="profile-flow-step-icon">📝</div>
            <h3>Register Profile</h3>
            <p>Create your free business account with company details and services.</p>
          </div>
          <div class="profile-flow-connector" aria-hidden="true"><span class="profile-flow-arrow">→</span></div>
          <div class="profile-flow-step">
            <div class="profile-flow-step-badge">Step 2</div>
            <div class="profile-flow-step-icon">📋</div>
            <h3>Get Listed</h3>
            <p>Your profile appears in category search and featured listings.</p>
          </div>
          <div class="profile-flow-connector" aria-hidden="true"><span class="profile-flow-arrow">→</span></div>
          <div class="profile-flow-step profile-flow-step-highlight">
            <div class="profile-flow-step-badge">Step 3</div>
            <div class="profile-flow-step-icon">📩</div>
            <h3>Receive Inquiries</h3>
            <p>Buyers discover you and send inquiries — grow your customer base.</p>
          </div>
        </div>
        <div class="profile-flow-cta">
          <a href="{{ route('front.register') }}" class="btn btn-accent btn-lg">Start Free Registration</a>
        </div>
      </div>
    </div>
  </section>

  <!-- Core Values -->
  <section class="section section-alt">
    <div class="container">
      <div class="section-header text-center about-section-head">
        <div>
          <h2 class="section-title">Our Core Values</h2>
          <p class="section-subtitle">What drives us every day</p>
        </div>
      </div>
      <div class="values-grid">
        <div class="value-card">
          <div class="value-icon" style="background:var(--primary-light);">🤝</div>
          <h3>Partnership</h3>
          <p>We build strong relationships between businesses and buyers, technology and trust.</p>
        </div>
        <div class="value-card">
          <div class="value-icon" style="background:var(--accent-light);">💡</div>
          <h3>Innovation</h3>
          <p>Leveraging technology to create seamless discovery experiences and growth opportunities.</p>
        </div>
        <div class="value-card">
          <div class="value-icon" style="background:var(--primary-light);">🛡️</div>
          <h3>Trust</h3>
          <p>Every listing and interaction is built on transparency and reliability you can count on.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Team -->
  <section class="about-team">
    <div class="container">
      <div class="section-header text-center about-section-head">
        <div>
          <h2 class="section-title">Leadership Team</h2>
          <p class="section-subtitle">The people behind Just Goom LLP</p>
        </div>
      </div>
      <div class="team-grid">
        <div class="team-card team-card-lg">
          <div class="team-avatar">PY</div>
          <div class="team-card-body">
            <strong>Patel Yogeshkumar</strong>
            <span class="team-role">Co-Founder</span>
            <p class="team-bio">Leading platform strategy and business partnerships across India.</p>
            <a href="tel:+919624898242">+91 96248 98242</a>
          </div>
        </div>
        <div class="team-card team-card-lg">
          <div class="team-avatar">PB</div>
          <div class="team-card-body">
            <strong>Patel Bhaveshkumar</strong>
            <span class="team-role">Co-Founder</span>
            <p class="team-bio">Driving product innovation and technology for B2B discovery.</p>
            <a href="tel:+917201838383">+91 72018 38383</a>
          </div>
        </div>
        <div class="team-card team-card-lg">
          <div class="team-avatar">K</div>
          <div class="team-card-body">
            <strong>Kumar</strong>
            <span class="team-role">Co-Founder</span>
            <p class="team-bio">Building operations and growth so MSMEs get discovered and connect with you.</p>
            <a href="tel:+917894561236">+91 78945 61236</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Corporate Team -->
  <section class="about-team about-corporate-team">
    <div class="container">
      <div class="section-header text-center about-section-head">
        <div>
          <h2 class="section-title">Corporate Team</h2>
          <p class="section-subtitle">The specialists who design, build, and deliver Just Goom</p>
        </div>
      </div>
      <div class="team-grid team-grid-4">
        <div class="team-card team-card-lg">
          <div class="team-avatar team-avatar-lead">TL</div>
          <div class="team-card-body">
            <strong>Team Lead</strong>
            <span class="team-role">Engineering</span>
            <p class="team-bio">Guides platform delivery, code quality, and day-to-day execution of the Just Goom product.</p>
          </div>
        </div>
        <div class="team-card team-card-lg">
          <div class="team-avatar team-avatar-dev">DV</div>
          <div class="team-card-body">
            <strong>Developer</strong>
            <span class="team-role">Technology</span>
            <p class="team-bio">Builds and maintains listings, search, and the B2B discovery experience across web and admin.</p>
          </div>
        </div>
        <div class="team-card team-card-lg">
          <div class="team-avatar team-avatar-design">DS</div>
          <div class="team-card-body">
            <strong>Designer</strong>
            <span class="team-role">Product Design</span>
            <p class="team-bio">Crafts the brand, UI, and user journeys so buyers and businesses can discover each other easily.</p>
          </div>
        </div>
        <div class="team-card team-card-lg">
          <div class="team-avatar team-avatar-ba">BA</div>
          <div class="team-card-body">
            <strong>Business Analyst</strong>
            <span class="team-role">BA Team</span>
            <p class="team-bio">Turns business needs into clear requirements and keeps product, ops, and stakeholders aligned.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Contact Info -->
  <section class="section about-contact-section">
    <div class="container">
      <div class="section-header text-center about-section-head">
        <div>
          <h2 class="section-title">Get In Touch</h2>
          <p class="section-subtitle">We'd love to hear from you</p>
        </div>
      </div>
      <div class="contact-info-grid about-contact-grid">
        <div class="contact-info-card">
          <div class="ci-icon">📧</div>
          <h4>Email</h4>
          <p><a href="mailto:info@justgoom.com">info@justgoom.com</a></p>
        </div>
        <div class="contact-info-card">
          <div class="ci-icon">🌐</div>
          <h4>Website</h4>
          <p><a href="https://www.justgoom.com" target="_blank" rel="noopener">www.justgoom.com</a></p>
        </div>
        <div class="contact-info-card">
          <div class="ci-icon">📍</div>
          <h4>Office Address</h4>
          <p>A/201, Oxford Avenue, Opp. C.U. Shah College, Ashram Road, Ahmedabad — 380014</p>
        </div>
      </div>
      <div class="about-contact-cta">
        <a href="{{ route('front.contact') }}" class="btn btn-outline-primary">Send a Message →</a>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="section section-alt">
    <div class="container">
      <div class="b2b-cta-grid">
        <div class="b2b-cta-card b2b-cta-buyer">
          <span class="b2b-cta-label">For Buyers</span>
          <h3>Find Verified Businesses</h3>
          <p>Browse profiles across 24+ sectors and connect with trusted suppliers.</p>
          <a href="{{ route('front.all-profiles') }}" class="btn btn-accent">Browse Profiles →</a>
        </div>
        <div class="b2b-cta-card b2b-cta-seller">
          <span class="b2b-cta-label">For Sellers</span>
          <h3>Grow Your Business</h3>
          <p>List your profile for free and start receiving buyer inquiries today.</p>
          <a href="{{ route('front.register') }}" class="btn btn-primary">Register Free →</a>
        </div>
      </div>
    </div>
  </section>
@endsection
