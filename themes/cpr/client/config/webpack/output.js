const paths = require('../paths');
const themePath = require('../themename');

module.exports = function getOutput(mode) {
  switch (mode) {
    case 'development':
      return {
        path: paths.buildRoot,
        publicPath: '//localhost:8080/client/build/',
        filename: 'js/[name].bundle.js',
        chunkFilename: 'js/[name].chunk.js',
      };

    case 'production':
    default:
      return {
        path: paths.buildRoot,
        publicPath: themePath,
        filename: 'js/[name].[chunkhash].bundle.min.js',
        chunkFilename: 'js/[name].[chunkhash].chunk.min.js',
      };
  }
};
