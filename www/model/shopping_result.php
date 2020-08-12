<?php
require_once MODEL_PATH . 'db.php';

//購入した商品の情報を、在庫数まで含めて得る
function get_result_carts($dbh) {
    try{
        $sql = 
            'SELECT           
                comics_information. id,
                comics_information. name,
                comics_information. author,
                comics_information. publisher,
                comics_information. type,
                comics_information. price,
                comics_information. img,
                comics_information. status,
                comics_carts. amount,
                comics_carts. product_id,
                comics_carts. user_id,
                comics_stock. stock
            FROM 
                comics_information
                INNER JOIN comics_carts
                ON comics_information. id = comics_carts. product_id
                INNER JOIN comics_stock
                ON comics_carts. product_id = comics_stock. id
            WHERE
                comics_carts. user_id = ?';
        $stmt=$dbh->prepare($sql);
        $stmt->bindValue(1, $_SESSION['id'], PDO::PARAM_INT); //ユーザIDごと
        $stmt->execute();
        $datas = $stmt->fetchAll();
    } catch(PDOException $e) {
        $err_msg[] = 'DBエラー:'.$e->getMessage();
    }
    return $datas;
}

//購入確定時に商品の在庫数を減らす
function result_stock_down($dbh, $data) {
    foreach($data as $rows) {
        $sql =
           'UPDATE
                comics_stock
            SET
                stock = ?,
                update_date = NOW()
            WHERE
                id = ?';
        $stmt=$dbh->prepare($sql);
        $stmt->bindValue(1, $rows['stock'] - $rows['amount'], PDO::PARAM_INT);
        //cartsのproduct_idを、products_stockのidとして割り当てる。
        $stmt->bindValue(2, $rows['product_id'], PDO::PARAM_INT); 
        $stmt->execute();
    }
}

//カートからの商品情報削除
function result_carts_delete($dbh) {
    $sql =
        'DELETE
        FROM
            comics_carts
        WHERE
            user_id = ?';
    $stmt=$dbh->prepare($sql);
    $stmt->bindValue(1,$_SESSION['id'],PDO::PARAM_INT);
    $stmt->execute();
}

//購入履歴への登録
function result_order_register($dbh, $now_date) {
    $sql =
        'INSERT INTO comics_orders
            (user_id, order_datetime)
        VALUES
            (?, ?)';
    $stmt=$dbh->prepare($sql);
    $stmt->bindValue(1, $_SESSION['id'], PDO::PARAM_INT);
    $stmt->bindValue(2, $now_date, PDO::PARAM_STR);
    $stmt->execute();
    //最後に取得したIDを取得(order_id)
    $order_ids = $dbh->lastInsertId();
    return $order_ids;
}

//購入明細への登録
function result_statement_register($dbh, $data, $order_id) {
    foreach($data as $row){
        $sql =
            'INSERT INTO comics_statements
                (order_id, item_id, item_name, price, amount)
            VALUES
                (?, ?, ?, ?, ?)';
        
        $stmt=$dbh->prepare($sql);
        $stmt->bindValue(1, $order_id, PDO::PARAM_INT);
        $stmt->bindValue(2, $row['id'], PDO::PARAM_STR);
        $stmt->bindValue(3, $row['name'], PDO::PARAM_INT);
        $stmt->bindValue(4, $row['price'], PDO::PARAM_STR);
        $stmt->bindValue(5, $row['amount'],PDO::PARAM_INT);
        $stmt->execute();
    }
}
?>