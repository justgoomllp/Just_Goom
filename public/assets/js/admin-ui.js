(function () {
    var body = document.body;
    if (!body.classList.contains('admin-app')) {
        return;
    }

    var fullscreen = document.getElementById('adminFullscreen');
    if (fullscreen) {
        fullscreen.addEventListener('click', function (event) {
            event.preventDefault();
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
            } else if (document.exitFullscreen) {
                document.exitFullscreen();
            }
        });
    }

    var sidebar = document.getElementById('sidebar');
    var menuToggle = document.getElementById('adminMenuToggle');
    var sidebarBackdrop = document.getElementById('adminSidebarBackdrop');

    function isMobileNav() {
        return window.matchMedia('(max-width: 991.98px)').matches;
    }

    function closeMobileSidebar() {
        body.classList.remove('admin-sidebar-open');
        if (sidebar) {
            sidebar.classList.remove('active');
        }
        if (sidebarBackdrop) {
            sidebarBackdrop.hidden = true;
        }
    }

    function openMobileSidebar() {
        body.classList.add('admin-sidebar-open');
        if (sidebar) {
            sidebar.classList.add('active');
        }
        if (sidebarBackdrop) {
            sidebarBackdrop.hidden = false;
        }
    }

    if (menuToggle) {
        menuToggle.addEventListener('click', function (event) {
            event.preventDefault();
            if (isMobileNav()) {
                if (body.classList.contains('admin-sidebar-open')) {
                    closeMobileSidebar();
                } else {
                    openMobileSidebar();
                }
                return;
            }

            closeMobileSidebar();
            body.classList.toggle('sidebar-icon-only');
            document.querySelectorAll('.sidebar-cta').forEach(function (cta) {
                cta.classList.toggle('cta-hide');
            });
        });
    }

    if (sidebarBackdrop) {
        sidebarBackdrop.addEventListener('click', closeMobileSidebar);
    }

    if (sidebar) {
        sidebar.querySelectorAll('a.nav-link').forEach(function (link) {
            link.addEventListener('click', function () {
                if (isMobileNav()) {
                    closeMobileSidebar();
                }
            });
        });
    }

    window.addEventListener('resize', function () {
        if (!isMobileNav()) {
            closeMobileSidebar();
        }
    });

    document.addEventListener('change', function (event) {
        var input = event.target;
        if (!input.classList.contains('admin-status-toggle-input')) {
            return;
        }
        if (input.disabled || !input.form) {
            return;
        }

        if (input.form.closest('table.admin-datatable')) {
            return;
        }

        input.form.submit();
    });

    function syncSidebarActive() {
        var sidebar = document.getElementById('sidebar');
        if (!sidebar) {
            return;
        }

        var path = window.location.pathname.replace(/\/+$/, '') || '/';

        sidebar.querySelectorAll('.nav > .nav-item').forEach(function (item) {
            if (item.classList.contains('sidebar-category')) {
                return;
            }

            var link = item.querySelector('a.nav-link');
            if (!link) {
                return;
            }

            var href = link.getAttribute('href');
            if (!href || href === '#') {
                item.classList.remove('active');
                link.classList.remove('active');
                return;
            }

            var linkPath;
            try {
                linkPath = new URL(href, window.location.origin).pathname.replace(/\/+$/, '');
            } catch (e) {
                return;
            }

            var isActive = path === linkPath || path.indexOf(linkPath + '/') === 0;
            item.classList.toggle('active', isActive);
            link.classList.toggle('active', isActive);
        });
    }

    syncSidebarActive();
    window.addEventListener('load', syncSidebarActive);
})();
