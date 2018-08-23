/* eslint-disable import/no-extraneous-dependencies */
const webpack = require('webpack');
const path = require('path');
const paths = require('./paths');

// Plugins
const StylelintPlugin = require('stylelint-webpack-plugin');

// Plugins used in all webpack configs
const defaultPlugins = [
  new webpack.optimize.ModuleConcatenationPlugin(),
  new webpack.NamedModulesPlugin(),
  new StylelintPlugin({
    configFile: path.join(paths.config, 'stylelint.config.js'),
  }),
];

// Plugins used only for `start` and `build` webpack configs
const buildPlugins = defaultPlugins.concat([
  new webpack.NoEmitOnErrorsPlugin(),
  new webpack.optimize.UglifyJsPlugin({
    sourceMap: true,
  }),
  new webpack.ProvidePlugin({
    $: 'jquery',
    jQuery: 'jquery',
  }),
  // Will move all modules used in 2 or more chunks into the commons chunk (must be loaded first)
  new webpack.optimize.CommonsChunkPlugin({
    name: 'common',
    minChunks: 2,
  }),
]);

module.exports = {
  defaultPlugins,
  buildPlugins,
};
