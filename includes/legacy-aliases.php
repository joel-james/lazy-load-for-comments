<?php
/**
 * Backward-compatible class aliases for the pre-2.1.0 namespace.
 *
 * Every class in this plugin lived under `DuckDev\LazyComments\` until
 * 2.1.0, when the plugin moved to the Foxe Labs brand. Third-party code
 * — add-ons, snippets, custom templates calling
 * `Front\Renderer::placeholder()` — may still reference the old names,
 * so they keep resolving.
 *
 * This is done with an autoloader rather than a list of `class_alias()`
 * calls on purpose: an eager alias has to load the real class to alias
 * it, which would pull every aliased class into memory on every
 * request. Registering a loader means nothing is loaded until somebody
 * actually names an old class.
 *
 * @package LazyComments
 */

declare( strict_types = 1 );

// If this file is called directly, abort.
defined( 'ABSPATH' ) || exit;

spl_autoload_register(
	/**
	 * Alias a legacy `DuckDev\LazyComments\*` name to its current one.
	 *
	 * @since 2.1.0
	 *
	 * @param string $class_name Fully-qualified name PHP is looking for.
	 *
	 * @return void
	 */
	static function ( string $class_name ): void {
		$legacy_prefix = 'DuckDev\\LazyComments\\';

		if ( 0 !== strpos( $class_name, $legacy_prefix ) ) {
			return;
		}

		$current = 'FoxeLabs\\LazyComments\\' . substr( $class_name, strlen( $legacy_prefix ) );

		// Let Composer resolve the real class first. Anything that does
		// not exist under the new namespace either never existed or was
		// removed, and must stay undefined so the usual "class not
		// found" error is raised against the name the caller used.
		if ( ! class_exists( $current ) && ! interface_exists( $current ) && ! trait_exists( $current ) ) {
			return;
		}

		class_alias( $current, $class_name );

		_doing_it_wrong(
			esc_html( $class_name ),
			sprintf(
				/* translators: 1: old class name, 2: new class name. */
				esc_html__( '%1$s has moved to %2$s. The old name still works, but will be removed in a future version.', 'lazy-load-for-comments' ),
				esc_html( $class_name ),
				esc_html( $current )
			),
			'2.1.0'
		);
	}
);
