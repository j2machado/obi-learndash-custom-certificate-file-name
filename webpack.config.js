const path = require('path');

module.exports = {
    entry: {
		'obi-options': [
			'./src/admin/js/react/obi-ld-cert-admin-options.js'
		],
	},
  output: {
    path: path.resolve(__dirname, 'dist/js'),
    filename: 'obi-ld-cert-admin-options.js',
  },
  resolve: {
    alias: {
      '@wordpress/element': 'wp.element'
    }
  },
  module: {
    rules: [
      {
        test: /\.jsx?$/,
        exclude: /(node_modules)/,
        use: {
          loader: 'babel-loader',
          options: {
            presets: ['@babel/preset-env', '@babel/preset-react'],
          },
        },
      },
    ],
  },
  externals: {
    react: 'React',
    'react-dom': 'ReactDOM',
    wp: 'wp',
  },
  mode: 'production',
};
