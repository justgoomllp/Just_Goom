<script>
  window.FRONT_ROUTES = @json(['allProfiles' => route('front.all-profiles'), 'profileBase' => url('/')]);
  window.FRONT_ASSETS = @json(asset('front/assets/images'));
  window.CATEGORY_BANNER_FILES = @json(\App\Support\ProfileBanner::files());
  @isset($categorySectors)
  window.CATEGORY_SECTORS = @json($categorySectors);
  @endisset
  @isset($companyProfiles)
  window.COMPANY_PROFILES = @json($companyProfiles);
  @endisset
</script>
