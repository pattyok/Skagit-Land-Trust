const autoprefixer = require("autoprefixer");
const MiniCSSExtractPlugin = require("mini-css-extract-plugin");
const OptimizeCSSAssetsPlugin = require("optimize-css-assets-webpack-plugin");
const TerserPlugin = require("terser-webpack-plugin");
const CleanPlugin = require("clean-webpack-plugin");
const ExtractTextPlugin = require("extract-text-webpack-plugin");

module.exports = (env, argv) => {
    function isDevelopment() {
        return argv.mode === "development";
    }
    var config = {
        entry: {
            editor: "./src/editor.js",
            script: "./src/script.js",
            plugins_editor: "./src/plugins_editor.js"
        },
        output: {
            filename: "[name].js"
        },
        optimization: {
            minimizer: [
                new TerserPlugin({
                    sourceMap: true
                }),
                new OptimizeCSSAssetsPlugin({
                    cssProcessorOptions: {
                        map: {
                            inline: false,
                            annotation: true
                        }
                    }
                })
            ]
        },
        plugins: [
            new CleanPlugin(),
            new MiniCSSExtractPlugin({
                chunkFilename: "[id].css",
                filename: chunkData => {
                    return chunkData.chunk.name === "script"
                        ? "style.css"
                        : "[name].css";
                }
            })
        ],
        devtool: isDevelopment() ? "cheap-module-source-map" : "source-map",
        module: {
            rules: [
                {
                    test: /\.js$/,
                    exclude: /node_modules/,
                    use: [
                        {
                            loader: "babel-loader",
                            options: {
                                plugins: [
                                    "@babel/plugin-proposal-class-properties"
                                ],
                                presets: [
                                    "@babel/preset-env",
                                    [
                                        "@babel/preset-react",
                                        {
                                            pragma: "wp.element.createElement",
                                            pragmaFrag: "wp.element.Fragment",
                                            development: isDevelopment()
                                        }
                                    ]
                                ]
                            }
                        },
                        "eslint-loader"
                    ]
                },
                {
                    test: /\.(sa|sc|c)ss$/,
                    use: [
                        MiniCSSExtractPlugin.loader,
                        "css-loader",
                        {
                            loader: "postcss-loader",
                            options: {
                                plugins: [autoprefixer()]
                            }
                        },
                        "resolve-url-loader",
                        "sass-loader"
                    ]
                },
                {
                    test: /\.(woff(2)?|ttf|eot)(\?v=\d+\.\d+\.\d+)?$/,
                    use: [
                      {
                        loader: 'file-loader',
                        options: {
                          name: '[name].[ext]',
                          outputPath: 'fonts/',
                          esModule: false
                        }
                      }
                    ]
                  },
                    {
                    test: /\.(jpg|png|gif|svg)$/,
                    use: {
                        loader: 'file-loader',
                        options: {
                        name: "[name].[ext]",
                        outputPath: "img",
                        esModule: false
                        }
                    },
                    },


            ]
        },
        externals: {
            jquery: "jQuery",
            lodash: "lodash",
            popup: "reactjs-popup",
            slideshow: "react-slideshow-image",
            "@wordpress/blocks": ["wp", "blocks"],
            "@wordpress/i18n": ["wp", "i18n"],
            "@wordpress/editor": ["wp", "editor"],
            "@wordpress/block-editor": ["wp", "blockEditor"],
            "@wordpress/components": ["wp", "components"],
            "@wordpress/element": ["wp", "element"],
            "@wordpress/blob": ["wp", "blob"],
            "@wordpress/data": ["wp", "data"],
            "@wordpress/html-entities": ["wp", "htmlEntities"],
            "@wordpress/compose": ["wp", "compose"],
            "@wordpress/plugins": ["wp", "plugins"],
            "@wordpress/edit-post": ["wp", "editPost"],
            "@wordpress/icons": ["wp", "icons"],
            "@wordpress/keycodes": ["wp", "keycodes"]
        }
    };
    return config;
};
