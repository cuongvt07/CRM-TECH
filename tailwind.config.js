/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./app/Livewire/**/*.php",
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['"Times New Roman"', 'Times', 'serif'],
        serif: ['"Times New Roman"', 'Times', 'serif'],
      },
      fontSize: {
        'content': '11px',
        'module-sm': '12px',
        'module-md': '13px',
        'module-lg': '15px',
      },
      colors: {
        primary: '#1677FF',
        success: '#52C41A',
        warning: '#FAAD14',
        error: '#FF4D4F',
        neutral: '#8C8C8C',
        surface: '#FFFFFF',
      }
    },
  },
  plugins: [],
}
