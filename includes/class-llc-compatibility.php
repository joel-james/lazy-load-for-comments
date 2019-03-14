<?php

// If this file is called directly, abort.
defined( 'WPINC' ) or die( 'Damn it.! Dude you are looking for what?' );

/**
 * The compatibility functionality with other plugins.
 *
 * @category   Core
 * @package    LLC
 * @subpackage Compatibility
 * @author     Joel James <mail@cjoel.com>
 * @license    http://www.gnu.org/licenses/ GNU General Public License
 * @link       https://wordpress.org/plugins/lazy-load-for-comments
 * @since      1.0.7
 */
class LLC_Compatibility {

	/**
	 * Do not lazy load woo reviews.
	 *
	 * Woocommerce use comments template to show review
	 * system. So exclude woocommerce products from lazy
	 * loading.
	 *
	 * @param bool $load Lazy load status.
	 *
	 * @since 1.0.7
	 *
	 * @return string
	 */
	public function woocommerce_reviews( $load ) {
		// Do not lazy load Woo reviews.
		if ( 'product' === get_post_type() ) {
			return false;
		}

		return $load;
	}
}
