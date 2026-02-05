import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Global state management
Alpine.data('globalState', () => ({
    loading: false,
    notifications: [],

    async refreshData() {
        this.loading = true;
        try {
            // Force page refresh to get latest data
            window.location.reload();
        } catch (error) {
            console.error('Error refreshing data:', error);
        } finally {
            this.loading = false;
        }
    },

    async clearCache() {
        try {
            const response = await fetch('/admin/clear-cache', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (response.ok) {
                this.refreshData();
            }
        } catch (error) {
            console.error('Error clearing cache:', error);
        }
    }
}));

Alpine.start();
