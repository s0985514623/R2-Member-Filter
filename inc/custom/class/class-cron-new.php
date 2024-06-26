<?php
/**
 * CronNew class
 */

declare (strict_types = 1);

namespace J7\WP_REACT_PLUGIN\React\Admin;

use J7\WP_REACT_PLUGIN\React\Admin\Bootstrap;

/** 1.__construct確保每一個用戶都有自己的Cron事件
 * 2.set_mail與clear_cron帶入userID用於設定與清除指定Cron事件
 * 3.透過getMember與getCartProducts取得會員跟購物車中資料
 */
final class CronNew extends Bootstrap {
	/**
	 * __construct function
	 */
	public function __construct() {
		// 會不會有效能問題??.
		// 確保每個用戶都能有自己個Cron事件，但只有在呼叫自己的Cron事件時才會執行
		\add_action( 'plugins_loaded', array( $this, 'addUserCronHook' ) );
		// 在會員篩選頁面加入子頁面
		\add_action( 'admin_menu', array( $this, 'cron_setting_submenu_page' ) );
		// 註冊一個手動寄信的cron事件
		\add_action( 'r2_Set_Manually', array( $this, 'set_Manually_CronExec' ), 10, 3 );
		// 在訂單完成後清除使用者的Cron事件
		\add_action( 'woocommerce_order_status_completed', array( $this, 'order_completed_clear_cron' ), 10, 1 );
		// 在使用者移除購物車時清除Cron事件,並重新設定
		\add_action( 'woocommerce_cart_item_removed', array( $this, 'woocommerce_cart_item_removed' ) );
		// 在成功加入購物車之後執行
		\add_action( 'woocommerce_add_to_cart', array( $this, 'set_mail' ) );
	}

	/**
	 * 為每個會員添加 hook function
	 *
	 * @return void
	 */
	public function addUserCronHook() {
		$user_query = new \WP_User_Query( array( 'number' => -1 ) );
		$users      = $user_query->get_results();
		if ( ! empty( $users ) ) {
			foreach ( $users as $user ) {
				\add_action( $user->ID . '_Cron_Hook', array( $this, 'cartProducts_CronExec' ), 10, 3 );
			}
		}
	}

