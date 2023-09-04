<?php

declare(strict_types=1);

namespace J7\WP_REACT_PLUGIN\React\Admin;

use J7\WP_REACT_PLUGIN\React\Admin\Bootstrap;

class Member extends Bootstrap
{
	function __construct()
	{
		\add_action('admin_menu', [$this, 'r2_notify_menu_page']);
	}

	function r2_notify_menu_page()
	{
		add_menu_page(
			'會員篩選',       // 頁面標題
			'會員篩選',       // 菜單標題
			'manage_options',       // 權限等級
			'r2-member-filter',       // 菜單的slug
			[$this, 'r2_notify_page_content'] // 回調函數，用於輸出頁面內容
		);
	}
	// 頁面內容
	function r2_notify_page_content()
	{
		echo '<div id="' . $_ENV['KEBAB'] . '" class="my-app"></div>';
		$this->getMember();
	}

	function getMember()
	{
		$ProductArray = $this->getAllProduct();
		$user_query = new \WP_User_Query(array('number' => -1,));
		$users = $user_query->get_results();

		$usersDataAarray = [];
		// 遍历用户数据并输出
		if (!empty($users)) {
			$key = 0;
			foreach ($users as $user) {
				$userID = $user->ID;
				// $userID = 87;

				$userDate = array(
					'key' => $key++,
					'Username' => $user->display_name,
					'UserID' => $userID,
					'Email' => $user->user_email,
					'CompletedOders' => $this->getCompletedProducts($userID)['order_count'],
					'CompletedProducts' => $this->getCompletedProducts($userID)['CompletedProducts'],
					// 'CartItems' => $cart_items_count,
					'CartProducts' => $this->getCartProducts($userID),
				);
				// var_dump($userDate);
				// echo '<hr>';
				$usersDataAarray[] = $userDate;
			}
		}

		\wp_localize_script($_ENV['KEBAB'], 'memderData', array(
			'usersDataAarray' => $usersDataAarray,
		));
		\wp_localize_script($_ENV['KEBAB'], 'ProductData', array(
			'ProductArray' => $ProductArray,
		));
	}
	function getCompletedProducts($userID)
	{
		//取得已完成訂單
		$arg = array(
			'customer_id' => $userID,
			'limit' => -1,
			'status' => array('completed'),
		);
		$query = new \WC_Order_Query($arg);
		$orders = $query->get_orders();
		$CompletedProducts = [];
		$existingProductIDs = []; // 用于跟踪已存在的productID
		foreach ($orders as $order) {
			$orderObject = new \WC_Order($order->id);
			foreach ($orderObject->get_items() as $item_id) {
				$item = new \WC_Order_Item_Product($item_id);
				$productID = $item->get_product_id();

				// 检查是否已存在相同的productID
				if (!in_array($productID, $existingProductIDs)) {
					$productName = wc_get_product($productID)->get_name();
					$Product = array(
						'productName' => $productName,
						'productID' => $productID,
					);
					$CompletedProducts[] = $Product;
					// 将productID添加到已存在的数组中
					$existingProductIDs[] = $productID;
				}
			}
		}
		// var_dump($CompletedProducts);
		//取得訂單總數
		$order_count = count($orders);
		$result = array(
			'CompletedProducts' => $CompletedProducts,
			'order_count' => $order_count,
		);
		return $result;
	}
	function getCartProducts($userID)
	{
		//取得購物車未結
		// 使用 get_user_meta 取得woocommerce的永久購物車資料 get_current_blog_id() 取得網站ID
		$cart_data = get_user_meta($userID, '_woocommerce_persistent_cart_' . get_current_blog_id(), true)['cart'] ?? [];

		$CartProducts = [];
		$existingCartProductIDs = [];
		foreach ($cart_data as $data) {
			$productID = $data['product_id'];
			wc_get_product();
			// 检查是否已存在相同的productID
			if (!in_array($productID, $existingCartProductIDs)) {
				$productName = wc_get_product($productID)->get_name();
				$Product = array(
					'productName' => $productName,
					'productID' => $productID,
				);
				$CartProducts[] = $Product;
				// 将productID添加到已存在的数组中
				$existingCartProductIDs[] = $productID;
			}
		}
		// var_dump($CartProducts);
		$cart_items_count = $cart_data !== [] ? count($cart_data) : 0;
		return $CartProducts;
	}

	function getAllProduct()
	{
		$args = array(
			'post_type' => 'product',
			'posts_per_page' => -1, // 获取所有商品
		);

		$query = new \WP_Query($args);
		$ProductArray = [];
		if ($query->have_posts()) {
			while ($query->have_posts()) {
				$query->the_post();
				global $product;

				// 现在您可以访问每个商品的信息
				$productName = $product->get_title();
				$product = array(
					'productName' => $productName,
				);
				$ProductArray[] = $product;
			}
			wp_reset_postdata(); // 重置查询
		}
		return $ProductArray;
	}
}
