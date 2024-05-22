<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://fclanka.com/
 * @since      1.0.0
 *
 * @package    Categories_And_Brands_Discount_Rules
 * @subpackage Categories_And_Brands_Discount_Rules/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Categories_And_Brands_Discount_Rules
 * @subpackage Categories_And_Brands_Discount_Rules/includes
 * @author     Chamila FCL <chamila@fclanka.com>
 */
class Categories_And_Brands_Discount_Rules_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'categories-and-brands-discount-rules',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
