@extends('front.layouts.app')

@section('title', 'Contact Us — Just Goom LLP')
@section('meta_description', 'Contact Just Goom LLP for business listings, buyer inquiries, partnerships, and platform support.')
@section('body_attrs', 'class="contact-page" data-page="contact"')

@section('content')
<!-- Page Hero -->
  <section class="page-hero contact-hero">
    <div class="container">
      <nav class="breadcrumb">
        <a href="{{ route('front.home') }}">Home</a>
        <span class="breadcrumb-sep">›</span>
        <span>Contact Us</span>
      </nav>
      <h1>Contact Just Goom</h1>
      <p>Questions about listings, buyer inquiries, or partnerships? Our team is here to help you grow on India's B2B platform.</p>
    </div>
    <div class="pixel-deco orange"><span></span><span></span><span></span><span></span></div>
  </section>

  <!-- Quick Stats -->
  <div class="stats-bar contact-stats">
    <div class="container stats-grid">
      <div class="stat-item">
        <div class="stat-num">24–48h</div>
        <div class="stat-label">Response Time</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">Mon–Sat</div>
        <div class="stat-label">Support Available</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">Ahmedabad</div>
        <div class="stat-label">Head Office</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">Global Reach</div>
        <div class="stat-label">Business Coverage</div>
      </div>
    </div>
  </div>

  <!-- Reach Us — 4 column base -->
  <section class="section contact-reach-section">
    <div class="container">
      <div class="section-header text-center about-section-head">
        <div>
          <h2 class="section-title">Get In Touch</h2>
          <p class="section-subtitle">Four ways to reach the Just Goom team</p>
        </div>
      </div>
      <div class="contact-reach-grid">
        <div class="contact-info-card">
          <div class="ci-icon">📧</div>
          <h4>Email</h4>
          <p><a href="mailto:info@justgoom.com">info@justgoom.com</a></p>
        </div>
        <div class="contact-info-card">
          <div class="ci-icon">📞</div>
          <h4>Phone</h4>
          <p><a href="tel:+919624898242">+91 96248 98242</a></p>
          <p><a href="tel:+917201838383">+91 72018 38383</a></p>
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
    </div>
  </section>

  <!-- Contact Form -->
  <section class="section section-alt contact-page-section">
    <div class="container contact-page-layout">

      <div class="contact-form-card">
        <span class="about-tag">Send a Message</span>
        <h2>How Can We Help?</h2>
        <p>Fill in the form below and our team will get back to you within 24–48 business hours.</p>
        <form class="contact-form" id="contactForm">
          <div class="contact-form-row">
            <div class="form-group">
              <label for="contactName">Full Name <span class="req">*</span></label>
              <input type="text" id="contactName" class="form-input" placeholder="Your full name" required>
            </div>
            <div class="form-group">
              <label for="contactEmail">Email Address <span class="req">*</span></label>
              <input type="email" id="contactEmail" class="form-input" placeholder="you@example.com" required>
            </div>
          </div>
          <div class="contact-form-row">
            <div class="form-group">
              <label for="contactPhone">Mobile Number</label>
              <div class="phone-input">
                <span class="phone-prefix">+91</span>
                <input type="tel" id="contactPhone" class="form-input" placeholder="Mobile number">
              </div>
            </div>
            <div class="form-group">
              <label for="contactSubject">Subject <span class="req">*</span></label>
              <select id="contactSubject" class="form-input" required>
                <option value="">Select a subject</option>
                <option>General Enquiry</option>
                <option>List My Business</option>
                <option>Buyer Inquiry Support</option>
                <option>Profile Verification</option>
                <option>Partnership / Advertising</option>
                <option>Technical Support</option>
                <option>Feedback</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="contactMessage">Message <span class="req">*</span></label>
            <textarea id="contactMessage" class="form-input contact-textarea" rows="5" placeholder="Tell us about your business or enquiry…" required></textarea>
          </div>
          <button type="submit" class="btn btn-accent btn-lg">Send Message</button>
          <p class="contact-note">By submitting this form, you agree to our <a href="#">Privacy Policy</a> and <a href="#">Terms of Use</a>.</p>
        </form>
      </div>

      <aside class="contact-sidebar">
        <div class="contact-hours-card">
          <h4>Business Hours</h4>
          <ul class="contact-hours-list">
            <li><span>Monday – Friday</span><strong>9:00 AM – 6:00 PM</strong></li>
            <li><span>Saturday</span><strong>10:00 AM – 4:00 PM</strong></li>
            <li><span>Sunday</span><strong>Closed</strong></li>
          </ul>
        </div>

        <div class="contact-quick-card">
          <h4>Quick Actions</h4>
          <div class="contact-quick-links">
            <a href="{{ route('front.register') }}" class="btn btn-accent btn-block">List Your Business</a>
            <a href="{{ route('front.all-profiles') }}" class="btn btn-outline-primary btn-block">Browse Profiles</a>
            <a href="{{ route('front.calculators') }}" class="btn btn-outline btn-block">Free Calculators</a>
          </div>
        </div>

        <div class="contact-partner-card">
          <div class="contact-partner-avatar" aria-hidden="true">ND</div>
          <div class="contact-partner-copy">
            <span class="contact-partner-label">Partnered By</span>
            <strong>NeatifyDocs LLP</strong>
          </div>
        </div>
      </aside>

    </div>
  </section>

  <!-- FAQ -->
  <section class="section section-alt contact-faq-section">
    <div class="container">
      <div class="section-header text-center about-section-head">
        <div>
          <h2 class="section-title">Frequently Asked Questions</h2>
          <p class="section-subtitle">Quick answers before you reach out</p>
        </div>
      </div>
      <div class="contact-faq-grid">
        <div class="contact-faq-item">
          <h4>How do I list my business for free?</h4>
          <p>Click Register, create your account, and complete your business profile. Listing on Just Goom is free for MSMEs and local businesses.</p>
        </div>
        <div class="contact-faq-item">
          <h4>How do buyers send inquiries?</h4>
          <p>Buyers browse All Profiles, open a company profile, and submit an inquiry form. Verified businesses receive leads directly.</p>
        </div>
        <div class="contact-faq-item">
          <h4>What is profile verification?</h4>
          <p>Verified badges help buyers trust your business. Contact us with your GST, registration, or business documents to apply.</p>
        </div>
        <div class="contact-faq-item">
          <h4>Do you cover businesses outside Ahmedabad?</h4>
          <p>Yes. Just Goom serves businesses and buyers across India with 24+ industry sectors and growing city coverage.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Map -->
  <section class="contact-map-section">
    <div class="container">
      <div class="contact-map-wrap">
        <img src="{{ asset('front/assets/images/hero-banner.jpg') }}" alt="Just Goom office location">
        <div class="contact-map-overlay">
          <strong>Just Goom LLP</strong>
          <span>Ahmedabad, Gujarat — 380014</span>
        </div>
      </div>
    </div>
  </section>

  <!-- Dual CTA -->
  <section class="section">
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
