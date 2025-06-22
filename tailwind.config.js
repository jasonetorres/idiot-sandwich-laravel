/** @type {import('tailwindcss').Config} */
export default {
    content: [
      "./resources/**/*.blade.php",
      "./resources/**/*.js",
    ],
    theme: {
      extend: {
          keyframes: {
              'fiery-flash': {
                  '0%, 100%': { backgroundColor: 'rgba(153, 27, 27, 0.4)', borderColor: '#dc2626' },
                  '50%': { backgroundColor: 'rgba(239, 68, 68, 0.6)', borderColor: '#ef4444' },
              },
              'golden-glow': {
                  '0%, 100%': { boxShadow: '0 0 25px 8px rgba(252, 211, 77, 0.4)' },
                  '50%': { boxShadow: '0 0 45px 18px rgba(252, 211, 77, 0.6)' },
              },
              'fade-in': {
                  '0%': { opacity: '0', transform: 'scale(0.95) translateY(20px)' },
                  '100%': { opacity: '1', transform: 'scale(1) translateY(0)' },
              }
          },
          animation: {
              'fiery-flash': 'fiery-flash 1.5s ease-in-out 2',
              'golden-glow': 'golden-glow 1.5s ease-in-out infinite alternate',
              'fade-in': 'fade-in 0.6s ease-out forwards',
          }
      },
    },
    plugins: [],
  }