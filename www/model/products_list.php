<?php
require_once MODEL_PATH . 'db.php';

//ログイン中のユーザ名を取得する関数
function get_user_name($dbh){
    try {
        $sql = 
           'SELECT
                user_name
            FROM
                comics_users
            WHERE
                id = ?';
        $stmt=$dbh->prepare($sql);
        $stmt -> bindValue(1, $_SESSION['id'], PDO::PARAM_INT);
        $stmt->execute();
        $names = $stmt->fetchAll();
    } catch (PDOException $e) {
        exit('接続できませんでした。理由：'.$e->getMessage() );
    }
    return $names;
}

//全ユーザーの購入数、上位3つを取得
function get_ranking($dbh) {
    try {
        $sql=
           'SELECT 
                name, 
                img, 
                SUM(amount) AS total
            FROM 
                comics_statements
            JOIN 
                comics_information on comics_statements.item_id = comics_information.id
            GROUP BY 
                comics_statements.item_id
            ORDER BY 
                total DESC
            LIMIT 3';

        $stmt=$dbh->prepare($sql);
        $stmt->execute();
        $ranking = $stmt->fetchAll();

    } catch (PDOException $e) {
        $err_msg[] = 'DBエラー:'.$e->getMessage();
    }
    return $ranking;
}

//全商品情報を取得
function get_all_products($dbh) {
    try {
        $sql = 
           'SELECT           
                comics_information. id,
                comics_information. name,
                comics_information. author,
                comics_information. price,
                comics_information. img,
                comics_information. create_date,
                comics_stock. stock
            FROM 
                comics_information
                INNER JOIN comics_stock
                ON comics_information. id = comics_stock. id
            WHERE 
                comics_information. status = 1
            ORDER BY
                create_date DESC'; 
        $stmt=$dbh->prepare($sql);
        $stmt->execute();
        $data_all = $stmt->fetchAll();

    } catch (PDOException $e) {
        $err_msg[] = 'DBエラー:'.$e->getMessage();
    }
    return $data_all;
}

//商品名検索をしていれば、該当する商品の情報を得る
function get_name_search($dbh) {
    try {
        $sql =
            'SELECT           
                comics_information. id,
                comics_information. name,
                comics_information. author,
                comics_information. price,
                comics_information. img,
                comics_stock. stock
            FROM 
                comics_information
                INNER JOIN comics_stock
                ON comics_information. id = comics_stock. id
            WHERE 
                name = ? AND comics_information. status = 1'; 

        $stmt=$dbh->prepare($sql);
        $stmt -> bindValue(1,$_GET['name'],PDO::PARAM_STR);
        $stmt->execute();
        $datas = $stmt->fetchAll();

    } catch (PDOException $e) {
        $err_msg[] = 'DBエラー:'.$e->getMessage();
    }
    return $datas;
}

//---- 並べ替えボタンが押された時、並べ替えられたデータを取得 ----//
function get_sort_products($dbh) {
    try {

        //"新着順"でデータ取得
        if($_GET['sort_products'] === "1") {
            $datas = get_sort_create_date($dbh);
                    
        //"価格の安い順"でデータ取得
        } else if($_GET['sort_products'] == "2") {
            $datas = get_sort_price_asc($dbh);

        //"価格の高い順"でデータ取得        
        } else if($_GET['sort_products'] == "3") {
            $datas = get_sort_price_desc($dbh);
        }

    } catch (PDOException $e) {
        $err_msg[] = 'DBエラー:'.$e->getMessage();
    }
    return $datas;
}

//---- 絞り込み検索用関数 ----//
function get_search($dbh) {
    try {

        //出版社を選択時
        if($_GET['search_author'] === '' && $_GET['search_publisher'] !== '' && $_GET['search_type'] === '0') {
            $datas = get_publisher($dbh);

        //作者名を選択時
        } else if($_GET['search_author'] !== '' && $_GET['search_publisher'] === '0' && $_GET['search_type'] === '0') {
            $datas = get_author($dbh);

        //ジャンルを選択時
        } else if($_GET['search_author'] === '' && $_GET['search_publisher'] === '0' && $_GET['search_type'] !== '') {
            $datas = get_type($dbh);

        //出版社、ジャンルを選択時
        } else if($_GET['search_author'] === '' && $_GET['search_publisher'] !== '' && $_GET['search_type'] !== '') {
            $datas = get_publisher_type($dbh);

        //作者名、出版社を選択時
        } else if($_GET['search_author'] !== '' && $_GET['search_publisher'] !== '' && $_GET['search_type'] === '0') {
            $datas = get_author_publisher($dbh);

        //作者名、ジャンルを選択時
        } else if($_GET['search_author'] !== '' && $_GET['search_publisher'] === '0' && $_GET['search_type'] !== '') {
            $datas = get_author_type($dbh);

        //作者名、出版社、ジャンルを選択時
        } else if($_GET['search_author'] !== '' && $_GET['search_publisher'] !== '' && $_GET['search_type'] !== '') {
            $datas = get_author_publisher_type($dbh);
        }

    } catch (PDOException $e) {
        $err_msg[] = 'DBエラー:'.$e->getMessage();
    }
    return $datas;
}

