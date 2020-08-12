<?php
require_once MODEL_PATH . 'db.php';

//商品情報テーブルへの新規登録
function products_information_register($dbh, $name, $author, $publisher, $type, $price, $new_img_filename, $status, $now_date) {
    $sql = 
       'INSERT INTO 
            comics_information
            (name, author, publisher, type, price, img, status, create_date, update_date) 
        VALUES
            (?,?,?,?,?,?,?,?,?)';
    $stmt=$dbh->prepare($sql);
    $stmt->bindValue(1, $name, PDO::PARAM_STR);
    $stmt->bindValue(2, $author, PDO::PARAM_STR);
    $stmt->bindValue(3, $publisher, PDO::PARAM_INT);
    $stmt->bindValue(4, $type, PDO::PARAM_INT);
    $stmt->bindValue(5, $price, PDO::PARAM_INT);
    $stmt->bindValue(6, $new_img_filename, PDO::PARAM_INT);
    $stmt->bindValue(7, $status, PDO::PARAM_STR);
    $stmt->bindValue(8, $now_date, PDO::PARAM_STR);
    $stmt->bindValue(9, $now_date, PDO::PARAM_STR);
    $stmt->execute();
    $ids = $dbh->lastInsertId('id'); //最後に取得したIDを取得
    return $ids;
}

//新規商品の在庫情報を登録
function products_stock_register($dbh, $id, $stock, $now_date) {
    $sql = 
       'INSERT INTO 
            comics_stock
            (id,stock,create_date,update_date) 
        VALUES
            (?,?,?,?)';
    $stmt=$dbh->prepare($sql);
    $stmt->bindValue(1, $id, PDO::PARAM_INT); //最後に取得したid
    $stmt->bindValue(2, $stock, PDO::PARAM_INT);
    $stmt->bindValue(3, $now_date, PDO::PARAM_STR);
    $stmt->bindValue(4, $now_date, PDO::PARAM_STR);
    $stmt->execute();
}

//在庫数の更新
function stock_update($dbh) {
    try{
        $sql =
           'UPDATE
                comics_stock
            SET
                stock = ?,
                update_date = NOW()
            WHERE
                id = ?';
        $stmt=$dbh->prepare($sql);
        $stmt->bindValue(1, $_POST['change_stock'], PDO::PARAM_INT);
        $stmt->bindValue(2, $_POST['id'], PDO::PARAM_INT);
        $stmt->execute();
        print '在庫数変更成功';
    } catch(PDOException $e) {
        $err_msg[] = 'DBエラー:'.$e->getMessage();
    }
}

//ステータスの変更 0 → 1
function change_status_01($dbh) {
    try{
        $sql =
           'UPDATE
                comics_information
            SET
                status = 1,
                update_date = NOW()
            WHERE
                id = ?';
        $stmt=$dbh->prepare($sql);
        $stmt->bindValue(1, intval($_POST['id']), PDO::PARAM_INT);
        $stmt->execute();
        print 'ステータス変更成功';
    } catch(PDOException $e) {
        $err_msg[] = 'DBエラー:'.$e->getMessage();
    }
}

//ステータスの変更 1 → 0
function change_status_10($dbh) {
    try{
        $sql =   
           'UPDATE
                comics_information
            SET
                status = 0,
                update_date = NOW()
            WHERE
                id = ?';
        $stmt=$dbh->prepare($sql);
        $stmt->bindValue(1, intval($_POST['id']), PDO::PARAM_INT);
        $stmt->execute();
        print 'ステータス変更成功';
    } catch(PDOException $e) {
        $err_msg[] = 'DBエラー:'.$e->getMessage();
    }
}

//商品情報の削除
function delete_information($dbh) {
    $sql = 
       'DELETE
        FROM 
            comics_information
        WHERE
            id = ?';
    $stmt=$dbh->prepare($sql);
    $stmt->bindValue(1, intval($_POST['id']), PDO::PARAM_INT);
    $stmt->execute();
}

//商品在庫の削除
function delete_stock($dbh) {
    $sql = 
       'DELETE
        FROM 
            comics_stock
        WHERE
            id = ?';
    $stmt=$dbh->prepare($sql);
    $stmt->bindValue(1, intval($_POST['id']), PDO::PARAM_INT);
    $stmt->execute();
}

//登録した商品の情報、および在庫数を取得
function get_products_information($dbh) {
    try{
        $sql =
            'SELECT           
                comics_information.id,
                comics_information.name,
                comics_information.author,
                comics_information.publisher,
                comics_information.type,
                comics_information.price,
                comics_information.img,
                comics_information.status,
                comics_stock.stock
            FROM 
                comics_information
                INNER JOIN comics_stock
                ON comics_information. id = comics_stock. id'; 
        $stmt=$dbh->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        foreach ($rows as $row) {
            $datas[] = $row;
        }
    } catch(PDOException $e) {
        $err_msg[] = 'DBエラー:'.$e->getMessage();
    }
    return $datas;
}
?>