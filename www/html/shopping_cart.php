<?php
require_once '../conf/setting.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'shopping_cart.php';

$data = array();
$price = array();
$err_msg = array();

//ログインチェック
session_start();
login_check($user_id);

//DB接続
$dbh = get_db_connect();


//トークンがPOST送信されていなければ、トークンを新たに生成し、セッションに保存、取得する。
if (isset($_POST['token']) !== TRUE) {
    $token = get_csrf_token();
//トークンがPOST送信されていれば、トークンを$tokenに格納
} else if (isset($_POST['token']) === TRUE) {
    $token = $_POST['token'];
}


//トークンチェック(CSRF対策)
if(is_valid_csrf_token($token)) { 

    //---- POSTデータが送られてきたとき、以下の処理を行う ----//
    if($_SERVER['REQUEST_METHOD'] === 'POST'){

        $action = '';
        if(isset($_POST['action']) === TRUE) {
            $action = $_POST['action'];
        }


        //購入予定数の入力チェック
        if($_POST['update_amount'] === '' || $_POST['update_amount'] === ' ' || $_POST['update_amount'] === '　') {
            $err_msg[]='個数を入力してください';
        } else if(isset($_POST['update_amount']) === TRUE && preg_match('/^[1-9][0-9]*$/', $_POST['update_amount']) !== 1) {
            $err_msg[]='購入予定数は正の半角数字のみです';
        }
        //購入予定数を$amountに格納
        if(isset($_POST['update_amount']) === TRUE && count($err_msg) === 0) {
            $amount = '';
            $amount = $_POST['update_amount'];
        } 


        //---- ◆ $action=update_amount （購入予定数変更ボタンが押されたとき） ----//
        if($action === 'update_amount') {

            //変更ボタンが押された商品の全情報を得る
            $products_information = get_products_information($dbh);

            //商品が非公開状態だとエラー表示
            if($products_information[0]['status'] === 0) {
                $err_msg[] = '非公開の商品です';

            //在庫数が足りないとエラー表示
            } else if($amount > $products_information[0]['stock']) {
                $err_msg[] = '在庫数が足りません。'.$products_information[0]['name'].'／在庫数：' . $products_information[0]['stock'] . '個';
            
            //エラーが無ければ購入予定数更新
            } else if (count($err_msg) === 0) {
                update_amount($dbh, $amount);
            }
        }


        //---- ◆ $action=delete_products （削除ボタンが押されたとき）----//
        if ($action === 'delete_products' && count($err_msg) === 0) {
            //商品の削除
            delete_products($dbh);
        }
    }

} else {
    $err_msg[] = '不正な処理です';
}


// 最新の合計金額を得る
$price = get_total_price($dbh);

//ユーザごとに、最新のカート内商品情報を得る
$data = get_carts($dbh);

include_once VIEW_PATH . '/shopping_cart_view.php';
?>