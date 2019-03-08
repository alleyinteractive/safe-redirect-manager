const path = require('path');
const paths = require('../paths');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

const exclude = [
  /node_modules/,
  /\.min\.js$/,
];
const cssUse = [
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
const defaultRules = [
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

module.exports = function getEntry(mode) {
  switch (mode) {
    case 'development':
      return [
        {
          test: /\.s?css$/,
          exclude: paths.appRoot,
          use: [
            'style-loader',
            ...cssUse
          ],
        },
        ...defaultRules,
      ];

    case 'production':
    default:
      return [
        {
          test: /\.s?css$/,
          exclude: paths.appRoot,
          use: [
            {
              loader: MiniCssExtractPlugin.loader,
              options: {
                publicPath: '../',
              },
            },
            ...cssUse,
          ],
        },
        ...defaultRules,
      ];
  }
};
