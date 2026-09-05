(function () {
    var body = document.body;
    if (!body.classList.contains('admin-app')) {
        return;
    }

    if (localStorage.getItem('admin-theme') === 'dark') {
        body.classList.add('admin-dark');
    }

    var themeToggle = document.getElementById('adminThemeToggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', function (event) {
            event.preventDefault();
            body.classList.toggle('admin-dark');
            localStorage.setItem('admin-theme', body.classList.contains('admin-dark') ? 'dark' : 'light');
        });
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

    document.querySelectorAll('.admin-status-form .admin-status-toggle-input').forEach(function (input) {
        input.addEventListener('change', function () {
            if (input.disabled || !input.form) {
                return;
            }

            input.form.submit();
        });
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
