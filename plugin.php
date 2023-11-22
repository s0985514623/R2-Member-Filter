<?php

/**
 * Plugin Name: r2-member-filter
 * Description: 可以對已註冊會員進行篩選，並將篩選結果匯出成 CSV 檔案。篩選條件包含已購買過商品、購物車未結商品，並且可對已完成訂單數量進行排序
 * Author: R2
 * Author URI: https://github.com/s0985514623
 * License: GPLv2
 * Version: 1.2.4
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
