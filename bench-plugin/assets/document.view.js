/**
 * Document view of the plugin: runs on every page of both themes, with view data and a shared module, so the
 * comparison of the themes stays fair.
 */
import { DocumentView } from '@saij/wp-app-framework/view';
import { next } from '#shared/counter';

export default class PluginMarker extends DocumentView {
	mount() {
		this.element.dataset.benchPlugin = `${
			this.data( 'plugin' ).label
		} ${ next() }`;
	}
}
