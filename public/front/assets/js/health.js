/* JustGoom — Health Assessment Module */
document.addEventListener('DOMContentLoaded', function() {
  if (!document.getElementById('healthForm')) return;

  var form = document.getElementById('healthForm');
  var resultBody = document.getElementById('healthResultBody');
  var resultTitle = document.getElementById('healthResultTitle');

  form.addEventListener('submit', function(e) {
    e.preventDefault();

    var age = parseInt(document.getElementById('healthAge').value, 10);
    var height = parseFloat(document.getElementById('healthHeight').value);
    var weight = parseFloat(document.getElementById('healthWeight').value);
    var gender = form.querySelector('.gender-option.active')?.dataset.gender || 'male';

    if (!age || !height || !weight || age < 1 || height <= 0 || weight <= 0) {
      alert('Please enter valid age, height, and weight.');
      return;
    }

    var heightM = height / 100;
    var bmi = weight / (heightM * heightM);
    var category, cls, tip;

    if (bmi < 18.5) {
      category = 'Underweight'; cls = 'underweight';
      tip = 'Focus on nutrient-dense foods and gradual calorie increase.';
    } else if (bmi < 25) {
      category = 'Normal Weight'; cls = 'normal';
      tip = 'Maintain balanced nutrition and regular physical activity.';
    } else if (bmi < 30) {
      category = 'Overweight'; cls = 'overweight';
      tip = 'Reduce processed foods and increase daily movement.';
    } else {
      category = 'Obese'; cls = 'obese';
      tip = 'Consult a healthcare professional for a structured plan.';
    }

    var diet = getDietPlan(bmi, age, gender);
    var exercise = getExercisePlan(bmi, age);

    if (resultTitle) resultTitle.textContent = 'Your Health Assessment';

    var markerPos = Math.min(Math.max((bmi / 40) * 100, 2), 98);
    resultBody.innerHTML =
      '<div class="health-result-section">' +
        '<div class="calc-result-value">' + bmi.toFixed(1) + '</div>' +
        '<div class="calc-result-badge ' + cls + '">' + category + ' · BMI</div>' +
        '<div class="bmi-scale" style="margin:20px 0;">' +
          '<div class="bmi-scale-bar"><div class="bmi-scale-marker" style="left:' + markerPos + '%;"></div></div>' +
          '<div class="bmi-scale-labels"><span>Under</span><span>Normal</span><span>Over</span><span>Obese</span></div>' +
        '</div>' +
        '<div class="calc-result-tip"><strong>Health Tip:</strong> ' + tip + '</div>' +
      '</div>' +
      '<div class="health-result-section">' +
        '<h4>🥗 Diet Recommendation</h4>' +
        '<ul class="health-rec-list">' + diet.map(function(d) { return '<li>' + d + '</li>'; }).join('') + '</ul>' +
      '</div>' +
      '<div class="health-result-section">' +
        '<h4>🏃 Exercise Recommendation</h4>' +
        '<ul class="health-rec-list">' + exercise.map(function(ex) { return '<li>' + ex + '</li>'; }).join('') + '</ul>' +
      '</div>';

    resultBody.closest('.health-result-card')?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  });

  form.querySelectorAll('.gender-option').forEach(function(opt) {
    opt.addEventListener('click', function() {
      form.querySelectorAll('.gender-option').forEach(function(o) { o.classList.remove('active'); });
      opt.classList.add('active');
    });
  });
});

function getDietPlan(bmi, age, gender) {
  var plans = [];

  if (bmi < 18.5) {
    plans.push('Increase daily calories with whole grains, nuts, and healthy fats.');
    plans.push('Include 3 main meals + 2 protein-rich snacks (eggs, paneer, dal).');
    plans.push('Drink milk or smoothies between meals for extra nutrition.');
  } else if (bmi < 25) {
    plans.push('Balanced plate: 50% vegetables, 25% protein, 25% complex carbs.');
    plans.push('Include seasonal fruits, leafy greens, and lean protein daily.');
    plans.push('Limit sugar, fried foods, and processed snacks.');
  } else if (bmi < 30) {
    plans.push('Reduce refined carbs; choose brown rice, oats, and millets.');
    plans.push('Portion control: use smaller plates and avoid second servings.');
    plans.push('Increase fiber intake with salads, sprouts, and whole grains.');
  } else {
    plans.push('Low-calorie, high-protein diet with medical supervision recommended.');
    plans.push('Avoid sugary drinks, white bread, and deep-fried foods entirely.');
    plans.push('Focus on vegetables, lean protein, and controlled portion sizes.');
  }

  if (age > 50) {
    plans.push('Ensure adequate calcium (milk, yogurt) and vitamin D for bone health.');
  }
  if (gender === 'female' && age >= 18 && age <= 45) {
    plans.push('Include iron-rich foods (spinach, jaggery, lentils) regularly.');
  }

  return plans;
}

function getExercisePlan(bmi, age) {
  var plans = [];

  if (bmi < 18.5) {
    plans.push('Light strength training 3×/week to build muscle mass.');
    plans.push('Walking 20–30 min daily at a comfortable pace.');
    plans.push('Yoga or stretching for flexibility and recovery.');
  } else if (bmi < 25) {
    plans.push('150 min moderate cardio weekly (brisk walking, cycling, swimming).');
    plans.push('Strength training 2×/week for all major muscle groups.');
    plans.push('Include flexibility work — yoga or stretching 2×/week.');
  } else if (bmi < 30) {
    plans.push('Daily 30–45 min brisk walking or low-impact cardio.');
    plans.push('Bodyweight exercises: squats, push-ups, planks — 3×/week.');
    plans.push('Avoid high-impact activities initially; progress gradually.');
  } else {
    plans.push('Start with 15–20 min daily walking; increase by 5 min each week.');
    plans.push('Chair exercises and water aerobics are gentle starting options.');
    plans.push('Consult a physiotherapist before starting intense workouts.');
  }

  if (age > 60) {
    plans.push('Balance exercises (heel-to-toe walk, single-leg stand) to prevent falls.');
  }

  return plans;
}
