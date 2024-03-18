<?php

/**
 * Class Custom_WC_Email
 */
class Custom_WC_Email
{

	/**
	 * Custom_WC_Email constructor.
	 */
	public function __construct()
	{
		add_action('init', array($this, 'init'));

		// Absolute path to the plugin folder.
		define('CUSTOM_WC_EMAIL_PATH', plugin_dir_path(__FILE__));
	}
	public function init()
	{
		// Filtering the emails and adding our own email.
		add_filter('woocommerce_email_classes', array($this, 'register_email'), 90, 1);
		add_filter('kadence_woomail_email_types', function ($types) {
			return $types = array_merge($types, array(
				'wc_customer_cancelled_order'     => __('Custom New Order', 'kadence-woocommerce-email-designer'),
			));
		}, 10, 1);
		apply_filters('kadence_woocommerce_email_previews', array('wc_customer_cancelled_order' => 'WC_Customer_Cancel_Order',));
	}
	/**
	 * @param array $emails
	 *
	 * @return array
	 */
	public function register_email($emails)
	{
		require_once 'class-wc-customer-cancel-order.php';

		$emails['WC_Customer_Cancel_Order'] = new WC_Customer_Cancel_Order();

		return $emails;
	}
}

new Custom_WC_Email();
