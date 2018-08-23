/* eslint-disable import/no-extraneous-dependencies */
// Plugin imports
const lost = require('lost');
const autoprefixer = require('autoprefixer');

// Config
module.exports = () => ({
  plugins: [
    lost(),
    autoprefixer(),
  ],
});
