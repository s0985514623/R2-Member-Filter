<?php
/**
 * Plugin Name: r2-member-filter
 * Description: 可以對已註冊會員進行篩選，並將篩選結果匯出成 CSV 檔案。篩選條件包含已購買過商品、購物車未結商品，並且可對已完成訂單數量進行排序
 * Author: R2
 * Author URI: https://github.com/s0985514623
 * License: GPLv2
 * Version: 1.2.9
 * Requires PHP: 7.4
 */

/**
 * Tags: woocommerce, shop, order
 * Requires at least: 4.6
 * Tested up to: 4.8
 * Stable tag: 4.3
 */

namespace J7\WP_REACT_PLUGIN\React;

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;
use Dotenv\Dotenv;

if ( ! \class_exists( 'J7\WP_REACT_PLUGIN\React\Plugin' ) ) {
	/**
		 * Class Plugin
		 */
	final class Plugin {
		const KEBAB       = 'r2-member-filter';
		const GITHUB_REPO = 'https://github.com/s0985514623/R2-Member-Filter';
		/**
		 * Plugin Directory
		 *
		 * @var string
		 */
		public static $dir;

		/**
		 * Plugin URL
		 *
		 * @var string
		 */
		public static $url;
		/**
		 * Instance
		 *
		 * @var Plugin
		 */
		private static $instance;

		/**
		 * Constructor
		 */
		public function __construct() {
			require_once __DIR__ . '/vendor/autoload.php';
			require_once __DIR__ . '/inc/admin.php';

			$dotenv = Dotenv::createImmutable( __DIR__, '.env.production' );
			$dotenv->safeLoad();

			\add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
			$this->plugin_update_checker();
		}

		/**
		 * Plugin update checker
		 *
		 * @return void
		 */
		public function plugin_update_checker(): void {
			$update_checker = PucFactory::buildUpdateChecker(
				self::GITHUB_REPO,
				__FILE__,
				self::KEBAB . '-release'
			);
			/**
			 * Type
			 *
			 * @var \Puc_v4p4_Vcs_PluginUpdateChecker $update_checker
			 */
			$update_checker->setBranch( 'master' );
			// if your repo is private, you need to set authentication
			// $update_checker->setAuthentication(self::$github_pat);
			$update_checker->getVcsApi()->enableReleaseAssets();
		}

		/**
		 * Check required plugins
		 *
		 * @return void
		 */
		public function plugins_loaded() {
			self::$dir = \untrailingslashit( \wp_normalize_path( \plugin_dir_path( __FILE__ ) ) );
			self::$url = \untrailingslashit( \plugin_dir_url( __FILE__ ) );
			$instance  = new Admin\Bootstrap();
			$instance->init();
		}

		/**
		 * Instance
		 *
		 * @return Plugin
		 */
		public static function instance() {
			if ( empty( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}
	}
	Plugin::instance();
}
