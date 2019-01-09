const path = require('path');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const WebpackManifestPlugin = require('webpack-manifest-plugin');

module.exports = {
  entry: {
    "debug-wp-enqueued-library": [
      './resources/scripts/debug-wp-enqueued-library.js'
    ],
  },
  plugins: [
    new CleanWebpackPlugin(),
    new WebpackManifestPlugin(),
  ],
  output: {
    filename: `scripts${path.sep}[name]_[hash].js`,
    path: path.resolve(__dirname, 'dist')
  }
};
