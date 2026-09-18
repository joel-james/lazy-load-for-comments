<?php
/**
 * Tests for the pre-2.1.0 namespace aliases.
 *
 * The plugin moved from `DuckDev\LazyComments\` to
 * `FoxeLabs\LazyComments\` in 2.1.0. Third-party code written against
 * the old names has to keep working, so these tests pin that contract.
 *
 * @package LazyComments
 */

declare( strict_types = 1 );

/**
 * Class Test_Legacy_Aliases
 */
class Test_Legacy_Aliases extends WP_UnitTestCase {

	/**
	 * Every documented class resolves under its old name.
	 *
	 * `Settings` is covered by {@see self::test_alias_is_the_same_class()}
	 * instead. A `class_alias()` survives for the rest of the process, so
	 * the deprecation notice only fires the first time a given legacy name
	 * is touched — each test here therefore claims its own class, keeping
	 * the assertions independent of the order the tests run in.
	 *
	 * @return void
	 */
	public function test_documented_classes_are_aliased(): void {
		$classes = array(
			'Core',
			'Cache\BlockCache',
			'Front\Renderer',
			'Utils\Permission',
		);

		foreach ( $classes as $class ) {
			$legacy = 'DuckDev\LazyComments\\' . $class;

			$this->setExpectedIncorrectUsage( $legacy );

			$this->assertTrue(
				class_exists( $legacy ),
				"The legacy class name {$legacy} no longer resolves."
			);
		}
	}

	/**
	 * An alias resolves to the very same class, not a copy.
	 *
	 * @return void
	 */
	public function test_alias_is_the_same_class(): void {
		$this->setExpectedIncorrectUsage( 'DuckDev\LazyComments\Settings' );

		$this->assertSame(
			\FoxeLabs\LazyComments\Settings::instance(),
			\DuckDev\LazyComments\Settings::instance(),
			'The aliased name returned a different singleton instance.'
		);
	}

	/**
	 * Interfaces are aliased too, not just classes.
	 *
	 * @return void
	 */
	public function test_interfaces_are_aliased(): void {
		$legacy = 'DuckDev\LazyComments\Contracts\Routable';

		$this->setExpectedIncorrectUsage( $legacy );

		$this->assertTrue( interface_exists( $legacy ) );
	}

	/**
	 * A name that never existed stays undefined.
	 *
	 * The alias loader must not invent classes — an unknown legacy name
	 * has to fail the way any other missing class would.
	 *
	 * @return void
	 */
	public function test_unknown_legacy_class_is_not_aliased(): void {
		$this->assertFalse( class_exists( 'DuckDev\LazyComments\NotAThing' ) );
	}

	/**
	 * Classes outside the plugin's legacy namespace are ignored.
	 *
	 * @return void
	 */
	public function test_other_duckdev_namespaces_are_untouched(): void {
		$this->assertFalse( class_exists( 'DuckDev\Loggedin\Plugin' ) );
	}
}
