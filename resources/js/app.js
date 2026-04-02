import './bootstrap';

// Alpine.js is automatically bundled with Livewire 3.
document.addEventListener('alpine:init', () => {
    Alpine.store('sidebar', {
        open: true,
        toggle() {
            this.open = !this.open;
        }
    });
});
