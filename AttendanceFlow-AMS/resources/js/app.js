import './bootstrap';
import Alpine from 'alpinejs';
import { createIcons, icons } from 'lucide';
import './attendance';
import './justifications';
import './calendar';
import './reports';

window.Alpine = Alpine;
window.createIcons = createIcons;
window.lucideIcons = icons;

// Initialize Lucide icons — call this any time new DOM is rendered
window.initIcons = () => {
    createIcons({ icons });
};

// On first load
document.addEventListener('DOMContentLoaded', () => {
    window.initIcons();
});

// After every Alpine.js component finishes rendering
document.addEventListener('alpine:initialized', () => {
    window.initIcons();
});

Alpine.start();