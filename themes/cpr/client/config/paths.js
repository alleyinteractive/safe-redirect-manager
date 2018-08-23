const path = require('path');

module.exports = {
  themeRoot: path.join(__dirname, '../../'),
  appRoot: path.join(__dirname, '../src/js'),
  styleRoot: path.join(__dirname, '../src/scss'),
  sourceRoot: path.join(__dirname, '../src'),
  buildRoot: path.join(__dirname, '../build'),
  // This value should be a relative path from `client/build/css` to `client/build/media`
  // (or whatever directory is configured for url-loader in `./loaders.js`)
  extractTextPublic: '../',
  config: __dirname,
};
