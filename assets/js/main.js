/* JustGoom Front — Main JS */
document.addEventListener('DOMContentLoaded', () => {
  initMobileNav();
  initTabs();
  initProfileTabs();
  initTeamFilter();
  initGallery();
  initHealthCalculator();
  initContactForm();
});

function initMobileNav() {
  const toggle = document.querySelector('.mobile-toggle');
  const overlay = document.querySelector('.mobile-nav');
  const closeBtn = document.querySelector('.mobile-nav-close');

  if (!toggle || !overlay) return;

  toggle.addEventListener('click', () => overlay.classList.add('open'));
  closeBtn?.addEventListener('click', () => overlay.classList.remove('open'));
  overlay.addEventListener('click', (e) => {
    if (e.target === overlay) overlay.classList.remove('open');
  });
}

function initTabs() {
  const tabNavs = document.querySelectorAll('.tab-nav');

  tabNavs.forEach(nav => {
    const buttons = nav.querySelectorAll('button');
    const container = nav.closest('.detail-tabs');
    if (!container) return;

    const panes = container.querySelectorAll('.tab-pane');

    buttons.forEach((btn, i) => {
      btn.addEventListener('click', () => {
        buttons.forEach(b => b.classList.remove('active'));
        panes.forEach(p => p.classList.remove('active'));
        btn.classList.add('active');
        panes[i]?.classList.add('active');
      });
    });
  });
}

function initProfileTabs() {
  const tabNav = document.getElementById('profileTabs');
  if (!tabNav) return;

  const buttons = tabNav.querySelectorAll('button[data-tab]');
  const panes = document.querySelectorAll('.profile-tab-pane');

  buttons.forEach(btn => {
    btn.addEventListener('click', () => {
      const tab = btn.dataset.tab;
      buttons.forEach(b => b.classList.remove('active'));
      panes.forEach(p => p.classList.remove('active'));
      btn.classList.add('active');
      document.querySelector(`.profile-tab-pane[data-pane="${tab}"]`)?.classList.add('active');
    });
  });
}

function initTeamFilter() {
  const filter = document.querySelector('.team-time-filter');
  if (!filter) return;

  filter.querySelectorAll('button').forEach(btn => {
    btn.addEventListener('click', () => {
      filter.querySelectorAll('button').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
    });
  });
}

function initGallery() {
  const mainImg = document.querySelector('.gallery-main img');
  const thumbs = document.querySelectorAll('.gallery-thumb');

  if (!mainImg || !thumbs.length) return;

  thumbs.forEach(thumb => {
    thumb.addEventListener('click', () => {
      const src = thumb.querySelector('img')?.src;
      if (src) mainImg.src = src;
      thumbs.forEach(t => t.classList.remove('active'));
      thumb.classList.add('active');
    });
  });
}

function initHealthCalculator() {
  const form = document.getElementById('bmiForm');
  if (!form) return;

  const heightInput = document.getElementById('height');
  const weightInput = document.getElementById('weight');
  const bmiValue = document.getElementById('bmiValue');
  const bmiCategory = document.getElementById('bmiCategory');
  const bmiMarker = document.getElementById('bmiMarker');
  const bmiTips = document.getElementById('bmiTips');
  const unitBtns = document.querySelectorAll('.unit-toggle button');
  const genderOptions = document.querySelectorAll('.gender-option');
  const heightLabel = document.getElementById('heightLabel');
  const weightLabel = document.getElementById('weightLabel');

  let unit = 'metric';

  unitBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      unitBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      unit = btn.dataset.unit;
      heightLabel.textContent = unit === 'metric' ? 'Height (cm)' : 'Height (inches)';
      weightLabel.textContent = unit === 'metric' ? 'Weight (kg)' : 'Weight (lbs)';
      heightInput.placeholder = unit === 'metric' ? 'e.g. 170' : 'e.g. 67';
      weightInput.placeholder = unit === 'metric' ? 'e.g. 70' : 'e.g. 154';
      heightInput.value = '';
      weightInput.value = '';
      resetResult();
    });
  });

  genderOptions.forEach(opt => {
    opt.addEventListener('click', () => {
      genderOptions.forEach(o => o.classList.remove('active'));
      opt.classList.add('active');
    });
  });

  form.addEventListener('submit', (e) => {
    e.preventDefault();
    calculateBMI();
  });

  function calculateBMI() {
    let height = parseFloat(heightInput.value);
    let weight = parseFloat(weightInput.value);

    if (!height || !weight || height <= 0 || weight <= 0) {
      alert('Please enter valid height and weight values.');
      return;
    }

    if (unit === 'imperial') {
      height = height * 2.54;
      weight = weight * 0.453592;
    }

    const heightM = height / 100;
    const bmi = weight / (heightM * heightM);
    const rounded = bmi.toFixed(1);

    bmiValue.textContent = rounded;

    let category, tip, colorClass;
    if (bmi < 18.5) {
      category = 'Underweight';
      colorClass = 'underweight';
      tip = 'Your BMI is below the healthy range. Consider consulting a nutritionist for a balanced diet plan.';
    } else if (bmi < 25) {
      category = 'Normal Weight';
      colorClass = 'normal';
      tip = 'Great! Your BMI is in the healthy range. Maintain a balanced diet and regular exercise.';
    } else if (bmi < 30) {
      category = 'Overweight';
      colorClass = 'overweight';
      tip = 'Your BMI is above the healthy range. Regular exercise and a healthy diet can help.';
    } else {
      category = 'Obese';
      colorClass = 'obese';
      tip = 'Your BMI indicates obesity. We recommend consulting a healthcare professional for guidance.';
    }

    bmiCategory.textContent = category;
    bmiCategory.className = 'bmi-category ' + colorClass;
    bmiTips.innerHTML = '<h4>Health Tip</h4><p>' + tip + '</p>';

    const markerPos = Math.min(Math.max((bmi / 40) * 100, 2), 98);
    bmiMarker.style.left = markerPos + '%';
  }

  function resetResult() {
    bmiValue.textContent = '--';
    bmiCategory.textContent = 'Enter details';
    bmiCategory.className = 'bmi-category';
    bmiTips.innerHTML = '<h4>Health Tip</h4><p>Fill in your details and click Calculate to see your BMI result.</p>';
    bmiMarker.style.left = '0%';
  }
}

function initContactForm() {
  const form = document.getElementById('contactForm');
  if (!form) return;

  form.addEventListener('submit', (e) => {
    e.preventDefault();
    alert('Thank you! Your message has been sent. We will contact you soon.');
    form.reset();
  });
}