	/**
	 * Cron設定子頁面 function
	 *
	 * @return void
	 */
	public function cron_setting_submenu_page() {
		\add_submenu_page(
			'r2-member-filter', // 父级菜单的slug
			'購物車未結提醒設定', // 子菜单页面标题
			'購物車未結提醒設定', // 子菜单标题
			'manage_options', // 用户权限
			'cron_setting', // 子菜单的slug
			array( $this, 'cron_setting_callback' ) // 回调函数
		);
	}
	// TODO 有空在優化這個區塊，使用可自由增加的方式
	public function cron_setting_callback() {
		// 如果用戶點擊了保存按鈕，則更新選項
		if ( isset( $_POST['r2_cart_notify_save'] ) ) {
			$r2_set_mail_1 = sanitize_text_field( $_POST['r2_set_mail_1'] ); // 獲取表單提交的值
			isset( $_POST['r2_set_mail_1'] ) && update_option( 'r2_set_mail_1', $r2_set_mail_1 ); // 更新選項
			$r2_set_mail_2 = sanitize_text_field( $_POST['r2_set_mail_2'] ); // 獲取表單提交的值
			isset( $_POST['r2_set_mail_2'] ) && update_option( 'r2_set_mail_2', $r2_set_mail_2 ); // 更新選項
			$r2_set_mail_3 = sanitize_text_field( $_POST['r2_set_mail_3'] ); // 獲取表單提交的值
			isset( $_POST['r2_set_mail_3'] ) && update_option( 'r2_set_mail_3', $r2_set_mail_3 ); // 更新選項
			$r2_set_mail_4 = sanitize_text_field( $_POST['r2_set_mail_4'] ); // 獲取表單提交的值
			isset( $_POST['r2_set_mail_4'] ) && update_option( 'r2_set_mail_4', $r2_set_mail_4 ); // 更新選項
			$r2_set_mail_5 = sanitize_text_field( $_POST['r2_set_mail_5'] ); // 獲取表單提交的值
			isset( $_POST['r2_set_mail_5'] ) && update_option( 'r2_set_mail_5', $r2_set_mail_5 ); // 更新選項
			echo '<div class="updated"><p>設置已保存。</p></div>';
		}

		?>
<div class="pageWrap">
	<h2>購物車未結提醒設定</h2>
	<form method="post" action="">
		<div class="pageSection" style="display: flex;gap:10px;align-items: center;">
			<div><span style="display:block; width: 120px;">第一次發送時間：(小時制)</span></div>
			<div><input type="text" name="r2_set_mail_1"
					value="<?php echo esc_attr( get_option( 'r2_set_mail_1', 1 ) ); ?>" style="text-align: right;"
					placeholder="留空為不進行Mail發送">
				<span>小時後</span>
			</div>
		</div>
		<div class="pageSection" style="display: flex;gap:10px;align-items: center;">
			<div><span style="display:block; width: 120px;">第二次發送時間：(小時制)</span></div>
			<div><input type="text" name="r2_set_mail_2"
					value="<?php echo esc_attr( get_option( 'r2_set_mail_2' ) ); ?>" style="text-align: right;"
					placeholder="留空為不進行Mail發送">
				<span>小時後</span>
			</div>
		</div>
		<div class="pageSection" style="display: flex;gap:10px;align-items: center;">
			<div><span style="display:block; width: 120px;">第三次發送時間：(小時制)</span></div>
			<div><input type="text" name="r2_set_mail_3"
					value="<?php echo esc_attr( get_option( 'r2_set_mail_3' ) ); ?>" style="text-align: right;"
					placeholder="留空為不進行Mail發送">
				<span>小時後</span>
			</div>
		</div>
		<div class="pageSection" style="display: flex;gap:10px;align-items: center;">
			<div><span style="display:block; width: 120px;">第四次發送時間：(小時制)</span></div>
			<div><input type="text" name="r2_set_mail_4"
					value="<?php echo esc_attr( get_option( 'r2_set_mail_4' ) ); ?>" style="text-align: right;"
					placeholder="留空為不進行Mail發送">
				<span>小時後</span>
			</div>
		</div>
		<div class="pageSection" style="display: flex;gap:10px;align-items: center;">
			<div><span style="display:block; width: 120px;">第五次發送時間：(小時制)</span></div>
			<div><input type="text" name="r2_set_mail_5"
					value="<?php echo esc_attr( get_option( 'r2_set_mail_5' ) ); ?>" style="text-align: right;"
					placeholder="留空為不進行Mail發送">
				<span>小時後</span>
			</div>
		</div>
		<div class="pageSection">
			<button type="submit" name="r2_cart_notify_save" class="button-primary">保存</button>
			<!-- 添加保存按钮 -->
		</div>
	</form>
</div>
		<?php
	}
	public function cartProducts_CronExec( $to, $subject, $content ) {
		$headers = array( 'Content-Type: text/html; charset=UTF-8' );
		\wp_mail( $to, $subject, $content, $headers );
	}

	/**
	 * 主要寄信功能function
	 * 帶入使用者ID與新加入購物車的商品ID
	 */
	public static function set_mail() {
		$user_id = get_current_user_id();
		// 先清除事件後再加入新事件
		self::clear_cron( $user_id );

		// 取得使用者資料
		$usersDataArray = self::getMember( $user_id );
		// error_log( '使用者資料' . print_r( $usersDataArray, true ) );
		// 取得使用者名稱
		$UserName = $usersDataArray['UserName'];
		// 取得現有購物車商品
		$CartProducts = $usersDataArray['CartProducts'];

		// 當購物車內沒有商品時不執行.
		if ( ! empty( $CartProducts ) ) {
			// 把外部變數帶入function
			self::Send_Content( $CartProducts, $UserName );
			// 處理Mail template
			ob_start();
			include plugin_dir_path( __FILE__ ) . '../../templates/reminder-add-to-cart.php';
			$content  = ob_get_clean();
			$mailArgs = array(
				'to'      => $usersDataArray['Email'],
				'subject' => '提醒您購物車中有課程尚未結帳唷',
				'content' => $content,
			);

			// 取得發信時間=>跑5次
			for ( $i = 1; $i < 6; $i++ ) {
				// code...
				if ( get_option( 'r2_set_mail_' . $i . '', 0 ) ) {
					$time = intval( get_option( 'r2_set_mail_' . $i . '' ) * 3600 );
					\wp_schedule_single_event( time() + $time, $user_id . '_Cron_Hook', $mailArgs, true );
				}
			}
		}
	}

