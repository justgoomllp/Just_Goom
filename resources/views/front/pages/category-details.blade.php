@extends('front.layouts.app')

@section('title', 'Just Goom | Company Profile | JustGoom')
@section('meta_description', 'Just Goom company profile — IT services, team, projects, and contact information on JustGoom.')
@section('body_attrs', 'class="profile-page" data-page="all-profiles"')

@section('content')
<!-- Profile Hero -->
  <section class="profile-hero">
    <div class="profile-hero-bg" style="background-image:url('assets/images/hero-banner.jpg')"></div>
    <div class="container profile-hero-inner">
      <div class="profile-hero-top">
        <div class="profile-hero-brand">
          <div class="profile-hero-logo">JG</div>
          <div>
            <h1>Just Goom</h1>
            <p class="profile-category">IT Company</p>
            <div class="profile-meta">
              <span>📍 California, United States</span>
              <a href="https://justgoom.com" target="_blank" rel="noopener">🌐 justgoom.com</a>
            </div>
          </div>
        </div>
        <div class="profile-hero-stats">
          <div class="profile-stat">
            <strong>24.3K</strong>
            <span>Followers</span>
          </div>
          <div class="profile-stat">
            <strong>1.3K</strong>
            <span>Following</span>
          </div>
        </div>
      </div>

      <div class="profile-hero-nav">
        <nav class="profile-tabs" id="profileTabs">
          <button type="button" class="active" data-tab="overview">Overview</button>
          <button type="button" data-tab="activities">Activities</button>
          <button type="button" data-tab="projects">Projects</button>
          <button type="button" data-tab="documents">Documents</button>
          <button type="button" data-tab="services">Services</button>
          <button type="button" data-tab="product">Product</button>
          <button type="button" data-tab="blog">My Blog</button>
          <button type="button" data-tab="location">My Location</button>
        </nav>
        <button type="button" class="btn-share-profile">↗ Share Profile</button>
      </div>
    </div>
  </section>

  <!-- Profile Body -->
  <div class="container profile-body">

    <aside class="profile-sidebar">
      <div class="profile-card profile-progress-card is-low">
        <h3>Complete Your Profile</h3>
        <div class="profile-progress-bar">
          <div class="profile-progress-fill" style="width:30%"></div>
        </div>
        <span class="profile-progress-label">30%</span>
      </div>

      <div class="profile-card">
        <h3>Info</h3>
        <ul class="profile-info-list">
          <li><span>Full Name :</span><strong>Just Goom</strong></li>
          <li><span>Mobile :</span><strong>+91 7201838383</strong></li>
          <li><span>E-mail :</span><strong>info@justgoom.com</strong></li>
          <li><span>Location :</span><strong>California, United States</strong></li>
          <li><span>Joining Date :</span><strong>24 Nov 2021</strong></li>
        </ul>
      </div>

      <div class="profile-card">
        <h3>Portfolio</h3>
        <div class="profile-portfolio">
          <a href="#" class="portfolio-icon github" title="GitHub">GH</a>
          <a href="#" class="portfolio-icon web" title="Website">🌐</a>
          <a href="#" class="portfolio-icon dribbble" title="Dribbble">Dr</a>
          <a href="#" class="portfolio-icon behance" title="Behance">Be</a>
        </div>
      </div>
    </aside>

    <main class="profile-content">

      <!-- Overview -->
      <div class="profile-tab-pane active" data-pane="overview">
        <div class="profile-card profile-about-card">
          <h3>About</h3>
          <div class="profile-about-text">
            <p>Hi I'm Just Goom, It will be as simple as Occidental in fact, it will be Occidental. To an English person, it will seem like simplified English, as a skeptical Cambridge friend of mine told me what Occidental is. The European languages are members of the same family.</p>
            <p>You always want to make sure that your fonts work well together and try to limit the number of fonts you use to three or fewer. Experiment and play around with the fonts that you already have in the software you're working with, or you can always revert to the defaults.</p>
          </div>
          <div class="profile-quick-info">
            <div class="quick-info-item">
              <span class="quick-info-icon">💼</span>
              <div>
                <span class="quick-info-label">Designation</span>
                <strong>Lead Designer / Developer</strong>
              </div>
            </div>
            <div class="quick-info-item">
              <span class="quick-info-icon">🌐</span>
              <div>
                <span class="quick-info-label">Website</span>
                <strong>www.justgoom.com</strong>
              </div>
            </div>
          </div>
        </div>

        <div class="profile-card profile-team-card">
          <div class="profile-team-header">
            <h3>Team</h3>
            <div class="team-time-filter">
              <button type="button" class="active">Today</button>
              <button type="button">Weekly</button>
              <button type="button">Monthly</button>
            </div>
          </div>
          <div class="profile-team-grid">
            <article class="team-member-card">
              <div class="team-avatar" style="background:#6366f1">GM</div>
              <h4>Glen Matney</h4>
              <p class="team-role">Marketing Director</p>
              <p class="team-location">📍 California, United States</p>
              <a href="{{ route('front.category-details') }}" class="btn btn-primary btn-block btn-sm">View Profile</a>
            </article>
            <article class="team-member-card">
              <div class="team-avatar" style="background:#ec4899">JD</div>
              <h4>James Dave</h4>
              <p class="team-role">Project Manager</p>
              <p class="team-location">📍 California, United States</p>
              <a href="{{ route('front.category-details') }}" class="btn btn-primary btn-block btn-sm">View Profile</a>
            </article>
            <article class="team-member-card">
              <div class="team-avatar" style="background:#14b8a6">LH</div>
              <h4>Laura Hansen</h4>
              <p class="team-role">UI/UX Designer</p>
              <p class="team-location">📍 California, United States</p>
              <a href="{{ route('front.category-details') }}" class="btn btn-primary btn-block btn-sm">View Profile</a>
            </article>
            <article class="team-member-card">
              <div class="team-avatar" style="background:#f59e0b">RK</div>
              <h4>Ronald Keith</h4>
              <p class="team-role">Backend Developer</p>
              <p class="team-location">📍 California, United States</p>
              <a href="{{ route('front.category-details') }}" class="btn btn-primary btn-block btn-sm">View Profile</a>
            </article>
          </div>
        </div>
      </div>

      <!-- Activities -->
      <div class="profile-tab-pane" data-pane="activities">
        <div class="profile-card">
          <h3>Recent Activities</h3>
          <ul class="profile-activity-list">
            <li><span class="activity-dot"></span> Updated company portfolio — <time>2 hours ago</time></li>
            <li><span class="activity-dot"></span> Added 3 new team members — <time>Yesterday</time></li>
            <li><span class="activity-dot"></span> Published blog post on digital trends — <time>3 days ago</time></li>
            <li><span class="activity-dot"></span> Completed project milestone for client — <time>1 week ago</time></li>
          </ul>
        </div>
      </div>

      <!-- Projects -->
      <div class="profile-tab-pane" data-pane="projects">
        <div class="profile-card">
          <h3>Projects</h3>
          <div class="profile-projects-grid">
            <div class="profile-project-item">
              <strong>JustGoom Platform</strong>
              <span>Web Development · 225 Tasks</span>
            </div>
            <div class="profile-project-item">
              <strong>Health Calculator App</strong>
              <span>Mobile · 197 Tasks</span>
            </div>
            <div class="profile-project-item">
              <strong>Category Discovery Portal</strong>
              <span>UI/UX · 164 Tasks</span>
            </div>
            <div class="profile-project-item">
              <strong>Business Listing Module</strong>
              <span>Backend · 182 Tasks</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Documents -->
      <div class="profile-tab-pane" data-pane="documents">
        <div class="profile-card">
          <h3>Documents</h3>
          <ul class="profile-doc-list">
            <li><span>📄</span> Company Profile.pdf <a href="#">Download</a></li>
            <li><span>📄</span> Service Agreement.docx <a href="#">Download</a></li>
            <li><span>📄</span> Portfolio Brochure.pdf <a href="#">Download</a></li>
          </ul>
        </div>
      </div>

      <!-- Services -->
      <div class="profile-tab-pane" data-pane="services">
        <div class="profile-card">
          <h3>Services</h3>
          <div class="profile-services-grid">
            <div class="profile-service-tag">Web Development</div>
            <div class="profile-service-tag">Mobile Apps</div>
            <div class="profile-service-tag">UI/UX Design</div>
            <div class="profile-service-tag">Digital Marketing</div>
            <div class="profile-service-tag">Cloud Solutions</div>
            <div class="profile-service-tag">IT Consulting</div>
          </div>
        </div>
      </div>

      <!-- Product -->
      <div class="profile-tab-pane" data-pane="product">
        <div class="profile-card">
          <h3>Products</h3>
          <div class="profile-projects-grid">
            <div class="profile-project-item">
              <strong>JustGoom Admin Panel</strong>
              <span>Business management software</span>
            </div>
            <div class="profile-project-item">
              <strong>Health Tracker Pro</strong>
              <span>Wellness & BMI tools</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Blog -->
      <div class="profile-tab-pane" data-pane="blog">
        <div class="profile-card">
          <h3>My Blog</h3>
          <div class="profile-blog-list">
            <a href="{{ route('front.articles') }}" class="profile-blog-item">
              <strong>How to grow your local business online</strong>
              <span>May 28, 2026</span>
            </a>
            <a href="{{ route('front.articles') }}" class="profile-blog-item">
              <strong>Top 10 categories trending in 2026</strong>
              <span>May 15, 2026</span>
            </a>
            <a href="{{ route('front.articles') }}" class="profile-blog-item">
              <strong>Building trust with verified profiles</strong>
              <span>Apr 30, 2026</span>
            </a>
          </div>
        </div>
      </div>

      <!-- Location -->
      <div class="profile-tab-pane" data-pane="location">
        <div class="profile-card">
          <h3>My Location</h3>
          <p class="profile-location-text">📍 California, United States</p>
          <div class="profile-map-placeholder">
            <img src="{{ asset('front/assets/images/hero-banner.jpg') }}" alt="Location map preview">
            <span>Map preview — California, United States</span>
          </div>
        </div>
      </div>

    </main>
  </div>

  <button type="button" class="profile-fab" aria-label="Settings">⚙</button>
@endsection
