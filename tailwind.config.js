/** @type {import('tailwindcss').Config} */
export default {
  content: ['./public/**/*.php', './resources/views/**/*.php', './admin/**/*.php', './app/**/*.php', './install.php'],
  safelist: ['accent-yellow','accent-green','accent-purple','accent-orange','flash-success','flash-error','badge-green','badge-orange','badge-gray'],
  theme: { extend: {
    colors: { brand: { 50: '#fff7f1', 100: '#ffede2', 200: '#ffd4b5', 500: '#f45b12', 600: '#dd4806', 700: '#b93400' }, ink: '#202128', muted: '#686974', peach: '#fff0e8' },
    fontFamily: { sans: ['Inter', 'Arial', 'sans-serif'], display: ['Inter', 'Arial', 'sans-serif'] },
    boxShadow: { card: '0 5px 30px rgba(32,33,40,.055)', lift: '0 14px 40px rgba(32,33,40,.09)' },
    borderRadius: { '4xl': '2rem' },
    maxWidth: { site: '1160px' }
  } },
  plugins: []
};
