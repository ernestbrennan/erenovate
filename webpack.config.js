const path = require('path');

module.exports = {
  resolve: {
    extensions: ['.js', '.vue', '.json'],
    alias: {
      '@': path.join(__dirname, '/resources/js-main'),
      '$scss': path.join(__dirname, '/resources/sass'),
    },
  },
};

