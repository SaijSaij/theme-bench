/**
 * View script of the block support Reveal: front-end behavior of a support.
 */
import { SupportView } from '@webcodingcat/wp-app-framework/view';

export default class Reveal extends SupportView {
	mount() {
		this.element.dataset.revealed = String( this.value );
	}
}