//新着順にデータを取得する
function get_sort_create_date($dbh) {
    $sql = 
        'SELECT           
            comics_information. id,
            comics_information. name,
            comics_information. author,
            comics_information. price,
            comics_information. img,
            comics_information. create_date,
            comics_stock. stock
        FROM 
            comics_information
            INNER JOIN comics_stock
            ON comics_information. id = comics_stock. id
        WHERE 
            comics_information. status = 1
        ORDER BY
            create_date DESC';
    $stmt=$dbh->prepare($sql);
    $stmt->execute();
    $datas_c = $stmt->fetchAll();
    return $datas_c;
}

//価格の安い順にデータを取得する
function get_sort_price_asc($dbh) {
    $sql = 
       'SELECT           
            comics_information. id,
            comics_information. name,
            comics_information. author,
            comics_information. price,
            comics_information. img,
            comics_stock. stock
        FROM 
            comics_information
            INNER JOIN comics_stock
            ON comics_information. id = comics_stock. id
        WHERE 
            comics_information. status = 1
        ORDER BY
            price ASC';
    $stmt=$dbh->prepare($sql);
    $stmt->execute();
    $datas_p_a = $stmt->fetchAll();
    return $datas_p_a;
}

//価格の高い順にデータを取得する
function get_sort_price_desc($dbh) {
    $sql = 
       'SELECT           
            comics_information. id,
            comics_information. name,
            comics_information. author,
            comics_information. price,
            comics_information. img,
            comics_stock. stock
        FROM 
            comics_information
            INNER JOIN comics_stock
            ON comics_information. id = comics_stock. id
        WHERE 
            comics_information. status = 1
        ORDER BY
            price DESC';
    $stmt=$dbh->prepare($sql);
    $stmt->execute();
    $datas_p_d = $stmt->fetchAll();
    return $datas_p_d;
}

//出版社ごとにデータを取得
function get_publisher($dbh) {
    $sql = 
        'SELECT           
            comics_information. id,
            comics_information. name,
            comics_information. author,
            comics_information. price,
            comics_information. img,
            comics_stock. stock
            FROM 
            comics_information
            INNER JOIN comics_stock
            ON comics_information. id = comics_stock. id
            WHERE
            publisher = ? AND comics_information. status = 1';
    $stmt=$dbh->prepare($sql);
    $stmt -> bindValue(1,$_GET['search_publisher'],PDO::PARAM_INT);
    $stmt->execute();
    $datas_p = $stmt->fetchAll();
    return $datas_p;
}

//作者ごとにデータを取得
function get_author($dbh) {
    $sql = 
        'SELECT           
            comics_information. id,
            comics_information. name,
            comics_information. author,
            comics_information. price,
            comics_information. img,
            comics_stock. stock
        FROM 
            comics_information
            INNER JOIN comics_stock
            ON comics_information. id = comics_stock. id
        WHERE
            author = ? AND comics_information. status = 1';
    $stmt=$dbh->prepare($sql);
    $stmt -> bindValue(1,$_GET['search_author'],PDO::PARAM_STR);
    $stmt->execute();
    $datas_a = $stmt->fetchAll();
    return $datas_a;
}

//ジャンルごとにデータを取得
function get_type($dbh) {
    $sql =
       'SELECT           
            comics_information. id,
            comics_information. name,
            comics_information. author,
            comics_information. price,
            comics_information. img,
            comics_stock. stock
        FROM 
            comics_information
            INNER JOIN comics_stock
            ON comics_information. id = comics_stock. id
        WHERE 
            type = ? AND comics_information. status = 1'; 
    $stmt=$dbh->prepare($sql);
    $stmt -> bindValue(1,$_GET['search_type'],PDO::PARAM_INT);
    $stmt->execute();
    $datas_t = $stmt->fetchAll();
    return $datas_t;
}

//出版社とジャンルが合致するデータを取得
function get_publisher_type($dbh) {
    $sql = 
       'SELECT           
            comics_information. id,
            comics_information. name,
            comics_information. author,
            comics_information. price,
            comics_information. img,
            comics_stock. stock
        FROM 
            comics_information
            INNER JOIN comics_stock
            ON comics_information. id = comics_stock. id
        WHERE
        publisher = ? AND type = ? AND comics_information. status = 1';
    $stmt=$dbh->prepare($sql);
    $stmt -> bindValue(1,$_GET['search_publisher'],PDO::PARAM_INT);
    $stmt -> bindValue(2,$_GET['search_type'],PDO::PARAM_INT);
    $stmt->execute();
    $datas_pt = $stmt->fetchAll();
    return $datas_pt;
}

