const merge = require("webpack-merge");
const baseWebpackConfig = require("./webpack.base.conf");
const OptimizeCSSAssetsPlugin = require("optimize-css-assets-webpack-plugin");

const webpackConfig = merge(baseWebpackConfig, {
  devtool: false,
  optimization: {
    runtimeChunk: {
      name: "runtime"
    },
    splitChunks: {
      cacheGroups: {
        vendor: {
          test(mod) {

            // Only node_modules are needed
            if (mod.context && !mod.context.includes("node_modules")) {
              return false;
            }

            return true;
          },
          name: "vendor",
          chunks: "initial",
          enforce: true
        },
        styles: {
          name: "styles",
          test: /\.(s?css|vue)$/,
          chunks: "all",
          enforce: true,
          minChunks: 1
        }
      }
    }
  },
  plugins: [new OptimizeCSSAssetsPlugin({})]
});

module.exports = webpackConfig;
