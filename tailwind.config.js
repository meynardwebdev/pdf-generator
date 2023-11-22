/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
      './assets/**/*.{js}}',
      './templates/**/*.{html,twig}',
  ],
  theme: {
    extend: {},
  },
  plugins: [
      require("@tailwindcss/forms")({
          strategy: 'base', // only generate global styles
          strategy: 'class', // only generate classes
      }),
  ],
}

