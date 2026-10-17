/**
 * View script of the panel: front-end behavior of a block.
 */
import { BlockView } from '@webcodingcat/wp-app-framework/view';

export default class Panel extends BlockView {
	mount() {
		this.listen( this.element, 'click', () =>
			this.element.classList.toggle( 'is-open' )
		);
	}
}
