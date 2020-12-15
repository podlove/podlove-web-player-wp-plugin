const path = require("path");
const cssClean = require("postcss-clean");
const autoprefixer = require("autoprefixer");
const tailwind = require('tailwindcss');
const { VueLoaderPlugin } = require("vue-loader");

function resolve(dir) {
  return path.join(__dirname, "..", dir);
}

module.exports = {
  entry: {
    "admin/js/app": "./src/configurator/main.js",
    "block/js/block": "./src/block/block.js",
    "js/cache": "./src/plugin/js/cache.js"
  },
  output: {
    path: resolve("dist"),
    filename: "[name].js"
  },
  resolve: {
    extensions: [".js", ".vue", ".json"],
    alias: {
      vue$: "vue/dist/vue.esm.js",
      "@": resolve("src/configurator/components"),
      styles: resolve("src/configurator/styles")
    }
  },
  module: {
    rules: [
      {
        test: /\.vue$/,
        use: "vue-loader"
      },
      {
        test: /\.(js|jsx)$/,
        loader: "babel-loader",
        include: [resolve("src/configurator"), resolve("src/block"), resolve("src/plugin")]
      },
      {
        test: /\.(png|jpe?g|gif|svg)(\?.*)?$/,
        loader: "url-loader"
      },
      {
        test: /\.(mp4|webm|ogg|mp3|wav|flac|aac)(\?.*)?$/,
        loader: "url-loader"
      },
      {
        test: /\.(woff2?|eot|ttf|otf)(\?.*)?$/,
        loader: "url-loader"
      },
      {
        test: /\.css$/,
        use: [
          {
            loader: "vue-style-loader"
          },
          {
            loader: "css-loader"
          }
        ]
      },
      {
        test: /\.scss$/,
        use: [
          {
            loader: "vue-style-loader"
          },
          {
            loader: "css-loader"
          },
          {
            loader: "postcss-loader",
            options: {
              ident: "postcss",
              plugins: [
                cssClean({
                  inline: ["none"]
                }),
                autoprefixer
              ]
            }
          },
          {
            loader: "sass-loader"
          }
        ]
      }, {
        test: /\.postcss$/,
        use: [
          {
            loader: "vue-style-loader"
          },
          {
            loader: "css-loader"
          },
          {
            loader: "postcss-loader",
            options: {
              ident: "postcss",
              plugins: [
                cssClean({
                  inline: ["none"]
                }),
                autoprefixer,
                tailwind()
              ]
            }
          }
        ]
      }
    ]
  },
  plugins: [new VueLoaderPlugin()]
};