//作者名と出版社が合致するデータを取得
function get_author_publisher($dbh) {
    $sql = 
       'SELECT           
            comics_information. id,
            comics_information. name,
            comics_information. author,
            comics_information. price,
            comics_information. img,
            comics_stock. stock
        FROM 
            comics_information
            INNER JOIN comics_stock
            ON comics_information. id = comics_stock. id
        WHERE
            author = ? AND publisher = ? AND comics_information. status = 1';
    $stmt=$dbh->prepare($sql);
    $stmt -> bindValue(1,$_GET['search_author'],PDO::PARAM_STR);
    $stmt -> bindValue(2,$_GET['search_publisher'],PDO::PARAM_INT);
    $stmt->execute();
    $datas_ap = $stmt->fetchAll();
    return $datas_ap;
}

//作者名とジャンルが合致するデータを取得
function get_author_type($dbh) {
    $sql = 
       'SELECT           
            comics_information. id,
            comics_information. name,
            comics_information. author,
            comics_information. price,
            comics_information. img,
            comics_stock. stock
        FROM 
            comics_information
            INNER JOIN comics_stock
            ON comics_information. id = comics_stock. id
        WHERE
            author = ? AND type = ? AND comics_information. status = 1';
    $stmt=$dbh->prepare($sql);
    $stmt -> bindValue(1,$_GET['search_author'],PDO::PARAM_STR);
    $stmt -> bindValue(2,$_GET['search_type'],PDO::PARAM_INT);
    $stmt->execute();
    $datas_at = $stmt->fetchAll();
    return $datas_at;
}

//作者名、出版社、ジャンルが合致するデータを取得
function get_author_publisher_type($dbh) {
    $sql = 
        'SELECT           
            comics_information. id,
            comics_information. name,
            comics_information. author,
            comics_information. price,
            comics_information. img,
            comics_stock. stock
        FROM 
            comics_information
            INNER JOIN comics_stock
            ON comics_information. id = comics_stock. id
        WHERE
            author = ? AND publisher = ? AND type = ? AND comics_information. status = 1';
    $stmt=$dbh->prepare($sql);
    $stmt -> bindValue(1,$_GET['search_author'],PDO::PARAM_STR);
    $stmt -> bindValue(2,$_GET['search_publisher'],PDO::PARAM_INT);
    $stmt -> bindValue(3,$_GET['search_type'],PDO::PARAM_INT);
    $stmt->execute();
    $datas_apt = $stmt->fetchAll();
    return $datas_apt;
}

//商品ID, ユーザIDに合致したカートIDとamountを取得
function get_cart_id_amount($dbh) {
    try {
        $sql = 
            'SELECT
                id,
                amount
            FROM
                comics_carts
            WHERE
                product_id = ?
                AND user_id = ?';
        $stmt=$dbh->prepare($sql);
        $stmt -> bindValue(1,$_POST['product_id'],PDO::PARAM_INT);
        $stmt -> bindValue(2,$_SESSION['id'],PDO::PARAM_INT);
        $stmt -> execute();
        $ids = $stmt -> fetchAll();
    } catch (PDOException $e) {
        $err_msg[] = 'DBエラー:'.$e->getMessage();
    }
    return $ids;
}

//商品ごとに現在の在庫数とステータスを得る
function get_stock_status($dbh){
    try{
        $sql=
           'SELECT
                stock,
                (SELECT status FROM comics_information WHERE id = ?) AS status
            FROM
                comics_stock
            WHERE
                id = ?';
        $stmt = $dbh->prepare($sql);
        $stmt -> bindValue(1,$_POST['product_id'],PDO::PARAM_INT);
        $stmt -> bindValue(2,$_POST['product_id'],PDO::PARAM_INT);
        $stmt -> execute();
        $stocks = $stmt -> fetchAll();
    } catch (PDOException $e) {
        $err_msg[] = 'DBエラー:'.$e->getMessage();
    }
    return $stocks;
}

//カートテーブル(comics_carts)に商品情報を登録
function cart_register($dbh, $now_date) {
    try {
        $sql = 
            'INSERT INTO comics_carts
                (user_id, product_id, amount, create_date, update_date)
            VALUES
                (?, ?, 1, "'.$now_date.'", "'.$now_date.'")'; //購入予定数を１に
        $stmt=$dbh->prepare($sql);
        $stmt -> bindValue(1, $_SESSION['id'], PDO::PARAM_INT);
        $stmt -> bindValue(2, $_POST['product_id'], PDO::PARAM_INT);
        $stmt->execute();
        echo 'カートに入れました';
    } catch (PDOException $e) {
        $err_msg[] = 'DBエラー:'.$e->getMessage();
    }
}

//購入予定数を＋１
function cart_amount_up($dbh, $now_date, $id) {
try {
    $sql = 
        'UPDATE
            comics_carts
        SET
            amount = ?,
            update_date = ?
        WHERE
            product_id = ?';
    $stmt=$dbh->prepare($sql);
    $stmt -> bindValue(1, $id[0]['amount'] + 1, PDO::PARAM_INT); //購入予定数＋１
    $stmt -> bindValue(2, $now_date, PDO::PARAM_STR);
    $stmt -> bindValue(3, $_POST['product_id'], PDO::PARAM_INT);
    $stmt->execute();
    echo '購入予定数を増やしました';

} catch (PDOException $e) {
    $err_msg[] = 'DBエラー:'.$e->getMessage();
}
}
?>