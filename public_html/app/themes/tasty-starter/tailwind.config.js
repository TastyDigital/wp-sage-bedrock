/** @type {import('tailwindcss').Config} config */

import tailwindForms from '@tailwindcss/forms';

const config = {
  content: ['./app/**/*.php', './resources/**/*.{php,vue,js}'],
  theme: {
    container: {
      center: true,
      padding: '2rem',
    },
    extend: {
      colors: {}, // Extend Tailwind's default colors
    },
  },
  plugins: [
    tailwindForms({
      strategy: 'base', // only generate global styles
    }),
  ],
};

export default config;
