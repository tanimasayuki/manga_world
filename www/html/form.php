<?php
require_once '../conf/setting.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'form.php';

$err_msg = array();

//ログインチェック
session_start();
login_check();


//トークンがPOST送信されていなければ、トークンを新たに生成し、セッションに保存、取得する。
if (isset($_POST['token']) !== TRUE) {
    $token = get_csrf_token();
//トークンがPOST送信されていれば、トークンを$tokenに格納
} else if (isset($_POST['token']) === TRUE) {
    $token = $_POST['token'];
}


//---- 入力チェック ----//
if($_SERVER['REQUEST_METHOD'] === 'POST') {

    //名前
    $name = '';
    if(isset($_POST['name']) === TRUE) {
      $name = $_POST['name'];
    }
    if($_POST['name'] === '' || $_POST['name'] === ' ' || $_POST['name'] === '　') {
        $err_msg[] = '名前を入力してください';
    //入力内容が半角英数字ではない時
    } else if(preg_match('/^[0-9a-zA-Z]*$/',$_POST['name']) !== 1) {
        $err_msg[] = '正しく名前を入力してください';
    }
    
    //電話番号
    $tel = '';
    if(isset($_POST['tel']) === TRUE) {
        $tel = $_POST['tel'];
    }
    if($_POST['tel'] === '' || $_POST['tel'] === ' ' || $_POST['tel'] === '　') {
        $err_msg[] = '電話番号を入力してください';
    } else if(preg_match('/^0\d{2,3}-\d{1,4}-\d{4}$/',$_POST['tel']) !== 1) {
        $err_msg[] = '正しく電話番号を入力してください';
    }
    
    //メールアドレス
    $mail = '';                                                     
    if(isset($_POST['mail']) === TRUE) {
        $mail = $_POST['mail'];
    }
    if($_POST['mail'] === '' || $_POST['mail'] === ' ' || $_POST['mail'] === '　') {
        $err_msg[] = 'メールアドレスを入力してください';
    } else if(preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/',$_POST['mail']) !== 1) {
        $err_msg[] = 'メールアドレスを正しく入力してください';
    }
    
    //性別
    $sex = '';
    if(isset($_POST['sex']) === TRUE) {
        $sex = $_POST['sex'];
    } else {
        $err_msg[] = '性別を選択してください';
    }
    
    //お問い合わせ内容
    $help = '';
    if(isset($_POST['help']) === TRUE) {
        $help = $_POST['help'];
    }
    if($_POST['help'] === '' || $_POST['help'] === ' ' || $_POST['help'] === '　'){
        $err_msg[] = 'お問い合わせ内容を入力してください';
    }
}


//トークンチェック(CSRF対策)
if(is_valid_csrf_token($token)) { 

    //---- POSTで値が送られてきた かつ エラーメッセージが無い時 ----//
    if($_SERVER['REQUEST_METHOD'] === 'POST' && count($err_msg) === 0) {

        //DBに接続
        $dbh = get_db_connect();

        //フォーム内容を登録
        form_register($dbh);
    }

} else {
    $err_msg[] = '不正な処理です';
}


include_once VIEW_PATH . '/form_view.php';
?>