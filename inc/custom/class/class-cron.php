<?php

declare(strict_types=1);

namespace J7\WP_REACT_PLUGIN\React\Admin;

use J7\WP_REACT_PLUGIN\React\Admin\Bootstrap;

//1.做出一個定時cron 判斷是否有使用者加入購物車但未結帳
//2.如果第一點符合則加入一個單次性的cron 用於寄信=>相同的hookName但參數不同不會被覆蓋
//3.做出一個手動按鈕=>可以直接並寄信
//4.做出一個下拉清單=>可以選擇要寄信的頻率(每天/每週/每月/每年)
class Cron extends Bootstrap
{
	private $eventName; //已棄用
	function __construct()
	{
		//註冊Cron事件
		\add_action('r2_repeatingCron', array($this, 'repeatingCronCallBack'), 10, 3);
		\add_action('r2_oneCron', array($this, 'oneCronCallBack'), 10, 3);
		//在會員篩選頁面加入子頁面
		\add_action('admin_menu', array($this, 'cron_setting_submenu_page'));
		// \add_action('woocommerce_add_to_cart', array($this, 'woocommerce_add_to_cart'), 10);
	}
	//cron設定子頁面
	function cron_setting_submenu_page()
	{
		\add_submenu_page(
			'r2-member-filter', // 父级菜单的slug
			'購物車未結提醒設定', // 子菜单页面标题
			'購物車未結提醒設定', // 子菜单标题
			'manage_options', // 用户权限
			'cron_setting', // 子菜单的slug
			array($this, 'cron_setting_callback') // 回调函数
		);
	}
	function cron_setting_callback()
	{
		// 如果用戶點擊了保存按鈕，則更新選項
		if (isset($_POST['r2_cart_notify_save'])) {
			$cartNotify_isSet = ($_POST['r2_cartNotify_isSet']); // 獲取表單提交的值
			update_option('r2_cartNotify_isSet', $cartNotify_isSet); // 更新選項
			$r2_scheduleTime = sanitize_text_field($_POST['r2_scheduleTime']); // 獲取表單提交的值
			update_option('r2_scheduleTime', $r2_scheduleTime); // 更新選項
			echo '<div class="updated"><p>設置已保存。</p></div>';

			if ($cartNotify_isSet == 'on') {
				$this->setScheduleEvent(false);
				$this->setScheduleEvent(true, $r2_scheduleTime);
			} else {
				# code...
				$this->setScheduleEvent(false);
			}
		}

?>
<div class="pageWrap">
	<h2>購物車未結提醒設定</h2>
	<form method="post" action="">
		<div class="pageSection" style="display: flex;gap:10px ;margin:10px 0px;">
			<!-- <input type="checkbox" id="r2_cartNotify_isSet" name="r2_cartNotify_isSet" />
					<label for="r2_cartNotify_isSet">是否啟用未結提醒</label> -->

			<div><span style="display:block; width: 180px;">是否啟用未結提醒</span></div>
			<div><input type="checkbox" name="r2_cartNotify_isSet" id="r2_cartNotify_isSet"
					<?php echo (get_option('r2_cartNotify_isSet', 1) === 'on') ? 'checked' : '' ?>></div>
		</div>
		<div class="pageSection" style="display: flex;gap:10px">
			<div><span style="display:block; width: 180px;">要每隔多久檢查一次：</span></div>
			<select name="r2_scheduleTime" id="r2_scheduleTime">
				<?php
						$argsArray = array(
							'hourly' => '每小時',
							'twicedaily' => '每天兩次',
							'daily' => '每天一次',
							'weekly' => '每週一次',
							'fifteendays' => '每 15 天',
							'monthly' => '每月',
						);
						foreach ($argsArray as $key => $value) {
						?>
				<option value="<?= $key ?>"
					<?php echo (get_option('r2_scheduleTime', 1) === $key) ? 'selected' : '' ?>><?= $value ?>
				</option>
				<?php
						}
						?>
			</select>
		</div>
		<div class="pageSection">
			<button type="submit" name="r2_cart_notify_save" class="button-primary">保存</button>
			<!-- 添加保存按钮 -->
		</div>
	</form>
</div>
<?php
	}
	//每固定時間觸發檢查
	public function setScheduleEvent($isSet = false, $scheduleTime = 'daily')
	{
		//如果有啟用才註冊事件
		if ($isSet) {

			if (!wp_next_scheduled('r2_repeatingCron')) {
				wp_schedule_event(time(), $scheduleTime, 'r2_repeatingCron');
			}
		} else {
			//清除事件
			\wp_unschedule_hook('r2_repeatingCron');
		}
	}
	//
	function repeatingCronCallBack()
	{
		$usersDataAarray = $this->getMember();
		if ($usersDataAarray) {
			foreach ($usersDataAarray as $usersData) {
				# 如果有商品在購物車內則加入單次性事件=>寄信
				if ($usersData['CartProducts']) {
					# code...
					$content = '';
					$CartProducts = $usersData['CartProducts'];
					foreach ($CartProducts as $Products) {
						$productsName = $Products['productName'];
						$content = $content . $productsName . '<br>';
					}
					$mailArgs = array(
						'to' => $usersData['Email'],
						'subject' => '提醒您購物車中有課程尚未結帳唷',
						'content' => '提醒您尚有：<br>' . $content . '等課程未結帳唷',
					);
					\wp_schedule_single_event(time() + 60, 'r2_oneCron', $mailArgs, true);
				}
			}
		}
	}
	// corn事件處理函數，接收參數並寄信
	public function oneCronCallBack($to = "s0985514623@gmail.com", $subject = "testNav", $content)
	{

		$headers = array('Content-Type: text/html; charset=UTF-8');
		\wp_mail($to, $subject, $content, $headers);
	}
	//取得所有會員資料
	function getMember()
	{
		//測試用
		$args = array('number' => -1, 'search' => '87', 'search_columns' => array('id'));
		//正式用=>取得客戶與訂閱者
		// $args = array('number' => -1, 'role__in' => array('customer', 'subscriber'));
		$user_query = new \WP_User_Query($args);
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
					'CartProducts' => $this->getCartProducts($userID),
					// 'sessionProduct' => $this->getSessionProduct($userID),
				);
				// var_dump($userDate);
				// echo '<hr>';
				$usersDataAarray[] = $userDate;
			}
		}
		return $usersDataAarray;
	}

	//取得購物車未結
	function getCartProducts($userID)
	{
		// 使用 get_user_meta 取得woocommerce的永久購物車資料 get_current_blog_id() 取得網站ID
		$cart_data = get_user_meta($userID, '_woocommerce_persistent_cart_' . get_current_blog_id(), true)['cart'] ?? [];

		$CartProducts = [];
		$existingCartProductIDs = [];
		foreach ($cart_data as $data) {
			$productID = $data['product_id'];
			// 检查是否已存在相同的productID
			if (!in_array($productID, $existingCartProductIDs) && wc_get_product($productID)) {
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

	//以下棄用
	//主要寄信功能function，使用wp_unschedule_hook清除符合hookName的corn，並安排新的事件
	public function set_mail($to = "s0985514623@gmail.com", $subject = "123", $content = "456", $sendTime = 3600)
	{
		//清除事件
		// $this->clear_cron();
		// 安排新的事件
		\wp_schedule_single_event(time() + $sendTime, $this->eventName . '_Cron_Hook', array($to, $subject, $content), true);
	}

	//單除拉出清除事件方法
	public function clear_cron()
	{
		\wp_unschedule_hook($this->eventName . '_Cron_Hook');
	}

	public function woocommerce_add_to_cart()
	{
		$user = \wp_get_current_user();
		$mail = $user->user_email;
		$this->set_mail($mail, 'woocommerce_add_to_cart3', '$content', 30);
	}
}
//問題:怎麼在用戶登入後才實例化類並帶入userID

//怎麼用:=>可以work
// new Cron('s0985514623');