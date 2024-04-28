const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const CssMinimizerPlugin = require("css-minimizer-webpack-plugin");
const TerserPlugin = require("terser-webpack-plugin");

var path = require("path");

// change these variables to fit your project
const jsPath = "./src";
const cssPath = "./src";
const outputPath = "themes";

const entryPoints = {
  // 'app' is the output name, people commonly use 'bundle'
  // you can have more than 1 entry point
  basic: cssPath + "/basic.scss",
  admin: cssPath + "/admin.scss",
  'no-theme': cssPath + "/no-theme.scss",
};

module.exports = { 
  entry: entryPoints,
  output: {
    path: path.resolve(__dirname, outputPath),
    filename: "[name].js",
    publicPath: "",
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: "[name].css",
    }),

   
  ],
  module: {
    rules: [
      {
        test: /\.s?[c]ss$/i,
        use: [MiniCssExtractPlugin.loader, "css-loader", "sass-loader"],
      },
      {
        test: /\.sass$/i,
        use: [
          MiniCssExtractPlugin.loader,
          "css-loader",
          {
            loader: "sass-loader",
            options: {
              sassOptions: { indentedSyntax: true },
            },
          },
        ],
      },
      {
        test: /\.(gif)$/i,
        use: "url-loader?limit=1024",
      },
    ],
  },
  optimization: {
    minimize: true,
    minimizer: [
      // For webpack@5 you can use the `...` syntax to extend existing minimizers (i.e. `terser-webpack-plugin`), uncomment the next line
      // `...`,
      new CssMinimizerPlugin(),
      new TerserPlugin({ parallel: true }),
    ],
  },
  resolve: {
    fallback: {
      "path": require.resolve("path-browserify")
    }
  }
  // resolve: {
  //   extensions: ['.tsx', '.ts', '.js'],
  // },
};