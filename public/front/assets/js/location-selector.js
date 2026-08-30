(function() {
  'use strict';

  var countrySelect = document.getElementById('locCountry');
  var stateSelect = document.getElementById('locState');
  var citySelect = document.getElementById('locCity');
  var searchBtn = document.getElementById('locSearchBtn');

  if (!countrySelect || !stateSelect || !citySelect) return;

  var apiBase = '/api';

  function fetchJSON(url, callback) {
    fetch(url)
      .then(function(res) { return res.json(); })
      .then(callback)
      .catch(function() { callback([]); });
  }

  function resetSelect(select, placeholder) {
    select.innerHTML = '<option value="">' + placeholder + '</option>';
    select.disabled = true;
  }

  fetchJSON(apiBase + '/countries', function(countries) {
    countries.forEach(function(c) {
      var opt = document.createElement('option');
      opt.value = c.id;
      opt.textContent = c.name;
      countrySelect.appendChild(opt);
    });
  });

  countrySelect.addEventListener('change', function() {
    resetSelect(stateSelect, 'Select State');
    resetSelect(citySelect, 'Select City');
    searchBtn.disabled = true;

    if (!this.value) return;

    fetchJSON(apiBase + '/states/' + this.value, function(states) {
      stateSelect.disabled = false;
      states.forEach(function(s) {
        var opt = document.createElement('option');
        opt.value = s.id;
        opt.textContent = s.name;
        stateSelect.appendChild(opt);
      });
    });
  });

  stateSelect.addEventListener('change', function() {
    resetSelect(citySelect, 'Select City');
    searchBtn.disabled = true;

    if (!this.value) return;

    fetchJSON(apiBase + '/cities/' + this.value, function(cities) {
      citySelect.disabled = false;
      cities.forEach(function(c) {
        var opt = document.createElement('option');
        opt.value = c.id;
        opt.textContent = c.name;
        citySelect.appendChild(opt);
      });
    });
  });

  citySelect.addEventListener('change', function() {
    searchBtn.disabled = !this.value;
  });

  searchBtn.addEventListener('click', function() {
    var city = citySelect.options[citySelect.selectedIndex]?.textContent;
    if (city && city !== 'Select City') {
      window.location.href = '/all-profiles?city=' + encodeURIComponent(city);
    }
  });
})();
