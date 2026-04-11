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
        get isSidebarForceExpanded() {
            return this.isExpanded || (window.innerWidth < 1280 && this.isMobileOpen);
        },
        toggleExpanded() {
            this.isExpanded = !this.isExpanded;
        },
        toggleMobileOpen() {
            this.isMobileOpen = !this.isMobileOpen;
        },
    });
    window.addEventListener('resize', () => {
        const store = Alpine.store('sidebar');
        if (window.innerWidth >= 1280 && !store.isExpanded && !store.isMobileOpen) {
            store.isExpanded = true;
        } else if (window.innerWidth < 1280 && store.isExpanded) {
            store.isExpanded = false;
        }
    });
});

applyTheme(localStorage.getItem('theme') || 'light');

