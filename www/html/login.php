<?php
//ログインの成否を判断する / ログイン結果を表示するファイル

require_once '../conf/setting.php';
require_once MODEL_PATH . 'login.php';
require_once MODEL_PATH . 'db.php';
require_once MODEL_PATH . 'functions.php';

$err_msg = array();
$data=array();

session_start();

//トークンの受け取り
$token = $_POST['token'];

//POST送信が無ければログインページへ
if($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('LOCATION:'.LOGIN_URL);
  exit;
}


//入力チェック(ユーザ名) 半角英数字6文字以上
$user_name = '';
if(isset($_POST['user_name']) === TRUE) {
    $user_name = $_POST['user_name'];
}
if($_POST['user_name'] === ''  || $_POST['user_name'] === ' ' || $_POST['user_name'] === '　') {
    $err_msg[] = 'ユーザ名を入力してください';
} else if(preg_match('/^([a-zA-Z0-9]{6,})$/',$_POST['user_name']) !== 1 && $_POST['user_name'] !== 'admin') {
    $err_msg[] = '正しいユーザ名を入力してください';
}


//入力チェック(パスワード) 半角英数字6文字以上
$password = '';
if(isset($_POST['password']) === TRUE) {
    $password = $_POST['password'];
}
if($_POST['password'] === '' || $_POST['password'] === ' ' || $_POST['password'] === '　') {
    $err_msg[] = 'パスワードを入力してください';
} else if(preg_match('/^([a-zA-Z0-9]{6,})$/',$_POST['password']) !== 1 && $_POST['password'] !== 'admin') {
    $err_msg[] = '正しいパスワードを入力してください';
}


//入力値をcookieに保存
setcookie('user_name', $user_name, time()+60);


//POSTで値が送られてきた時、DB接続・ユーザIDの取得を行う
if($_SERVER['REQUEST_METHOD'] === 'POST') {         
    $dbh = get_db_connect();
    $data = get_user_id($sql, $dbh, $user_name, $password);
}


//トークンチェック(CSRF対策)
if(is_valid_csrf_token($token)) { 

    // ◎ ユーザIDを得られていれば、セッション変数に格納し、商品一覧へ
    if(isset($data[0]['id']) && $data[0]['id'] !== 9) {
        $_SESSION['id'] = $data[0]['id'];
        header('Location:'.PRODUCTS_LIST_URL);
        exit;
    } else if(isset($data[0]['id']) !== TRUE && count($err_msg) === 0) {
        $err_msg[] = 'ユーザ名またはパスワードが間違っています';
    }

    // ◎ 得たユーザIDが9(ユーザ名:admin/パスワード:admin)なら、商品管理へ移行
    if($data[0]['id'] === 9) {
        $_SESSION['id'] = 9;
        header('Location:' . TOOL_URL);
        exit;
    }

} else {
    $err_msg[] = '不正な処理です';
}

include_once VIEW_PATH . '/login_view.php';
?>