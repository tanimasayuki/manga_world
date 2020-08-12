<?php
require_once '../conf/setting.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user_tool.php';

$data = array();
$err_msg=[];

//ログインチェック
session_start();
login_check($user_id);

//DB接続
$dbh = get_db_connect();

//全ユーザ情報の取得
$data = get_user($dbh);

include_once VIEW_PATH . '/user_tool_view.php';
?>