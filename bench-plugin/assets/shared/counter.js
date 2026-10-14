/**
 * Shared module of the plugin: one instance for its document view and its editor script.
 */
let count = 0;

/**
 * @return {number} How often it was called on this page.
 */
export function next() {
	count += 1;
	return count;
}
