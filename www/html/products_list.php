<?php
require_once '../conf/setting.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'products_list.php';
require_once MODEL_PATH . 'db.php';

$now_date = date('Y-m-d H:i:s'); //現在時刻
$data = array();
$id = array();
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


//DB接続
$dbh = get_db_connect();
    
//ログイン中のユーザーIDに応じたユーザー名を取得 
$user = get_user_name($dbh);
    
//全ユーザーの購入数、上位３つを取得する
$rankings = get_ranking($dbh);
 

//---- 商品名検索をした時、以下の処理を行う ----//
if(isset($_GET['name_search']) === TRUE) {

    //取得データを$dataに格納
    $data = get_name_search($dbh);

    //取得データが無かった時、エラーメッセージを表示
    if(count($data) === 0) {
        $err_msg[] = '見つかりませんでした。';
    }


//---- 商品の並べ替えボタンを押した時、以下の処理を行う ----//
} else if(isset($_GET['sort']) === TRUE) {

    //項目を選択せずボタンを押した時、エラーメッセージを表示
    if($_GET['sort_products'] === "0") {
        $err_msg[] = '並べ替え条件を選択してください。';

    //項目を選択した時、取得データを$dataに格納
    } else {
        $data = get_sort_products($dbh);
    }

    
//---- 絞り込み検索を行った時、以下の処理を行う ----//
} else if(isset($_GET['search']) === TRUE) {
    
    //取得データを$dataに格納
    $data = get_search($dbh);

    //取得データが無ければエラーメッセージを表示
    if(count($data) === 0) {
        $err_msg[] = '見つかりませんでした。';
    }


//---- 作者名がクリックされた時、以下の処理を行う ----//
} else if(isset($_GET['search_author']) === TRUE) {

    //取得データを$dataに格納
    $data = get_author($dbh);

    //取得データが無ければエラーメッセージを表示
    if(count($data) === 0) {
        $err_msg[] = '見つかりませんでした。';
    }


//---- 出版社名がクリックされた時、以下の処理を行う ----//
} else if(isset($_GET['search_publisher']) === TRUE) {

    //取得データを$dataに格納
    $data = get_publisher($dbh);

    //取得データが無ければエラーメッセージを表示
    if(count($data) === 0) {
        $err_msg[] = '見つかりませんでした。';
    }


//---- ジャンル名がクリックされた時、以下の処理を行う ----//
} else if(isset($_GET['search_type']) === TRUE) {

    //取得データを$dataに格納
    $data = get_type($dbh);

    //取得データが無ければエラーメッセージを表示
    if(count($data) === 0) {
        $err_msg[] = '見つかりませんでした。';
    }
    

//---- 検索・並べ替えが何も行われていない時、全商品情報を得る ----//
} else {
    $data = get_all_products($dbh);
}


//トークンチェック(CSRF対策)
if(is_valid_csrf_token($token)) { 

    //---- カートに入れるボタンが押されたとき、以下の処理を行う (公開商品のみ) ----//
    if(isset($_POST['product_id'])) {

        //カートIDと、購入予定数amountを取得
        $id = get_cart_id_amount($dbh);

        //ボタンが押された商品の在庫数とステータスを得る
        $stock_status = get_stock_status($dbh);

        //選んだ商品がカートに無ければ、カートテーブルに商品情報を登録
        if(count($id) === 0 && $stock_status[0]['status'] !== 0 && count($err_msg) === 0) {
            cart_register($dbh, $now_date);
        } else if(count($id) === 0 && $stock_status[0]['status'] === 0) {
            $err_msg[] = '非公開商品です'; 
        }

        //選んだ商品が既にカートにあれば、購入予定数を＋１ （在庫数を超えるとエラー）
        if(count($id) !== 0 && $stock_status[0]['status'] !== 0 && ($id[0]['amount']+1) <= $stock_status[0]['stock'] && count($err_msg) === 0) {
            cart_amount_up($dbh, $now_date, $id);
        } else if(count($id) !== 0 && ($id[0]['amount']+1) > $stock_status[0]['stock']) {
            $err_msg[] = '在庫数が足りません／在庫数：' . $stock_status[0]['stock'];
        } else if(count($id) !== 0 && $stock_status[0]['status'] === 0) {
            $err_msg[] = '非公開商品です'; 
        }
    }

} else {
    $err_msg[] = '不正な処理です';
}


include_once VIEW_PATH . '/products_list_view.php';
?>