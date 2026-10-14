/**
 * Builds the framework scripts of the plugin (settings page) into build/ (see
 * vendor/saij/wp-app-framework/webpack.config.js); the plugin has no scripts of its own.
 */
module.exports = require( './vendor/saij/wp-app-framework/webpack.config.js' )(
	{
		slug: 'bench-plugin',
		root: __dirname,
	}
);
