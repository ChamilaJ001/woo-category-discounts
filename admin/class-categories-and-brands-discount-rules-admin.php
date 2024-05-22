<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://fclanka.com/
 * @since      1.0.0
 *
 * @package    Categories_And_Brands_Discount_Rules
 * @subpackage Categories_And_Brands_Discount_Rules/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Categories_And_Brands_Discount_Rules
 * @subpackage Categories_And_Brands_Discount_Rules/admin
 * @author     Chamila FCL <chamila@fclanka.com>
 */
class Categories_And_Brands_Discount_Rules_Admin
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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/categories-and-brands-discount-rules-admin.css', array(), $this->version, 'all');

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/categories-and-brands-discount-rules-admin.js', array('jquery'), $this->version, false);

	}

	function fcl_cdr_add_admin_menu()
	{
		add_menu_page(
			'Custom Discount',
			'Custom Discount',
			'manage_options',
			'custom-discount',
			array($this, 'fcl_cdr_settings_page'),
			'dashicons-category',
			20
		);
	}

	public function fcl_cdr_register_settings()
	{
		register_setting('fcl_cdr_settings_group', 'fcl_cdr_discount_rules');
		add_settings_section('fcl_cdr_main_section', __('Main Settings', 'custom-discount-plugin'), null, 'custom-discount');
		add_settings_field('fcl_cdr_discount_rules', __('Discount Rules', 'custom-discount-plugin'), array($this, 'fcl_cdr_discount_rules_callback'), 'custom-discount', 'fcl_cdr_main_section');
	}

	public function fcl_cdr_settings_page()
	{
		if (isset($_GET['action']) && $_GET['action'] == 'add') {
			$this->fcl_cdr_add_rule_page();
		} elseif (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['rule_index'])) {
			$this->fcl_cdr_edit_rule_page(intval($_GET['rule_index']));
		} else {
			$this->fcl_cdr_list_rules_page();
		}
	}

	// List rules page
	private function fcl_cdr_list_rules_page()
	{
		if (isset($_POST['fcl_cdr_delete_rule'])) {
			$this->fcl_cdr_delete_rule();
		}

		if (isset($_POST['fcl_cdr_toggle_rule'])) {
			$this->fcl_cdr_toggle_rule();
		}

		$rules = get_option('fcl_cdr_discount_rules', array());
		?>
																																								<div class="wrap">
																																									<h1><?php esc_html_e('Custom Discount Settings', 'custom-discount-plugin'); ?></h1>
																																									<a href="?page=custom-discount&action=add" class="button button-primary"><?php esc_html_e('Add New Discounts', 'custom-discount-plugin'); ?></a>
																																									<h2><?php esc_html_e('Existing Discounts', 'custom-discount-plugin'); ?></h2>
																																									<table class="widefat fixed" cellspacing="0">
																																										<thead>
																																											<tr>
																																											<th><?php _e('Discount Name', 'custom-discount-plugin'); ?></th>
																																												<th><?php _e('Cart Total', 'custom-discount-plugin'); ?></th>
																																												<th><?php _e('Product Category', 'custom-discount-plugin'); ?></th>
																																												<th><?php _e('Product Brand', 'custom-discount-plugin'); ?></th>
																																												<th><?php _e('Discount Type', 'custom-discount-plugin'); ?></th>
																																												<th><?php _e('Discount Amount', 'custom-discount-plugin'); ?></th>
																																												<th><?php _e('Actions', 'custom-discount-plugin'); ?></th>
																																											</tr>
																																										</thead>
																																										<tbody>
																																											<?php if (!empty($rules)): ?>
																																																															<?php foreach ($rules as $index => $rule): ?>
																																																																																			<tr>
																																																																																			<td><?php echo esc_html($rule['discount_name']); ?></td>
																																																																																				<td><?php echo esc_html($rule['cart_total']); ?></td>
																																																																																				<td><?php echo esc_html($rule['category']); ?></td>
																																																																																				<td><?php echo esc_html($rule['brand']); ?></td>
																																																																																				<td><?php echo esc_html($rule['discount_type']); ?></td>
																																																																																				<td><?php echo esc_html($rule['discount_amount']); ?></td>
																																																																																				<td>
																																																																																					<form method="post" action="" style="display:inline;">
																																																																																						<?php wp_nonce_field('fcl_cdr_delete_rule_nonce', 'fcl_cdr_delete_nonce_field'); ?>
																																																																																						<input type="hidden" name="rule_index" value="<?php echo esc_attr($index); ?>" />
																																																																																						<?php submit_button(__('Delete', 'custom-discount-plugin'), 'delete', 'fcl_cdr_delete_rule', false); ?>
																																																																																					</form>
																																																																																					<a href="?page=custom-discount&action=edit&rule_index=<?php echo esc_attr($index); ?>" class="button"><?php _e('Edit', 'custom-discount-plugin'); ?></a>
																																																																																					<form method="post" action="" style="display:inline;">
																																																																																						<?php wp_nonce_field('fcl_cdr_toggle_rule_nonce', 'fcl_cdr_toggle_nonce_field'); ?>
																																																																																						<input type="hidden" name="rule_index" value="<?php echo esc_attr($index); ?>" />
																																																																																						<?php
																																																																																						$button_text = isset($rule['disabled']) && $rule['disabled'] ? __('Enable', 'custom-discount-plugin') : __('Disable', 'custom-discount-plugin');
																																																																																						$button_class = isset($rule['disabled']) && $rule['disabled'] ? 'secondary' : 'primary';
																																																																																						submit_button($button_text, $button_class, 'fcl_cdr_toggle_rule', false);
																																																																																						?>
																																																																																					</form>
																																																																																				</td>
																																																																																			</tr>
																																																															<?php endforeach; ?>
																																											<?php else: ?>
																																																															<tr>
																																																																<td colspan="5"><?php _e('No discount rules found.', 'custom-discount-plugin'); ?></td>
																																																															</tr>
																																											<?php endif; ?>
																																										</tbody>
																																									</table>
																																								</div>
																																								<?php
	}

	// Add discount page
	private function fcl_cdr_add_rule_page()
	{
		if (isset($_POST['fcl_cdr_add_rule'])) {
			$this->fcl_cdr_save_rule();
			wp_redirect(admin_url('admin.php?page=custom-discount'));
			exit;
		}

		?>
																																								<div class="wrap">
																																									<h1><?php esc_html_e('Add New Discount', 'custom-discount-plugin'); ?></h1>
																																									<form method="post" action="">
																																										<?php wp_nonce_field('fcl_cdr_save_rule_nonce', 'fcl_cdr_nonce_field'); ?>
																																										<table class="form-table">
																																										<tr>
																																												<th scope="row"><label for="fcl_cdr_discount_name"><?php esc_html_e('Discount Name', 'custom-discount-plugin'); ?></label></th>
																																												<td><input type="text" name="fcl_cdr_discount_name" id="fcl_cdr_discount_name" value="" required></td>
																																											</tr>
																																											<tr>
																																												<th scope="row"><label for="fcl_cdr_cart_total"><?php esc_html_e('Cart Total', 'custom-discount-plugin'); ?></label></th>
																																												<td><input type="number" name="fcl_cdr_cart_total" id="fcl_cdr_cart_total" value="" required></td>
																																											</tr>
																																											<tr>
																																												<th scope="row"><label for="fcl_cdr_category"><?php esc_html_e('Product Category', 'custom-discount-plugin'); ?></label></th>
																																												<td><?php $this->fcl_cdr_category_select(); ?></td>
																																											</tr>
																																											<tr>
																																												<th scope="row"><label for="fcl_cdr_brand"><?php esc_html_e('Product Brand', 'custom-discount-plugin'); ?></label></th>
																																												<td><?php $this->fcl_cdr_brand_select(); ?></td>
																																											</tr>
																																											<tr valign="top">
																																	<th scope="row"><?php esc_html_e('Discount Type', 'custom-discount-plugin'); ?></th>
																																	<td>
																																		<select name="fcl_cdr_discount_type" required>
																																			<option value="fixed"><?php esc_html_e('Fixed Rate', 'custom-discount-plugin'); ?></option>
																																			<option value="percentage"><?php esc_html_e('Percentage', 'custom-discount-plugin'); ?></option>
																																		</select>
																																	</td>
																																</tr>
																																											<tr>
																																												<th scope="row"><label for="fcl_cdr_discount_amount"><?php esc_html_e('Discount Amount', 'custom-discount-plugin'); ?></label></th>
																																												<td><input type="number" name="fcl_cdr_discount_amount" id="fcl_cdr_discount_amount" value="" required></td>
																																											</tr>
																																										</table>
																																										<?php submit_button(__('Add Discount Rule', 'custom-discount-plugin'), 'primary', 'fcl_cdr_add_rule'); ?>
																																									</form>
																																								</div>
																																								<?php
	}

	// Edit rule page
	private function fcl_cdr_edit_rule_page($rule_index)
	{
		$rules = get_option('fcl_cdr_discount_rules', array());

		if (!isset($rules[$rule_index])) {
			wp_redirect(admin_url('admin.php?page=custom-discount'));
			exit;
		}

		if (isset($_POST['fcl_cdr_save_rule'])) {
			$this->fcl_cdr_save_rule();
			wp_redirect(admin_url('admin.php?page=custom-discount'));
			exit;
		}

		$rule = $rules[$rule_index];

		?>
																																								<div class="wrap">
																																									<h1><?php esc_html_e('Edit Discount', 'custom-discount-plugin'); ?></h1>
																																									<form method="post" action="">
																																										<?php wp_nonce_field('fcl_cdr_save_rule_nonce', 'fcl_cdr_nonce_field'); ?>
																																										<input type="hidden" name="rule_index" value="<?php echo esc_attr($rule_index); ?>">
																																										<table class="form-table">
																																										<tr>
																																												<th scope="row"><label for="fcl_cdr_discount_name"><?php esc_html_e('Discount Name', 'custom-discount-plugin'); ?></label></th>
																																												<td><input type="text" name="fcl_cdr_discount_name" id="fcl_cdr_discount_name" value="<?php echo esc_attr($rule['discount_name']); ?>" required></td>
																																											</tr>
																																											<tr>
																																												<th scope="row"><label for="fcl_cdr_cart_total"><?php esc_html_e('Cart Total', 'custom-discount-plugin'); ?></label></th>
																																												<td><input type="number" name="fcl_cdr_cart_total" id="fcl_cdr_cart_total" value="<?php echo esc_attr($rule['cart_total']); ?>" required></td>
																																											</tr>
																																											<tr>
																																												<th scope="row"><label for="fcl_cdr_category"><?php esc_html_e('Product Category', 'custom-discount-plugin'); ?></label></th>
																																												<td><?php $this->fcl_cdr_category_select($rule['category']); ?></td>
																																											</tr>
																																											<tr>
																																												<th scope="row"><label for="fcl_cdr_brand"><?php esc_html_e('Product Brand', 'custom-discount-plugin'); ?></label></th>
																																												<td><?php $this->fcl_cdr_brand_select($rule['brand']); ?></td>
																																											</tr>
																																											<tr valign="top">
																											<th scope="row"><?php esc_html_e('Discount Type', 'custom-discount-plugin'); ?></th>
																											<td>
																												<select name="fcl_cdr_discount_type" required>
																													<option value="fixed" <?php selected($rule['discount_type'], 'fixed'); ?>><?php esc_html_e('Fixed Rate', 'custom-discount-plugin'); ?></option>
																													<option value="percentage" <?php selected($rule['discount_type'], 'percentage'); ?>><?php esc_html_e('Percentage', 'custom-discount-plugin'); ?></option>
																												</select>
																											</td>
																										</tr>
																																											<tr>
																																												<th scope="row"><label for="fcl_cdr_discount_amount"><?php esc_html_e('Discount Amount', 'custom-discount-plugin'); ?></label></th>
																																												<td><input type="number" name="fcl_cdr_discount_amount" id="fcl_cdr_discount_amount" value="<?php echo esc_attr($rule['discount_amount']); ?>" required></td>
																																											</tr>
																																										</table>
																																										<?php submit_button(__('Save Changes', 'custom-discount-plugin'), 'primary', 'fcl_cdr_save_rule'); ?>
																																									</form>
																																								</div>
																																								<?php
	}

	private function fcl_cdr_save_rule()
	{
		if (!isset($_POST['fcl_cdr_nonce_field']) || !wp_verify_nonce($_POST['fcl_cdr_nonce_field'], 'fcl_cdr_save_rule_nonce')) {
			return;
		}

		$rules = get_option('fcl_cdr_discount_rules', array());
		$rule_data = array(
			'discount_name' => sanitize_text_field($_POST['fcl_cdr_discount_name']),
			'cart_total' => sanitize_text_field($_POST['fcl_cdr_cart_total']),
			'category' => sanitize_text_field($_POST['fcl_cdr_category']),
			'brand' => sanitize_text_field($_POST['fcl_cdr_brand']),
			'discount_type' => sanitize_text_field($_POST['fcl_cdr_discount_type']),
			'discount_amount' => sanitize_text_field($_POST['fcl_cdr_discount_amount']),
		);

		if (isset($_POST['rule_index'])) {
			$rule_index = intval($_POST['rule_index']);
			$rules[$rule_index] = $rule_data;
		} else {
			$rules[] = $rule_data;
		}

		update_option('fcl_cdr_discount_rules', $rules);

		// Redirect to the list page
		wp_redirect(admin_url('admin.php?page=custom-discount'));
		exit;
	}

	private function fcl_cdr_delete_rule()
	{
		if (!isset($_POST['fcl_cdr_delete_nonce_field']) || !wp_verify_nonce($_POST['fcl_cdr_delete_nonce_field'], 'fcl_cdr_delete_rule_nonce')) {
			return;
		}

		$rules = get_option('fcl_cdr_discount_rules', array());
		$rule_index = intval($_POST['rule_index']);

		if (isset($rules[$rule_index])) {
			unset($rules[$rule_index]);
			update_option('fcl_cdr_discount_rules', $rules);
		}
	}

	private function fcl_cdr_toggle_rule()
	{
		if (!isset($_POST['fcl_cdr_toggle_nonce_field']) || !wp_verify_nonce($_POST['fcl_cdr_toggle_nonce_field'], 'fcl_cdr_toggle_rule_nonce')) {
			return;
		}

		$rules = get_option('fcl_cdr_discount_rules', array());
		$rule_index = intval($_POST['rule_index']);

		if (isset($rules[$rule_index])) {
			$rules[$rule_index]['disabled'] = !isset($rules[$rule_index]['disabled']) || !$rules[$rule_index]['disabled'];
			update_option('fcl_cdr_discount_rules', $rules);
		}
	}


	private function fcl_cdr_category_select($selected = '')
	{
		$categories = get_terms(array('taxonomy' => 'product_cat', 'hide_empty' => false));
		if (!empty($categories) && !is_wp_error($categories)) {
			echo '<select name="fcl_cdr_category">';
			foreach ($categories as $category) {
				$is_selected = ($selected === $category->slug) ? 'selected' : '';
				echo '<option value="' . esc_attr($category->slug) . '" ' . $is_selected . '>' . esc_html($category->name) . '</option>';
			}
			echo '</select>';
		} else {
			echo '<p>' . __('No categories found', 'custom-discount-plugin') . '</p>';
		}
	}

	private function fcl_cdr_brand_select($selected = '')
	{
		$brands = get_terms(array('taxonomy' => 'pa_brands', 'hide_empty' => false));
		if (!empty($brands) && !is_wp_error($brands)) {
			echo '<select name="fcl_cdr_brand">';
			foreach ($brands as $brand) {
				$is_selected = ($selected === $brand->slug) ? 'selected' : '';
				echo '<option value="' . esc_attr($brand->slug) . '" ' . $is_selected . '>' . esc_html($brand->name) . '</option>';
			}
			echo '</select>';
		} else {
			echo '<p>' . __('No brands found', 'custom-discount-plugin') . '</p>';
		}
	}




}
