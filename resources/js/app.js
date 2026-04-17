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

    Alpine.store('confirm', {
        show: false,
        title: 'Konfirmasi',
        message: 'Apakah Anda yakin ingin melakukan tindakan ini?',
        confirmText: 'Ya, Lanjutkan',
        cancelText: 'Batal',
        variant: 'danger', // danger, warning, primary
        callback: null,

        open(options) {
            this.title = options.title || 'Konfirmasi';
            this.message = options.message || 'Apakah Anda yakin?';
            this.confirmText = options.confirmText || 'Ya, Lanjutkan';
            this.cancelText = options.cancelText || 'Batal';
            this.variant = options.variant || 'danger';
            this.callback = options.onConfirm || null;
            this.show = true;
        },

        confirm() {
            if (this.callback) this.callback();
            this.show = false;
        },

        close() {
            this.show = false;
        }
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

