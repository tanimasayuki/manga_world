<?php
require_once MODEL_PATH . 'db.php';

//ユーザーごとのカート内商品情報を得る
function get_carts($dbh) {
    try {
        $sql = 
            'SELECT           
                comics_information. id,
                comics_information. name,
                comics_information. author,
                comics_information. publisher,
                comics_information. type,
                comics_information. price,
                comics_information. img,
                comics_carts. amount,
                comics_carts. user_id
            FROM 
                comics_information
                INNER JOIN comics_carts
                ON comics_information. id = comics_carts. product_id
            WHERE
                comics_carts. user_id = ?';
        $stmt=$dbh->prepare($sql);
        $stmt->bindValue(1,$_SESSION['id'],PDO::PARAM_INT);
        $stmt->execute();
        $datas = $stmt->fetchAll();
    } catch (PDOException $e) {
        $err_msg[] = 'DBエラー:'.$e->getMessage();
    }
    return $datas;
}

//クリックした商品ごとに、在庫数や購入予定数を含めた情報を得る
function get_products_information($dbh) {
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
                comics_carts. user_id = ? AND comics_carts. product_id = ?'; 
        $stmt=$dbh->prepare($sql);
        $stmt->bindValue(1, $_SESSION['id'], PDO::PARAM_INT); //ユーザIDごと
        $stmt->bindValue(2, $_POST['product_id'], PDO::PARAM_INT); //商品ごと
        $stmt->execute();
        $datas = $stmt->fetchAll();
    } catch(PDOException $e) {
        $err_msg[] = 'DBエラー:'.$e->getMessage();
    }
    return $datas;
}

//在庫数を更新する
function update_amount($dbh, $amount) {
    try {
        $sql =
            'UPDATE
                comics_carts
            SET
                amount = ?,
                update_date = NOW()
            WHERE
                product_id = ?';
        $stmt=$dbh->prepare($sql);
        $stmt->bindValue(1, $amount, PDO::PARAM_INT);
        $stmt->bindValue(2, $_POST['product_id'], PDO::PARAM_INT);
        $stmt->execute();
        print '購入予定数を変更しました';
    } catch (PDOException $e) {
        $err_msg[] = 'DBエラー:'.$e->getMessage();
    }
}

//カートから商品を削除する
function delete_products($dbh) {
    try{
        $sql =
           'DELETE
            FROM
                comics_carts
            WHERE
                product_id = ?';
        $stmt=$dbh->prepare($sql);
        $stmt->bindValue(1, $_POST['product_id'], PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        $err_msg[] = 'DBエラー:'.$e->getMessage();
    }
}

//購入予定商品の合計金額を得る
function get_total_price($dbh) {
    try {
        $sql = 
            'SELECT           
                SUM(comics_information. price * comics_carts. amount)
            FROM 
                comics_information
                INNER JOIN comics_carts
                ON comics_information. id = comics_carts. product_id
            WHERE
                user_id = ?';
        $stmt=$dbh->prepare($sql);
        $stmt->bindValue(1,$_SESSION['id'],PDO::PARAM_INT);
        $stmt->execute();
        $prices = $stmt->fetchAll();
    } catch(PDOException $e) {
        $err_msg[] = 'DBエラー:'.$e->getMessage();
    }
    return $prices;
}
?>