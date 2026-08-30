@extends('front.layouts.app')

@section('title', 'Free Calculators — Loan, Gold &amp; Silver | Just Goom')
@section('meta_description', 'Free online calculators — loan EMI, gold loan, silver loan, making charges, and age calculator by Just Goom LLP.')
@section('body_attrs', 'class="calculators-page"')

@section('content')
<section class="page-hero calculators-hero">
    <div class="container">
      <nav class="breadcrumb">
        <a href="{{ route('front.home') }}">Home</a>
        <span class="breadcrumb-sep">›</span>
        <span>Calculators</span>
      </nav>
      <h1>Finance Calculators</h1>
      <p>Loan EMI, gold loan, silver loan, making charges, and age calculators — separate from our Health module.</p>
    </div>
    <div class="pixel-deco orange"><span></span><span></span><span></span><span></span></div>
  </section>

  <div class="container calc-hub-layout">
    <aside class="calc-sidebar">
      <h3 class="calc-sidebar-title">Choose Calculator</h3>
      <nav class="calc-sidebar-nav">
        <button type="button" class="calc-sidebar-item active" data-calc="loan">
          <span class="calc-sidebar-icon">🏦</span>
          <span class="calc-sidebar-label">Loan Calculator</span>
        </button>
        <button type="button" class="calc-sidebar-item" data-calc="age">
          <span class="calc-sidebar-icon">📅</span>
          <span class="calc-sidebar-label">Age Calculator</span>
        </button>
        <button type="button" class="calc-sidebar-item" data-calc="gold-loan">
          <span class="calc-sidebar-icon">🪙</span>
          <span class="calc-sidebar-label">Gold Loan Calculator</span>
        </button>
        <button type="button" class="calc-sidebar-item" data-calc="silver-loan">
          <span class="calc-sidebar-icon">⚪</span>
          <span class="calc-sidebar-label">Silver Loan Calculator</span>
        </button>
        <button type="button" class="calc-sidebar-item" data-calc="making-charges">
          <span class="calc-sidebar-icon">💍</span>
          <span class="calc-sidebar-label">Making Charges Calculator</span>
        </button>
      </nav>
      <div class="calc-sidebar-note">
        <p>Looking for BMI &amp; health tools?</p>
        <a href="health.html" class="btn btn-outline btn-sm btn-block">Go to Health Module →</a>
      </div>
    </aside>

    <div class="calc-hub-main">
      <div class="calc-layout calc-layout-hub">
        <div class="calc-panels">

          <!-- Loan -->
          <div class="calc-panel active" id="panel-loan">
            <div class="calc-card">
              <h2>Loan Calculator</h2>
              <p>Calculate monthly EMI, total interest, and total payment for your loan.</p>
              <form id="loanForm">
                <div class="calc-form-grid">
                  <div class="form-group full">
                    <label>Loan Amount (₹)</label>
                    <input type="number" id="loanAmount" class="form-input" placeholder="e.g. 500000" min="1" required>
                  </div>
                  <div class="form-group">
                    <label>Interest Rate (% p.a.)</label>
                    <input type="number" id="loanRate" class="form-input" placeholder="e.g. 10.5" min="0" step="0.1" required>
                  </div>
                  <div class="form-group">
                    <label>Tenure</label>
                    <div class="calc-input-row">
                      <input type="number" id="loanTenure" class="form-input" placeholder="e.g. 5" min="1" required>
                      <select id="loanTenureType" class="filter-select">
                        <option value="years">Years</option>
                        <option value="months">Months</option>
                      </select>
                    </div>
                  </div>
                </div>
                <button type="submit" class="btn btn-accent btn-lg btn-block calc-submit-btn">Calculate EMI</button>
              </form>
            </div>
          </div>

          <!-- Age -->
          <div class="calc-panel" id="panel-age">
            <div class="calc-card">
              <h2>Age Calculator</h2>
              <p>Find your exact age in years, months, days, and days until next birthday.</p>
              <form id="ageForm">
                <div class="calc-form-grid">
                  <div class="form-group full">
                    <label>Date of Birth</label>
                    <input type="date" id="dobInput" class="form-input" required>
                  </div>
                </div>
                <button type="submit" class="btn btn-accent btn-lg btn-block calc-submit-btn">Calculate Age</button>
              </form>
            </div>
          </div>

          <!-- Gold Loan -->
          <div class="calc-panel" id="panel-gold-loan">
            <div class="calc-card">
              <h2>Gold Loan Calculator</h2>
              <p>Estimate loan amount against gold jewellery including purity, rate, LTV, and making charges.</p>
              <form id="goldLoanForm">
                <div class="calc-form-grid">
                  <div class="form-group">
                    <label>Gold Weight (grams)</label>
                    <input type="number" id="glWeight" class="form-input" placeholder="e.g. 50" min="0.1" step="0.1" required>
                  </div>
                  <div class="form-group">
                    <label>Purity</label>
                    <select id="glPurity" class="filter-select">
                      <option value="99.9">24K (99.9%)</option>
                      <option value="91.6" selected>22K (91.6%)</option>
                      <option value="75">18K (75%)</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Gold Rate (₹ / 10g)</label>
                    <input type="number" id="glRate" class="form-input" placeholder="e.g. 72000" min="1" value="72000" required>
                  </div>
                  <div class="form-group">
                    <label>LTV Ratio (%)</label>
                    <input type="number" id="glLtv" class="form-input" placeholder="e.g. 75" min="1" max="100" value="75" required>
                  </div>
                  <div class="form-group full">
                    <label>Making Charges (% of gold value)</label>
                    <input type="number" id="glMaking" class="form-input" placeholder="e.g. 8" min="0" step="0.1" value="8">
                  </div>
                </div>
                <button type="submit" class="btn btn-accent btn-lg btn-block calc-submit-btn">Calculate Gold Loan</button>
              </form>
            </div>
          </div>

          <!-- Silver Loan -->
          <div class="calc-panel" id="panel-silver-loan">
            <div class="calc-card">
              <h2>Silver Loan Calculator</h2>
              <p>Estimate loan amount against silver assets with purity, market rate, and making charges.</p>
              <form id="silverLoanForm">
                <div class="calc-form-grid">
                  <div class="form-group">
                    <label>Silver Weight (grams)</label>
                    <input type="number" id="slWeight" class="form-input" placeholder="e.g. 500" min="1" step="0.1" required>
                  </div>
                  <div class="form-group">
                    <label>Purity</label>
                    <select id="slPurity" class="filter-select">
                      <option value="99.9" selected>999 Fine (99.9%)</option>
                      <option value="92.5">Sterling (92.5%)</option>
                      <option value="80">80% Silver</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Silver Rate (₹ / kg)</label>
                    <input type="number" id="slRate" class="form-input" placeholder="e.g. 95000" min="1" value="95000" required>
                  </div>
                  <div class="form-group">
                    <label>LTV Ratio (%)</label>
                    <input type="number" id="slLtv" class="form-input" placeholder="e.g. 70" min="1" max="100" value="70" required>
                  </div>
                  <div class="form-group full">
                    <label>Making Charges (% of silver value)</label>
                    <input type="number" id="slMaking" class="form-input" placeholder="e.g. 5" min="0" step="0.1" value="5">
                  </div>
                </div>
                <button type="submit" class="btn btn-accent btn-lg btn-block calc-submit-btn">Calculate Silver Loan</button>
              </form>
            </div>
          </div>

          <!-- Making Charges -->
          <div class="calc-panel" id="panel-making-charges">
            <div class="calc-card">
              <h2>Making Charges Calculator</h2>
              <p>Calculate making charges for gold or silver jewellery — per gram or percentage based.</p>
              <form id="makingForm">
                <div class="calc-form-grid">
                  <div class="form-group">
                    <label>Metal Type</label>
                    <select id="mcMetal" class="filter-select">
                      <option value="gold">Gold</option>
                      <option value="silver">Silver</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Weight (grams)</label>
                    <input type="number" id="mcWeight" class="form-input" placeholder="e.g. 10" min="0.1" step="0.1" required>
                  </div>
                  <div class="form-group">
                    <label>Metal Rate</label>
                    <input type="number" id="mcRate" class="form-input" placeholder="₹ per 10g (gold) or per kg (silver)" min="1" value="72000" required>
                  </div>
                  <div class="form-group">
                    <label>Charge Type</label>
                    <select id="mcType" class="filter-select">
                      <option value="percent">Percentage (%)</option>
                      <option value="pergram">Per Gram (₹)</option>
                      <option value="flat">Flat Amount (₹)</option>
                    </select>
                  </div>
                  <div class="form-group full">
                    <label>Making Charge Value</label>
                    <input type="number" id="mcValue" class="form-input" placeholder="e.g. 8 (for 8% or ₹8/gram)" min="0" step="0.1" value="8" required>
                  </div>
                </div>
                <button type="submit" class="btn btn-accent btn-lg btn-block calc-submit-btn">Calculate Making Charges</button>
              </form>
            </div>
          </div>

        </div>

        <div class="calc-result-card calc-result-hub">
          <h3 id="calcResultTitle">Loan Calculator Result</h3>
          <div id="calcResultBody">
            <div class="calc-result-placeholder">
              <span class="calc-result-icon">📊</span>
              <p>Enter details and click Calculate to see your result here.</p>
            </div>
          </div>
          <p class="calc-result-note" id="calcResultNote"></p>
        </div>
      </div>
    </div>
  </div>

  <section class="calc-info-section">
    <div class="container">
      <div class="section-header text-center about-section-head">
        <div>
          <h2 class="section-title">Finance Calculators</h2>
          <p class="section-subtitle">Loan, gold, silver, and jewellery making charge tools</p>
        </div>
      </div>
      <div class="calc-tools-grid">
        <div class="calc-tool-card">
          <span class="calc-tool-icon">🏦</span>
          <h3>Loan EMI</h3>
          <p>Plan home, car, or personal loan monthly payments.</p>
        </div>
        <div class="calc-tool-card">
          <span class="calc-tool-icon">🪙</span>
          <h3>Gold Loan</h3>
          <p>Estimate loan against gold with LTV and making charges.</p>
        </div>
        <div class="calc-tool-card">
          <span class="calc-tool-icon">⚪</span>
          <h3>Silver Loan</h3>
          <p>Calculate loan value for silver assets and ornaments.</p>
        </div>
        <div class="calc-tool-card">
          <span class="calc-tool-icon">💍</span>
          <h3>Making Charges</h3>
          <p>Percentage, per-gram, or flat making charge calculation.</p>
        </div>
      </div>
    </div>
  </section>

  <script src="{{ asset('front/assets/js/calculators.js') }}"></script>
@endsection
