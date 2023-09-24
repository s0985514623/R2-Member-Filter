<?php

declare(strict_types=1);
//判斷用戶是否登入，如果未登入就加入購物車則檔下來強迫用Google登入
namespace J7\WP_REACT_PLUGIN\React\Admin;

class userIsLogin
{
	public function __construct() //類型指定
	{
		\add_action('woocommerce_add_to_cart_validation', array($this, 'init'), 10, 1);
	}
	public function init($passed)
	{
		if (!\is_user_logged_in()) {
?>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet"
	href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
<div
	class="noLoginPup flex flex-col fixed right-0 top-32 z-[1000] w-80 bg-white font-sans shadow-xl duration-300 animate__animated animate__fadeInRight">
	<p class="flex items-center px-8 min-h-[100px] text-[#4562A8] font-semibold">請先加入會員，<br>才能加入購物車哦！
	</p>
	<a class="flex items-center justify-center w-full bg-[#4562A8] text-sm font-semibold text-white h-10 gap-2 fill-white hover:fill-[#4562A8] hover:text-[#374a6d] hover:bg-white"
		href="<?= home_url() ?>/wp-login.php?loginSocial=google" data-plugin="nsl" data-action="connect"
		data-redirect="current" data-provider="google" data-popupwidth="600" data-popupheight="600">
		<svg class="" class="google" xmlns="http://www.w3.org/2000/svg" height="1em"
			viewBox="0 0 488 512">
			<!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
			<path
				d="M488 261.8C488 403.3 391.1 504 248 504 110.8 504 0 393.2 0 256S110.8 8 248 8c66.8 0 123 24.5 166.3 64.9l-67.5 64.9C258.5 52.6 94.3 116.6 94.3 256c0 86.5 69.1 156.6 153.7 156.6 98.2 0 135-70.4 140.8-106.9H248v-85.3h236.1c2.3 12.7 3.9 24.9 3.9 41.4z" />
		</svg>
		</svg> Google 登入
	</a>
	<div class="closeBtn absolute top-4 right-4 cursor-pointer"><svg class="fill-[#374a6d]"
			xmlns="http://www.w3.org/2000/svg" height="1.25em" viewBox="0 0 384 512">
			<!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
			<path
				d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z" />
		</svg></div>
</div>
<script>
setTimeout(() => {

	// 獲取匹配元素
	const LoginPup = document.querySelector('.noLoginPup');
	const closeBtn = LoginPup.querySelector('.closeBtn');

	// 添加點擊事件監聽器
	closeBtn.addEventListener('click', function() {
		// 檢查是否具有 'animate__fadeInRight' 類
		if (LoginPup.classList.contains('animate__fadeInRight')) {
			// 移除 'animate__fadeInRight' 並添加 'animate__fadeOutRight' 類
			LoginPup.classList.remove('animate__fadeInRight');
			LoginPup.classList.add('animate__fadeOutRight');
		}
	});
}, 0);
</script>
<?php
			return false; // 阻止加入購物車
		} //else => 未登入出現彈窗
		return $passed;
	}
}