@extends('front.layouts.app')

@section('title', 'Contact Us — Just Goom LLP')
@section('meta_description', 'Contact Just Goom LLP for business listings, buyer inquiries, partnerships, and platform support.')
@section('body_attrs', 'class="contact-page" data-page="contact"')

@section('content')
  <section class="page-hero contact-hero">
    <div class="contact-hero-orbs" aria-hidden="true">
      <span></span><span></span><span></span>
    </div>
    <div class="container contact-hero-grid">
      <div class="contact-hero-copy">
        <nav class="breadcrumb">
          <a href="{{ route('front.home') }}">Home</a>
          <span class="breadcrumb-sep">›</span>
          <span>Contact Us</span>
        </nav>
        <p class="contact-kicker">Direct line to Just Goom</p>
        <h1>Talk to the team that <em>connects</em> Indian business.</h1>
        <p>Listings, buyer leads, verification, or partnerships — we reply within 24–48 business hours from Ahmedabad.</p>
        <div class="contact-hero-actions">
          <a href="#contactForm" class="btn btn-accent btn-lg">Send a message</a>
          <a href="tel:+919624898242" class="btn btn-outline contact-hero-call">Call +91 96248 98242</a>
        </div>
        <ul class="contact-hero-pills">
          <li>24–48h reply</li>
          <li>Mon–Sat desk</li>
          <li>Ahmedabad HQ</li>
          <li>India-wide coverage</li>
        </ul>
      </div>
      <div class="contact-orbit" aria-hidden="true">
        <div class="contact-orbit-ring r1"></div>
        <div class="contact-orbit-ring r2"></div>
        <div class="contact-orbit-ring r3"></div>
        <div class="contact-orbit-core">JG</div>
        <span class="contact-sat s1">✉</span>
        <span class="contact-sat s2">☎</span>
        <span class="contact-sat s3">◎</span>
        <span class="contact-sat s4">📍</span>
      </div>
    </div>
  </section>

  <section class="section contact-reach-section">
    <div class="container">
      <div class="section-header text-center about-section-head">
        <div>
          <h2 class="section-title">Get In Touch</h2>
          <p class="section-subtitle">Four live channels — pick the fastest path for you</p>
        </div>
      </div>
      <div class="contact-reach-grid">
        <a href="mailto:info@justgoom.com" class="contact-info-card contact-reach-card">
          <span class="contact-reach-index">01</span>
          <div class="ci-icon ci-icon-mail" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none"><path d="M4 7.5A2.5 2.5 0 0 1 6.5 5h11A2.5 2.5 0 0 1 20 7.5v9A2.5 2.5 0 0 1 17.5 19h-11A2.5 2.5 0 0 1 4 16.5v-9Z" stroke="currentColor" stroke-width="1.7"/><path d="m5 7 7 6 7-6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </div>
          <h4>Email</h4>
          <p>info@justgoom.com</p>
          <span class="contact-reach-go">Write us →</span>
        </a>
        <a href="tel:+919624898242" class="contact-info-card contact-reach-card">
          <span class="contact-reach-index">02</span>
          <div class="ci-icon ci-icon-phone" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none"><path d="M7.2 3.8h3.1l1.2 3.1-1.7 1.7a12.5 12.5 0 0 0 5.6 5.6l1.7-1.7 3.1 1.2v3.1c0 .9-.7 1.7-1.6 1.8C9.4 19.4 4.6 14.6 3.4 5.4c-.1-.9.7-1.6 1.6-1.6Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg>
          </div>
          <h4>Phone</h4>
          <p>+91 96248 98242<br>+91 72018 38383</p>
          <span class="contact-reach-go">Call now →</span>
        </a>
        <a href="https://www.justgoom.com" target="_blank" rel="noopener" class="contact-info-card contact-reach-card">
          <span class="contact-reach-index">03</span>
          <div class="ci-icon ci-icon-web" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="8.2" stroke="currentColor" stroke-width="1.7"/><path d="M3.8 12h16.4M12 3.8c2.2 2.4 3.4 5.2 3.4 8.2S14.2 18 12 20.2C9.8 18 8.6 15.2 8.6 12S9.8 6.2 12 3.8Z" stroke="currentColor" stroke-width="1.7"/></svg>
          </div>
          <h4>Website</h4>
          <p>www.justgoom.com</p>
          <span class="contact-reach-go">Visit site →</span>
        </a>
        <div class="contact-info-card contact-reach-card">
          <span class="contact-reach-index">04</span>
          <div class="ci-icon ci-icon-pin" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none"><path d="M12 21s6.5-5.4 6.5-10.2A6.5 6.5 0 0 0 5.5 10.8C5.5 15.6 12 21 12 21Z" stroke="currentColor" stroke-width="1.7"/><circle cx="12" cy="10.6" r="2.1" stroke="currentColor" stroke-width="1.7"/></svg>
          </div>
          <h4>Office Address</h4>
          <p>A/201, Oxford Avenue, Opp. C.U. Shah College, Ashram Road, Ahmedabad — 380014</p>
        </div>
      </div>
    </div>
  </section>

  <section class="section section-alt contact-page-section">
    <div class="container contact-page-layout">

      <div class="contact-form-card">
        <div class="contact-form-head">
          <span class="about-tag">Send a Message</span>
          <h2>How Can We Help?</h2>
          <p>Share your enquiry. A Just Goom specialist will follow up on the same thread.</p>
          <ol class="contact-form-steps" aria-hidden="true">
            <li class="is-on">Details</li>
            <li>Topic</li>
            <li>Message</li>
          </ol>
        </div>
        <form class="contact-form" id="contactForm">
          <div class="contact-form-success" hidden>
            <strong>Message received</strong>
            <span>Thank you. We will contact you within 24–48 business hours.</span>
            <button type="button" class="btn btn-outline-primary" id="contactFormReset">Send another</button>
          </div>
          <div class="contact-form-fields">
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
              <textarea id="contactMessage" class="form-input contact-textarea" rows="5" placeholder="Tell us about your business or enquiry…" required maxlength="1200"></textarea>
              <span class="contact-char" id="contactChar">0 / 1200</span>
            </div>
            <button type="submit" class="btn btn-accent btn-lg contact-submit">
              <span>Send Message</span>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
            <p class="contact-note">By submitting this form, you agree to our Privacy Policy and Terms of Use.</p>
          </div>
        </form>
      </div>

      <aside class="contact-sidebar">
        <div class="contact-hours-card">
          <div class="contact-hours-top">
            <h4>Business Hours</h4>
            <span class="contact-live" id="contactLive">Checking…</span>
          </div>
          <ul class="contact-hours-list">
            <li><span>Monday – Friday</span><strong>9:00 AM – 6:00 PM</strong></li>
            <li><span>Saturday</span><strong>10:00 AM – 4:00 PM</strong></li>
            <li><span>Sunday</span><strong>Closed</strong></li>
          </ul>
          <p class="contact-hours-zone">Times shown in India Standard Time</p>
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

  <section class="section contact-faq-section">
    <div class="container contact-faq-wrap">
      <div class="contact-faq-intro">
        <span class="about-tag">Help desk</span>
        <h2 class="section-title">Frequently asked questions</h2>
        <p class="section-subtitle">Quick answers before you reach out. Still stuck? Use the form.</p>
      </div>
      <div class="contact-faq-list">
        <details class="contact-faq-item" open>
          <summary>How do I list my business for free?</summary>
          <p>Click Register, create your account, and complete your business profile. Listing on Just Goom is free for MSMEs and local businesses.</p>
        </details>
        <details class="contact-faq-item">
          <summary>How do buyers send inquiries?</summary>
          <p>Buyers browse All Profiles, open a company profile, and submit an inquiry form. Verified businesses receive leads directly.</p>
        </details>
        <details class="contact-faq-item">
          <summary>What is profile verification?</summary>
          <p>Verified badges help buyers trust your business. Contact us with your GST, registration, or business documents to apply.</p>
        </details>
        <details class="contact-faq-item">
          <summary>Do you cover businesses outside Ahmedabad?</summary>
          <p>Yes. Just Goom serves businesses and buyers across India with 24+ industry sectors and growing city coverage.</p>
        </details>
      </div>
    </div>
  </section>

  <section class="contact-map-section">
    <div class="contact-map-frame">
      <iframe
        title="Just Goom LLP office on Ashram Road, Ahmedabad"
        src="https://maps.google.com/maps?q=Oxford%20Avenue%20Ashram%20Road%20Ahmedabad%20380014&z=16&output=embed"
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"
        allowfullscreen></iframe>
      <div class="contact-map-card">
        <strong>Just Goom LLP</strong>
        <span>A/201, Oxford Avenue, Ashram Road<br>Ahmedabad, Gujarat — 380014</span>
        <a href="https://maps.google.com/?q=Oxford+Avenue+Ashram+Road+Ahmedabad" target="_blank" rel="noopener">Open in Maps →</a>
      </div>
    </div>
  </section>

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
