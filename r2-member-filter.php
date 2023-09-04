<?php

/**
 * Plugin Name: r2-member-filter
 * Description: 可根據wc訂單 / 時間 / 動作(加入購物車/購買)等條件篩選會員
 * Author: R2
 * Author URI: https://github.com/j7-dev
 * License: GPLv2
 * Version: 1.1.0
 * Requires PHP: 7.4
 */

/**
 * Tags: woocommerce, shop, order
 * Requires at least: 4.6
 * Tested up to: 4.8
 * Stable tag: 4.3
 */

namespace J7\WP_REACT_PLUGIN\React;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/inc/admin.php';


use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__, '.env.production');
$dotenv->safeLoad();


$instance = new Admin\Bootstrap();
$instance->init();