<?php

declare(strict_types=1);

namespace J7\WP_REACT_PLUGIN\React\Admin;

use Kucrut\Vite;

class Bootstrap
{
	const PLUGIN_DIR = __DIR__ . '/../';

	function __construct()
	{
		$_ENV['APP_NAME'];
		$_ENV['KEBAB'] = str_replace(' ', '-', strtolower($_ENV['APP_NAME']));
		$_ENV['SNAKE'] = str_replace(' ', '_', strtolower($_ENV['APP_NAME']));

		new ShortCode($_ENV['SNAKE'] . '_shortcode');
		new Ajax();
		// new CPT($_ENV['KEBAB'], array(
		// 	'post_metas' => ['meta', 'settings'],
		// 	'rewrite' => array(
		// 		'template_path' => 'test.php',
		// 		'slug' => 'test',
		// 		'var' => $_ENV['SNAKE'] . '_test',
		// 	),
		// ));
		new Member();
		new Cron();
		new userIsLogin();
	}

	public function init(): void
	{
		\add_action('admin_enqueue_scripts', [$this, 'enqueue_script'], 99);
		\add_action('wp_enqueue_scripts', [$this, 'enqueue_script'], 99);
		\add_action('wp_footer', [$this, 'render_app']);
	}

	/**
	 * Render application's markup
	 */
	public function render_app(): void
	{
		echo '<div id="' . $_ENV['KEBAB'] . '" class="my-app"></div>';
	}


	/**
	 * Enqueue script
	 */
	public function enqueue_script(): void
	{
		/*
		 * enquene script on demand
		if (\is_admin()) {
			// match wp-admin screen_id
			$screen = \get_current_screen();
			if (($screen->id !== $_ENV['KEBAB'])) return;
		} else {
			// match front-end post_type slug {$_ENV['KEBAB']}
			if (strpos($_SERVER['REQUEST_URI'], $_ENV['KEBAB']) === false) return;
		}
		*/

		Vite\enqueue_asset(
			dirname(__DIR__) . '/js/dist',
			'js/src/main.tsx',
			[
				'handle' => $_ENV['KEBAB'],
				'in-footer' => true,
			]
		);

		$post_id = \get_the_ID();
		$permalink = \get_permalink($post_id);

		\wp_localize_script($_ENV['KEBAB'], 'appData', array(
			'siteUrl' => \site_url(),
			'ajaxUrl' => \admin_url('admin-ajax.php'),
			'ajaxNonce'  => \wp_create_nonce($_ENV['KEBAB']),
			'userId' => \wp_get_current_user()->data->ID ?? null,
			'postId' => $post_id,
			'permalink' => $permalink,
		));

		\wp_localize_script($_ENV['KEBAB'], 'wpApiSettings', array(
			'root' => \esc_url_raw(rest_url()),
			'nonce' => \wp_create_nonce('wp_rest'),
		));
	}

	public static function get_plugin_dir(): string
	{
		$plugin_dir = \wp_normalize_path(\plugin_dir_path(__DIR__ . '../'));
		return $plugin_dir;
	}

	public static function get_plugin_url(): string
	{
		$plugin_url = \plugin_dir_url(self::get_plugin_dir() . 'plugin.php');
		return $plugin_url;
	}

	public static function get_plugin_ver(): string
	{
		$plugin_data = \get_plugin_data(self::get_plugin_dir() . 'plugin.php');
		$plugin_ver = $plugin_data['Version'];
		return $plugin_ver;
	}
}

require_once __DIR__ . '/utils/includes.php';
require_once __DIR__ . '/custom/includes.php';