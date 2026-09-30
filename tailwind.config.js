/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  darkMode: 'class',
  theme: {
    extend: {
      fontFamily: {
        sans: ['IBM Plex Sans Arabic', 'Cairo', 'system-ui', 'sans-serif'],
        display: ['Playfair Display', 'Cairo', 'serif'],
      },
      colors: {
        forest: {
          50:  '#f0f9f4',
          100: '#dcf0e3',
          200: '#bce0cb',
          300: '#8ec8a8',
          400: '#5aa87e',
          500: '#2d8a5c',
          600: '#1e6b46',
          700: '#175236',
          800: '#12402b',
          900: '#0e3222',
          950: '#071a12',
        },
        gold: {
          50:  '#f0fdfa',
          100: '#ccfbf1',
          200: '#99f6e4',
          300: '#5eead4',
          400: '#2dd4bf',
          500: '#14b8a6',
          600: '#0d9488',
          700: '#0f766e',
          800: '#115e59',
          900: '#134e4a',
        },
        ink: {
          DEFAULT: '#0f0f0f',
          soft: '#3a3a3a',
          muted: '#6b6b5e',
          faint: '#9a9a8c',
        },
        cream: {
          DEFAULT: '#f5f5f0',
          warm: '#faf8f3',
        },
        canvas: '#fefdfb',
      },
      borderRadius: {
        sm: '4px',
        md: '6px',
        lg: '8px',
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}