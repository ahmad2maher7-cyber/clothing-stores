/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Tajawal', 'ui-sans-serif', 'system-ui', 'sans-serif'],
      },
      colors: {
        // نحتفظ بالألوان الافتراضية
        // لكن سنستخدم Gray/Slate فقط
      },
    },
  },
  plugins: [],
}