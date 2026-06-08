/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './templates/**/*.html.twig',
    './assets/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        'vc': {
          'blue-royal': '#0A3BFB',
          'blue': '#0786FF',
          'blue-sky': '#039AF3',
          'mint': '#4EEBA8',
          'dark': '#0D0D1A',
          'dark-alt': '#13132A',
          'text': '#1A1A2E',
          'text-light': '#475569',
          'text-muted': '#64748B',
          'bg': '#F8FAFB',
        },
      },
      fontFamily: {
        'heading': ['Sora', 'sans-serif'],
        'body': ['Plus Jakarta Sans', 'sans-serif'],
      },
    },
  },
  plugins: [],
 
};
