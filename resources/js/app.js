import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Theme toggle functionality
window.toggleTheme = function() {
    const isLight = document.documentElement.classList.toggle('light');
    localStorage.setItem('theme', isLight ? 'light' : 'dark');
    
    // Sync all toggle buttons on the page (by checking all sun and moon icons)
    const sunIcons = document.querySelectorAll('.sunIcon, #sunIcon');
    const moonIcons = document.querySelectorAll('.moonIcon, #moonIcon');
    
    if (isLight) {
        sunIcons.forEach(icon => icon.classList.add('hidden'));
        moonIcons.forEach(icon => icon.classList.remove('hidden'));
    } else {
        sunIcons.forEach(icon => icon.classList.remove('hidden'));
        moonIcons.forEach(icon => icon.classList.add('hidden'));
    }
};

// Sync theme icons on DOM load
document.addEventListener('DOMContentLoaded', () => {
    const isLight = document.documentElement.classList.contains('light');
    const sunIcons = document.querySelectorAll('.sunIcon, #sunIcon');
    const moonIcons = document.querySelectorAll('.moonIcon, #moonIcon');
    
    if (isLight) {
        sunIcons.forEach(icon => icon.classList.add('hidden'));
        moonIcons.forEach(icon => icon.classList.remove('hidden'));
    } else {
        sunIcons.forEach(icon => icon.classList.remove('hidden'));
        moonIcons.forEach(icon => icon.classList.add('hidden'));
    }
});
