/* eslint-disable import/no-extraneous-dependencies */
// Config requires
const os = require('os');
const fs = require('fs');
const path = require('path');
const loaders = require('./loaders');
const { getSingleEntry } = require('./entry');
const entry = require('./entry');
const paths = require('./paths');
const { buildPlugins } = require('./plugins');
const resolve = require('./resolve');
const externals = require('./externals');
const filenames = require('./filenames');
const themename = require('./themename');
const wpAssets = require('../bin/wpAssets');

// Plugins
const LiveReloadPlugin = require('webpack-livereload-plugin');
const ExtractTextPlugin = require('extract-text-webpack-plugin');
const StatsPlugin = require('webpack-stats-plugin').StatsWriterPlugin;

// Livreload settings
const defaultSettings = {
  appendScriptTag: true,
  hostname: 'localhost',
  port: 35728, // so it won't conflict with browser plugin for php files
};
const httpsSettings = {
  protocol: 'https',
  cert: fs.readFileSync(
    path.join(
      os.homedir(),
      'broadway/config/nginx-config/server.crt'
    ),
    'utf8'
  ),
  key: fs.readFileSync(
    path.join(
      os.homedir(),
      'broadway/config/nginx-config/server.key'
    ),
    'utf8'
  ),
};

/**
 * Primary WebPack config.
 */
module.exports = (env) => ({
  entry: Object.assign(
    getSingleEntry(env),
    entry.admin
  ),

  // Plugins array we configured above
  plugins: buildPlugins.concat([
    new LiveReloadPlugin(env.http ?
      defaultSettings :
      Object.assign({}, defaultSettings, httpsSettings)
    ),
    new ExtractTextPlugin(filenames.cssNoHash),
    new StatsPlugin({
      transform: wpAssets,
      fields: ['assetsByChunkName', 'hash'],
      filename: 'assetMap.json',
    }),
  ]),

  devtool: 'cheap-module-eval-source-map',

  // Define module outputs
  output: {
    path: paths.buildRoot,
    publicPath: themename.themePublic,
    filename: filenames.fileNoHash,
    chunkFilename: filenames.chunkNoHash,
  },

  // Where webpack resolves modules
  resolve,

  // Enable require('jquery') where jquery is already a global
  externals,

  // Loaders
  module: {
    rules: [
      {
        test: /\.s?css$/,
        exclude: paths.appRoot,
        use: ExtractTextPlugin.extract({
          fallback: 'style-loader',
          publicPath: paths.extractTextPublic,
          use: loaders.cssLoaders,
        }),
      },
    ].concat(loaders.defaultLoaders),
  },
});
