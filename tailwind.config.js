/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './templates/**/*.html.twig',
    './assets/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        'varya': {
          'dark': '#0D0B2E',
          'darker': '#090720',
          'light': '#161345',
          'green': '#5CFFA0',
          'blue-sky': '#4DA8F0',
          'blue': '#3366CC',
          'text': '#FFFFFF',
          'text-secondary': '#B0B0C8',
        },
      },
      fontFamily: {
        'heading': ['Space Grotesk', 'sans-serif'],
        'body': ['Inter', 'sans-serif'],
      },
      backgroundImage: {
        'gradient-varya': 'linear-gradient(135deg, #5CFFA0, #3366CC)',
        'gradient-varya-hover': 'linear-gradient(135deg, #4DE88E, #2B57B3)',
      },
      boxShadow: {
        'glow-green': '0 0 20px rgba(92, 255, 160, 0.3)',
        'glow-blue': '0 0 20px rgba(77, 168, 240, 0.3)',
        'glow-gradient': '0 0 30px rgba(92, 255, 160, 0.2), 0 0 60px rgba(51, 102, 204, 0.1)',
      },
    },
  },
  plugins: [],
};
