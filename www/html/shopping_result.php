<?php
require_once '../conf/setting.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'shopping_result.php';
require_once MODEL_PATH . 'shopping_cart.php';

$now_date = date('Y-m-d H:i:s'); 
$data = array();
$err_msg = array();

//ログインチェック
session_start();
login_check($user_id);

//DB接続
$dbh = get_db_connect();

//購入した商品の情報を、在庫数まで含めて得る
$data = get_result_carts($dbh);

//購入合計金額を得る
$price = get_total_price($dbh);


//トークンがPOST送信されていなければ、トークンを新たに生成し、セッションに保存、取得する。
if (isset($_POST['token']) !== TRUE) {
    $token = get_csrf_token();
//トークンがPOST送信されていれば、トークンを$tokenに格納
} else if (isset($_POST['token']) === TRUE) {
    $token = $_POST['token'];
}


//在庫数チェック 0ならエラーメッセージを表示
if(count($data) === 0){
    $err_msg[] = 'カートに商品が入っていません。';
}

foreach($data as $values){
    //非公開ならエラーメッセージを表示
    if($values['status'] === 0) {
        $err_msg[] = $values['name'] . 'は非公開となったため現在購入できません。';
    }
    //在庫数が足りなければエラーメッセージを表示
    if($values['stock'] - $values['amount'] < 0){
        $err_msg[] = $values['name'] . 'の在庫が足りません。在庫数：' . $values['stock'];
    }
}


//トークンチェック(CSRF対策)
if(is_valid_csrf_token($token)) {

    //---- 購入確定時、エラーが無ければ以下の処理を行う ----//
    if(isset($_POST['result']) === TRUE && count($err_msg) === 0) {

        //トランザクション開始
        $dbh->beginTransaction();
        try{
            // 1⃣ 商品の在庫数を減らす
            result_stock_down($dbh, $data);
            // 2⃣ カートから商品を削除
            result_carts_delete($dbh);
            // 3⃣ 購入履歴への登録
            $order_id = result_order_register($dbh, $now_date);
            // 4⃣ 購入明細への登録
            result_statement_register($dbh, $data, $order_id);
            $dbh->commit();

        } catch(PDOException $e) {
            $dbh -> rollBack();
            $err_msg[] = 'DBエラー：' . $e->getMessage();
        }

    }

} else {
    $err_msg[] = '不正な処理です';
}

include_once VIEW_PATH . '/shopping_result_view.php';
?>