<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://fclanka.com/
 * @since      1.0.0
 *
 * @package    Categories_And_Brands_Discount_Rules
 * @subpackage Categories_And_Brands_Discount_Rules/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Categories_And_Brands_Discount_Rules
 * @subpackage Categories_And_Brands_Discount_Rules/public
 * @author     Chamila FCL <chamila@fclanka.com>
 */
class Categories_And_Brands_Discount_Rules_Public
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Categories_And_Brands_Discount_Rules_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Categories_And_Brands_Discount_Rules_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/categories-and-brands-discount-rules-public.css', array(), $this->version, 'all');

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Categories_And_Brands_Discount_Rules_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Categories_And_Brands_Discount_Rules_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/categories-and-brands-discount-rules-public.js', array('jquery'), $this->version, false);

	}

	public function fcl_cdr_apply_cart_discount()
	{
		$cart_total = WC()->cart->cart_contents_total;

		// Get all discount rules
		$rules = get_option('fcl_cdr_discount_rules', array());

		// Filter only enabled rules
		$enabled_rules = array_filter($rules, function ($rule) {
			return !isset ($rule['disabled']) || !$rule['disabled'];
		});

		// Sort the rules by cart total in descending order
		usort($enabled_rules, function ($a, $b) {
			return $b['cart_total'] - $a['cart_total'];
		});

		$applicable_discount = 0;
		$discount_name = '';
		$brand_name = '';

		foreach ($enabled_rules as $rule) {
			if ($cart_total >= $rule['cart_total'] && $applicable_discount === 0) {
				// Check if the cart contains products from the specified category and brand
				if ($this->fcl_cdr_check_cart_contains_category_and_brand($rule['category'], $rule['brand'])) {
					if ($rule['discount_type'] === 'fixed') {
						$applicable_discount = max($applicable_discount, $rule['discount_amount']);
						$discount_name = $rule['discount_name'];
						$brand_name = $rule['brand'];
					} elseif ($rule['discount_type'] === 'percentage') {
						$percentage_discount = ($cart_total * $rule['discount_amount']) / 100;
						$applicable_discount = max($applicable_discount, $percentage_discount);
						$discount_name = $rule['discount_name'];
						$brand_name = $rule['brand'];
					}
				}
			}
		}

		// Apply the discount if applicable
		if ($applicable_discount > 0) {
			WC()->cart->add_fee(__($discount_name, 'custom-discount-plugin'), -$applicable_discount);
			wc_add_notice(__('Congratulations! You have received a discount of ' . wc_price($applicable_discount) . ' for ' . $brand_name . ' brand products.', 'custom-discount-plugin'), 'success');
		}
	}


	private function fcl_cdr_check_cart_contains_category_and_brand($category_name, $brand_name)
	{
		foreach (WC()->cart->get_cart() as $cart_item) {
			$product_id = $cart_item['product_id'];
			$product = wc_get_product($product_id);

			if ($product) {
				// Check product category
				$product_categories = wp_get_post_terms($product_id, 'product_cat', array('fields' => 'slugs'));
				if (in_array($category_name, $product_categories)) {
					// Check product brand
					$product_brands = wp_get_post_terms($product_id, 'pa_brands', array('fields' => 'slugs'));
					if (in_array($brand_name, $product_brands)) {
						return true;
					}
				}
			}
		}
		return false;
	}

}
