/**
 * Builds the framework scripts of the plugin (settings page) into build/ (see the
 * webpack helper of the framework, `@saij/wp-app-framework/webpack`); the plugin has no scripts of its own.
 */
module.exports = require( '@saij/wp-app-framework/webpack' )( {
	slug: 'bench-plugin',
	root: __dirname,
} );
