// Staff Sidebar Toggle & Header Adjustments
window.toggleSidebar = function () {
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('mainContent');
    const header = document.getElementById('mainHeader');
    const texts = document.querySelectorAll('.sidebar-text');
    const title = document.getElementById('sidebarTitle');

    sidebar.classList.toggle('sidebar-expanded');
    sidebar.classList.toggle('sidebar-collapsed');

    content.classList.toggle('content-expanded');
    content.classList.toggle('content-collapsed');

    if (header) {
        header.classList.toggle('header-expanded');
        header.classList.toggle('header-collapsed');
    }

    // Header shadow on scroll
    window.addEventListener('scroll', () => {
        if (window.scrollY > 10) {
            header?.classList.add('shadow-lg');
        } else {
            header?.classList.remove('shadow-lg');
        }
    });

    // Adjust header position when sidebar collapses
    if (sidebar.classList.contains('sidebar-collapsed')) {
        header?.classList.remove('left-64');
        header?.classList.add('left-20');
    } else {
        header?.classList.remove('left-20');
        header?.classList.add('left-64');
    }

    // Show/hide sidebar text
    texts.forEach(el => {
        el.style.display = sidebar.classList.contains('sidebar-collapsed') ? 'none' : 'inline';
    });

    if (title) {
        title.style.display = sidebar.classList.contains('sidebar-collapsed') ? 'none' : 'block';
    }
};

// Staff Submenu Toggle (Accordion)
document.addEventListener('DOMContentLoaded', function () {
    const toggles = document.querySelectorAll('.submenu-toggle');

    toggles.forEach(btn => {
        btn.addEventListener('click', function () {
            const targetId = btn.dataset.target;
            if (!targetId) return;
            const menu = document.getElementById(targetId);
            if (!menu) return;

            // Accordion behavior: close other menus
            document.querySelectorAll('ul[id$="Menu"]').forEach(ul => {
                if (ul.id !== targetId) {
                    ul.classList.add('hidden');
                    const c = ul.previousElementSibling?.querySelector('.caret');
                    if (c) c.classList.remove('rotate-180');
                }
            });

            // Toggle current menu
            menu.classList.toggle('hidden');

            // Rotate caret inside the button
            const caret = btn.querySelector('.caret');
            if (caret) caret.classList.toggle('rotate-180');
        });
    });
});
