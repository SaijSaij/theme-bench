/**
 * Editor script without a block: loads in the block editor of both themes.
 */
import { getData } from '@webcodingcat/wp-app-framework';
import { next } from '#shared/counter';

window.benchPluginEditor = `${ getData( 'plugin' ).label } ${ next() }`;
