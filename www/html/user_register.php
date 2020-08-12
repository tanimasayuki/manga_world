<?php
require_once '../conf/setting.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH .'user_register.php';

$now_date = date('Y-m-d H:i:s');
$err_msg = array();

//DB接続
$dbh = get_db_connect();

session_start();


//トークンがPOST送信されていなければ、トークンを新たに生成し、セッションに保存、取得する。
if (isset($_POST['token']) !== TRUE) {
    $token = get_csrf_token();

//トークンがPOST送信されていれば、トークンを$tokenに格納
} else if (isset($_POST['token']) === TRUE) {
    $token = $_POST['token'];
}
 

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    //ユーザ名を変数に格納 (半角英数字6文字以上 admin以外)
    $user_name = '';
    if(isset($_POST['user_name']) === TRUE) {
        $user_name = $_POST['user_name'];
    }
    if($_POST['user_name'] === '' || $_POST['user_name'] === ' ' || $_POST['user_name'] === '　') {
        $err_msg[] = 'ユーザ名を入力してください';
    } else if(preg_match('/^([a-zA-Z0-9]{6,15})$/',$_POST['user_name']) !== 1 && $_POST['user_name'] !== 'admin') {
        $err_msg[] = '正しいユーザ名を入力してください';
    }
    
    //パスワードを変数に格納 (半角英数字6文字以上 admin以外)
    $user_password = '';
    if(isset($_POST['user_password']) === TRUE) {
        $user_password = $_POST['user_password'];
    }
    if($_POST['user_password'] === '' || $_POST['password'] === ' ' || $_POST['password'] === '　') {
        $err_msg[] = 'パスワードを入力してください';
    } else if(preg_match('/^([a-zA-Z0-9]{6,})$/',$_POST['user_password']) !== 1 && $_POST['user_password'] !== 'admin') {
        $err_msg[] = '正しいパスワードを入力してください';
    }
    
    //メールアドレスを変数に格納
    $mail = '';
    if(isset($_POST['email_address']) === TRUE) {
        $mail = $_POST['email_address'];
    }
    if($_POST['email_address'] === '' || $_POST['email_address'] === ' ' || $_POST['email_address'] === '　') {
        $err_msg[] = 'メールアドレスを入力してください';
    } else if(preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/', $_POST['email_address']) !== 1 ) {
        $err_msg[] = 'メールアドレスを正しく入力してください';
    }
    
    //性別情報を変数に格納
    $sex = '';
    if(isset($_POST['sex']) === TRUE) {
        $sex = $_POST['sex'];
    } else {
        $err_msg[] = '性別を選択してください';
    }
    
    //生年月日を変数に格納
    $birthday = '';
    if(isset($_POST['birthday']) === TRUE) {
        $birthday = $_POST['birthday'];
    }
    if($_POST['birthday'] === '' || $_POST['birthday'] === ' ' || $_POST['birthday'] === '　') {
        $err_msg[] = '生年月日を入力してください';
    } else if(preg_match('/^([1-9][0-9]{3})\/([1-9]{1}|1[0-2]{1})\/([1-9]{1}|[1-2]{1}[0-9]{1}|3[0-1]{1})$/', $_POST['birthday']) !== 1) {
        $err_msg[] = '生年月日を正しく入力してください';
    }

}

//トークンチェック(以下CSRF対策)
if(is_valid_csrf_token($token)) {

    if (count($err_msg) === 0 ) { //エラーなし
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { //POST送信あり
            //ユーザ情報の登録
            user_register($dbh, $user_name, $user_password, $mail, $sex, $birthday, $now_date);

        }
    }

} else {
    $err_msg[] = '不正な処理です';
}

include_once VIEW_PATH . '/user_register_view.php';
?>