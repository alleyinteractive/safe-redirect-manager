const path = require('path');
const paths = require('./paths');

const exclude = [
  /node_modules/,
  /\.min\.js$/,
];

// Loaders used for processing CSS
module.exports.cssLoaders = [
  {
    loader: 'css-loader',
    options: {
      sourceMap: true,
      minimize: {
        autoprefixer: false,
      },
    },
  },
  {
    loader: 'postcss-loader',
    options: {
      sourceMap: true,
      config: {
        path: path.join(paths.config, 'postcss.config.js'),
      },
    },
  },
  {
    loader: 'sass-loader',
    options: {
      sourceMap: true,
    },
  },
];

// Loaders common to all webpack configs
module.exports.defaultLoaders = [
  {
    enforce: 'pre',
    test: /\.js$/,
    exclude,
    use: 'eslint-loader',
  },
  {
    test: /\.js$/,
    exclude,
    use: 'babel-loader',
  },
  {
    test: /\.s?css$/,
    include: paths.appRoot,
    use: [
      'style-loader',
      {
        loader: 'css-loader',
        options: {
          modules: true,
          localIdentName: '[name]__[local]__[hash:base64:5]',
          minimize: {
            autoprefixer: false,
          },
        },
      },
      {
        loader: 'postcss-loader',
        options: {
          config: {
            path: path.join(paths.config, 'postcss.config.js'),
          },
        },
      },
      'sass-loader',
    ],
  },
  {
    test: [
      /\.png$/,
      /\.jpg$/,
      /\.svg$/,
      /\.woff2?$/,
      /\.ttf$/,
    ],
    use: {
      loader: 'url-loader',
      options: {
        limit: 10000,
        name: 'media/[name].[ext]',
      },
    },
  },
  {
    test: [
      /\.eot$/,
      /\.min\.js$/,
    ],
    exclude: [
      /node_modules/,
    ],
    use: {
      loader: 'file-loader',
      options: {
        name: 'media/[name].[ext]',
      },
    },
  },
];
