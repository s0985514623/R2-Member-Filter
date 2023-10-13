<?php

declare(strict_types=1);

namespace J7\WP_REACT_PLUGIN\React\Admin;

use J7\WP_REACT_PLUGIN\React\Admin\Bootstrap;

//1.__construct確保每一個用戶都有自己的Cron事件
//2.set_mail與clear_cron帶入userID用於設定與清除指定Cron事件
//3.透過getMember與getCartProducts取得會員跟購物車中資料
class CronNew extends Bootstrap
{
	function __construct()
	{
		//會不會有效能問題??
		//確保每個用戶都能有自己個Cron事件，但只有在呼叫自己的Cron事件時才會執行
		$user_query = new \WP_User_Query(array('number' => -1));
		$users = $user_query->get_results();
		if (!empty($users)) {
			foreach ($users as $user) {
				\add_action($user->ID . '_Cron_Hook', array($this, 'cartProducts_CronExec'), 10, 3);
			}
		}
		//在會員篩選頁面加入子頁面
		\add_action('admin_menu', array($this, 'cron_setting_submenu_page'));
		//註冊一個手動寄信的cron事件
		\add_action('r2_Set_Manually', array($this, 'set_Manually_CronExec'), 10, 3);
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
			$r2_set_mail_1 = sanitize_text_field($_POST['r2_set_mail_1']); // 獲取表單提交的值
			isset($_POST['r2_set_mail_1']) && update_option('r2_set_mail_1', $r2_set_mail_1); // 更新選項
			$r2_set_mail_2 = sanitize_text_field($_POST['r2_set_mail_2']); // 獲取表單提交的值
			isset($_POST['r2_set_mail_2']) && update_option('r2_set_mail_2', $r2_set_mail_2); // 更新選項
			$r2_set_mail_3 = sanitize_text_field($_POST['r2_set_mail_3']); // 獲取表單提交的值
			isset($_POST['r2_set_mail_3']) && update_option('r2_set_mail_3', $r2_set_mail_3); // 更新選項
			$r2_set_mail_4 = sanitize_text_field($_POST['r2_set_mail_4']); // 獲取表單提交的值
			isset($_POST['r2_set_mail_4']) && update_option('r2_set_mail_4', $r2_set_mail_4); // 更新選項
			$r2_set_mail_5 = sanitize_text_field($_POST['r2_set_mail_5']); // 獲取表單提交的值
			isset($_POST['r2_set_mail_5']) && update_option('r2_set_mail_5', $r2_set_mail_5); // 更新選項
			echo '<div class="updated"><p>設置已保存。</p></div>';
		}

?>
		<div class="pageWrap">
			<h2>購物車未結提醒設定</h2>
			<form method="post" action="">
				<div class="pageSection" style="display: flex;gap:10px;align-items: center;">
					<div><span style="display:block; width: 120px;">第一次發送時間：(小時制)</span></div>
					<div><input type="text" name="r2_set_mail_1" value="<?php echo esc_attr(get_option('r2_set_mail_1', 1)); ?>" style="text-align: right;" placeholder="留空為不進行Mail發送">
						<span>小時後</span>
					</div>
				</div>
				<div class="pageSection" style="display: flex;gap:10px;align-items: center;">
					<div><span style="display:block; width: 120px;">第二次發送時間：(小時制)</span></div>
					<div><input type="text" name="r2_set_mail_2" value="<?php echo esc_attr(get_option('r2_set_mail_2')); ?>" style="text-align: right;" placeholder="留空為不進行Mail發送">
						<span>小時後</span>
					</div>
				</div>
				<div class="pageSection" style="display: flex;gap:10px;align-items: center;">
					<div><span style="display:block; width: 120px;">第三次發送時間：(小時制)</span></div>
					<div><input type="text" name="r2_set_mail_3" value="<?php echo esc_attr(get_option('r2_set_mail_3')); ?>" style="text-align: right;" placeholder="留空為不進行Mail發送">
						<span>小時後</span>
					</div>
				</div>
				<div class="pageSection" style="display: flex;gap:10px;align-items: center;">
					<div><span style="display:block; width: 120px;">第四次發送時間：(小時制)</span></div>
					<div><input type="text" name="r2_set_mail_4" value="<?php echo esc_attr(get_option('r2_set_mail_4')); ?>" style="text-align: right;" placeholder="留空為不進行Mail發送">
						<span>小時後</span>
					</div>
				</div>
				<div class="pageSection" style="display: flex;gap:10px;align-items: center;">
					<div><span style="display:block; width: 120px;">第五次發送時間：(小時制)</span></div>
					<div><input type="text" name="r2_set_mail_5" value="<?php echo esc_attr(get_option('r2_set_mail_5')); ?>" style="text-align: right;" placeholder="留空為不進行Mail發送">
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
	function cartProducts_CronExec($to, $subject, $content)
	{
		$headers = array('Content-Type: text/html; charset=UTF-8');
		\wp_mail($to, $subject, $content, $headers);
	}