	// 自定義信件內容
	public static function Send_Content( $CartProducts, $UserName ) {
		// 插入自定義內容在woocommerce_email中
		add_action(
			'custom_hook_name',
			function () use ( $CartProducts ) {
				foreach ( $CartProducts as $CartProduct ) {

					?>
<div style="display:flex;align-items: center;">
	<div style="width:20%">
		<a href="<?php echo $CartProduct['productLink']; ?>">
			<img style="width:100%" src="<?php echo $CartProduct['productImage']; ?>" alt="">
		</a>
	</div>
	<div style="width:80%;text-align: left;padding:0 10px 10px;">
		<div>
			<a style="color:#4562a8;text-decoration:none;font-size:18px;font-weight: 700;"
				href="<?php echo $CartProduct['productLink']; ?>"><?php echo $CartProduct['productName']; ?></a>
		</div>
		<div style="padding:10px 0px"><?php echo $CartProduct['productShortDescription']; ?></div>
		<div>NT$<?php echo number_format( $CartProduct['productPrice'] ); ?></div>
	</div>

</div>
					<?php
					// code...
				}
			},
			5
		);
		// 插入自定義內容在woocommerce_email中
		add_action(
			'custom_customer_details',
			function () use ( $UserName ) {
				?>
<span
	style="color:#636363;width:100%;display:block;text-align:left;padding:15px 15px 0"><?php echo $UserName; ?>，您好</span>
				<?php
			},
			5
		);
	}

	// 手動寄信function
	public static function set_Manually_Mail( $subject, $userEmail, $date, $content, $template ) {
		// 插入自定義內容在woocommerce_email中
		add_action(
			'custom_hook_name',
			function () use ( $content ) {
				echo '<div style="text-align: left;padding:15px;">' . $content . '</div>';
			},
			5
		);
		// 選擇要發送的範本
		switch ( $template ) {
			case 'courses_info':
				ob_start();
				include plugin_dir_path( __FILE__ ) . '../../templates/courses_info.php';
				$fxnContent = ob_get_clean();
				break;
			case 'template1':
				ob_start();
				include plugin_dir_path( __FILE__ ) . '../../templates/template_1.php';
				$fxnContent = ob_get_clean();
				break;
			case 'template2':
				ob_start();
				include plugin_dir_path( __FILE__ ) . '../../templates/template_2.php';
				$fxnContent = ob_get_clean();
				break;
			default:
				ob_start();
				include plugin_dir_path( __FILE__ ) . '../../templates/courses_info.php';
				$fxnContent = ob_get_clean();
		}

		$mailArgs  = array(
			'userEmail' => $userEmail,
			'subject'   => $subject,
			'content'   => $fxnContent,
		);
		$dateTime  = new \DateTime( $date, wp_timezone() );
		$timestamp = $dateTime->getTimestamp();
		if ( ! \wp_next_scheduled( 'r2_Set_Manually', $mailArgs ) ) {
			\wp_schedule_single_event( $timestamp, 'r2_Set_Manually', $mailArgs, true );
		}
	}

	// 手動寄信Cron 回調
	public function set_Manually_CronExec( $userEmail = 's0985514623@gmail.com', $subject = '課前通知', $content = 'testContent' ) {
		$adminEmail = \get_option( 'admin_email' );
		$headers    = array(
			'Content-Type: text/html; charset=UTF-8',
			'Bcc:' . $userEmail . '',
		);
		\wp_mail( $adminEmail, $subject, $content, $headers );
	}

