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
})();
