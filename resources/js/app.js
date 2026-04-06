import './bootstrap';

function applyTheme(theme) {
    document.documentElement.classList.toggle('dark', theme === 'dark');
}

document.addEventListener('alpine:init', () => {
    Alpine.store('theme', {
        theme: localStorage.getItem('theme') || 'light',
        toggle() {
            this.theme = this.theme === 'light' ? 'dark' : 'light';
            localStorage.setItem('theme', this.theme);
            applyTheme(this.theme);
        },
    });

    Alpine.store('sidebar', {
        isExpanded: window.innerWidth >= 1280,
        isMobileOpen: false,
        toggleExpanded() {
            this.isExpanded = !this.isExpanded;
        },
        toggleMobileOpen() {
            this.isMobileOpen = !this.isMobileOpen;
        },
    });
});

applyTheme(localStorage.getItem('theme') || 'light');

