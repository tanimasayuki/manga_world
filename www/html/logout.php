<?php
require_once '../conf/setting.php';

//セッション変数を削除
session_start();
$session_name = session_name();
$_SESSION = array();

//---- ユーザのCookieに保存されているセッションIDを削除 ----//
if (isset($_COOKIE[$session_name])) {
  //sessionに関連する設定を取得 
  $params = session_get_cookie_params();
  //cookieの有効期限を過去に
  setcookie($session_name, '', time() - 42000,
    $params["path"], $params["domain"],
    $params["secure"], $params["httponly"]
  );
}

//セッションIDを無効化
session_destroy();

//ログアウトの処理が完了したらログインページへ
header('Location: '.LOGIN_URL);
exit;

?>