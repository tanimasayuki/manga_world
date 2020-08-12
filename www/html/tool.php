<?php
require_once '../conf/setting.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'tool.php';

$now_date = date('Y-m-d H:i:s');
$new_img_filename = ''; //UPした画像ファイル名を入れる
$data = array();                  
$err_msg = array();               

//ログインチェック
session_start();
login_check_admin();

//DB接続
$dbh = get_db_connect();


//トークンがPOST送信されていなければ、トークンを新たに生成し、セッションに保存、取得する。
if (isset($_POST['token']) !== TRUE) {
    $token = get_csrf_token();
//トークンがPOST送信されていれば、トークンを$tokenに格納
} else if (isset($_POST['token']) === TRUE) {
    $token = $_POST['token'];
}


//---- 値の格納および入力チェック ----//

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    //$_POST['action']=new_productsならば
    if($_POST['action'] === 'new_products') {
        
        //商品名を変数に格納
        $name = '';
        if(isset($_POST['name']) === TRUE) {
            $name = $_POST['name'];
        }
        if($_POST['name'] === '' || $_POST['name'] === ' ' || $_POST['name'] === '　') {
            $err_msg[]='名前を入力してください';
        }

        //作者名を変数に格納
        $author = '';
        if(isset($_POST['author']) === TRUE) {
            $author = $_POST['author'];
        }
        if($_POST['author'] === '' || $_POST['author'] === ' ' || $_POST['author'] === '　') {
            $err_msg[]='作者名を入力してください';
        }
        
        //出版社名を変数に格納
        $publisher = '';
        if(isset($_POST['publisher']) === TRUE) {
            $publisher = $_POST['publisher'];
        }
        if($_POST['publisher'] === '0') {
            $err_msg[]='出版社を選択してください';
        }

        //ジャンル名を変数に格納
        $type = '';
        if(isset($_POST['type']) === TRUE) {
            $type = $_POST['type'];
        }
        if($_POST['type'] === '0') {
            $err_msg[]='ジャンルを選択してください';
        }
        
        //価格を変数に格納
        $price = '';
        if(isset($_POST['price']) === TRUE) {
            $price = $_POST['price'];
        }
        if($_POST['price'] === '' || $_POST['price'] === ' ' || $_POST['price'] === '　') {
            $err_msg[]='値段を入力してください';
        } else if(preg_match('/^[0-9]+$/', $price) !== 1) {
            $err_msg[]='値段は正の整数のみ可です';
        }
        
        //在庫数を変数に格納
        $stock = '';
        if(isset($_POST['stock']) === TRUE) {
            $stock = $_POST['stock'];
        }
        if($_POST['stock'] === '' || $_POST['stock'] === ' ' || $_POST['stock'] === '　') {
            $err_msg[]='個数を入力してください';
        } else if(preg_match('/^[0-9]+$/', $stock) !== 1) {
            $err_msg[]='個数は正の整数のみ可です';
        }
        
        //ステータスを変数に格納
        $status = '';
        if(isset($_POST['status']) === TRUE) {
            $status = $_POST['status'];
        } else {
            $err_msg[]='ステータスを選択してください';
        }
        if($status === '0' || $status === '1') {
        } else {
            $err_msg[] = '公開ステータスが不正です';
        }
    }

    $action = '';
    if(isset($_POST['action']) === TRUE) {
        $action = $_POST['action'];
    } 
} 


//---- $_POST['action']=new_products ◆ ----//
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if($_POST['action'] === 'new_products') {

        //画像がアップロードされた時、送られてきた拡張子を取得し$extensionに代入
        if (is_uploaded_file($_FILES['new_img']['tmp_name']) === TRUE) { 
            $extension = pathinfo($_FILES['new_img']['name'], PATHINFO_EXTENSION);
        }

        //送られてきた拡張子$extensionがjpeg/JPEGもしくはpng/PNGなら、保存先となる新しいファイル名を生成
        if($extension === 'JPEG' || $extension === 'jpeg' || $extension === 'JPG' || $extension === 'jpg' || $extension === 'PNG' || $extension === 'png') {
            $new_img_filename = sha1(uniqid(mt_rand(), true)).'.'.$extension;
        }

        //エラーメッセージ
        if (is_uploaded_file($_FILES['new_img']['tmp_name']) !== TRUE) {
            $err_msg[] = 'ファイルを選択してください';
        } else if($extension !== 'JPEG' && $extension !== 'jpeg' && $extension !== 'JPG' && $extension !== 'jpg' && $extension !== 'PNG' && $extension !== 'png') {
            $err_msg[] = 'ファイル形式が異なります。画像ファイルはJPEG/JPGかPNGのみ利用可能です。';
        }

        //同名のディレクトリ名/ファイル名が存在していないかつ、UPされたファイルを保存できなかった場合
        if(is_file(IMAGE_DIR . $new_img_filename) !== TRUE) {
            if(move_uploaded_file($_FILES['new_img']['tmp_name'] ,IMAGE_DIR.$new_img_filename) !== TRUE) {
                $err_msg[] = 'ファイルのアップロードに失敗しました。';
            }
        } else {
            $err_msg[] = 'ファイルアップロードに失敗しました。';
        }

    }
}

//トークンチェック(以下CSRF対策)
if(is_valid_csrf_token($token)) { 
    if (count($err_msg) === 0) { //エラーなし
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { //POST送信あり

            //---- $action=new_products ◆ ----//
            if ($action === 'new_products') {
                $dbh -> beginTransaction();
                try {
                    //商品情報の登録
                    $id = products_information_register($dbh, $name, $author, $publisher, $type, $price, $new_img_filename, $status, $now_date);
                    //在庫数情報の登録
                    products_stock_register($dbh, $id, $stock, $now_date);
                    $dbh -> commit();
                    print 'データが登録できました。';
                } catch(PDOException $e) {
                    $dbh -> rollBack();
                    $err_msg[] = 'データの登録に失敗しました。DBエラー：' . $e->getMessage();
                }

            //---- $action=update_stock ◆ ----//
            } else if ($action === 'update_stock') {
                if($_POST['change_stock'] === '' || $_POST['change_stock'] === ' ' || $_POST['change_stock'] === '　') {
                    $err_msg[]='在個数を入力してください';
                } else if(preg_match('/^[0-9]+$/', $_POST['change_stock']) !== 1) {
                    $err_msg[]='在個数は正の半角数字のみ可です';
                }
                if(count($err_msg) === 0) {
                    //在庫数更新
                    stock_update($dbh);
                }

            //---- $action=change_status ◆ ----//
            } else if ($action === 'change_status' && $_POST['change_status'] === '0') {
                //ステータスの変更 0 → 1
                change_status_01($dbh);

            //---- $action=change_status ◆ ----//
            } else if ($action === 'change_status' && $_POST['change_status'] === '1') {
                //ステータスの変更 1 → 0
                change_status_10($dbh);

            //---- $action=delete_column ◆ ----//
            } else if ($action === 'delete_column') {
                $dbh -> beginTransaction();
                try{
                    //商品情報の削除
                    delete_information($dbh);
                    //商品在庫の削除
                    delete_stock($dbh);
                        $dbh -> commit();
                        print '削除完了';
                } catch(PDOException $e) {
                    $dbh -> rollBack();
                    $err_msg[] = 'DBエラー：' . $e->getMessage();
                }
            }

        }
    }
} else {
    $err_msg[] = '不正な処理です';
}

//登録した商品の情報、および在庫数を取得
$data = get_products_information($dbh);


include_once VIEW_PATH . '/tool_view.php';
?>