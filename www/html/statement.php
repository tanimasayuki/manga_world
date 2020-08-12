<?php 
require_once '../conf/setting.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'statement.php';

$err_msg = array();

//ログインチェック
session_start();
login_check();

//POST送信されてきたトークンを$tokenに格納
$token = $_POST['token'];

//DB接続
$dbh = get_db_connect();


//トークンチェック(CSRF対策)
if(is_valid_csrf_token($token)) { 

    //---- 購入明細ボタンが押された時のみ、明細データを取得 ----//
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $statements = get_statements($dbh);
    }

} else {
    $err_msg[] = '不正な処理です';
}


include_once VIEW_PATH . '/statement_view.php';
?>