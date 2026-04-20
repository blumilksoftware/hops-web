import defaultTheme from 'tailwindcss/defaultTheme'
import forms from '@tailwindcss/forms'

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
  ],

  theme: {
    extend: {
      fontFamily: {
        sans: ['Figtree', ...defaultTheme.fontFamily.sans],
      },
    },
  },
  colors: {
    hops: {
      darkest:   '#37330C',
      dark:      '#4E4C12',
      mid:       '#6F7A31',
      warm:      '#97933B',
      accent:    '#A6AE34',
      light:     '#F6F6F6',
      ink:       '#1C1C1C',
    },
  },

  plugins: [forms],
}