	// 單除拉出清除事件方法
	public static function clear_cron( $user_id ) {
				// error_log( '單獨拉出的clear_cron userID:' . $userID );
				$wp_unschedule_hook_int = \wp_unschedule_hook( $user_id . '_Cron_Hook' );
		if ( $wp_unschedule_hook_int === false ) {
			error_log( 'clear_cron取消事件失敗:' . $wp_unschedule_hook_int );
		}
		// else {
		// error_log( 'wp_unschedule_hook_int:' . $wp_unschedule_hook_int );
		// }
	}
	// 訂單完成後清除使用者的Cron事件
	public static function order_completed_clear_cron( $order_id ) {
		$userID = get_post_meta( $order_id, '_customer_user', true );
				error_log( 'order_completed_clear_cron userID:' . $userID );
				$wp_unschedule_hook_int = \wp_unschedule_hook( $userID . '_Cron_Hook' );
		if ( $wp_unschedule_hook_int === false ) {
			error_log( '取消事件失敗:' . $wp_unschedule_hook_int );
		} else {
			error_log( 'wp_unschedule_hook_int:' . $wp_unschedule_hook_int );
		}
		// 下方不起作用
		// self::clear_cron($userID);
	}
	// 取得會員資料
	public static function getMember( $userID ) {
		$args           = array(
			'search'         => $userID,
			'search_columns' => array( 'id' ),
		);
		$user_query     = new \WP_User_Query( $args );
		$users          = $user_query->get_results();
		$usersDataArray = array();
		// 遍历用户数据并输出
		if ( ! empty( $users ) ) {
			foreach ( $users as $user ) {
				$userDate = array(
					'UserName'     => $user->display_name,
					'Email'        => $user->user_email,
					'CartProducts' => self::getCartProducts( $userID ),
				);
				// var_dump($userDate);
				// echo '<hr>';
				$usersDataArray[] = $userDate;
			}
		}
		return $usersDataArray[0];
	}

	// 取得購物車未結
	public static function getCartProducts( $userID ) {
		// 因為執行序的問題,不能使用永久購物車資料.
		// $cart_data = get_user_meta( $userID, '_woocommerce_persistent_cart_' . get_current_blog_id(), true )['cart'] ?? array();
		// 需要使用全域變數
		global $woocommerce;
		$cart_data              = $woocommerce->cart->get_cart();
		$CartProducts           = array();
		$existingCartProductIDs = array();
		foreach ( $cart_data as $data ) {
			$productID = $data['product_id'];
			// 检查是否已存在相同的productID
			if ( ! in_array( $productID, $existingCartProductIDs ) && wc_get_product( $productID ) ) {
				// 取得產品名稱
				$productName = wc_get_product( $productID )->get_name();
				// 取得產品圖片
				$productImage = wp_get_attachment_url( absint( wc_get_product( $productID )->get_image_id() ) );
				// 取得產品短介
				$productShortDescription = wc_get_product( $productID )->get_short_description();
				// 取得產品價錢
				$productPrice = floatval( wc_get_product( $productID )->get_price() );
				// 取得商品連結
				$productLink    = wc_get_product( $productID )->get_permalink();
				$CartProducts[] = array(
					'productName'             => $productName,
					'productID'               => $productID,
					'productImage'            => $productImage,
					'productShortDescription' => $productShortDescription,
					'productPrice'            => $productPrice,
					'productLink'             => $productLink,
				);
				// 将productID添加到已存在的数组中
				$existingCartProductIDs[] = $productID;
			}
		}
		return $CartProducts;
	}
	/**
	 * Woocommerce_cart_item_removed function
	 *
	 * @return void
	 */
	public function woocommerce_cart_item_removed() {
		// error_log( 'woocommerce_cart_item_removed' );
		// 重新設定事件
		self::set_mail();
	}
}
