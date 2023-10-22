<!DOCTYPE html>
<html lang="zh-TW">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>無限創意行銷</title>
</head>

<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
	<?php do_action('thwec_before_contents'); ?><?php if (!isset($order) && isset($gift_card->order_id)) {
																								$order = wc_get_order($gift_card->order_id);
																							} ?><?php if (isset($order) && is_a($order, 'WC_Order_Refund')) {
																										$order = wc_get_order($order->get_parent_id());
																									} ?>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>無限創意行銷</title>
	<?php do_action('thwec_before_contents'); ?><?php if (!isset($order) && isset($gift_card->order_id)) {
																								$order = wc_get_order($gift_card->order_id);
																							} ?><?php if (isset($order) && is_a($order, 'WC_Order_Refund')) {
																										$order = wc_get_order($order->get_parent_id());
																									} ?><table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="thwec_template_wrapper">
		<tr>
			<td align="center" class="thwec-template-wrapper-column" valign="top" style="background-color: #f7f7f7; padding: 70px 0;" bgcolor="#f7f7f7">
				<div id="thwec_template_container">
					<table id="tp_temp_builder" width="600" cellspacing="0" cellpadding="0" class="main-builder thwec-template-block" style="max-width: 600px; width: 600px; margin: auto; box-sizing: border-box;" align="center">
						<tr>
							<td class="thwec-builder-column" style="vertical-align: top; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; border-style: none; border-color: transparent; background-color: #fff; background-image: none; background-position: center; background-size: 100%; background-repeat: no-repeat;" bgcolor="#fff">
								<table class="thwec-row thwec-block-one-column builder-block" id="tp_1001" cellpadding="0" cellspacing="0px" style="width: 100%; table-layout: fixed; max-width: 100%; margin: 0 auto; border-spacing: 0px; padding-top: 12px; padding-right: 10px; padding-bottom: 12px; padding-left: 10px; margin-top: 0px; margin-right: auto; margin-bottom: 0px; margin-left: auto; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px; border-style: dotted; border-color: #ddd; background-image: none; background-color: transparent; background-position: center; background-size: 100%; background-repeat: no-repeat;" width="100%" align="center" bgcolor="transparent">
									<tr>
										<td class="column-padding thwec-col thwec-columns" id="tp_1002" style="box-sizing: border-box; word-break: break-word; padding: 10px 10px; width: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px; text-align: center; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px; border-style: dotted; border-color: #ddd; background-image: none; background-color: transparent; background-position: center; background-size: 100%; background-repeat: no-repeat; vertical-align: top;" width="100%" align="center" bgcolor="transparent">
											<table class="thwec-block thwec-block-text builder-block" id="tp_1004" cellspacing="0" cellpadding="0" style='table-layout: fixed; margin: 0 auto; box-sizing: border-box; color: #636363; text-align: left; font-size: 13px; line-height: 22px; font-weight: normal; font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif; width: 100%; margin-top: 0px; margin-right: auto; margin-bottom: 0px; margin-left: auto;' align="center" width="100%">
												<tr style='color: #636363; font-size: 13px; font-weight: normal; line-height: 22px; font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;'>
													<td class="thwec-block-child thwec-block-text-holder" style='vertical-align: top; box-sizing: border-box; padding: 15px 15px; color: #636363; font-size: 13px; font-weight: normal; line-height: 22px; text-align: left; font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif; background-color: transparent; background-image: none; background-size: 100%; background-position: center; background-repeat: no-repeat; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; border-color: transparent; border-style: none; padding-top: 15px; padding-right: 15px; padding-bottom: 15px; padding-left: 15px;' align="left" bgcolor="transparent">
														<div class="wec-txt-wrap" style='color: #636363; font-size: 13px; font-weight: normal; line-height: 22px; font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;'>
															您好，感謝您報名了此堂課程<br style='color: #636363; font-size: 13px; font-weight: normal; line-height: 22px; font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;'>
														</div>
													</td>
												</tr>
											</table><?php $obj = isset($order) && is_a($order, "WC_Order") ? $order : null;
															do_action('custom_hook_name', $obj, $email); ?><table class="thwec-block thwec-block-text builder-block" id="tp_1007" cellspacing="0" cellpadding="0" style='table-layout: fixed; margin: 0 auto; box-sizing: border-box; color: #636363; text-align: left; font-size: 14px; line-height: 22px; font-weight: 700; font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif; width: 100%; margin-top: 0px; margin-right: auto; margin-bottom: 0px; margin-left: auto;' align="center" width="100%">
												<tr style='color: #636363; font-size: 14px; font-weight: 700; line-height: 22px; font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;'>
													<td class="thwec-block-child thwec-block-text-holder" style='vertical-align: top; box-sizing: border-box; padding: 15px 15px; color: #636363; font-size: 14px; font-weight: 700; line-height: 22px; text-align: left; font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif; background-color: transparent; background-image: none; background-size: 100%; background-position: center; background-repeat: no-repeat; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; border-color: transparent; border-style: none; padding-top: 15px; padding-right: 15px; padding-bottom: 15px; padding-left: 15px;' align="left" bgcolor="transparent">
														<div class="wec-txt-wrap" style='color: #636363; font-size: 14px; font-weight: 700; line-height: 22px; font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;'>
															課程須知<br style='color: #636363; font-size: 14px; font-weight: 700; line-height: 22px; font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;'>
														</div>
													</td>
												</tr>
											</table>
											<table class="thwec-block thwec-block-text builder-block" id="tp_1008" cellspacing="0" cellpadding="0" style='table-layout: fixed; margin: 0 auto; box-sizing: border-box; color: #636363; text-align: left; font-size: 13px; line-height: 22px; font-weight: normal; font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif; width: 100%; margin-top: 0px; margin-right: auto; margin-bottom: 0px; margin-left: auto;' align="center" width="100%">
												<tr style='color: #636363; font-size: 13px; font-weight: normal; line-height: 22px; font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;'>
													<td class="thwec-block-child thwec-block-text-holder" style='vertical-align: top; box-sizing: border-box; padding: 15px 15px; color: #636363; font-size: 13px; font-weight: normal; line-height: 22px; text-align: left; font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif; background-color: transparent; background-image: none; background-size: 100%; background-position: center; background-repeat: no-repeat; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; border-color: transparent; border-style: none; padding-top: 15px; padding-right: 15px; padding-bottom: 15px; padding-left: 15px;' align="left" bgcolor="transparent">
														<div class="wec-txt-wrap" style='color: #636363; font-size: 13px; font-weight: normal; line-height: 22px; font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;'>
															1.上課嚴禁錄音及錄影，請各位學員遵守規範<br style='color: #636363; font-size: 13px; font-weight: normal; line-height: 22px; font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;'>
														</div>
														<div class="wec-txt-wrap" style='color: #636363; font-size: 13px; font-weight: normal; line-height: 22px; font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;'>
															2.上課時請將手機等裝置調至靜音或震動，如需接聽電話請移步至教室外<br style='color: #636363; font-size: 13px; font-weight: normal; line-height: 22px; font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;'>
														</div>
														<div class="wec-txt-wrap" style='color: #636363; font-size: 13px; font-weight: normal; line-height: 22px; font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;'>
															3. 若當日無故曠課未到，則取消名額，謝謝合作 (重要請看三遍!)<br style='color: #636363; font-size: 13px; font-weight: normal; line-height: 22px; font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;'>
														</div>
														<div class="wec-txt-wrap" style='color: #636363; font-size: 13px; font-weight: normal; line-height: 22px; font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;'>
															4. 場地提供WIFI。<br style='color: #636363; font-size: 13px; font-weight: normal; line-height: 22px; font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;'>
														</div>
														<div class="wec-txt-wrap" style='color: #636363; font-size: 13px; font-weight: normal; line-height: 22px; font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;'>
															<u style='color: #636363; font-size: 13px; font-weight: normal; line-height: 22px; font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;'><b style='color: #636363; font-size: 13px; font-weight: normal; line-height: 22px; font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;'>課程小提醒</b>：最後，由於課程緊湊，建議您可先準備相關問題，於下課時發問。</u><br style='color: #636363; font-size: 13px; font-weight: normal; line-height: 22px; font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;'>
														</div>
														<div class="wec-txt-wrap" style='color: #636363; font-size: 13px; font-weight: normal; line-height: 22px; font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;'>
															<br style='color: #636363; font-size: 13px; font-weight: normal; line-height: 22px; font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;'>
														</div>
														<div class="wec-txt-wrap" style='color: #636363; font-size: 13px; font-weight: normal; line-height: 22px; font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;'>
															如果有任何想討論的問題，都可以加官方Line@詢問喔，我們都將誠摯的回應，謝謝！<br style='color: #636363; font-size: 13px; font-weight: normal; line-height: 22px; font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;'>
														</div>
														<div class="wec-txt-wrap" style='color: #636363; font-size: 13px; font-weight: normal; line-height: 22px; font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;'>
															官方Line ID：@898sheqq （https://lin.ee/J8up7ei）<br style='color: #636363; font-size: 13px; font-weight: normal; line-height: 22px; font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;'>
														</div>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</div>
			</td>
		</tr>
	</table>
</body>

</html>