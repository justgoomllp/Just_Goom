/* JustGoom — Finance Calculator Hub */
document.addEventListener('DOMContentLoaded', function() {
  if (!document.querySelector('.calc-hub-layout')) return;
  initCalculatorHub();
});

function initCalculatorHub() {
  var sidebarItems = document.querySelectorAll('.calc-sidebar-item');
  var panels = document.querySelectorAll('.calc-panel');
  var resultTitle = document.getElementById('calcResultTitle');
  var resultBody = document.getElementById('calcResultBody');
  var resultNote = document.getElementById('calcResultNote');

  function showCalculator(id) {
    sidebarItems.forEach(function(item) {
      item.classList.toggle('active', item.dataset.calc === id);
    });
    panels.forEach(function(panel) {
      panel.classList.toggle('active', panel.id === 'panel-' + id);
    });
    resetResult();
    var activeItem = document.querySelector('.calc-sidebar-item[data-calc="' + id + '"]');
    if (resultTitle && activeItem) {
      resultTitle.textContent = activeItem.querySelector('.calc-sidebar-label').textContent + ' Result';
    }
  }

  function resetResult() {
    if (resultBody) {
      resultBody.innerHTML = '<div class="calc-result-placeholder"><span class="calc-result-icon">📊</span><p>Enter details and click Calculate to see your result here.</p></div>';
    }
    if (resultNote) resultNote.textContent = '';
  }

  window.setCalcResult = function(html, note) {
    if (resultBody) resultBody.innerHTML = html;
    if (resultNote) resultNote.textContent = note || '';
  };

  sidebarItems.forEach(function(item) {
    item.addEventListener('click', function() {
      showCalculator(item.dataset.calc);
    });
  });

  /* ── Loan EMI ── */
  var loanForm = document.getElementById('loanForm');
  if (loanForm) {
    loanForm.addEventListener('submit', function(e) {
      e.preventDefault();
      var principal = parseFloat(document.getElementById('loanAmount').value);
      var rate = parseFloat(document.getElementById('loanRate').value);
      var tenure = parseFloat(document.getElementById('loanTenure').value);
      var tenureType = document.getElementById('loanTenureType').value;
      if (!principal || !rate || !tenure) { alert('Please fill all loan fields.'); return; }

      var months = tenureType === 'years' ? tenure * 12 : tenure;
      var r = rate / 12 / 100;
      var emi = r === 0 ? principal / months : principal * r * Math.pow(1 + r, months) / (Math.pow(1 + r, months) - 1);
      var total = emi * months;
      var interest = total - principal;

      setCalcResult(
        '<div class="calc-result-value calc-result-currency">₹' + formatINR(emi) + '</div>' +
        '<div class="calc-result-badge normal">Monthly EMI</div>' +
        '<ul class="calc-result-list">' +
          '<li><span>Principal</span><strong>₹' + formatINR(principal) + '</strong></li>' +
          '<li><span>Total Interest</span><strong>₹' + formatINR(interest) + '</strong></li>' +
          '<li><span>Total Payment</span><strong>₹' + formatINR(total) + '</strong></li>' +
          '<li><span>Tenure</span><strong>' + months + ' months</strong></li>' +
        '</ul>',
        'Indicative EMI · actual rates may vary by lender'
      );
    });
  }

  /* ── Age ── */
  var ageForm = document.getElementById('ageForm');
  if (ageForm) {
    ageForm.addEventListener('submit', function(e) {
      e.preventDefault();
      var dob = new Date(document.getElementById('dobInput').value);
      if (isNaN(dob.getTime())) { alert('Please select a valid date of birth.'); return; }
      var today = new Date();
      if (dob > today) { alert('Date of birth cannot be in the future.'); return; }

      var years = today.getFullYear() - dob.getFullYear();
      var months = today.getMonth() - dob.getMonth();
      var days = today.getDate() - dob.getDate();
      if (days < 0) { months--; days += new Date(today.getFullYear(), today.getMonth(), 0).getDate(); }
      if (months < 0) { years--; months += 12; }

      var totalDays = Math.floor((today - dob) / (1000 * 60 * 60 * 24));

      setCalcResult(
        '<div class="calc-result-value">' + years + '</div>' +
        '<div class="calc-result-badge normal">Years Old</div>' +
        '<ul class="calc-result-list">' +
          '<li><span>Exact age</span><strong>' + years + 'y ' + months + 'm ' + days + 'd</strong></li>' +
          '<li><span>Total months</span><strong>' + (years * 12 + months) + '</strong></li>' +
          '<li><span>Total days lived</span><strong>' + totalDays.toLocaleString('en-IN') + '</strong></li>' +
          '<li><span>Next birthday in</span><strong>' + daysUntilBirthday(dob) + ' days</strong></li>' +
        '</ul>',
        'Calculated from date of birth'
      );
    });
  }

  /* ── Gold Loan ── */
  var goldLoanForm = document.getElementById('goldLoanForm');
  if (goldLoanForm) {
    goldLoanForm.addEventListener('submit', function(e) {
      e.preventDefault();
      var weight = parseFloat(document.getElementById('glWeight').value);
      var purity = parseFloat(document.getElementById('glPurity').value);
      var rate = parseFloat(document.getElementById('glRate').value);
      var ltv = parseFloat(document.getElementById('glLtv').value);
      var makingPct = parseFloat(document.getElementById('glMaking').value) || 0;
      if (!weight || !rate || !ltv) { alert('Please fill all required fields.'); return; }

      var pureWeight = weight * (purity / 100);
      var goldValue = (pureWeight / 10) * rate;
      var makingCharges = goldValue * (makingPct / 100);
      var totalValue = goldValue + makingCharges;
      var loanAmount = totalValue * (ltv / 100);

      setCalcResult(
        '<div class="calc-result-value calc-result-currency">₹' + formatINR(loanAmount) + '</div>' +
        '<div class="calc-result-badge normal">Eligible Loan Amount</div>' +
        '<ul class="calc-result-list">' +
          '<li><span>Gold value</span><strong>₹' + formatINR(goldValue) + '</strong></li>' +
          '<li><span>Making charges (' + makingPct + '%)</span><strong>₹' + formatINR(makingCharges) + '</strong></li>' +
          '<li><span>Total asset value</span><strong>₹' + formatINR(totalValue) + '</strong></li>' +
          '<li><span>LTV @ ' + ltv + '%</span><strong>₹' + formatINR(loanAmount) + '</strong></li>' +
          '<li><span>Weight / Purity</span><strong>' + weight + 'g · ' + purity + '%</strong></li>' +
        '</ul>',
        'Indicative gold loan · rates and LTV vary by lender'
      );
    });
  }

  /* ── Silver Loan ── */
  var silverLoanForm = document.getElementById('silverLoanForm');
  if (silverLoanForm) {
    silverLoanForm.addEventListener('submit', function(e) {
      e.preventDefault();
      var weight = parseFloat(document.getElementById('slWeight').value);
      var purity = parseFloat(document.getElementById('slPurity').value);
      var rate = parseFloat(document.getElementById('slRate').value);
      var ltv = parseFloat(document.getElementById('slLtv').value);
      var makingPct = parseFloat(document.getElementById('slMaking').value) || 0;
      if (!weight || !rate || !ltv) { alert('Please fill all required fields.'); return; }

      var pureWeight = weight * (purity / 100);
      var silverValue = (pureWeight / 1000) * rate;
      var makingCharges = silverValue * (makingPct / 100);
      var totalValue = silverValue + makingCharges;
      var loanAmount = totalValue * (ltv / 100);

      setCalcResult(
        '<div class="calc-result-value calc-result-currency">₹' + formatINR(loanAmount) + '</div>' +
        '<div class="calc-result-badge normal">Eligible Loan Amount</div>' +
        '<ul class="calc-result-list">' +
          '<li><span>Silver value</span><strong>₹' + formatINR(silverValue) + '</strong></li>' +
          '<li><span>Making charges (' + makingPct + '%)</span><strong>₹' + formatINR(makingCharges) + '</strong></li>' +
          '<li><span>Total asset value</span><strong>₹' + formatINR(totalValue) + '</strong></li>' +
          '<li><span>LTV @ ' + ltv + '%</span><strong>₹' + formatINR(loanAmount) + '</strong></li>' +
          '<li><span>Weight / Purity</span><strong>' + weight + 'g · ' + purity + '%</strong></li>' +
        '</ul>',
        'Indicative silver loan · rates and LTV vary by lender'
      );
    });
  }

  /* ── Making Charges ── */
  var makingForm = document.getElementById('makingForm');
  if (makingForm) {
    makingForm.addEventListener('submit', function(e) {
      e.preventDefault();
      var metal = document.getElementById('mcMetal').value;
      var weight = parseFloat(document.getElementById('mcWeight').value);
      var rate = parseFloat(document.getElementById('mcRate').value);
      var chargeType = document.getElementById('mcType').value;
      var chargeValue = parseFloat(document.getElementById('mcValue').value);
      if (!weight || !rate || chargeValue === undefined || isNaN(chargeValue)) {
        alert('Please fill all fields.');
        return;
      }

      var metalValue = metal === 'gold'
        ? (weight * (91.6 / 100) / 10) * rate
        : (weight * (99.9 / 100) / 1000) * rate;

      var makingCharges;
      if (chargeType === 'percent') makingCharges = metalValue * (chargeValue / 100);
      else if (chargeType === 'pergram') makingCharges = weight * chargeValue;
      else makingCharges = chargeValue;

      var totalCost = metalValue + makingCharges;

      setCalcResult(
        '<div class="calc-result-value calc-result-currency">₹' + formatINR(makingCharges) + '</div>' +
        '<div class="calc-result-badge normal">Making Charges</div>' +
        '<ul class="calc-result-list">' +
          '<li><span>Metal type</span><strong>' + (metal === 'gold' ? 'Gold' : 'Silver') + '</strong></li>' +
          '<li><span>Metal value</span><strong>₹' + formatINR(metalValue) + '</strong></li>' +
          '<li><span>Making charges</span><strong>₹' + formatINR(makingCharges) + '</strong></li>' +
          '<li><span>Total cost</span><strong>₹' + formatINR(totalCost) + '</strong></li>' +
          '<li><span>Weight</span><strong>' + weight + ' grams</strong></li>' +
        '</ul>',
        'Making charge calculation for ' + metal + ' jewellery'
      );
    });

    document.getElementById('mcMetal').addEventListener('change', function() {
      document.getElementById('mcRate').value = this.value === 'gold' ? '72000' : '95000';
      document.getElementById('mcRate').placeholder = this.value === 'gold' ? '₹ per 10g' : '₹ per kg';
    });
  }

  showCalculator('loan');
}

function formatINR(num) {
  return Math.round(num).toLocaleString('en-IN');
}

function daysUntilBirthday(dob) {
  var today = new Date();
  var next = new Date(today.getFullYear(), dob.getMonth(), dob.getDate());
  if (next <= today) next.setFullYear(today.getFullYear() + 1);
  return Math.ceil((next - today) / (1000 * 60 * 60 * 24));
}
