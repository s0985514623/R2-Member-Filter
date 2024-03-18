# R2-Member-Filter (RMF) 外掛介紹

>一句話說明：可以對已註冊會員進行篩選，以及將篩選結果匯出成 CSV 檔案，並可以對已加入購物車但未結帳用戶發送Email提醒。

## 使用方法

啟用外掛之後會自動新增一個會員篩選的頁面
- 進入頁面便會自動取得"所有"會員資料
<img src="https://github.com/s0985514623/R2-Member-Filter/assets/35906564/e6e14185-946e-4011-a683-68ef6d38720c">

- 會員篩選展開可以依照會員名稱、Email、已購買過的商品、購物車未結商品進行篩選
<img src="https://github.com/s0985514623/R2-Member-Filter/assets/35906564/15710e89-892a-406c-a0ec-e660dac18b9e">

- 手動寄信展開可以對勾選會員進行寄信，並且可以選擇寄信的範本(需有Email php範本)，選擇寄信時間後會自動進入Cron排程
<img src="https://github.com/s0985514623/R2-Member-Filter/assets/35906564/27e8da28-e5b2-44b2-9385-c6d1ab5deb6d">

- 可以依照已完成訂單數量進行排序，會員名稱及訂單數量為超連結，可以連結至訂單頁面顯示該使用者的歷史訂單，會員勾選後可以匯出成CSV檔案
<img src="https://github.com/s0985514623/R2-Member-Filter/assets/35906564/567806ab-9783-4e15-893b-8ff49f151eab">

- 購物車未結提醒設定，可以設定5個時間段的發送時間
<img src="https://github.com/s0985514623/R2-Member-Filter/assets/35906564/bf55390f-d21a-470e-bb22-e5e3fc57bbdc">

## 未來可能開發功能
1. 將購物車未結提醒設定改為Repeater Field，可自行決定要幾個時間段，不限於5個
2. 將手動寄信的發送內容欄位可視化編輯器Tiny 改成WP內建的Editor以避免衝突
3. 自動帶入Email模板

## 參考
- 1.React腳手架來源 [J7](https://github.com/j7-dev/boilerplate-react-SPA.wordpress-plugin)