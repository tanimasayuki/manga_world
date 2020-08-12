<?php
require_once '../conf/setting.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'products_information.php';
require_once MODEL_PATH . 'products_list.php';

$now_date = date('Y-m-d H:i:s') . "\n"; //現在時刻の取得
$publishers = array('S英社','K談社','S学館','A田書店'); //出版社表示用変数
$type = array('バトル','ギャグ','スポーツ','ラブコメ'); //ジャンル表示用変数
$data = array();
$id = array();
$name = array();
$average = array();
$err_msg = array();
$reviews = array();
$review_err_msg = array();

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


//---- 商品一覧から商品名をクリックした時だけ以下の処理を行う ----//
if(isset($_GET['id']) === TRUE) { 

    
    //送られてきた商品IDを変数に格納 
    $product_id = $_GET['id'];
    //送られてきた評価を変数に格納
    $evaluation = $_POST['evaluation'];
    //DB接続
    $dbh = get_db_connect();
    //商品情報の取得を行う
    $data = get_comics_information($dbh);
    //ログイン中のユーザ名を取得
    $name = get_user_name($dbh);
    //商品IDごとにレビューを取得
    $reviews = get_review($dbh, $product_id);
    //現在の平均評価点数を取得
    $average = get_average($dbh, $product_id);


    //トークンチェック(CSRF対策)
    if(is_valid_csrf_token($token)) { 

        //---- カートに入れるボタンが押されたときだけ以下の処理を行う ----//
        if(isset($_POST['product_id'])) {

            //カートIDとamountを取得(商品ID、ユーザIDに合致したもの)
            $id = get_cart_id_amount($dbh);

            //ボタンが押された商品の在庫数とステータスを得る
            $stock_status = get_stock_status($dbh);

            //カートに商品が入っていなければ、テーブルに商品情報を新規登録
            if(count($id) === 0 && $stock_status[0]['status'] !== 0 && count($err_msg) === 0) {
                cart_register($dbh, $now_date);
            } else if(count($id) === 0 && $stock_status[0]['status'] === 0) {
                $err_msg[] = '非公開商品です'; 
            }

            //カートに商品が入っていれば、購入予定数を＋１
            if(count($id) !== 0 && $stock_status[0]['status'] !== 0 && ($id[0]['amount']+1) <= $stock_status[0]['stock'] && count($err_msg) === 0) {
                cart_amount_up($dbh, $now_date, $id);
            } else if(count($id) !== 0 && ($id[0]['amount']+1) > $stock_status[0]['stock']) {
                $err_msg[] = '在庫数が足りません／在庫数：' . $stock_status[0]['stock'];
            } else if(count($id) !== 0 && $stock_status[0]['status'] === 0) {
                $err_msg[] = '非公開商品です'; 
            }
        }

        //---- レビューを送信ボタンが押された時だけ以下の処理を行う ----//
        if(isset($_POST['review']) === TRUE) {
            //レビュー内容を変数に格納
            if(isset($_POST['review']) === TRUE) {
                $review = $_POST['review'] . "\n";
            }
            
            //エラー設定
            if(mb_strlen($review) > 300) {
                $review_err_msg[] = '300文字以内で入力してください';
            }
            if(trim(str_replace('　', ' ', $_POST['review'])) === '') {
                $review_err_msg[] = 'レビューを入力してください';
            }

            //レビューをDBに保存する。
            if($_POST['review'] !== '' && $_POST['review'] !== ' ' && $_POST['review'] !== '　'){
                review_register($dbh, $product_id, $name[0]['user_name'], $review, $evaluation, $now_date);
            }
            
            //書き込んだレビューも含めて、全レビューを取得
            $reviews = get_review($dbh, $product_id);

            //レビュー投稿直後の平均評価点数を取得
            $average = get_average($dbh, $product_id);
        }

    } else {
        $err_msg[] = '不正な処理です';
    }
}


include_once VIEW_PATH . '/products_information_view.php';
?>