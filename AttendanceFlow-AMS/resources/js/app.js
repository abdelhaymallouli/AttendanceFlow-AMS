import './bootstrap';
import Alpine from 'alpinejs';
import { createIcons, icons } from 'lucide';
import 'preline';
import './attendance';
import './attendance-mark';
import './justifications';
import './calendar';
import './reports';
import './session-form';
import './teacher-attendance';
import './notifications';
 
window.Alpine = Alpine;
window.createIcons = createIcons;
window.lucideIcons = icons;

// Initialize Lucide icons — call this any time new DOM is rendered
window.initIcons = () => {
    createIcons({ icons });
};

// Initialize Preline UI components (selects, datepickers, dropdowns, etc.)
window.initPreline = () => {
    if (window.HSStaticMethods && typeof window.HSStaticMethods.autoInit === 'function') {
        window.HSStaticMethods.autoInit();
    }
};

// On first load
document.addEventListener('DOMContentLoaded', () => {
    window.initIcons();
    window.initPreline();
});

// After every Alpine.js component finishes rendering
document.addEventListener('alpine:initialized', () => {
    window.initIcons();
    window.initPreline();
});

Alpine.start();