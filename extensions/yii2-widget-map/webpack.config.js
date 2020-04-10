const {resolve} = require('path');
const webpack = require('webpack');
const AssetsPlugin = require('assets-webpack-plugin')

module.exports = {
    entry: {
        'main': [
            resolve(__dirname, 'src/index.js')
        ],
        'react-maps': [
            'react-maps'
        ]
    },
    output: {
        filename: '[name].js',
        path: resolve(__dirname, 'dist/js'),
        publicPath: '/'
    },

    // devtool: 'inline-source-map',
    devtool: 'source-map',

    externals: {
        react: 'React',
        'react-dom': 'ReactDOM',
        leaflet: 'L',
        lodash: '_',
        jquery: 'jQuery',
        moment: 'moment',
        '@turf/turf': 'turf'
    },

    resolve: {
        alias: {
            'react-base': resolve(__dirname, '../../../MAPS/Base'),
            'react-utils': resolve(__dirname, '../../../MAPS/modules/react-utils'),
            'react-maps': resolve(__dirname, '../../../MAPS/modules/react-maps'),
            'leaflet-measure': resolve(__dirname, '../../../MAPS/vendor/leaflet-measure'),
        },
    },

    module: {
        rules: [
            {
                test: /\.jsx?$/,
                use: ['babel-loader'],
                exclude: /node_modules/,
            },
            {
                test: /\.css$/,
                use: [ 'style-loader', 'css-loader' ]
            },
            {
                test: /\.(png|jpg|gif)$/,
                loader: "file-loader",
                options: {
                    name: 'images/[hash].[ext]',
                    limit: 10000,
                    outputPath: '../css/'
                }
            },
            {
                test: /\.(css|scss)$/,
            },
            {
                test: /\.svg(\?v=\d+\.\d+\.\d+)?$/,
                loader: "url-loader?limit=10000&mimetype=image/svg+xml"
            },
        ],
    },

    plugins: [
        new AssetsPlugin(),
        new webpack.DefinePlugin({
            'process.env': {
                NODE_ENV: JSON.stringify(process.env.NODE_ENV || 'development'),
            },
        }),

        new webpack.optimize.UglifyJsPlugin({
            sourceMap: true,
            minimize: true,
            comments: false,
            compress: {
                warnings: false
            }
        }),

        // new webpack.HotModuleReplacementPlugin(),
        // new webpack.NamedModulesPlugin(),
        // new webpack.NoEmitOnErrorsPlugin(),
    ],

    // devServer: {
    //     host: 'localhost',
    //     port: 3000,
    //     historyApiFallback: true,
    //     hot: true,
    // },
};