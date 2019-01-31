const path = require('path');

module.exports = {
	mode: 'development',
	entry: {
		app: './src/admin.js'
	},
	output: {
		path: __dirname,
		filename: 'dist/admin.js',
	},
	module: {
		rules: [{
			test: /.js$/,
			exclude: /node_modules/,
			use: [{
				loader: 'babel-loader'
			}]
		}]
	},
};