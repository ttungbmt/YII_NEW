const {resolve} = require('path');
const webpack = require('webpack');

module.exports = {
    entry: resolve(__dirname, 'src/react-maps.js'),
    output: {
        filename: 'static/bundle.js',
        path: resolve(__dirname, 'dist'),
        publicPath: '/'
    },
    devtool: 'source-map',
    module: {
        rules: [
            {
                test: /\.jsx?$/,
                use: ['babel-loader'],
                exclude: /node_modules/
            }
        ]
    },

    plugins: [
        new webpack.optimize.UglifyJsPlugin({
            sourceMap: true,
            comments: false
        })
    ]
};