import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** Stitch "Technical Architect" blueprint tokens (from .cursor/stitch exports) */
const stitchColors = {
    'inverse-on-surface': '#9b9d9e',
    tertiary: '#4b6274',
    outline: '#737c7f',
    'outline-variant': '#abb3b7',
    'primary-fixed-dim': '#b7dae6',
    'error-dim': '#4e0309',
    'on-secondary-fixed': '#3e4041',
    'primary-container': '#c5e8f5',
    error: '#9f403d',
    'error-container': '#fe8983',
    'on-primary': '#edfaff',
    'on-tertiary-fixed-variant': '#485e71',
    'on-secondary-fixed-variant': '#5a5c5d',
    'on-secondary-container': '#505253',
    'secondary-fixed': '#e2e2e3',
    'secondary-fixed-dim': '#d4d4d5',
    'inverse-primary': '#c2e6f2',
    surface: '#f8f9fa',
    'on-tertiary-container': '#3e5466',
    'on-tertiary-fixed': '#2b4253',
    'surface-dim': '#d1dce0',
    'secondary-dim': '#515354',
    'surface-container-highest': '#dbe4e7',
    'tertiary-fixed': '#cee5fc',
    'primary-dim': '#355761',
    background: '#f8f9fa',
    'on-secondary': '#f9f9fa',
    'on-primary-container': '#355761',
    secondary: '#5d5f60',
    'primary-fixed': '#c5e8f5',
    'surface-tint': '#42636e',
    'on-background': '#2b3437',
    'surface-variant': '#dbe4e7',
    'on-primary-fixed-variant': '#3f606b',
    'tertiary-container': '#cee5fc',
    'on-error': '#fff7f6',
    'tertiary-dim': '#3f5668',
    'on-error-container': '#752121',
    'inverse-surface': '#0c0f10',
    'on-tertiary': '#f6f9ff',
    'on-surface-variant': '#586064',
    'surface-container-high': '#e3e9ec',
    'surface-container-lowest': '#ffffff',
    'on-primary-fixed': '#21444e',
    'tertiary-fixed-dim': '#c0d7ed',
    primary: '#42636e',
    'surface-container': '#eaeff1',
    'secondary-container': '#e2e2e3',
    'on-surface': '#2b3437',
    'surface-container-low': '#f1f4f6',
    'surface-bright': '#f8f9fa',
};

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: stitchColors,
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                headline: ['Inter', ...defaultTheme.fontFamily.sans],
                body: ['Inter', ...defaultTheme.fontFamily.sans],
                label: ['"Space Grotesk"', ...defaultTheme.fontFamily.sans],
                mono: ['JetBrains Mono', ...defaultTheme.fontFamily.mono],
            },
            borderRadius: {
                DEFAULT: '0px',
                lg: '0px',
                xl: '0px',
                full: '9999px',
            },
            transitionDuration: {
                blueprint: '150ms',
            },
        },
    },

    plugins: [forms],
};
