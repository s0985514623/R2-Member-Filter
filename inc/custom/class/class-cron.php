<?php

declare(strict_types=1);

namespace J7\WP_REACT_PLUGIN\React\Admin;

use J7\WP_REACT_PLUGIN\React\Admin\Bootstrap;

class Cron extends Bootstrap
{
	private $eventName;
	//初始化動態的hook Name 取得每一個使用者的mail(其實也可以用userID)作為hook，但是吃同一支function去註冊corn事件
	public function __construct($Email)
	{
		$this->eventName = $Email;
		add_action($this->eventName . '_Cron_Hook', 'CartProducts_Cron_Exec', 10, 3);
	}

	// corn事件處理函數，接收參數並寄信
	public function CartProducts_Cron_Exec($to, $subject, $content)
	{
		$content_type = function () {
			return 'text/html';
		};
		add_filter('wp_mail_content_type', $content_type);
		wp_mail($to, $subject, $content);
		remove_filter('wp_mail_content_type', $content_type);
	}

	//主要寄信功能function，使用wp_unschedule_hook清除符合hookName的corn，並安排新的事件
	public function set_mail($to = "s0985514623@gmail.com", $subject = "123", $content = "456", $sendTime = 3600)
	{
		//清除事件
		$this->clear_cron();
		// 安排新的事件
		wp_schedule_single_event(time() + $sendTime, $this->eventName . '_Cron_Hook', array($to, $subject, $content));
	}

	//單除拉出清除事件方法
	public function clear_cron()
	{
		wp_unschedule_hook($this->eventName . '_Cron_Hook');
	}
}

//怎麼用:
// $r2 = new Cron('s0985514623');
// $r2->set_mail('s0985514623@gmail.com', '我是subject', '我是content');