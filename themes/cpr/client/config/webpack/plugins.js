const webpack = require('webpack');
const path = require('path');
const StylelintPlugin = require('stylelint-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CleanPlugin = require('clean-webpack-plugin');
const StatsPlugin = require('webpack-stats-plugin').StatsWriterPlugin;
const paths = require('../paths');
const createWriteWpAssetManifest = require('./wpAssets');

module.exports = function getPlugins(mode) {
  const plugins = [
    new StylelintPlugin({
      configFile: path.join(paths.config, 'stylelint.config.js'),
    }),
    new StatsPlugin({
      transform: createWriteWpAssetManifest(mode),
      fields: ['assetsByChunkName', 'hash'],
      filename: 'assetMap.json',
    }),
    new webpack.ProvidePlugin({
      $: 'jquery',
      jQuery: 'jquery',
    }),
  ];

  switch (mode) {
    case 'development':
      return [
        new webpack.HotModuleReplacementPlugin({
          multiStep: true,
        }),
        ...plugins,
      ];

    case 'production':
    default:
      return [
        new MiniCssExtractPlugin({
          filename: 'css/[name].[contenthash].min.css',
          chunkFilename: 'css/[name].[contenthash].chunk.min.css',
        }),
        ...plugins,
      ];
  }
}
