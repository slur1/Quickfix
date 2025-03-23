/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./path/to/your/html/**/*.html",  // add the path to your HTML files
    "./node_modules/flowbite/**/*.js", // this line ensures Flowbite components are processed
  ],
  theme: {
    extend: {},
  },
  plugins: [
    require('flowbite/plugin'), // Enables Flowbite components
  ],
};
