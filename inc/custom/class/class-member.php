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
		// $args = array(
		// 	'post_type' => 'product',
		// 	'posts_per_page' => -1, // 获取所有商品
		// );
		// $query = new \WP_Query($args);
		// if ($query->have_posts()) {
		// 	while ($query->have_posts()) {
		// 		$query->the_post();
		// 		global $product;
		// 		if ($product->is_type('variable')) {
		// 			// 獲取變體類型
		// 			$variations = $product->get_available_variations();
		// 			foreach ($variations as $variation) {
		// 				// 獲取變體屬性

		// 				$variationAttributes = wc_get_formatted_variation($variation["attributes"], true, false);
		// 				echo $variationAttributes . '<br>';
		// 			}
		// 		}
		// 	}
		// 	wp_reset_postdata(); // 重置查询
		// }

		echo '<div id="' . $_ENV['KEBAB'] . '" class="my-app"></div>';
		$this->getMember();
		// $this->CustomEmailTest();
	}

	// 	function CustomEmailTest()
	// 	{
	// $jsonData = \get_option('thwec_template_settings')["templates"]["date_mail"]["template_data"];
	// $phpData = json_decode($jsonData, true);
	// // echo $phpData["contents"];
	// echo '<pre>';
	// var_dump($jsonData);
	// echo '</pre>';
	// 		$jsonData = \get_option('thwec_template_settings')["templates"]["date_mail"]["template_data"];
	// 		$phpData = json_decode($jsonData, true);
	// 		ob_start();
	// 		$content =  '';
	//
	// 		$content .=  ob_get_clean();

	// 		$headers = array('Content-Type: text/html; charset=UTF-8');
	// 		\wp_mail('s0985514623@gmail.com', 'test', $content, $headers);
	// 	}
	function getMember()
	{
		$ProductArray = $this->getAllProduct();
		$user_query = new \WP_User_Query(array('number' => -1));
		$users = $user_query->get_results();

		$usersDataArray = [];
		// 遍历用户数据并输出
		if (!empty($users)) {
			$key = 0;
			foreach ($users as $user) {
				$userID = $user->ID;
				// $userID = 87;

				$userDate = array(
					'key' => $key++,
					'userName' => $user->display_name,
					'userID' => $userID,
					'email' => $user->user_email,
					'completedOrders' => $this->getCompletedProducts($userID)['orderCount'],
					'completedProducts' => $this->getCompletedProducts($userID)['completedProducts'],
					// 'CartItems' => $cart_items_count,
					'cartProducts' => $this->getCartProducts($userID),
					// 'sessionProduct' => $this->getSessionProduct($userID),
				);
				// var_dump($userDate);
				// echo '<hr>';
				$usersDataArray[] = $userDate;
			}
		}

		\wp_localize_script($_ENV['KEBAB'], 'memberData', array(
			'usersDataArray' => $usersDataArray,
		));
		\wp_localize_script($_ENV['KEBAB'], 'productData', array(
			'productArray' => $ProductArray,
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
				$variationID = $item->get_variation_id(); //如果沒有會返回0

				if ($variationID) {
					//如果是變體商品的處理邏輯
					if (!in_array($variationID, $existingProductIDs) && wc_get_product($variationID)) {
						$productName = wc_get_product($productID)->get_name();
						/**
						 * @var \WC_Product_Variation $variation =>改善vscode會提示 $variation is not defined錯誤
						 */
						$variation = wc_get_product($variationID);
						//由變體類型取得商品屬性
						$attributes = $variation->get_variation_attributes();
						$variationAttributes = wc_get_formatted_variation($attributes, true, false);
						$CompletedProducts[] = array(
							'productName' => $productName . ' - ' . $variationAttributes,
							'productID' => $variationID,
						);;
						// 将productID添加到已存在的数组中
						$existingProductIDs[] = $variationID;
					}
				} else {
					//如果是一般商品的處理邏輯
					// 检查是否已存在相同的productID
					if (!in_array($productID, $existingProductIDs) && wc_get_product($productID)) {
						$productName = wc_get_product($productID)->get_name();

						$CompletedProducts[] = array(
							'productName' => $productName,
							'productID' => $productID,
						);;
						// 将productID添加到已存在的数组中
						$existingProductIDs[] = $productID;
					}
				}
			}
		}
		// var_dump($CompletedProducts);
		//取得訂單總數
		$order_count = count($orders);
		$result = array(
			'completedProducts' => $CompletedProducts,
			'orderCount' => $order_count,
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
			$variationID = $data['variation_id'];
			if ($variationID) {
				//如果是變體商品的處理邏輯
				if (!in_array($variationID, $existingCartProductIDs) && wc_get_product($variationID)) {
					$productName = wc_get_product($productID)->get_name();
					/**
					 * @var \WC_Product_Variation $variation =>改善vscode會提示 $variation is not defined錯誤
					 */
					$variation = wc_get_product($variationID);
					//由變體類型取得商品屬性
					$attributes = $variation->get_variation_attributes();
					$variationAttributes = wc_get_formatted_variation($attributes, true, false);
					$CartProducts[] = array(
						'productName' => $productName . ' - ' . $variationAttributes,
						'productID' => $variationID,
					);;
					// 将productID添加到已存在的数组中
					$existingCartProductIDs[] = $variationID;
				}
			} else {
				//如果是一般商品的處理邏輯
				// 检查是否已存在相同的productID
				if (!in_array($productID, $existingCartProductIDs) && wc_get_product($productID)) {
					$productName = wc_get_product($productID)->get_name();

					$CartProducts[] = array(
						'productName' => $productName,
						'productID' => $productID,
					);;
					// 将productID添加到已存在的数组中
					$existingCartProductIDs[] = $productID;
				}
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
				//增加變體商品判斷
				if ($product->is_type('variable')) {
					$variations = $product->get_available_variations();
					//循環每個可變商品
					foreach ($variations as $variation) {
						// 獲取單個可變商品中的屬性並做格式化
						$variationAttributes = wc_get_formatted_variation($variation["attributes"], true, false);
						$productName = $product->get_title() . ' - ' . $variationAttributes;
						$ProductArray[] = array(
							'productName' => $productName,
						);
					}
				} else {
					$productName = $product->get_title();
					$ProductArray[] = array(
						'productName' => $productName,
					);
				}
			}
			wp_reset_postdata(); // 重置查询
		}
		return $ProductArray;
	}
	// function getSessionProduct($userID)
	// {
	// 	//WC() 會自動取得當前用戶ID
	// 	// $cart_session = \WC()->session->get('cart') ?? [];
	// 	$session_Handler = WC()->session;
	// 	$cart_session = isset($session_Handler) ? $session_Handler->get('cart') : [];

	// 	$sessionProduct = [];
	// 	$existingSessionProductIDs = [];
	// 	foreach ($cart_session as $data) {
	// 		$productID = $data['product_id'];
	// 		// 检查是否已存在相同的productID
	// 		if (!in_array($productID, $existingSessionProductIDs) && wc_get_product($productID)) {
	// 			$productName = wc_get_product($productID)->get_name();
	// 			$Product = array(
	// 				'productName' => $productName,
	// 				'productID' => $productID,
	// 			);
	// 			$sessionProduct[] = $Product;
	// 			// 将productID添加到已存在的数组中
	// 			$existingSessionProductIDs[] = $productID;
	// 		}
	// 	}
	// 	// var_dump($CartProducts);
	// 	$session_items_count = $sessionProduct !== [] ? count($sessionProduct) : 0;
	// 	return $sessionProduct;
	// }
}
