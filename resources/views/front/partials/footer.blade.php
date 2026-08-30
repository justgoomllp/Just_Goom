<footer class="site-footer">
  <div class="container">
    <div class="footer-grid">
      <div class="footer-brand">
        <a href="{{ route('front.home') }}" class="logo logo-footer logo-wrap">
          <img src="{{ asset('front/assets/images/justgoom-logo.png') }}" alt="JustGoom" class="logo-img logo-img-footer">
        </a>
        <p>Just Goom LLP — Your trusted B2B platform for discovering verified business profiles, categories, and expert content across India.</p>
        <div class="footer-social">
          <a href="#" aria-label="Facebook">f</a>
          <a href="#" aria-label="Twitter">𝕏</a>
          <a href="#" aria-label="Instagram">📷</a>
          <a href="#" aria-label="LinkedIn">in</a>
        </div>
      </div>
      <div class="footer-col">
        <h4>Quick Links</h4>
        <ul>
          <li><a href="{{ route('front.home') }}">Home</a></li>
          <li><a href="{{ route('front.about') }}">About Us</a></li>
          <li><a href="{{ route('front.categories') }}">Categories</a></li>
          <li><a href="{{ route('front.all-profiles') }}">All Profiles</a></li>
          <!-- <li><a href="{{ route('front.articles') }}">Articles</a></li> -->
          <li><a href="{{ route('front.pricing') }}">Pricing</a></li>
          <li><a href="{{ route('front.contact') }}">Contact Us</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Profile Categories</h4>
        <ul>
          <li><a href="{{ route('front.all-profiles') }}">Real Estate</a></li>
          <li><a href="{{ route('front.all-profiles') }}">Health & Wellness</a></li>
          <li><a href="{{ route('front.all-profiles') }}">Entertainment</a></li>
          <li><a href="{{ route('front.all-profiles') }}">Education</a></li>
          <li><a href="{{ route('front.all-profiles') }}">Business Services</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Support</h4>
        <ul>
          <li><a href="{{ route('front.contact') }}">Contact Us</a></li>
          <li><a href="#">FAQ</a></li>
          <li><a href="#">Privacy Policy</a></li>
          <li><a href="#">Terms & Conditions</a></li>
          <li><a href="#">Advertise</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© {{ date('Y') }} Just Goom LLP. All rights reserved.</span>
      <span class="footer-partner">Partnered By <strong>NeatifyDocs LLP</strong></span>
      <div class="footer-bottom-links">
        <a href="#">Privacy</a>
        <a href="#">Terms</a>
        <a href="#">Sitemap</a>
      </div>
    </div>
  </div>
</footer>
