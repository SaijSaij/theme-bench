/**
 * View script of the media block: reads view data set in render().
 */
import { BlockView } from '@webcodingcat/wp-app-framework/view';

export default class Media extends BlockView {
	mount() {
		this.element.dataset.label = this.data( 'media' ).label;
	}
}
