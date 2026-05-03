/**
 * ---------------------------------------
 * resources/js/app.js
 * ---------------------------------------
 * Main JS entry file (loaded by Vite).
 * It imports bootstrap, sets up Echo + Pusher
 * and is used in your Blade via @vite().
 */

import './bootstrap'; // loads Laravel Echo, axios, etc.

import '../css/app.css'; // Tailwind styles (optional but common)

// Theme Management handled by Alpine.js component below

// Alpine.js Theme Component
document.addEventListener('alpine:init', () => {
    Alpine.data('themeToggle', () => ({
        theme: localStorage.getItem('theme') || 'light',

        init() {
            this.applyTheme();
        },

        toggleTheme() {
            this.theme = this.theme === 'light' ? 'dark' : 'light';
            this.applyTheme();
        },

        applyTheme() {
            document.documentElement.setAttribute('data-theme', this.theme);
            localStorage.setItem('theme', this.theme);
        }
    }));

    // Mobile Menu Component
    Alpine.data('mobileMenu', () => ({
        isOpen: false,
        theme: localStorage.getItem('theme') || 'light',

        init() {
            this.applyTheme();
        },

        toggle() {
            this.isOpen = !this.isOpen;
            this.updateBodyScroll();
        },

        close() {
            this.isOpen = false;
            this.updateBodyScroll();
        },

        updateBodyScroll() {
            if (this.isOpen) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        },

        toggleTheme() {
            this.theme = this.theme === 'light' ? 'dark' : 'light';
            this.applyTheme();
        },

        applyTheme() {
            document.documentElement.setAttribute('data-theme', this.theme);
            localStorage.setItem('theme', this.theme);
        }
    }));
});
