/**
 * Builds the editor runtime of the framework into build/ (see
 * vendor/saij/wp-app-framework/webpack.config.js); the theme has no scripts
 * of its own.
 */
module.exports = require( './vendor/saij/wp-app-framework/webpack.config.js' )(
	{
		slug: 'theme-bench',
		root: __dirname,
	}
);
