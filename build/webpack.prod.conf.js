const merge = require("webpack-merge");
const baseWebpackConfig = require("./webpack.base.conf");

const webpackConfig = merge(baseWebpackConfig, {
  devtool: false,
  mode: 'production'
});

module.exports = webpackConfig;
