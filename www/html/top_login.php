<?php
//ログインページに関するファイル

require_once '../conf/setting.php';
require_once MODEL_PATH . 'functions.php';

$err_msg = array();

session_start();

//トークンの作成とセッションへの保存
$token = get_csrf_token();

//----- ユーザIDがセッション変数の中にあればHPへ ----//
if(isset($_SESSION['user_id'])) {
  header('Location:'.PRODUCTS_LIST_URL);
  exit;
}

//---- ユーザ名のCookieがあればその値を$user_nameに代入する ----//
if(isset($_COOKIE['user_name'])) {
  $user_name = $_COOKIE['user_name'];
} else {
  $user_name = '';
}

include_once VIEW_PATH . '/top_login_view.php';
?>