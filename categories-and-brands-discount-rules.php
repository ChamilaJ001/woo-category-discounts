<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://fclanka.com/
 * @since             1.0.0
 * @package           Categories_And_Brands_Discount_Rules
 *
 * @wordpress-plugin
 * Plugin Name:       Categories and Brands Discount Rules
 * Plugin URI:        https://kidzcare.lk/
 * Description:       This plugin allows to add multiple rules for categories and brands.
 * Version:           1.0.0
 * Author:            Chamila FCL
 * Author URI:        https://fclanka.com//
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       categories-and-brands-discount-rules
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'CATEGORIES_AND_BRANDS_DISCOUNT_RULES_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-categories-and-brands-discount-rules-activator.php
 */
function activate_categories_and_brands_discount_rules() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-categories-and-brands-discount-rules-activator.php';
	Categories_And_Brands_Discount_Rules_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-categories-and-brands-discount-rules-deactivator.php
 */
function deactivate_categories_and_brands_discount_rules() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-categories-and-brands-discount-rules-deactivator.php';
	Categories_And_Brands_Discount_Rules_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_categories_and_brands_discount_rules' );
register_deactivation_hook( __FILE__, 'deactivate_categories_and_brands_discount_rules' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-categories-and-brands-discount-rules.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_categories_and_brands_discount_rules() {

	$plugin = new Categories_And_Brands_Discount_Rules();
	$plugin->run();

}
run_categories_and_brands_discount_rules();
