<?php
namespace FTFWCWP\Base;

/**
 * Class for plucin deactivation.
 *
 * @since: 1.1.0
 * @package FTFWCWP
 */
class Deactivate {

	/**
	 * Deactivate the plugin.
	 */
	public static function deactivate() { // phpcs:ignore
		flush_rewrite_rules();
	}
}
