window.addEventListener('DOMContentLoaded', function () {
    var sidebarToggle = document.body.querySelector('#sidebarToggle');

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function (event) {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
        });
    }
});
