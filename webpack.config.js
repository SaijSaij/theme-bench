/**
 * Builds the editor runtime of the framework into build/ (see the
 * webpack helper of the framework, `@saij/wp-app-framework/webpack`); the theme has no scripts
 * of its own.
 */
module.exports = require( '@saij/wp-app-framework/webpack' )( {
	slug: 'theme-bench',
	root: __dirname,
} );
