<?php

declare(strict_types=1);

namespace J7\WP_REACT_PLUGIN\React\Admin;

use J7\WP_REACT_PLUGIN\React\Admin\Bootstrap;

class Ajax
{

	const GET_POST_META_ACTION = 'handle_get_post_meta';
	const UPDATE_POST_META_ACTION = 'handle_update_post_meta';
	const SET_CRON_EMAIL = 'handle_set_cron_email';
	function __construct()
	{
		foreach ([self::GET_POST_META_ACTION, self::UPDATE_POST_META_ACTION, self::SET_CRON_EMAIL] as $action) {
			\add_action('wp_ajax_' . $action, [$this,  $action . '_callback']);
			\add_action('wp_ajax_nopriv_' . $action, [$this, $action . '_callback']);
		}
	}


	public function handle_get_post_meta_callback()
	{
		// Security check
		\check_ajax_referer($_ENV['KEBAB'], 'nonce');
		$post_id = \sanitize_text_field($_POST['post_id'] ?? '');
		$meta_key = \sanitize_text_field($_POST['meta_key'] ?? '');

		if (empty($post_id)) return;
		$post_id = $post_id;
		$post_meta = empty($meta_key) ? \get_post_meta($post_id) : \get_post_meta($post_id, $meta_key, true);

		$return = array(
			'message'  => 'success',
			'data'       => [
				'post_meta' => $post_meta,
			]
		);

		\wp_send_json($return);

		\wp_die();
	}

	public function handle_update_post_meta_callback()
	{
		// Security check
		\check_ajax_referer($_ENV['KEBAB'], 'nonce');
		$post_id = \sanitize_text_field($_POST['post_id'] ?? '');
		$meta_key = \sanitize_text_field($_POST['meta_key'] ?? '');
		$meta_value = \sanitize_text_field($_POST['meta_value'] ?? '');


		if (empty($post_id) || empty($meta_key)) return;
		$post_id = $post_id;
		$update_result = \update_post_meta($post_id, $meta_key, $meta_value);

		$return = array(
			'message'  => 'success',
			'data'       => [
				'update_result' => $update_result,
			]
		);

		\wp_send_json($return);

		\wp_die();
	}

	//AJAX手動寄信功能
	public function handle_set_cron_email_callback()
	{
		// Security check
		\check_ajax_referer($_ENV['KEBAB'], 'nonce');
		$subject = \sanitize_text_field($_POST['subject'] ?? '');
		$userEmail = \sanitize_text_field($_POST['userEmail'] ?? ''); //array
		$date = \sanitize_text_field($_POST['date'] ?? ''); //date
		$content = ($_POST['content'] ?? ''); //html
		//接收要發送哪一個範本
		$template = \sanitize_text_field($_POST['template'] ?? ''); //string
		CronNew::set_Manually_Mail($subject, $userEmail, $date, $content, $template);

		// $adminEmail = \get_option('admin_email');
		// $headers = array(
		// 	'Content-Type: text/html; charset=UTF-8',
		// 	'cc:' . $userEmail . ''
		// );
		// \wp_mail($adminEmail, 'test', $content, $headers);


		$update_result = array(
			'userEmail' => $userEmail,
			'date' => $date,
			'content' => $content,
			'template' => $template
		);

		$return = array(
			'message'  => 'success',
			'data'       => [
				'update_result' => $update_result,
			]
		);
		\wp_send_json($return);

		\wp_die();
	}
}
