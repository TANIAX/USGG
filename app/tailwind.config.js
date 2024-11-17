/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './app/Views/**/*.{php,js}',
  ],
  theme: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}

//C:\Compilation\Autres\USGG\app>tailwindcss.exe -i public/assets/css/input.css -o public/assets/css/output.css --watch