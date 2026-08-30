@php
  $departmentOptions = ['Sales', 'Operations', 'Wholesale', 'Customer Support', 'Management'];
  $savedDepartment = $currentDepartment ?? '';
  $isOtherDepartment = filled($savedDepartment) && ! in_array($savedDepartment, $departmentOptions, true);
  $departmentSelectValue = old('department_select', $isOtherDepartment ? '__other__' : $savedDepartment);
  $departmentOtherValue = old('department_other', $isOtherDepartment ? $savedDepartment : '');
@endphp
<div class="user-form-group" data-field="department">
  <label>Department</label>
  <select name="department_select" id="teamDepartmentSelect" class="user-form-control @error('department') is-invalid @enderror @error('department_other') is-invalid @enderror">
    <option value="">Select department</option>
    @foreach($departmentOptions as $option)
      <option value="{{ $option }}" @selected($departmentSelectValue === $option)>{{ $option }}</option>
    @endforeach
    <option value="__other__" @selected($departmentSelectValue === '__other__')>Other</option>
  </select>
  <input
    type="text"
    name="department_other"
    id="teamDepartmentOther"
    class="user-form-control @error('department') is-invalid @enderror @error('department_other') is-invalid @enderror"
    value="{{ $departmentOtherValue }}"
    placeholder="Enter department name"
    style="margin-top:8px;{{ $departmentSelectValue === '__other__' ? '' : 'display:none;' }}"
  >
  <small class="user-field-error">@error('department'){{ $message }}@enderror @error('department_other'){{ $message }}@enderror</small>
</div>

@once
@push('scripts')
<script>
  (function () {
    var select = document.getElementById('teamDepartmentSelect');
    var otherInput = document.getElementById('teamDepartmentOther');
    if (!select || !otherInput) return;

    function toggleDepartmentOther() {
      var showOther = select.value === '__other__';
      otherInput.style.display = showOther ? '' : 'none';
      otherInput.required = showOther;
      if (!showOther) otherInput.value = '';
    }

    select.addEventListener('change', toggleDepartmentOther);
    toggleDepartmentOther();
  })();
</script>
@endpush
@endonce
