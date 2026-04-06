import './bootstrap';

import 'preline';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// Lucide icons initialization for dynamic content or Alpine components
import { createIcons, icons } from 'lucide';

document.addEventListener('DOMContentLoaded', () => {
    createIcons({ icons });
});

// Preline re-initialization for SPA-like navigation if needed (e.g. Livewire)
document.addEventListener('livewire:navigated', () => {
    window.HSStaticMethods.autoInit();
});
