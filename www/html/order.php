<?php 
require_once '../conf/setting.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'order.php';
require_once MODEL_PATH . 'db.php';

$orders = array();
$err_msg = array();

//ログインチェック
session_start();
login_check();

//トークン生成
$token = get_csrf_token();

//DB接続
$dbh = get_db_connect();

//購入履歴の取得
$orders = get_orders($dbh);

include_once VIEW_PATH . '/order_view.php';
?>