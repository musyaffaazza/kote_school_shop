module.exports = {
  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php'
  ],
  safelist: [
    'px-8','py-4','pl-12','pr-12','min-h-[56px]',
    'rounded-card','rounded-input','bg-primary','shadow-card','shadow-btn'
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Montserrat', 'ui-sans-serif', 'system-ui']
      },
      colors: {
        primary: '#3d2a1f',
        'primary-dark': '#2f1f17',
        surface: '#f6f0eb',
        ink: '#19181a',
        muted: '#57534e',
        border: '#e3dcd5',
        background: '#fcfaf8'
      },
      borderRadius: {
        'card': '2rem',
        'input': '1.5rem'
      },
      boxShadow: {
        'card': '0 35px 80px rgba(15,23,42,0.1)',
        'btn': '0 16px 30px rgba(33,20,11,0.22)'
      },
      spacing: {
        '14': '3.5rem'
      }
    }
  },
  plugins: []
}