	//主要寄信功能function
	static function set_mail($userID, $product_id)
	{
		//先清除事件後再加入新事件
		self::clear_cron($userID);

		//取得使用者資料
		$usersDataArray = self::getMember($userID);
		$content = '';
		$CartProducts = $usersDataArray['CartProducts'];
		foreach ($CartProducts as $Products) {
			$productsName = $Products['productName'];
			$content = $content . $productsName . '<br>';
		}
		//取得剛加入購物車的商品
		$content = $content . wc_get_product($product_id)->get_name() . '<br>';
		$mailArgs = array(
			'to' => $usersDataArray['Email'],
			'subject' => '提醒您購物車中有課程尚未結帳唷',
			'content' => '提醒您尚有：<br>' . $content . '等課程未結帳唷',
		);

		//取得發信時間=>跑5次
		for ($i = 1; $i < 6; $i++) {
			# code...
			if (get_option('r2_set_mail_' . $i . '', 0)) {
				$time = intval(get_option('r2_set_mail_' . $i . '') * 3600);
				\wp_schedule_single_event(time() + $time, $userID . '_Cron_Hook', $mailArgs, true);
			};
		}
		// \wp_schedule_single_event(time() + 3600, $userID . '_Cron_Hook', $mailArgs, true);
	}

	//手動寄信function
	static function set_Manually_Mail($subject, $userEmail, $date, $content)
	{
		$mailArgs = array(
			'userEmail' => $userEmail,
			'subject' => $subject,
			'content' => $content,
		);
		$dateTime = new \DateTime($date, wp_timezone());
		$timestamp = $dateTime->getTimestamp();
		if (!\wp_next_scheduled('r2_Set_Manually', $mailArgs)) {
			\wp_schedule_single_event($timestamp, 'r2_Set_Manually', $mailArgs, true);
		}
	}

	//手動寄信Cron 回調
	public function set_Manually_CronExec($userEmail = "s0985514623@gmail.com", $subject = "課前通知", $content = "testContent")
	{
		$adminEmail = \get_option('admin_email');
		$headers = array(
			'Content-Type: text/html; charset=UTF-8',
			'Bcc:' . $userEmail . ''
		);
		\wp_mail($adminEmail, $subject, $content, $headers);
	}

	//單除拉出清除事件方法
	static function clear_cron($userID)
	{
		\wp_unschedule_hook($userID . '_Cron_Hook');
	}

	//取得會員資料
	static function getMember($userID)
	{
		$args = array('search' => $userID, 'search_columns' => array('id'));
		$user_query = new \WP_User_Query($args);
		$users = $user_query->get_results();
		$usersDataArray = [];
		// 遍历用户数据并输出
		if (!empty($users)) {
			foreach ($users as $user) {
				$userDate = array(
					'UserName' => $user->display_name,
					'Email' => $user->user_email,
					'CartProducts' => self::getCartProducts($userID),
				);
				// var_dump($userDate);
				// echo '<hr>';
				$usersDataArray[] = $userDate;
			}
		}
		return $usersDataArray[0];
	}

	//取得購物車未結
	static function getCartProducts($userID)
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
				$CartProducts[] = array(
					'productName' => $productName,
					'productID' => $productID,
				);
				// 将productID添加到已存在的数组中
				$existingCartProductIDs[] = $productID;
			}
		}
		return $CartProducts;
	}
}
